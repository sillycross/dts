<?php
@ob_end_clean();
header('Content-Type: text/HTML; charset=utf-8'); // 以事件流的形式告知浏览器进行显示
header('Cache-Control: no-cache');         // 告知浏览器不进行缓存
header('X-Accel-Buffering: no');           // 关闭加速缓冲
@ini_set('implicit_flush',1);
ob_implicit_flush(1);
set_time_limit(0);
@ini_set('zlib.output_compression',0);

define('IN_MODULEMNG', TRUE);
define('IN_MODULE_ACTIVATE', TRUE);
require './include/common.inc.php';
require GAME_ROOT.'./include/modulemng/modulemng.func.php';

if (!file_exists(GAME_ROOT.'./gamedata/modules.list.pass.php'))
{
	echo '文件不存在。<br>';
	die();
}

register_shutdown_function('shutDownFunction');

function shutDownFunction() { 
	$error = error_get_last();

	global $faillog;
	if ($faillog!='')
	{
		echo $faillog;
		echo '<br>或者因为如下原因：<br>';
		echo '['.$error['type'].'] '.$error['message'].' at line '.$error['line'].' in file '.$error['file'].'<br>';
		echo '<br><a href="modulemng.php?mode=edit" style="text-decoration: none"><span><font color="blue">[返回编辑模式]</font></span></a><br>';   
		die();
	}
}

$faillog = '<font color="red">看起来遇到了一个错误。请检查模块是否工作正常，然后返回编辑模式再试一次。<br>修改没有保存。<br></font>';

$codelist=Array(); 
$quickmode = isset($_GET['mode']) && 'quick'==$_GET['mode'] ? 1 : 0;

global $___MOD_CODE_ADV1;

echo str_repeat(" ",1024);
echo '<script language="javascript"> 
$z=setInterval(function() { window.scroll(0,document.body.scrollHeight); },100); 
function stop() { window.scroll(0,document.body.scrollHeight); clearInterval($z); }</script>
<body onload=stop(); ></body>'; 

echo '<br><font size=4 color="red">请务必阅读以下运行日志，确定没有错误。</font><br><br>'; ob_end_flush(); flush();

