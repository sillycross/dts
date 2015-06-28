<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

//----------------------------------------
//              底层机制函数
//----------------------------------------

function gameerrorhandler($code, $msg, $file, $line){
	global $errorinfo;
	if(!$errorinfo){return;}
	if($code == 2){$emessage = '<b style="color:#ff0">Warning</b> ';}
	elseif($code == 4){$emessage = '<b style="color:#f00">Parse</b> ';}
	elseif($code == 8){$emessage = '<b>Notice</b> ';}
	elseif($code == 256){$emessage = '<b>User Error</b> ';}
	elseif($code == 512){$emessage = '<b>User Warning</b> ';}
	elseif($code == 1024){$emessage = '<b>User Notice</b> ';}
	else{$emessage = '<b style="color:#f00>Fatal error</b> ';}
	$emessage .= "($code): $msg in $file on line $line";
	if ($code == 1024 && $file=='/srv/http/dts-test/command.php' && function_exists('__SOCKET_WARNLOG__')) 
		__SOCKER__WARNLOG__($emessage);
	if(isset($GLOBALS['error'])){
		$GLOBALS['error'] .= '<br>'.$emessage;
	}else{
		$GLOBALS['error'] = $emessage;
	}
	return true;
}

function gexit($message = '',$file = '', $line = 0) {
	global $charset,$title,$extrahead,$allowcsscache,$errorinfo;
	include template('error');
	exit();
}

function output($content = '') {
	//if(!$content){$content = ob_get_contents();}
	//ob_end_clean();
	//$GLOBALS['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
	//echo $content;
	ob_end_flush();
}

//----------------------------------------
//              输入输出函数
//----------------------------------------

function gstrfilter($str) {
	if(is_array($str)) {
		foreach($str as $key => $val) {
			$str[$key] = gstrfilter($val);
		}
	} else {		
		if($GLOBALS['magic_quotes_gpc']) {
			$str = stripslashes($str);
		}
		$str = str_replace("'","",$str);//屏蔽单引号'
		$str = str_replace("\\","",$str);//屏蔽反斜杠/
		$str = htmlspecialchars($str,ENT_COMPAT);//转义html特殊字符，即"<>&
		$str = str_replace("___","",$str);//屏蔽连续的三个下划线，由于模块化用到了这些变量。防止注入
	}
	return $str;
}

function language($file, $templateid = 0, $tpldir = '') {
	$tpldir = $tpldir ? $tpldir : TPLDIR;
	$templateid = $templateid ? $templateid : TEMPLATEID;

	$languagepack = GAME_ROOT.'./'.$tpldir.'/'.$file.'.lang.php';
	if(file_exists($languagepack)) {
		return $languagepack;
	} elseif($templateid != 1 && $tpldir != './templates/default') {
		return language($file, 1, './templates/default');
	} else {
		return FALSE;
	}
}

function template($file, $templateid = 0, $tpldir = '') {
	global $tplrefresh;

	$tpldir = $tpldir ? $tpldir : TPLDIR;
	$templateid = $templateid ? $templateid : TEMPLATEID;

	if (substr($file,0,4)=='MOD_') $file=__MODULE_GET_TEMPLATE__($file);
	if (strpos($file,'/')===false)
	{
		$tplfile = GAME_ROOT.'./'.$tpldir.'/'.$file.'.htm';
		$objfile = GAME_ROOT.'./gamedata/templates/'.$templateid.'_'.$file.'.tpl.php';
	}
	else  
	{
		global $___MOD_CODE_ADV2;
		if ($___MOD_CODE_ADV2) 	//写死吧…… 无所谓了
		{
			$file = str_replace('include/modules','gamedata/run',$file);
			if (substr($file, -4) != '.adv') $file .= '.adv';
		}
		
		$tplfile = $file.'.htm';
		$xdname=dirname($file); 
		$xdname=substr($xdname,strlen(GAME_ROOT));
		$xdname=substr($xdname,strlen(__MOD_DIR__));
		$xdname=str_replace('/','_',$xdname);
		$xbname=basename($file);
		$objfile = GAME_ROOT.'./gamedata/templates/'.$templateid.'_mod_'.$xdname.'_'.$xbname.'.tpl.php';
	}
	/*
	if(TEMPLATEID != 1 && $templateid != 1 && !file_exists($tplfile)) {
		return template($file, 1, './templates/default/');
	}
	*/
	if($tplrefresh == 1) {
		if(!file_exists($objfile) || filemtime($tplfile) > filemtime($objfile)) {
			require_once GAME_ROOT.'./include/template.func.php';
			parse_template($tplfile, $objfile, $templateid, $tpldir);
		}
	}
	return $objfile;
}

