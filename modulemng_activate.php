<?php

@ini_set('zlib.output_compression',0);
@ini_set('implicit_flush',1);
@ob_end_clean();
header('Content-Type: text/HTML; charset=utf-8');
header( 'Content-Encoding: none; ' );
    
set_time_limit(0);

define('IN_MODULEMNG', TRUE);
define('IN_MODULE_ACTIVATE', TRUE);
require './include/common.inc.php';

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
		echo '<br>或者因为如下原因：';
		var_dump($error);
		echo '<br><a href="modulemng.php?mode=edit" style="text-decoration: none"><span><font color="blue">[返回编辑模式]</font></span></a><br>';   
		die();
	}
}

$faillog = '<font color="red">看起来遇到了一个错误。请检查模块是否工作正常，然后返回编辑模式再试一次。<br>修改没有保存。<br></font>';

$codelist=Array(); 

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
			$___TEMP_MOD_LOAD_CMD[$___TEMP_MOD_LIST_n]=GAME_ROOT.'./include/modules/'.$modpath.'module.inc.php';;
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
		
		$codelist[$___TEMP_MOD_LOAD_i]=Array();
		foreach(explode(' ',$___MODULE_codelist) as $key) if ($key!='') array_push($codelist[$___TEMP_MOD_LOAD_i],$key);
		foreach(explode(' ',$___MODULE_templatelist) as $key) if ($key!='') array_push($codelist[$___TEMP_MOD_LOAD_i],$key.'.htm'); 
		unset($key); unset($sc); unset($sr); unset($ss); unset($key); unset($value);
		unset($tplfile); unset($objfile); unset($modname); unset($modpath); unset($suf); unset($str);
		
		echo '完成。<br>'; ob_end_flush(); flush();
	}
	
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
	for ($i=1; $i<=$n; $i++)
	{
		/*
		if (strtoupper($modn[$i])=='INPUT')
		{
			echo '跳过模块input。<br>';
			continue;
		}
		*/
		echo '开始处理模块'.$modn[$i].'...<br>'; ob_end_flush(); flush();
		$srcdir = GAME_ROOT.'./include/modules/'.$modp[$i];
		$tpldir = GAME_ROOT.'./gamedata/run/'.$modp[$i];
		create_dir($tpldir);
		clear_dir($tpldir);
		copy_dir($srcdir,$tpldir);
		foreach ($codelist[$i] as $key)
		{
			echo '&nbsp;&nbsp;&nbsp;&nbsp;正在处理代码'.$key.'.. '; ob_end_flush(); flush();
			$src=GAME_ROOT.'./include/modules/'.$modp[$i].$key;
			$objfile=GAME_ROOT.'./gamedata/run/'.$modp[$i].$key;
			$objfile=substr($objfile,0,-4).'.adv'.substr($objfile,strlen($objfile)-4);
			$delfile=GAME_ROOT.'./gamedata/run/'.$modp[$i].$key;
			preparse($modn[$i],$i,$src);
			parse($modn[$i],$src,$objfile);
			unlink($delfile);
			echo '完成。<br>'; ob_end_flush(); flush();
		}
		echo '完成。<br>'; ob_end_flush(); flush();
	}
	
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
				$objfile = template('MOD_'.strtoupper($modn[$i]).'_'.strtoupper(substr($key,0,-4)));
				$data = highlight_file($objfile,true);
				writeover($objfile,parse_codeadv3($data));
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
				if ($sid=='help.htm') continue;
				if ($sid=='mixhelp.htm') continue;
				if ($sid=='itemhelp.htm') continue;
				if (substr($sid,strlen($sid)-4)=='.htm')
				{
					echo '&nbsp;&nbsp;&nbsp;&nbsp;正在处理模板'.$sid.'.. '; ob_end_flush(); flush();
					$objfile = template(substr($sid,0,-4));
					$data = highlight_file($objfile,true);
					writeover($objfile,parse_codeadv3($data));
					echo '完成。<br>'; ob_end_flush(); flush();
				}
			}
		}
	}
	echo '<font color="blue">代码预处理CODE_ADV3完成。</font><br><br>';
	
	$str='___temp_s = new String(\''.base64_encode(gzencode(json_encode($___TEMP_codeadv3_v))).'\');
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

if ($___MOD_SRV)
{
	//重启daemon
	echo '<font color="blue">正在重启Daemon...</font> '; ob_end_flush(); flush();
	require GAME_ROOT.'./include/socket.func.php';
	__STOP_ALL_SERVER__();
	touch(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
	__SOCKET_LOG__("已请求脚本启动一台新的服务器。");
	echo '<font color="blue">完成。</font><br><br>';
}

echo '<font color="green">操作成功完成。修改已经被应用。<br><br></font>';
echo '<a href="modulemng.php" style="text-decoration: none"><span><font color="blue">[返回首页]</font></span></a><br>';   
		

/* End of file modulemng_active.php */
/* Location: /modulemng_active.php */