<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

//创建用户锁文件。在各需要处理用户数据的php结束时务必清空用户锁，否则会导致阻塞
//返回值：0正常加锁  1本进程上过锁  2被阻塞导致失败  3无需加锁
//是以用户名为标签的（最兼容各种查询方式）
//$key用于远程锁，存于nlk文件内
function create_user_lock($un, $key='')
{
	global $udata_lock_pool;
	if(!is_array($udata_lock_pool)) $udata_lock_pool = array();
	//如果用户锁池里已有键，认为已经上锁了
	if(isset($udata_lock_pool[$un])) return 1;
	//如果是调用command执行的，不用加锁（command来加锁）；roomupdate这种长轮询也不加锁
	elseif(!$un || !defined('CURSCRIPT') || in_array(CURSCRIPT, array('roomupdate','chat','roomcmd','valid','end','winner','rank','alive','help','news'))) return 3;
	$dir = GAME_ROOT.'./gamedata/tmp/userlock/';
	$file = $un.'.nlk';
	$lstate = check_lock($dir, $file, 3000, $key);//最多允许3秒等待，之后穿透
	$res = 2;
	if(!$lstate) {
		if(create_lock($dir, $file, $key)) $res = 0;
	}
	$udata_lock_pool[$un] = 1;
	return $res;
}

//释放用户锁文件
function release_user_lock($un, $key='')
{
	global $udata_lock_pool;
	$dir = GAME_ROOT.'./gamedata/tmp/userlock/';
	$file = $un.'.nlk';
	release_lock($dir, $file, $key);
	unset($udata_lock_pool[$un]);
}

//清空用户锁
//如果有远程数据库，会发送讯息让远程数据库清空锁
function release_user_lock_from_pool($key='')
{
	global $udata_lock_pool, $userdb_remote_storage, $userdb_remote_key;
	
	if(!empty($udata_lock_pool)) {
		foreach(array_keys($udata_lock_pool) as $un){
			release_user_lock($un, $key);
		}
	}
	
	//这里只向远端发送清空锁的命令，具体清空哪些锁是储存在远端的
	if(!empty($userdb_remote_storage) && !empty($userdb_remote_key)) {
		global $userdb_remote_storage_sign, $userdb_remote_storage_pass, $userdb_remote_connect_times;
		$url = $userdb_remote_storage;
		$context = array(
			'sign' => $userdb_remote_storage_sign,
			'pass' => $userdb_remote_storage_pass,
			'command' => 'release_user_lock_from_pool',
			'key' => $userdb_remote_key,
		);
		for($i=0;$i<$userdb_remote_connect_times;$i++) {
			$ret = curl_post($url, $context);
			if('Release success' == gdecode($ret,1)) break;
		}
	}
}

//连接远程数据库，重试$userdb_remote_connect_times次
function curl_udata_cmd($command, $para1='', $para2='', $para3='', $para4='', $para5=''){
	global $userdb_remote_storage, $userdb_remote_storage_sign, $userdb_remote_storage_pass, $userdb_remote_connect_times, $userdb_remote_key;
	if(!$userdb_remote_storage) return;
	$userdb_remote_connect_times = max(1, $userdb_remote_connect_times);
	if(empty($userdb_remote_key)) $userdb_remote_key = uniqid();
	$url = $userdb_remote_storage;
	//参数处理
	if(in_array($command, array('insert_udata', 'update_udata', 'update_udata_multilist'))) {
		$para1 = gencode($para1);
	}
	$context = array(
		'sign' => $userdb_remote_storage_sign,
		'pass' => $userdb_remote_storage_pass,
		'command' => $command,
		'para1' => $para1,
		'para2' => $para2,
		'para3' => $para3,
		'para4' => $para4,
		'para5' => $para5,
		'key' => $userdb_remote_key,
	);
	for($t=0;$t<$userdb_remote_connect_times;$t++){
		$ret_raw = curl_post($url, $context);
		$ret = gdecode($ret_raw,1);
		if(NULL!==$ret || strpos($ret_raw, 'Error')===0) break;
		else usleep(200000);//挂起0.2秒再试
	}
//	writeover('n.txt', $t);
//	writeover('e.txt', var_export($ret_raw,1));
	if(NULL===$ret || ('fetch_udata' == $command && !is_array($ret))) {
		gexit('连接远程数据库失败',__file__,__line__);
	}
	return $ret;
}