function content($file = '') {
	ob_clean();
	include template($file);
	$content = ob_get_contents();
	ob_end_clean();
	$GLOBALS['gzipcompress'] ? ob_start('ob_gzhandler') : ob_start();
	return $content;
}

function gsetcookie($var, $value, $life = 0, $prefix = 1) {
	global $tablepre, $cookiedomain, $cookiepath, $now, $_SERVER;
	setcookie(($prefix ? $tablepre : '').$var, $value,
		$life ? $now + $life : 0, $cookiepath,
		$cookiedomain, $_SERVER['SERVER_PORT'] == 443 ? 1 : 0);
}

function clearcookies() {
	global $cookiepath, $cookiedomain, $game_uid, $game_user, $game_pw, $game_secques, $adminid, $groupid, $credits;
	dsetcookie('auth', '', -86400 * 365);

	$game_uid = $adminid = $credits = 0;
	$game_user = $game_pw = $game_secques = '';
}

function config($file = '', $cfg = 1) {
	$cfgfile = file_exists(GAME_ROOT."./gamedata/cache/{$file}_{$cfg}.php") ? GAME_ROOT."./gamedata/cache/{$file}_{$cfg}.php" : GAME_ROOT."./gamedata/cache/{$file}_1.php";
	return $cfgfile;
}

function dir_clear($dir) {
	$directory = dir($dir);
	while($entry = $directory->read()) {
		$filename = $dir.'/'.$entry;
		if(is_file($filename)) {
			unlink($filename);
		}
	}
	$directory->close();
}

//读取文件
function readover($filename,$method="rb"){
	strpos($filename,'..')!==false && exit('Forbidden');
	//$filedata=file_get_contents($filename);
	$handle=fopen($filename,$method);
	if(flock($handle,LOCK_SH)){
		$filedata='';
		while (!feof($handle)) {
   		$filedata .= fread($handle, 8192);
		}
		//$filedata.=fread($handle,filesize($filename));
		fclose($handle);
	} else {exit ('Read file error.');}
	return $filedata;
}

//写入文件
function writeover($filename,$data,$method="rb+",$iflock=1,$check=1,$chmod=1){
	$check && strpos($filename,'..')!==false && exit('Forbidden');
	touch($filename);
	$handle=fopen($filename,$method);
	if($iflock){
		if(flock($handle,LOCK_EX)){
			fwrite($handle,$data);
			if($method=="rb+") ftruncate($handle,strlen($data));
			fclose($handle); 
		} else {exit ('Write file error.');}
	} else {
		fwrite($handle,$data);
		if($method=="rb+") ftruncate($handle,strlen($data));
		fclose($handle); 
	}
	$chmod && chmod($filename,0777);
	return;
}

//打开文件，以数组形式返回
function openfile($filename){
	$filedata=readover($filename);
	$filedata=str_replace("\n","\n<:game:>",$filedata);
	$filedb=explode("<:game:>",$filedata);
	$count=count($filedb);
	if($filedb[$count-1]==''||$filedb[$count-1]=="\r"){unset($filedb[$count-1]);}
	if(empty($filedb)){$filedb[0]='';}
	return $filedb;
}

function clear_dir($dirName)	//递归清空目录
{
	if ($dirName[strlen($dirName)-1]=='/') $dirName=substr($dirName,0,-1);
	if ($handle=opendir($dirName)) 
	{
		while (($item=readdir($handle))!==false) 
		{
			if ($item!='.' && $item!='..') 
			{
				if (is_dir($dirName.'/'.$item)) 
				{
					clear_dir($dirName.'/'.$item);
				} else {
					if (!unlink($dirName.'/'.$item))
					{
						__SOCKET_WARNLOG__("clear_dir错误：无法删除文件。");
					}
				}
			}
		}
		closedir($handle);
		if (!rmdir($dirName))
		{
			__SOCKET_WARNLOG__("clear_dir错误：无法删除目录{$dirName}。");
		}
		
	}
	else
	{
		__SOCKET_WARNLOG__('clear_dir错误: 进入目录'.$dirname."失败。");
	}
}

