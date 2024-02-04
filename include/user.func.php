<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

//无需加锁的CURSCRIPT值
//在create_user_lock及fetch_udata里都用到
function user_lock_unnecessity()
{
	if(!defined('CURSCRIPT')) return true;
	elseif(in_array(CURSCRIPT, array('roomupdate','roomcmd','chat','valid','end','winner','rank','alive','help','news','aranking_receive'))) return true;
	elseif (!empty($GLOBALS['page']) && in_array($GLOBALS['page'], array('command_roomcmd','command_valid','command_end','command_winner','command_rank','command_alive','command_help','command_news','command_aranking'))) return true;
	return false;
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
	//不过这段对远程是失效的
	//远程的情况下，目前sys\init()是强制本地加锁，不影响这里
	elseif(!$un || user_lock_unnecessity()) return 3;
	$dir = GAME_ROOT.'./gamedata/tmp/userlock/';
	$file = $un.'.nlk';
	$lstate = check_lock($dir, $file, 3000, $key);//最多允许3秒等待，之后穿透
	$res = 2;
	if(!$lstate) {
		if(create_lock($dir, $file, $key)) $res = 0;
	}
	//writeover('b.txt', $key.' '.$res."\r\n", 'ab+');
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
		global $userdb_remote_storage_sign, $userdb_remote_storage_pass, $userdb_remote_reconnect_times;
		$url = $userdb_remote_storage;
		$context = array(
			'sign' => $userdb_remote_storage_sign,
			'pass' => timestamp_salt($userdb_remote_storage_pass),
			'command' => 'release_user_lock_from_pool',
			'key' => $userdb_remote_key,
		);
		for($i=0;$i<$userdb_remote_reconnect_times;$i++) {
			$ret = curl_post($url, $context);
			if(strpos(gdecode($ret,1), 'Release success') !== false) break;
		}
		$userdb_remote_key = '';
	}
}

//连接远程数据库，重试$userdb_remote_reconnect_times次
function curl_udata_cmd($command, $para1='', $para2='', $para3='', $para4='', $para5=''){
	global $userdb_remote_storage, $userdb_remote_storage_sign, $userdb_remote_storage_pass, $userdb_remote_reconnect_times, $userdb_remote_reconnect_duration, $userdb_remote_key;
	if(!$userdb_remote_storage) return;
	$userdb_remote_reconnect_times = max(1, $userdb_remote_reconnect_times);
	if(empty($userdb_remote_key)) $userdb_remote_key = uniqid();
	$url = $userdb_remote_storage;
	//参数处理
	if(in_array($command, array('insert_udata', 'update_udata', 'update_udata_multilist'))) {
		$para1 = gencode($para1);
	}
	$context = array(
		'sign' => $userdb_remote_storage_sign,
		'pass' => timestamp_salt($userdb_remote_storage_pass),
		'command' => $command,
		'para1' => $para1,
		'para2' => $para2,
		'para3' => $para3,
		'para4' => $para4,
		'para5' => $para5,
		'key' => $userdb_remote_key,
	);
	for($t=0;$t<$userdb_remote_reconnect_times;$t++){
		$ret_raw = curl_post($url, $context);
		$ret = gdecode($ret_raw,1);
		if(NULL!==$ret || strpos($ret_raw, 'Error')===0) break;
		else usleep($userdb_remote_reconnect_duration * 1000);//挂起0.2秒再试
	}
	//writeover('a.txt', debug_backtrace()[2]['file'].' '.debug_backtrace()[3]['file'].' '.$userdb_remote_key."\r\n", 'ab+');
	if(NULL===$ret || ('fetch_udata' == $command && !is_array($ret))) {
		$error_message = '连接远程数据库失败';
		if(strpos($ret_raw, 'Error')===0) $error_message .= ' '.$ret_raw;
		if(!in_array(CURSCRIPT, array('chat'))) gexit($error_message,__file__,__line__);
		else {
			global $error;
			$error = $error_message;
		}
	}
	return $ret;
}

/*
function update_udata_from_remote($udata, $username)
{
	global $db, $gtablepre, $userdb_forced_local;
	$tmp_userdb_forced_local = $userdb_forced_local;
	$userdb_forced_local = 1;
	$flag = $db->query("SELECT uid FROM {$gtablepre}users WHERE username='$username'");
	$flag = $db->num_rows($flag);
	if(!$flag) {
		$udata = curl_udata_cmd('fetch_udata', '*', "username='$username'");
		$udata = reset($udata);
	}
	$db->array_update("{$gtablepre}users", $udata, "username='$username'");
	$userdb_forced_local = $tmp_userdb_forced_local;
}*/

