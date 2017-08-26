<?php

namespace _____TEMPLATE_MODULE_NAME_____
{
	if (!defined('IN_GAME')) exit('Access Denied');
	$___TEMP_namespace_name=strtoupper(__NAMESPACE__);
	//各TEMP变量在以下代码中即赋即用，用途前后未必一致
	$___TEMP_a=""; $___TEMP_b=""; $___TEMP_c="global "; $___TEMP_d=""; $___TEMP_e=""; $___TEMP_key="";
	$___TEMP_a=array_keys(get_defined_vars());
	//载入各modules.init.php里定义的php代码文件
	foreach(explode(' ',$___MODULE_codelist) as $___TEMP_key) if ($___TEMP_key!="") include GAME_ROOT._____TEMPLATE_MODULE_PATH_____.$___TEMP_key; 
	global $___PRIVATE_PFUNC,$___PRIVATE_CFUNC; $___PRIVATE_PFUNC=Array(); $___PRIVATE_CFUNC=Array();
	if ($___MOD_SRV)	
	{
		//这一段务必unset所有临时变量！！！！
		//生成daemon模式下模拟载入代码，也即不通过include定义各预设变量，而是直接把各预设变量的值放在内存中进行传递
		$___TEMP_b=array_keys(get_defined_vars()); 
		$___TEMP_STR1=''; $___TEMP_STR2='global '; $___TEMP_STR3=''; 
		foreach(array_diff($___TEMP_b, $___TEMP_a) as $___TEMP_key)
		{
			if (strpos($___TEMP_key,'___PRIVATE_')!==0)
			{
				$___TEMP_STR1.='$___PRESET_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.'=$'.$___TEMP_key.';';
				$___TEMP_STR2.='$___PRESET_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.',$'.$___TEMP_key.',';
				$___TEMP_STR3.='$'.$___TEMP_key.'=$___PRESET_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.';';
			}
		}
		unset($___TEMP_b); unset($___TEMP_key);
		define('___SAVE_MOD_'.$___TEMP_namespace_name.'_PRESET_VARS',$___TEMP_STR1);
		if ($___TEMP_STR2=='global ') $___TEMP_STR2=''; else $___TEMP_STR2=substr($___TEMP_STR2,0,-1).';';
		define('___LOAD_MOD_'.$___TEMP_namespace_name.'_PRESET_VARS',$___TEMP_STR2.$___TEMP_STR3);
		unset($___TEMP_STR1); unset($___TEMP_STR2); unset($___TEMP_STR3); 
	}
	if (!$___TEMP_DRY_RUN) init();
	$___TEMP_b=array_keys(get_defined_vars()); 
	//为各模板htm文件对应的常量赋值
	foreach(explode(' ',$___MODULE_templatelist) as $___TEMP_key) if ($___TEMP_key!="") 
	{
		$___TEMP_key2=strtoupper($___TEMP_key); $___TEMP_key2=str_replace('/','_',$___TEMP_key2);
		//global ${'MOD_'.$___TEMP_namespace_name.'_'.$___TEMP_key2};
		define('MOD_'.$___TEMP_namespace_name.'_'.$___TEMP_key2,GAME_ROOT._____TEMPLATE_MODULE_PATH_____.$___TEMP_key);
		unset($___TEMP_key2); 
	}
	$___TEMP_d="'; "; $___TEMP_e="'; "; $___TEMP_f=''; $___TEMP_cc=0;
	//生成import用的global代码，实际原理是用LOCAL前缀的变量进行传递
	$___TEMP_im_varnames = array();
	foreach(array_diff($___TEMP_b, $___TEMP_a) as $___TEMP_key)
	{
		if (strpos($___TEMP_key,'___PRIVATE_')!==0)
		{
			$___TEMP_im_varnames[] = $___TEMP_key;
			${"___LOCAL_{$___TEMP_namespace_name}__VARS__{$___TEMP_key}"}=$$___TEMP_key;
			$___TEMP_c.='$___LOCAL_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.',';
			if ($___TEMP_namespace_name=='INPUT')
				$___TEMP_f.='if (!isset($'.$___TEMP_key.')) $'.$___TEMP_key.'=&$___LOCAL_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.'; ';
			else  $___TEMP_f.='$'.$___TEMP_key.'=&$___LOCAL_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.'; ';
			$___TEMP_d.='global $___LOCAL_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.'; ${$___TEMP_PREFIX.\''.$___TEMP_key.'\'}=&$___LOCAL_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.'; ';
			$___TEMP_e.='global $___LOCAL_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.'; ${$___TEMP_VARNAME}[\''.$___TEMP_key.'\']=&$___LOCAL_'.$___TEMP_namespace_name.'__VARS__'.$___TEMP_key.'; ';
			$___TEMP_cc++;
			if ($___TEMP_cc%10==0)
			{
				$___TEMP_c.="\n"; $___TEMP_d.="\n"; $___TEMP_e.="\n"; $___TEMP_f.="\n";
			}
			unset($$___TEMP_key);
		}
		else 
		{
			${"___PRIVATE_{$___TEMP_namespace_name}__VARS__{$___TEMP_key}"}=$$___TEMP_key;
			unset($$___TEMP_key);
		}
	}
	define("MODULE_{$___TEMP_namespace_name}_GLOBALS_VARNAMES",implode(',', $___TEMP_im_varnames));
	if ($___TEMP_cc>0 && $___TEMP_cc%10==0) 
	{
		$___TEMP_c=substr($___TEMP_c,0,-1); 
		$___TEMP_d=substr($___TEMP_d,0,-1); 
		$___TEMP_e=substr($___TEMP_e,0,-1); 
		$___TEMP_f=substr($___TEMP_f,0,-1); 
	}
	if ($___TEMP_f=='') $___TEMP_c=''; else $___TEMP_c=substr($___TEMP_c,0,-1).'; '.$___TEMP_f;
	$___TEMP_d.='unset($___TEMP_PREFIX); '; $___TEMP_e.='unset($___TEMP_VARNAME); ';
	define("MOD_{$___TEMP_namespace_name}",1);
	define("IMPORT_MODULE_{$___TEMP_namespace_name}_GLOBALS",$___TEMP_c);
	if (!defined('IMPORT_WITH')) define("IMPORT_WITH",'$___TEMP_PREFIX=\'');
	define("PREFIX_MODULE_{$___TEMP_namespace_name}_GLOBALS",$___TEMP_d);
	if (!defined('IMPORT_TO')) define("IMPORT_TO",'$___TEMP_VARNAME=\'');
	define("MODULE_{$___TEMP_namespace_name}_GLOBALS",$___TEMP_e);
	//最后把有继承关系的函数全部排序并放入特定数组
	$___TEMP_a=get_defined_functions()['user']; $___TEMP_b=Array();
	foreach ($___TEMP_a as $___TEMP_key)
		if (strtoupper(substr($___TEMP_key,0,strlen($___TEMP_namespace_name)+1))==strtoupper($___TEMP_namespace_name.'\\'))
			array_push($___TEMP_b,substr($___TEMP_key,strlen($___TEMP_namespace_name)+1));
	foreach ($___TEMP_b as $___TEMP_key)
		if ($___TEMP_key!='init' && $___TEMP_key!='___pre_init' && $___TEMP_key!='___post_init')
			hook_register(__NAMESPACE__,$___TEMP_key);
	unset($___TEMP_namespace_name);
	unset($___TEMP_cc); unset($___TEMP_a); unset($___TEMP_b); unset($___TEMP_c); unset($___TEMP_d); unset($___TEMP_e); unset($___TEMP_f); unset($___TEMP_key);
}

?>