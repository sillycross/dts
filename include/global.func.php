<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

require GAME_ROOT.'./include/roommng.config.php';

//----------------------------------------
//              底层机制函数
//----------------------------------------

function mgzdecode($data)
{
	return gzinflate(substr($data,10,-8));
}
    
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
//	if ($code == 2){
//		$d = debug_backtrace();
//		$emessage .= serialize($d);
//	} 
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
	if (defined('IN_DAEMON'))
	{
		if (defined('GEXIT_RETURN_JSON'))
		{
			$gamedata['url'] = 'error.php';
			$gamedata['errormsg'] = $message;
			ob_clean();
			echo base64_encode(gzencode(compatible_json_encode($gamedata)));
		}
		else
		{
			ob_clean();
			include template('error');
		}
	}
	else
	{
		if (defined('GEXIT_RETURN_JSON'))
		{
			$gamedata['url'] = 'error.php';
			$gamedata['errormsg'] = $message;
			ob_clean();
			echo base64_encode(gzencode(compatible_json_encode($gamedata)));
			exit();
		}
		else
		{
			ob_clean();
			include template('error');
			exit();
		}
	}
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
			$str[gstrfilter($key)] = gstrfilter($val);
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
		if (strpos($xdname,'./include/modules/')===0)
			$xdname=substr($xdname,strlen('./include/modules/'));
		else  $xdname=substr($xdname,strlen('./gamedata/run/'));
		$xdname=str_replace('/','_',$xdname);
		$xdname=str_replace("\\",'_',$xdname);//for windows
		$xbname=basename($file);
		$objfile = GAME_ROOT.'./gamedata/templates/'.$templateid.'_mod_'.$xdname.'_'.$xbname.'.tpl.php';
	}
	/*
	if(TEMPLATEID != 1 && $templateid != 1 && !file_exists($tplfile)) {
		return template($file, 1, './templates/default/');
	}
	*/
	global $___TEMP_template_force_refresh;
	if($tplrefresh == 1 || (isset($___TEMP_template_force_refresh) && $___TEMP_template_force_refresh==1)) {
		if ((!file_exists($objfile) || filemtime($tplfile) > filemtime($objfile)) || (isset($___TEMP_template_force_refresh) && $___TEMP_template_force_refresh==1)) {
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
	global $tablepre, $gtablepre, $cookiedomain, $cookiepath, $now, $_SERVER;
	setcookie(($prefix ? $gtablepre : '').$var, $value,
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
	strpos($filename,'..')!==false && debug_print_backtrace() && exit('Forbidden');
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
	$check && strpos($filename,'..')!==false && debug_print_backtrace() && exit('Forbidden');
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

function clear_dir($dirName, $keep_root = 0)	//递归清空目录
{
	if ($dirName[strlen($dirName)-1]=='/') $dirName=substr($dirName,0,-1);
	if ($handle=opendir($dirName)) 
	{
		while (($item=readdir($handle))!==false) 
		{
			if ($item!='.' && $item!='..' && $item!='.gitignore') 
			{
				if (is_dir($dirName.'/'.$item)) 
				{
					clear_dir($dirName.'/'.$item,0);
				} else {
					if (!unlink($dirName.'/'.$item))
					{
						//__SOCKET_WARNLOG__("clear_dir错误：无法删除文件。");
					}
				}
			}
		}
		closedir($handle);
		if (!$keep_root)
			if (!rmdir($dirName))
			{
				//__SOCKET_WARNLOG__("clear_dir错误：无法删除目录{$dirName}。");
			}
		
	}
	else
	{
		//__SOCKET_WARNLOG__('clear_dir错误: 进入目录'.$dirname."失败。");
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

function compatible_json_encode(&$data)
{	
	//提供了json_encode的php版本直接使用自带的，否则使用JSON.php
	if (!function_exists('json_encode'))
	{
		require_once GAME_ROOT.'./include/JSON.php';
		$json = new Services_JSON();
		$jdata = $json->encode($data);
	}
	else  $jdata = json_encode($data);
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

//把一个非负整数用64进制编码/解码

function base64_char_decode($c)
{
	if ('a'<=$c && $c<='z') return ord($c)-ord('a');
	if ('A'<=$c && $c<='Z') return ord($c)-ord('A')+26;
	if ('0'<=$c && $c<='9') return ord($c)-ord('0')+52;
	if ($c=='+') return 62;
	if ($c=='-') return 63;
	return 0;
}
	
function base64_char_encode($c)
{
	if ($c>=0)
	{
		if ($c<=25) return chr(ord('a')+$c);
		if ($c<=51) return chr(ord('A')+$c-26);
		if ($c<=61) return chr(ord('0')+$c-52);
		if ($c==62) return '+';
		if ($c==63) return '-';
	}
	return ' ';
}

function base64_encode_number($val, $len)
{
	$ret='';
	for ($i=0; $i<$len; $i++)
	{
		$ret=base64_char_encode($val%64).$ret;
		$val=(int)floor($val/64);
	}
	return $ret;
}

function base64_decode_number($val)
{
	$ret=0;
	for ($i=0; $i<strlen($val); $i++)
	{
		$ret=$ret*64+base64_char_decode($val[$i]);
	}
	return $ret;
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

////暂时丢在这……
function set_credits(){
	global $db,$gtablepre,$tablepre,$winmode,$gamenum,$winner,$pdata,$now,$gametype;
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE type='0'");
	$list = $creditlist = $updatelist = Array();
	while($data = $db->fetch_array($result)){
		$list[$data['name']]['players'] = $data;
	}	
	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE lastgame='$gamenum'");
	while($data = $db->fetch_array($result)){
		$list[$data['username']]['users'] = $data;
	}
	eval(import_module('sys'));
	foreach($list as $key => $val){
		if(isset($val['players']) && isset($val['users'])){
			$credits = get_credit_up($val['players'],$winner,$winmode) + $val['users']['credits'];
			$gold = get_gold_up($val['players'],$winner,$winmode) + $val['users']['gold'];
			$validgames = $val['users']['validgames'] + 1;
			$wingames = $key == $winner ? $val['users']['wingames'] + 1 : $val['users']['wingames'];
			//$obtain = get_honour_obtain($val['players'],$val['users']);
			//$honour = $val['users']['honour'] . $obtain;
			$lastwin=$val['users']['lastwin'];
			//首胜已放入每日任务
			/*if (($winner==$val['players']['name'])&&(($now-$lastwin)>72000)&&(!in_array($gametype,$qiegao_ignore_mode))){
				if ($lastwin==0) $gold+=800;//帐号首次获胜
				$lastwin=$now;
				$gold+=200;
			}*/
			$updatelist[] = Array('username' => $key, 'credits' => $credits, 'wingames' => $wingames, 'validgames' => $validgames,'lastwin'=>$lastwin,'gold'=>$gold);
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
	$db->multi_update("{$gtablepre}users", $updatelist, 'username');
//	if(!empty($udghkey)){
//		$udghkey = implode(',',$udghkey);
//		$db->multi_update("{$tablepre}players", $upghlist, 'name', "name IN ($udghkey)");
//	}
	return;
}

function get_credit_up($data,$winner = '',$winmode = 0){
	global $gametype;
	eval(import_module('sys'));
	if (in_array($gametype,$qiegao_ignore_mode)) return 0;
	if($data['name'] == $winner){//获胜
		if($winmode == 2){$up = 200;}//最后幸存+200
		elseif($winmode == 3){$up = 500;}//解禁+500
		elseif($winmode == 5){$up = 100;}//核弹+100
		elseif($winmode == 7){$up = 1200;}//解离+1200
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

function get_gold_up($data,$winner = '',$winmode = 0){
	global $gametype,$now;
	eval(import_module('sys'));
	if (in_array($gametype,$qiegao_ignore_mode)) return 0;//嘻嘻
	if($data['name'] == $winner){//获胜
		if($winmode == 3){$up = 60;}//解禁
		elseif($winmode == 7){$up = 150;}//解离
		else{$up = 40;}//其他胜利方式
	}else{$up = 10;}
	return $up;
}

?>