if ($___MOD_CODE_ADV1)
{
	echo '<font color="blue">正在进行代码预处理CODE_ADV1..</font><br>'; ob_end_flush(); flush();
	
	global $___TEMP_DRY_RUN, $___TEMP_DRY_RUN_COUNTER;
	$___TEMP_DRY_RUN=0;
		
	$file=GAME_ROOT.'./gamedata/modules.list.pass.php';
	$content=openfile($file);
	$in=sizeof($content); 
	$___TEMP_MOD_LIST_n=0;
	for ($i=0; $i<$in; $i++)
	{
		list($modname,$modpath,$inuse) = explode(',',$content[$i]);
		if ($inuse==1)
		{
			$___TEMP_MOD_LIST_n++; 
			$___TEMP_MOD_LOAD_CMD[$___TEMP_MOD_LIST_n]=GAME_ROOT.'./include/modules/'.$modpath.'module.inc.php';
			$___TEMP_MOD_LOAD_NAME[$___TEMP_MOD_LIST_n]=$modname;
			$___TEMP_MOD_LOAD_PATH[$___TEMP_MOD_LIST_n]=$modpath;
		}
		unset($modname); unset($modepath); unset($inuse);
	}
	unset($i); unset($content); unset($in); unset($file);

	$___TEMP_all=$___TEMP_MOD_LIST_n;
	$___TEMP_defined_funclist = Array();
	
	for ($___TEMP_MOD_LOAD_i=1; $___TEMP_MOD_LOAD_i<=$___TEMP_MOD_LIST_n; $___TEMP_MOD_LOAD_i++)
	{
		echo '开始处理模块'.$___TEMP_MOD_LOAD_NAME[$___TEMP_MOD_LOAD_i].'... '; ob_end_flush(); flush();
		
		$___TEMP_defined_funclist[$___TEMP_MOD_LOAD_i]=Array();
		
		//获取init部分新定义的常量
		global $___TEMPV_a,$___TEMPC_a,$___TEMPC_b,$___TEMPV_b;
		$___TEMPV_a=array_keys(get_defined_vars());
		$___TEMPC_a=get_defined_constants();
		//这一步载入模块时也同时会确认init函数没有import sys或input，实际的判断是在modules.func里做的
		global $___TEMP_MOD_INITING_FLAG, $___TEMP_CUR_INITING_MODULE_NAME; 
		$___TEMP_MOD_INITING_FLAG=1; $___TEMP_CUR_INITING_MODULE_NAME = $___TEMP_MOD_LOAD_NAME[$___TEMP_MOD_LOAD_i];
		require $___TEMP_MOD_LOAD_CMD[$___TEMP_MOD_LOAD_i];
		$___TEMP_MOD_INITING_FLAG=0;
		$___TEMPC_b=get_defined_constants();
		$___TEMPV_b=array_keys(get_defined_vars());
		$str1='';
		foreach(array_diff_key($___TEMPC_b, $___TEMPC_a) as $key => $value)
		{
			ob_start();
			var_export($value);
			$ssz=ob_get_contents();
			ob_clean();
			ob_end_flush();
			$str1.='define(\''.$key.'\','.$ssz.');'."\n";
			unset($ssz);
		}
		$str2='global '; $str3=''; $str4='unset('; $str6='global '; $str7='unset('; $str8='';
		foreach(array_diff($___TEMPV_b, $___TEMPV_a) as $key)
		{
			$ss='___LOCAL_'.strtoupper($___TEMP_MOD_LOAD_NAME[$___TEMP_MOD_LOAD_i]).'__VARS__';
			if (strpos($key,$ss)===0)
			{
				$str2.='$'.$key.',';
				$str3.='$'.$key.'=&$'.substr($key,strlen($ss)).';';
				$str4.='$'.substr($key,strlen($ss)).',';
				$str6.='$'.substr($key,strlen($ss)).',';
				$str7.='$GLOBALS[\''.substr($key,strlen($ss)).'\'],';
				$str8.='$'.$key.'=$GLOBALS[\''.substr($key,strlen($ss)).'\'];';
			}
			$ss='___PRIVATE_'.strtoupper($___TEMP_MOD_LOAD_NAME[$___TEMP_MOD_LOAD_i]).'__VARS__';
			if (strpos($key,$ss)===0) 
			{
				$str3.='$'.$key.'=Array();';
				$str2.='$'.$key.',';
			}
		}
		if ($str2=='global ') $str2=''; else $str2=substr($str2,0,-1).';';
		if ($str4=='unset(') $str4=''; else $str4=substr($str4,0,-1).');';
		if ($str6=='global ') $str6=''; else $str6=substr($str6,0,-1).';';
		if ($str7=='unset(') $str7=''; else $str7=substr($str7,0,-1).');';
		$str5='';
		$nname=$___TEMP_MOD_LOAD_NAME[$___TEMP_MOD_LOAD_i];
		$___TEMP_a=get_defined_functions()['user']; $___TEMP_b=Array();
		foreach ($___TEMP_a as $___TEMP_key)
			if (strtoupper(substr($___TEMP_key,0,strlen($nname)+1))==strtoupper($nname.'\\'))
				array_push($___TEMP_b,substr($___TEMP_key,strlen($nname)+1));
		foreach ($___TEMP_b as $___TEMP_key)
			if ($___TEMP_key!='init' && $___TEMP_key!='___pre_init' && $___TEMP_key!='___post_init')
			{
				$str5.='hook_register(\''.$nname.'\',\''.$___TEMP_key.'\');';
				array_push($___TEMP_defined_funclist[$___TEMP_MOD_LOAD_i],$nname.'\\'.$___TEMP_key);
			}
		unset($___TEMP_a); unset($___TEMP_b); unset($___TEMP_key);
		$sc=$str1."\n".$str2."\n".$str3."\n".$str4."\n".$str5."\n";
		if ($___MOD_SRV)
		{
			$sc.='function ___post_init() { '.$str2."\n".$str8."\n".$str7."\n".'}';
		}
		if ($___MOD_SRV)
		{
			$sr=constant('___SAVE_MOD_'.strtoupper($nname).'_PRESET_VARS')."\n".
			'function ___pre_init() { '.constant('___LOAD_MOD_'.strtoupper($nname).'_PRESET_VARS').' }'."\n";
		}
		define('___GLOBAL_'.strtoupper($___TEMP_MOD_LOAD_NAME[$___TEMP_MOD_LOAD_i]).'_VARS___',$str6);
		
		unset($nname); 
		unset($str); unset($str1); unset($str2); unset($str3); unset($str4); unset($str5); unset($str6); unset($str7); unset($str8);
		unset($___TEMPC_a); unset($___TEMPC_b);
		unset($___TEMPV_a); unset($___TEMPV_b);
		unset($key); unset($value); unset($str);
			
		//如果快速模式，跳过之后的文件写入过程
		$skipflag = 0;
		if($quickmode && filemtime($___TEMP_MOD_LOAD_CMD[$___TEMP_MOD_LOAD_i]) < filemtime(GAME_ROOT.'./gamedata/modules.list.php')) {
			echo '未修改，跳过。<br>';ob_end_flush(); flush();
			$skipflag = 1;
		}else{//否则继续处理
			$modname=$___TEMP_MOD_LOAD_NAME[$___TEMP_MOD_LOAD_i];
			$modpath=$___TEMP_MOD_LOAD_PATH[$___TEMP_MOD_LOAD_i];
			$modpath='__MOD_DIR__.\''.$modpath.'\'';
			
			$modpath_suf=str_replace('\\','/',$modpath);//噫
			
			$suf=substr(md5($modpath_suf),0,8);
			$tplfile = GAME_ROOT.'./include/modules/modules.init.template.adv.php';
			$objfile = GAME_ROOT.'./gamedata/modinit/1_mod'.$modname.'.'.$suf.'.init.adv.php';
			
			$str=file_get_contents($tplfile);
			$str=str_replace('_____TEMPLATE_MODULE_NAME_____',$modname,$str);
			$str=str_replace('_____TEMPLATE_MODULE_PATH_____',$modpath,$str);
			if ($___MOD_SRV)
			{
				$str=str_replace('_____TEMPLATE_PRESET_CODE_____',$sr,$str);
			}
			else
			{
				$str=str_replace('_____TEMPLATE_PRESET_CODE_____','',$str);
			}
			$str=str_replace('_____TEMPLATE_ADV_CODE_____',$sc,$str);
			writeover($objfile,$str);
		}
		
		$codelist[$___TEMP_MOD_LOAD_i]=Array();
		foreach(explode(' ',$___MODULE_codelist) as $key) if ($key!='') array_push($codelist[$___TEMP_MOD_LOAD_i],$key);
		foreach(explode(' ',$___MODULE_templatelist) as $key) if ($key!='') array_push($codelist[$___TEMP_MOD_LOAD_i],$key.'.htm'); 
		unset($key); unset($sc); unset($sr); unset($ss); unset($key); unset($value);
		unset($tplfile); unset($objfile); unset($modname); unset($modpath); unset($suf); unset($str);
		
		if(!$skipflag) echo '完成。<br>'; ob_end_flush(); flush();
	}
	unset($skipflag);
	
	$n=$___TEMP_all;
	for ($i=1; $i<=$n; $i++)
	{
		$modn[$i]=$___TEMP_MOD_LOAD_NAME[$i];
		$modp[$i]=$___TEMP_MOD_LOAD_PATH[$i];
	}
	echo '<font color="blue">代码预处理CODE_ADV1完成。</font><br><br>';
}