//获取用户数据的通用函数，会自动获取远端数据
//返回相当于fetch_array得到的数组
//$keytype==0为无键名，为1是username当键名，为2是uid当键名
//$userdb_forced_local为真时强制查询本地
function fetch_udata($fields='', $where='', $sort='', $keytype=0, $nolock=0){
	global $db, $gtablepre, $userdb_remote_storage, $userdb_forced_local, $userdb_forced_key;

	//新建usettings字段
	if(1){
		$column_existed = 0;
		$result = $db->query("SHOW COLUMNS FROM {$gtablepre}users");
		while($r = $db->fetch_array($result)){
			if($r['Field'] == 'u_settings') {
				$column_existed = 1;
				break;
			}
		}
		if(!$column_existed) {
			$db->query("ALTER TABLE {$gtablepre}users ADD COLUMN `u_settings` text NOT NULL DEFAULT '' AFTER `n_achievements`");
		}
	}

	//缺省查询
	if(empty($fields)) $fields = '*';
	if(empty($where)) $where = '1';
	$ret = array();
	//如果设置了远程数据库储存
	if($userdb_remote_storage && empty($userdb_forced_local)) {
		if(user_lock_unnecessity()) $nolock = 1;
		return curl_udata_cmd('fetch_udata', $fields, $where, $sort, $keytype, $nolock);
	}	
	
	//以下是无远程储存时
	//如果where里有username，直接加锁；否则先查询username再加锁，加完锁才真查询
	if(!$nolock && strpos($fields, 'COUNT(')===false){
		$unlist = get_where_username($where);
		if(!empty($unlist)){
			if(sizeof($unlist) <= 500) {
				foreach($unlist as $un) {
					create_user_lock($un, $userdb_forced_key);
				}
			}
		}else{
			$qry = "SELECT username FROM {$gtablepre}users WHERE {$where} ";
			if(!empty($sort)) $qry .= "ORDER BY $sort";
			$result = $db->query($qry);
			if($db->num_rows($result)) {
				while($r = $db->fetch_array($result)) {
					$un = $r['username'];
					create_user_lock($un, $userdb_forced_key);
				}
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
	//有远程数据库、单条返回记录时，判定本地数据是否可能不合法，如果不合法则读远程数据并直接覆盖本地
	//可能不合法：无用户、空密码，都要去远程再验证一次
	if($userdb_remote_storage && sizeof($ret) <= 1) {
		if(empty($ret) || (isset(reset($ret)['password']) && empty(reset($ret)['password']))) {
			$ret_remote = curl_udata_cmd('fetch_udata', '*', $where, $sort, $keytype, $nolock);
			if(!empty($ret_remote)) {
				$udarr = $ret_remote;
				foreach($udarr as &$iv) unset($iv['uid']);
				if(empty($ret)) {
					$db->array_insert("{$gtablepre}users", $udarr);
				}else{
					if(sizeof($udarr) > 1) {
						$db->multi_update("{$gtablepre}users", $udarr, 'username');
					}else{
						$udarr = reset($udarr);
						$db->array_update("{$gtablepre}users", $udarr, "username='{$udarr['username']}'");
					}
				}
				$ret = $ret_remote;
			}
		}		
	}
	
	return $ret;
}

function get_where_username($where)
{
	$ret = array();
	if(preg_match('/username\s*?IN\s*?\((.*?)\)/s', $where, $matches)) {
		$wherecont = explode(',',$matches[1]);
		foreach($wherecont as $un){
			$ret[] = trim($un,"' \n\r\t");
		}
	}elseif(preg_match('/username\s*?=\s*?\'(.*?)\'/s', $where, $matches)){
		$ret[] = $matches[1];
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
	global $db, $gtablepre, $userdb_forced_key, $userdb_remote_storage, $userdb_forced_local;
	if(empty($udata['username']) || empty($udata['password'])) {
		$tmp = reset($udata);
		if(empty($tmp['username']) || empty($tmp['password'])) return false;
	}
	//插入好像不需要加锁
	//create_user_lock($un, $userdb_forced_key);
	
	//远程储存时
	if($userdb_remote_storage && empty($userdb_forced_local)) {
		$ret = curl_udata_cmd('insert_udata', $udata, $on_duplicate_update);
		$db->array_insert("{$gtablepre}users", $udata, 1, 'username');
		return $ret;
	}	
	
	//非远程储存时
	return $db->array_insert("{$gtablepre}users", $udata, $on_duplicate_update, 'username');
}

//通过数组来更新user表
//更新好像也不需要加锁……
function update_udata($udata, $where)
{
	global $db, $gtablepre, $userdb_remote_storage, $userdb_forced_local;
	
	//远程储存时
	if($userdb_remote_storage && empty($userdb_forced_local)) {
		$ret = curl_udata_cmd('update_udata', $udata, $where);
		
		//检查本地是否有数据，有则更新，无则不管
		$db->array_update("{$gtablepre}users", $udata, $where);
		//本来想没有数据则插入的，但其实这里插入的话会导致本地数据严重缺失。插入在fetch的时候做
//		$unlist = get_where_username($where);
//		if(!empty($unlist)) {
//			foreach ($unlist as $un) {
//				$ud = $udata;
//				if(!isset($ud['username'])) $ud['username'] = $un;
//				if(isset($ud['username'])) $db->array_insert("{$gtablepre}users", $ud, 1, 'username');
//			}
//		}
		
		return $ret;
	}	
	
	//非远程储存时
	return $db->array_update("{$gtablepre}users", $udata, $where);
}

//同时更新多个用户，按数组的username字段判定
function update_udata_multilist($updatelist)
{
	global $db, $gtablepre, $userdb_remote_storage, $userdb_forced_local;
	
	//远程储存时
	if($userdb_remote_storage && empty($userdb_forced_local)) {
		$ret = curl_udata_cmd('update_udata_multilist', $updatelist);
		
		//检查本地是否有数据，有则更新，无则不管
		$db->multi_update("{$gtablepre}users", $updatelist, 'username');
		return $ret;
	}	
	
	//非远程储存时
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
	global $db, $gtablepre, $cuser, $cpass, $cudata, $_ERROR, $userdb_remote_storage;
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
	if(!pass_compare($udata['username'], $cpass, $udata['password'])) { 
		//如果是强制读取本地时密码错误，则再验证一次远端
		$wrong_pw_flag = 1;
		if($userdb_remote_storage && ('game' == CURSCRIPT || 'chat' == CURSCRIPT)) {
			$udata_remote = curl_udata_cmd('fetch_udata', 'password', "username='{$udata['username']}'");
			$udata_remote = reset($udata_remote);
			if(!empty($udata_remote['password']) && pass_compare($udata['username'], $cpass, $udata_remote['password'])) {
				$wrong_pw_flag = 0;
				$db->array_update("{$gtablepre}users", array('password' => $udata_remote['password']), "username='{$udata['username']}'");
				$udata['password'] = $udata_remote['password'];
			}
		} 
		if($wrong_pw_flag) {
			gexit($_ERROR['wrong_pw'], $file, $line);
			return; 
		}
	}
	if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], $file, $line);return; }
	return $udata;
}

//判定用户取的名字是否合规：非空，不能超过15字符，不能有某些特殊字符，不能包含被禁关键词
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
	} elseif(mb_strlen($pass,'utf-8')>24) {
		return 'pass_too_long';
	}
	return 'pass_ok';
}