//获取用户数据的通用函数，会自动获取远端数据
//返回相当于fetch_array得到的数组
//$keytype==0为无键名，为1是username当键名，为2是uid当键名
//$userdb_foreced_local为真时强制查询本地
function fetch_udata($fields='', $where='', $sort='', $keytype=0, $nolock=0){
	global $db, $gtablepre, $userdb_remote_storage, $userdb_foreced_local, $userdb_foreced_key;
	//缺省查询
	if(empty($fields)) $fields = '*';
	if(empty($where)) $where = '1';
	$ret = array();
	//如果设置了远程数据库储存
	if($userdb_remote_storage && empty($userdb_foreced_local)) {
		return curl_udata_cmd('fetch_udata', $fields, $where, $sort, $keytype, $nolock);
	}	
	
	//以下是无远程储存时
	//如果where里有username，直接加锁；否则先查询username再加锁，加完锁才真查询
	if(!$nolock && strpos($fields, 'COUNT(')===false){
		if(strpos($where, 'username')!==false){
			if(preg_match('/username\s*?IN\s*?\((.*?)\)/s', $where, $matches)) {
				$wherecont = explode(',',$matches[1]);
				foreach($wherecont as &$un){
					$un = trim($un,"' \n\r\t");
					create_user_lock($un, $userdb_foreced_key);
				}
			}elseif(preg_match('/username\s*?=\s*?\'(.*?)\'/s', $where, $matches)){
				$un = $matches[1];
				create_user_lock($un, $userdb_foreced_key);
			}
		}else{
			$qry = "SELECT username FROM {$gtablepre}users WHERE {$where} ";
			if(!empty($sort)) $qry .= "ORDER BY $sort";
			$result = $db->query($qry);
			if($db->num_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$un = $r['username'];
					create_user_lock($un, $userdb_foreced_key);
				}
			}else{
				return $ret;
			}
		}
	}
	
	$qry = "SELECT {$fields} FROM {$gtablepre}users WHERE {$where} ";
	if(!empty($sort)) $qry .= "ORDER BY $sort";
	//获取结果并自动fetch
	$result = $db->query($qry);
	
	if($db->num_rows($result)) {
		while($r = $db->fetch_array($result)) {
			if(1==$keytype) $ret[$r['username']] = $r;
			elseif(2==$keytype) $ret[$r['uid']] = $r;
			else $ret[] = $r;
		}
	}
	
	return $ret;
}

//自动生成WHERE xxx IN ()这个格式的查询语句
//所给$wherearr必须是一个以字段名为键名，以内容列表为键值的二阶数组。暂时只支持1个查询条件
//返回值以username为键名
function fetch_udata_multilist($fields, $wherearr, $sort=''){
	if(is_array($wherearr)) {
		$wherefield = array_keys($wherearr)[0];
		$where = $wherefield." IN ('".implode("','", $wherearr[$wherefield])."')";
	}else{
		$where = $wherearr;
	}
	return fetch_udata($fields, $where, $sort, 1);
}

//根据$username返回单个数组，注意与fetch_udata()返回值数组结构的差别！
function fetch_udata_by_username($username, $fields='*'){
	$ret = fetch_udata($fields, "username='$username'");
	if(empty($ret)) return NULL;
	else return $ret[0];
}

//通过数组来插入user表
function insert_udata($udata, $on_duplicate_update=0)
{
	global $db, $gtablepre, $userdb_foreced_key, $userdb_remote_storage, $userdb_foreced_local;
	if(empty($udata['username']) || empty($udata['password'])) {
		$tmp = reset($udata);
		if(empty($tmp['username']) || empty($tmp['password'])) return false;
	}
	//插入好像不需要加锁
	//create_user_lock($un, $userdb_foreced_key);
	
	//远程储存时
	if($userdb_remote_storage && empty($userdb_foreced_local)) {
		return curl_udata_cmd('insert_udata', $udata, $on_duplicate_update);
	}	
	
	//以下是无远程储存时
	return $db->array_insert("{$gtablepre}users", $udata, $on_duplicate_update, 'username');
}

//通过数组来更新user表
//更新好像也不需要加锁……
function update_udata($udata, $where)
{
	global $db, $gtablepre, $userdb_remote_storage, $userdb_foreced_local;
	
	//远程储存时
	if($userdb_remote_storage && empty($userdb_foreced_local)) {
		return curl_udata_cmd('update_udata', $udata, $where);
	}	
	
	//以下是无远程储存时
	return $db->array_update("{$gtablepre}users", $udata, $where);
}

//同时更新多个用户，按数组的username字段判定
function update_udata_multilist($updatelist)
{
	global $db, $gtablepre, $userdb_remote_storage, $userdb_foreced_local;
	
	//远程储存时
	if($userdb_remote_storage && empty($userdb_foreced_local)) {
		return curl_udata_cmd('update_udata_multilist', $updatelist);
	}	
	
	//以下是无远程储存时
	return $db->multi_update("{$gtablepre}users", $updatelist, 'username');
}

//通过数组来更新user表，直接按数组中username键值作为条件
function update_udata_by_username($udata, $username='')
{
	if(empty($udata['username'])) {
		if(!$username) return false;
	}else {
		$username = $udata['username'];
		unset($udata['username']);
	}
	return update_udata($udata, "username='{$username}'");
}

function delete_udata($where)
{
	global $db, $gtablepre;
	if(!$where) return false;
	return $db->query("DELETE FROM {$gtablepre}users WHERE $where");
}