global $___MOD_CODE_ADV2;
if ($___MOD_CODE_ADV1 && $___MOD_CODE_ADV2)
{
	echo '<font color="blue">正在进行代码预处理CODE_ADV2..</font><br>';
	include GAME_ROOT.'./include/modulemng/modulemng.codeadv2.func.php';
	$___TEMP_modfuncs=Array();
	$___TEMP_flipped_modn = array_flip($modn);
	$changed_filelist = array();
	//第一遍：复制文件，记录各函数依赖关系
	for ($i=1; $i<=$n; $i++)
	{
		/*
		if (strtoupper($modn[$i])=='INPUT')
		{
			echo '跳过模块input。<br>';
			continue;
		}
		*/
		echo '开始分析模块'.$modn[$i].'...<br>'; ob_end_flush(); flush();
		$srcdir = GAME_ROOT.'./include/modules/'.$modp[$i];
		$tpldir = GAME_ROOT.'./gamedata/run/'.$modp[$i];
		
//		create_dir($tpldir);
//		clear_dir($tpldir);
//		copy_dir($srcdir,$tpldir,'<DOC>');//这里其实只起到建立文件夹结构的作用，后边把php和htm全部拷贝了
		//快速模式不清空、复制文件夹
		if(!$quickmode) {
			create_dir($tpldir);
			clear_dir($tpldir);
			copy_dir($srcdir,$tpldir,'<DOC>');
		}
		foreach ($codelist[$i] as $key)
		{
			$src=GAME_ROOT.'./include/modules/'.$modp[$i].$key;
			$srcfiletype = substr($src,strlen($src)-3);
			echo '&nbsp;&nbsp;&nbsp;&nbsp;正在分析代码'.$key.'.. '; ob_end_flush(); flush();
			$objfile=GAME_ROOT.'./gamedata/run/'.$modp[$i].$key;
			
			//非快速模式或者快速模式且文件修改过
			if(!$quickmode || ($quickmode && filemtime($src) >= filemtime(GAME_ROOT.'./gamedata/modules.list.php'))) {
				//copy($src, $objfile);
				copy_without_comments($src, $objfile);
				$changed_filelist[] = $modp[$i].$key;
			}
			if('htm' == $srcfiletype) {
				foreach($___MOD_EXTRA_HTMLTPL_LIST as $tplid){
					$src_a = substr($src,0,-3).$tplid.'.'.substr($src,strlen($src)-3);
					if(file_exists($src_a)) {
						$objfile_a = substr($objfile,0,-3).$tplid.'.'.substr($objfile,strlen($objfile)-3);
						if(!$quickmode || ($quickmode && filemtime($src_a) >= filemtime(GAME_ROOT.'./gamedata/modules.list.php'))) {
							copy_without_comments($src_a, $objfile_a);
							$changed_filelist[] = $modp[$i].$key;
						}
					}
				}
			}
			
			//无论是不是快速模式都得预读全部函数内容
			if('php' == $srcfiletype) preparse($i,$src);
			echo '完成。<br>'; ob_end_flush(); flush();
			//快速模式且未修改文件，直接跳过
		}
	}
	//第二遍：整理并合并同名函数
	global $___MOD_CODE_COMBINE;
	if($___MOD_CODE_COMBINE){
		if($quickmode){
			//快速模式需要预判哪些函数名需要重载
			$quickmode_funclist = array();
			foreach($___TEMP_func_contents as $modid => $mval){
				foreach($mval as $fn => $fc){
					$fc_filename = substr($fc['filename'], strpos($fc['filename'], 'include/modules/') + 16);
					if(in_array($fc_filename, $changed_filelist) && !in_array($fn, $quickmode_funclist)){
						$quickmode_funclist[] = $fn;
					}
				}
			}
			//根据需要重载的函数名更新一遍需要修改的文件列表，这些文件需要以adv文件为基础进行combine
			foreach($___TEMP_func_contents as $modid => $mval){
				foreach($mval as $fn => $fc){
					$fc_filename = substr($fc['filename'], strpos($fc['filename'], 'include/modules/') + 16);
					if(in_array($fn, $quickmode_funclist) && !in_array($fc_filename, $changed_filelist)){
						$changed_filelist[] = $fc_filename;
						$objfile = GAME_ROOT.'./gamedata/run/'.$fc_filename;
						$tplfile = substr($objfile,0,-4).'.adv'.substr($objfile,strlen($objfile)-4);
						//copy($tplfile, $objfile);
						copy_without_comments($tplfile, $objfile);
					}
				}
			}
		}
		for ($i=1; $i<=$n; $i++)
		{
			echo '开始整理模块'.$modn[$i].'...'; ob_end_flush(); flush();
			merge_contents_calc($i);
			echo '完成。<br>'; ob_end_flush(); flush();
		}
	}	
	//第三遍：展开各文件的import和eval
	for ($i=1; $i<=$n; $i++)
	{
		echo '开始写入模块'.$modn[$i].'...<br>'; ob_end_flush(); flush();
		foreach ($codelist[$i] as $key)
		{
			//$src=GAME_ROOT.'./include/modules/'.$modp[$i].$key;
			echo '&nbsp;&nbsp;&nbsp;&nbsp;正在写入代码'.$key.'.. '; ob_end_flush(); flush();
			$basefile=GAME_ROOT.'./gamedata/run/'.$modp[$i].$key;
			$delfile=$basefile;
			$advfile=substr($basefile,0,-4).'.adv'.substr($basefile,strlen($basefile)-4);
			if($quickmode && !in_array($modp[$i].$key, $changed_filelist)) {
				echo '未修改，跳过。<br>'; ob_end_flush(); flush();
				continue;
			}

			if(pathinfo($basefile,PATHINFO_EXTENSION)!='php'){
				copy($basefile,$advfile);
				if(pathinfo($basefile,PATHINFO_EXTENSION) == 'htm') {
					foreach($___MOD_EXTRA_HTMLTPL_LIST as $tplid){
					$basefile_a = substr($basefile,0,-3).$tplid.'.'.substr($basefile,strlen($basefile)-3);
					if(file_exists($basefile_a)) {
						$advfile_a = substr($advfile,0,-3).$tplid.'.'.substr($advfile,strlen($advfile)-3);
						copy($basefile_a,$advfile_a);
					}
				}
				}
			}else{
				if($___MOD_CODE_COMBINE){
					merge_contents_write($i,$basefile,$advfile);
					parse($i,$advfile,$advfile);
				}else{
					parse($i,$basefile,$advfile);
				}
			}
			unlink($delfile);
			echo '写入完成。<br>'; ob_end_flush(); flush();
		}
	}
	echo '完成。<br>'; ob_end_flush(); flush();
	echo '<font color="blue">代码预处理CODE_ADV2完成。</font><br><br>';
}