function create_cookiepass($pass){//获得cookie密码，单纯对输入密码进行1次md5加密
	return md5($pass);
}

function create_storedpass($cuser, $cpass){//获得储存密码（加盐，用用户名做盐）
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
		# If a site is proxied through CloudFLare, we use the CloudFLare value here to grab the original IP of the user.
		elseif (isset($_SERVER['HTTP_CF_CONNECTING_IP']))  
		{  
			$realip = $_SERVER['HTTP_CF_CONNECTING_IP'];  
		} 
		elseif (isset($_SERVER['REMOTE_ADDR']))  
		{  
			$realip = $_SERVER['REMOTE_ADDR'];  
		}  
		else 
		{  
			$realip = '0.0.0.0';  
		}
	}
	elseif (getenv('HTTP_X_FORWARDED_FOR'))  
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
	  
	preg_match("/[\d\.]{7,15}/", $realip, $onlineip);  
	$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';  
//	global $cuser;
//	if($cuser == 'Yoshiko' || $cuser == 'Yoshiko_G'){$realip = '70.54.1.30';}
	return $realip;  
} 


function get_iconlist(){
	global $iconlimit,$icon;
	$iconarray = array();
	for($n = 0; $n <= $iconlimit; $n++)	{
		$n_show = $n;
		if(!$n) $n_show .= '（随机）';
		$iconarray[] = '<option value='.$n.($icon == $n ? ' selected' : '').'>'.$n_show.'</option>';
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
	if($simple && $s1 > 0 && $s3 > 30) $s2 += 1;//如果$simple，有天数显示（则不显示分钟数），那么如果分钟数大于30则小时数+1
	if($s2 > 0) $ret.=$s2.'小时';
	if($s1 <= 0 || !$simple) $ret.=$s3.'分钟';//超过1天，在$simple时不显示详细分钟数
	return $ret;
}

//以服务器时间（分钟数）为密码加盐
function timestamp_salt($pass, $offset=0){
	$time = (int)ceil(time()/60) + $offset;
	return sha1($time.$pass);
}

//比较传来的加盐密码与本地加盐密码（误差3分钟）
function compare_ts_pass($rsha, $pass){
	foreach(array(0, -1, 1, -2, 2, -3, 3) as $o) {
		if($rsha === timestamp_salt($pass, $o)) {
			return true;
		}
	}
	return false;
}

/* End of file user.func.php */
/* Location: /include/user.func.php */