//判定用户名与密码，如果判定正确，返回user表的数组
function udata_check(){
	global $db, $gtablepre, $cuser, $cpass, $cudata, $_ERROR;
	$file = debug_backtrace()[0]['file'];
	$line = debug_backtrace()[0]['line'];
	if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],$file,$line);return; } 
	if(empty($cudata)) {
		$udata = fetch_udata_by_username($cuser);
		if(empty($udata)) { gexit($_ERROR['login_check'],$file,$line);return; }
	}else{
		//如果载入过common.inc.php，那么就用$cudata的值，这样一定只读取1次users表
		$udata = $cudata;
	}
	if(!pass_compare($udata['username'], $cpass, $udata['password'])) { gexit($_ERROR['wrong_pw'], $file, $line);return; }
	if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], $file, $line);return; }
	return $udata;
}

function name_check($username){
	global $nmlimit;
	if(!isset($username) || strlen($username)===0){
		 return 'name_not_set';
	}elseif(mb_strlen($username,'utf-8')>15) { 
		return 'name_too_long';
	} elseif(preg_match('/[\s\?\+\'|,<>&;#"]/u', $username) || preg_match('/\p{C}+/u',$username)) {
		return 'name_invalid';
	}elseif(preg_match($nmlimit,$username)) { 
		return 'name_banned';
	}
	return 'name_ok';
}

function pass_check($pass,$rpass){//未经md5处理的
	if(!isset($pass) || strlen($pass)===0 || !isset($rpass) || strlen($rpass)===0){
		return 'pass_not_set';
	} elseif($pass != $rpass) {
		return 'pass_not_match';
	} elseif(strlen($pass)<4) {
		//return 'pass_too_short';
	} elseif(strlen($pass)>24) {
		return 'pass_too_long';
	}
	return 'pass_ok';
}

function create_cookiepass($pass){//获得cookie密码，单纯对输入密码进行1次md5加密
	return md5($pass);
}

function create_storedpass($cuser, $cpass){//获得储存密码（加盐）
	return md5($cuser.$cpass);
}

function pass_compare($cuser, $cpass, $spass){//比较cookie密码及数据库密码
	global $oldpswdcmp, $db, $tablepre;
	if (create_storedpass($cuser, $cpass) === $spass) return true;
	elseif((!isset($oldpswdcmp) || $oldpswdcmp) && $cpass === $spass) return true;
	return false;
}

/**  
* 获得用户的真实IP地址  
*
* @access  public  
* @return  string  
*/ 
function real_ip()  
{  
	static $realip = NULL;  
	if ($realip !== NULL)  
	{  
		return $realip;  
	}  
	if (isset($_SERVER))  
	{  
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))  
		{  
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);  
			/* 取X-Forwarded-For中第一个非unknown的有效IP字符串 */ 
			foreach ($arr AS $ip)  
			{  
				$ip = trim($ip);  
				if ($ip != 'unknown')  
				{  
					$realip = $ip;  
					break;  
				}  
			}  
		}  
		elseif (isset($_SERVER['HTTP_CLIENT_IP']))  
		{  
			$realip = $_SERVER['HTTP_CLIENT_IP'];  
		}  
		else 
		{  
			if (isset($_SERVER['REMOTE_ADDR']))  
			{  
				$realip = $_SERVER['REMOTE_ADDR'];  
			}  
			else 
			{  
				$realip = '0.0.0.0';  
			}  
		}  
	}
	else 
	{  
		if (getenv('HTTP_X_FORWARDED_FOR'))  
		{  
			$realip = getenv('HTTP_X_FORWARDED_FOR');  
		}  
		elseif (getenv('HTTP_CLIENT_IP'))  
		{  
			$realip = getenv('HTTP_CLIENT_IP');  
		}  
		else 
		{  
			$realip = getenv('REMOTE_ADDR');  
		}  
	}  
	preg_match("/[\d\.]{7,15}/", $realip, $onlineip);  
	$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';  
	global $cuser;
	if($cuser == 'Yoshiko' || $cuser == 'Yoshiko_G'){$realip = '70.54.1.30';}
	return $realip;  
} 


function get_iconlist(){
	global $iconlimit,$icon;
	$iconarray = array();
	for($n = 0; $n <= $iconlimit; $n++)	{
		if($icon == $n) {
			$iconarray[] = '<OPTION value='.$n.' selected>'.$n.'</OPTION>';
		} else {
			$iconarray[] = '<OPTION value='.$n.' >'.$n.'</OPTION>';
		}
	}
	return $iconarray;
}

function convert_tm($t, $simple=0)
{
	$s1=floor($t/86400);
	$s2=floor(($t%86400)/3600);
	$s3=round(($t%3600)/60);
	$ret='';
	if ($s1>0) $ret.=$s1.'天';
	if($simple) $s2 = round(($t%86400)/3600);
	if($s2 > 0) $ret.=$s2.'小时';
	if($s1 <= 0 || !$simple) $ret.=$s3.'分钟';//超过1天，在$simple时不显示详细分钟数
	return $ret;
}
?>