clear_dir(GAME_ROOT.'./gamedata/templates',1);

global $___MOD_CODE_ADV3;
if ($___MOD_CODE_ADV1 && $___MOD_CODE_ADV2 && $___MOD_CODE_ADV3)
{
	$___TEMP_template_force_refresh = 1;
	$___TEMP_codeadv3=Array(); $___TEMP_codeadv3_c=0; $___TEMP_codeadv3_v=Array();
	include GAME_ROOT.'./include/modulemng/modulemng.codeadv3.func.php';
	echo '<font color="blue">正在进行代码预处理CODE_ADV3..</font><br>';
	for ($i=1; $i<=$n; $i++)
	{
		echo '开始处理模块'.$modn[$i].'...<br>'; ob_end_flush(); flush();
		foreach ($codelist[$i] as $key)
			if (substr($key,strlen($key)-4)=='.htm')
			{
				echo '&nbsp;&nbsp;&nbsp;&nbsp;正在处理模板'.$key.'.. '; ob_end_flush(); flush();
				//基础模板
				$objfile = template('MOD_'.strtoupper($modn[$i]).'_'.strtoupper(substr($key,0,-4)));
				$data = highlight_file($objfile,true);
				writeover($objfile,parse_codeadv3($data));
				foreach($___MOD_EXTRA_HTMLTPL_LIST as $tplid){
					$objfile_a = template('MOD_'.strtoupper($modn[$i]).'_'.strtoupper(substr($key,0,-4)), $tplid);
					if($objfile_a != $objfile) {
						$data_a = highlight_file($objfile_a,true);
						writeover($objfile_a,parse_codeadv3($data_a));
					}
				}
				echo '完成。<br>'; ob_end_flush(); flush();
			}
		echo '完成。<br>'; ob_end_flush(); flush();
	}
	
	if ($handle=opendir(GAME_ROOT.'./templates/default')) 
	{
		while (($sid=readdir($handle))!==false) 
		{
			if ($sid=='.' || $sid=='..') continue;
			if (!is_dir(GAME_ROOT.'./templates/default/'.(string)$sid))
			{
				if (strpos($sid,'admin')===0) continue;
				elseif (strpos($sid,'mixhelp')===0) continue;
				elseif (strpos($sid,'alive')===0) continue;
				elseif (strpos($sid,'winner')===0) continue;
				elseif (strpos($sid,'valid')===0) continue;
				elseif (strpos($sid,'user')===0) continue;
				elseif (strpos($sid,'rank')===0) continue;
				elseif (strpos($sid,'kuji')===0) continue;
				elseif (substr($sid,strlen($sid)-8)=='help.htm') continue;
				elseif (in_array($sid, array('header.htm', 'footer.htm', 'donate.htm', 'map.htm', 'replay.htm', 'register.htm', 'end.htm', 'updatelist.htm', 'areainfo.htm'))) continue;
				elseif (substr($sid,strlen($sid)-4)=='.htm')
				{
					echo '&nbsp;&nbsp;&nbsp;&nbsp;正在处理模板'.$sid.'.. '; ob_end_flush(); flush();
					$objfile = template(substr($sid,0,-4));
					$data = highlight_file($objfile,true);
					writeover($objfile,parse_codeadv3($data));
					foreach($___MOD_EXTRA_HTMLTPL_LIST as $tplid){
						$objfile_a = template(substr($sid,0,-4), $tplid);
						if($objfile_a != $objfile) {
							$data_a = highlight_file($objfile_a,true);
							writeover($objfile_a,parse_codeadv3($data_a));
						}
					}
					echo '完成。<br>'; ob_end_flush(); flush();
				}
			}
		}
	}
	echo '<font color="blue">代码预处理CODE_ADV3完成。</font><br><br>';
	
	$str='___temp_s = new String(\''.gencode($___TEMP_codeadv3_v).'\');
	___datalib = JSON.parse(JXG.decompress(___temp_s));
	delete ___temp_s;
	';
	
	$file='datalib.'.uniqid('',true).'.js';
	writeover(GAME_ROOT.'./gamedata/javascript/'.$file,$str);
	writeover(GAME_ROOT.'./gamedata/javascript/datalib.current.txt',$file);
}

$faillog='';

copy(GAME_ROOT.'./gamedata/modules.list.pass.php',GAME_ROOT.'./gamedata/modules.list.php');
unlink(GAME_ROOT.'./gamedata/modules.list.pass.php');
unlink(GAME_ROOT.'./gamedata/modules.list.temp.php');
touch(GAME_ROOT.'./gamedata/modules.list.php');//更新文件时间以保证quick模式正常运转

if ($___MOD_SRV)
{
	//重启daemon
	echo '<font color="blue">正在重启Daemon...</font> '; ob_end_flush(); flush();
	require GAME_ROOT.'./include/socket.func.php';
	__STOP_ALL_SERVER__();
	//touch(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
	//__SOCKET_LOG__("已请求脚本启动一台新的服务器。");
	echo '<font color="blue">完成。</font><br><br>';
}

//执行1次服务器维护
$url = url_dir().'command.php';
$context = array('command'=>'maintain');
curl_post($url, $context, NULL, 1);

echo '<font color="green">操作成功完成。修改已经被应用。<br><br></font>';
echo '<a href="modulemng.php" style="text-decoration: none"><span><font color="blue">[返回首页]</font></span></a><br>';   
		

/* End of file modulemng_active.php */
/* Location: /modulemng_active.php */