function mymkdir($pa)
{
	mkdir($pa); chmod($pa, 0777);
}

function create_dir($pa)	//建立目录（自动创建不存在的父文件夹），别用父目录符号“../”
{
	while (1)
	{
		if ($pa[strlen($pa)-1]=='/') $pa=substr($pa,0,-1);
		if ($pa=='') return;
		if (basename($pa)=='.') $pa=substr($pa,0,-1); else break;
	}
	$parent=substr($pa,0,-strlen(basename($pa)));
	if (!file_exists($parent) || !is_dir($parent))
	{
		create_dir($parent);
	}
	if (!file_exists($pa) || !is_dir($pa))
	{
		if (file_exists($pa)) unlink($pa);	//如果是文件而不是目录，删掉
		mymkdir($pa);
	}
}

function copy_dir($source, $destination)		//递归复制目录
{   
	if(!is_dir($destination)) mymkdir($destination);
	if ($source[strlen($source)-1]=='/') $source=substr($source,0,-1);
	if ($destination[strlen($destination)-1]=='/') $destination=substr($destination,0,-1);
	if ($handle=opendir($source)) 
	{
		while (($entry=readdir($handle))!==false)
		{   
			if(($entry!=".")&&($entry!=".."))
			{   
				if(is_dir($source."/".$entry))
				{ 
					copy_dir($source."/".$entry,$destination."/".$entry);
				} 
				else
				{   
					if (!copy($source."/".$entry,$destination."/".$entry))
					{
						echo "&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"red\">copy_dir错误</font>：无法复制文件{$source}/{$entry}到{$destination}/{$entry}。<br>";
					}
				}   
			}
		}   
	}   
	else
	{
		echo '&nbsp;&nbsp;&nbsp;&nbsp;<font color=\"red\">copy_dir错误</font>: 进入目录'.$source.'失败。<br>';
	}
}

function compatible_json_encode($data){	
	$jdata = json_encode($data);
	return $jdata;	
}

function getmicrotime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

function putmicrotime($t_s,$t_e,$file,$info)
{
	$mtime = ($t_e - $t_s)*1000;
	writeover( $file.'.txt',"$info ；执行时间：$mtime 毫秒 \n",'ab');
}

function get_script_runtime($pagestartime)
{
	$pageendtime = microtime();
	$p_starttime = explode(" ",$pagestartime);
	$p_endtime = explode(" ",$pageendtime);
	$p_totaltime = $p_endtime[0]-$p_starttime[0]+$p_endtime[1]-$p_starttime[1];
	$timecost = sprintf("%.2f",$p_totaltime); 
	return $timecost;
}

function check_alnumudline($key)
{
	$key=(string)$key;
	for ($i=0; $i<strlen($key); $i++)
	{
		if (!(('a'<=$key[$i] && $key[$i]<='z') || ('A'<=$key[$i] && $key[$i]<='Z') || $key[$i]=='_' || ('0'<=$key[$i] && $key[$i]<='9')))
			return false;
	}
	return true;
}

function swap(&$a, &$b)
{
	$c=$a; $a=$b; $b=$c;
}

//因为调用次数太多，懒得一个一个改了
function save_gameinfo() {	
	\sys\save_gameinfo();
}

function addnews($t = 0, $n = '',$a='',$b='',$c = '', $d = '', $e = '') {
	\sys\addnews($t, $n,$a,$b,$c, $d, $e);
}

function getchat($last,$team='',$limit=0) {
	return \sys\getchat($last,$team,$limit);
}

function systemputchat($time,$type,$msg = ''){
	\sys\systemputchat($time,$type,$msg );
}

//////////////////////////////

//这个函数应该完成所有外层错误处理（通过了这个函数就代表正式进入游戏了，不应该返回没有登录或者游戏已经结束之类的错误）
function do_ingame_error_checks()
{
	eval(import_module('sys'));
	//判断是否进入游戏
	if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); } 

	$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");

	if(!$db->num_rows($result)) { header("Location: valid.php");exit(); }

	$pdata = $db->fetch_array($result);

	//判断是否密码错误
	if($pdata['pass'] != $cpass) {
		$tr = $db->query("SELECT `password` FROM {$tablepre}users WHERE username='$cuser'");
		$tp = $db->fetch_array($tr);
		$password = $tp['password'];
		if($password == $cpass) {
			$db->query("UPDATE {$tablepre}players SET pass='$password' WHERE name='$cuser'");
		} else {
			gexit($_ERROR['wrong_pw'],__file__,__line__);
		}
	}
	
	if($gamestate == 0) {
		$gamedata['url'] = 'end.php';
		ob_clean();
		$jgamedata = compatible_json_encode($gamedata);
		echo $jgamedata;
		ob_end_flush();
		exit();
	}
}

////暂时丢在这……
function set_credits(){
	global $db,$tablepre,$winmode,$gamenum,$winner,$pdata;
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE type='0'");
	$list = $creditlist = $updatelist = Array();
	while($data = $db->fetch_array($result)){
		$list[$data['name']]['players'] = $data;
	}	
	$result = $db->query("SELECT * FROM {$tablepre}users WHERE lastgame='$gamenum'");
	while($data = $db->fetch_array($result)){
		$list[$data['username']]['users'] = $data;
	}
	foreach($list as $key => $val){
		if(isset($val['players']) && isset($val['users'])){
			$credits = get_credit_up($val['players'],$winner,$winmode) + $val['users']['credits'];
			$validgames = $val['users']['validgames'] + 1;
			$wingames = $key == $winner ? $val['users']['wingames'] + 1 : $val['users']['wingames'];
			//$obtain = get_honour_obtain($val['players'],$val['users']);
			//$honour = $val['users']['honour'] . $obtain;
			$updatelist[] = Array('username' => $key, 'credits' => $credits, 'wingames' => $wingames, 'validgames' => $validgames);
//			if(!empty($obtain)){
//				$udghkey[] = $key;
//				if($pdata['name'] == $key){
//					$pdata['gainhonour'] = $obtain;
//				}else{
//					$udghlist[] = Array('name' => $key, 'gainhonour' => $obtain);
//				}
//			}			
		}
	}
	$db->multi_update("{$tablepre}users", $updatelist, 'username');
//	if(!empty($udghkey)){
//		$udghkey = implode(',',$udghkey);
//		$db->multi_update("{$tablepre}players", $upghlist, 'name', "name IN ($udghkey)");
//	}
	return;
}

function get_credit_up($data,$winner = '',$winmode = 0){
	if($data['name'] == $winner){//获胜
		if($winmode == 2){$up = 200;}//最后幸存+200
		elseif($winmode == 3){$up = 500;}//解禁+500
		elseif($winmode == 5){$up = 100;}//核弹+100
		else{$up = 50;}//其他胜利方式+50（暂时没有这种胜利方式）
	}
	elseif($data['hp']>0){$up = 25;}//存活但不是获胜者+25
	else{$up = 10;}//死亡+5
	if($data['killnum']){
		$up += $data['killnum'] * 2;//杀一玩家/NPC加2
	}
	if($data['lvl']){
		$up += round($data['lvl'] /2);//等级每2级加1
	}
//	$skill = $data['wp'] + $data['wk'] + $data['wg'] + $data['wc'] + $data['wd'] + $data['wf'];
//	$maxskill = ;
	$skill = array ($data['wp'] , $data['wk'] , $data['wg'] , $data['wc'] , $data['wd'] , $data['wf']);
	rsort ( $skill );
	$maxskill = $skill[0];
	$up += round($maxskill / 25);//熟练度最高的系每25点熟练加1
	$up += round($data['money']/500);//每500点金钱加1
//	foreach(Array('wp','wk','wg','wc','wd','wf') as $val){
//		$skill = $data[$val];
//		$up += round($skill / 100);//每100点熟练加1
//	}
	return $up;
}

?>