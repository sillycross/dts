<?php

require GAME_ROOT.'./include/modulemng/modulemng.config.php';

if (!$___MOD_CODE_ADV2 || defined('IN_MODULEMNG')) define('__MOD_DIR__','./include/modules/'); else define('__MOD_DIR__','./gamedata/run/');

$module_hook_list=Array();

$___TEMP_DRY_RUN=0;
$___TEMP_DRY_RUN_COUNTER=0;

//新MAGIC串
//v1.8 现在开启了code_combine以后会大量合并函数，加快执行效率25%-50%
//v1.7 现在在codeadv2中会进一步展开magic串，现在magic串几乎不会消耗任何时间
//v1.6 修正了debug_backtrace参数不对导致的严重效率问题
//v1.5 修复了大写字母函数名带来的bug
//v1.4 增加了codeadv2的展开eval支持
//v1.3 增加了平行化函数的支持
//v1.2 改变了eval调用方式，降低了调用层数
//v1.2 增加了递归深度记录功能，供调试
//v1.1 通过reflection function支持了函数引用参数问题，现在可以使用类似function a(&$b)了
if (!$___MOD_CODE_ADV2 || (defined('IN_MODULEMNG') && !defined('IN_MODULE_ACTIVATE')))
define('__MAGIC__',<<<'MAGIC_1_DOC'
	global $___MOD_CODE_ADV2;
	$___TEMP_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3-$___MOD_CODE_ADV2); 
	if (!$___MOD_CODE_ADV2 || (defined('IN_MODULEMNG') && !defined('IN_MODULE_ACTIVATE'))) array_shift($___TEMP_backtrace);	
	$___TEMP_FUNCNAME=strtolower($___TEMP_backtrace[0]['function']); 
	global $___TEMP_DRY_RUN, $___TEMP_DRY_RUN_COUNTER; 
	if ($___TEMP_DRY_RUN) { 
		global $___TEMP_FUNCNAME_EXPECT;
		if ($___TEMP_FUNCNAME==$___TEMP_FUNCNAME_EXPECT) {
			$___TEMP_DRY_RUN_COUNTER++; $___RET_VALUE='23333333'; return 1; 
		} else  return 1;
	} 
	$___TEMP_NSNAME=strtoupper(substr($___TEMP_FUNCNAME,0,strpos($___TEMP_FUNCNAME,'\\',0)));
	$___TEMP_FUNCBASENAME=substr($___TEMP_FUNCNAME,strpos($___TEMP_FUNCNAME,'\\',0)+1);
	$___TEMP_ARGS_REFL = new \ReflectionFunction($___TEMP_FUNCNAME);
	$___TEMP_ARGLIST='';
	foreach ($___TEMP_ARGS_REFL->getParameters() as $___TEMP_para_key) $___TEMP_ARGLIST.='$'.$___TEMP_para_key->name.',';   
	$___TEMP_ARGLIST=substr($___TEMP_ARGLIST,0,strlen($___TEMP_ARGLIST)-1);
	unset($___TEMP_ARGS_REFL); unset($___TEMP_para_key); 
	array_shift($___TEMP_backtrace);
	if (count($___TEMP_backtrace)==0) $___TEMP_PARENT_NAME=''; else $___TEMP_PARENT_NAME=strtolower($___TEMP_backtrace[0]['function']);
	global $module_hook_list, ${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_PFUNC'},${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_CFUNC'};
	if (isset(${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_PFUNC'}[$___TEMP_FUNCNAME]) && 
	($___TEMP_PARENT_NAME!=${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_PFUNC'}[$___TEMP_FUNCNAME])) 
	{ 
		eval('$___RET_VALUE=\\'.end($module_hook_list[$___TEMP_FUNCBASENAME]).'\\'.$___TEMP_FUNCBASENAME.'('.$___TEMP_ARGLIST.');'); 
		if ($___MOD_CODE_ADV2) return $___RET_VALUE; else return 1;
	}
	if (isset(${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_CFUNC'}[$___TEMP_FUNCNAME])) 
		$chprocess=${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_CFUNC'}[$___TEMP_FUNCNAME];
	else  $chprocess='__MODULE_NULLFUNCTION__';
	unset($___TEMP_PARENT_NAME); unset($___TEMP_backtrace); unset($___TEMP_NSNAME); unset($___TEMP_FUNCBASENAME); unset($___TEMP_FUNCNAME); unset($___TEMP_ARGS); unset($___TEMP_ARGLIST);
MAGIC_1_DOC
);
else
{
define('__MAGIC__',<<<'MAGIC_2_DOC'
	$___TEMP_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 3); 
	array_shift($___TEMP_backtrace);
	global $___TEMP_DESIRE_PARENTNAME,$___TEMP_EVACODE,$___TEMP_CHPROCESS;
	$___TEMP_FUNCNAME=strtolower($___TEMP_backtrace[0]['function']); 
	$___TEMP_NSNAME=strtoupper(substr($___TEMP_FUNCNAME,0,strpos($___TEMP_FUNCNAME,'\\',0)));
	$___TEMP_FUNCBASENAME=substr($___TEMP_FUNCNAME,strpos($___TEMP_FUNCNAME,'\\',0)+1);
	$___TEMP_ARGS_REFL = new \ReflectionFunction($___TEMP_FUNCNAME);
	$___TEMP_ARGLIST='';
	foreach ($___TEMP_ARGS_REFL->getParameters() as $___TEMP_para_key) $___TEMP_ARGLIST.='$'.$___TEMP_para_key->name.',';   
	$___TEMP_ARGLIST=substr($___TEMP_ARGLIST,0,strlen($___TEMP_ARGLIST)-1);
	unset($___TEMP_ARGS_REFL); unset($___TEMP_para_key); 
	array_shift($___TEMP_backtrace);
	global $module_hook_list, ${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_PFUNC'},${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_CFUNC'};
	$___TEMP_DESIRE_PARENTNAME = '';
	if (isset(${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_PFUNC'}[$___TEMP_FUNCNAME]))
		$___TEMP_DESIRE_PARENTNAME = ${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_PFUNC'}[$___TEMP_FUNCNAME];
	
	$___TEMP_EVACODE = 'return \\'.end($module_hook_list[$___TEMP_FUNCBASENAME]).'\\'.$___TEMP_FUNCBASENAME.'('.$___TEMP_ARGLIST.');'; 
	if (isset(${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_CFUNC'}[$___TEMP_FUNCNAME])) 
		$___TEMP_CHPROCESS=${'___PRIVATE_'.$___TEMP_NSNAME.'__VARS_____PRIVATE_CFUNC'}[$___TEMP_FUNCNAME];
	else  $___TEMP_CHPROCESS='__MODULE_NULLFUNCTION__';
	return 1;
MAGIC_2_DOC
);
define('__MAGIC_CODEADV2__',<<<'MAGIC_CODEADV2_DOC'
	if (_____TEMPLATE_MAGIC_CODEADV2_INIT_DESIRE_PARENTNAME_____!='')
	{
		$___TEMP_backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 2); 
		array_shift($___TEMP_backtrace);
		if (count($___TEMP_backtrace)==0) $___TEMP_PARENT_NAME=''; else $___TEMP_PARENT_NAME=strtolower($___TEMP_backtrace[0]['function']);
		if ($___TEMP_PARENT_NAME!=_____TEMPLATE_MAGIC_CODEADV2_INIT_DESIRE_PARENTNAME_____) 
		{
			_____TEMPLATE_MAGIC_CODEADV2_INIT_EVACODE_____
		}
		unset($___TEMP_PARENT_NAME); unset($___TEMP_backtrace); 
	}
	$chprocess=_____TEMPLATE_MAGIC_CODEADV2_INIT_CHPROCESS_____;
MAGIC_CODEADV2_DOC
);
}

function hook_register($module,$func)
{
	global $module_hook_list;
	if (!isset($module_hook_list[$func])) 
	{
		$module_hook_list[$func]=Array();
		array_push($module_hook_list[$func],$module);
	}
	else
	{
		global ${'___PRIVATE_'.strtoupper(end($module_hook_list[$func])).'__VARS_____PRIVATE_PFUNC'}, ${'___PRIVATE_'.strtoupper($module).'__VARS_____PRIVATE_CFUNC'};
		${'___PRIVATE_'.strtoupper(end($module_hook_list[$func])).'__VARS_____PRIVATE_PFUNC'}[end($module_hook_list[$func]).'\\'.$func]=$module.'\\'.$func;
		${'___PRIVATE_'.strtoupper($module).'__VARS_____PRIVATE_CFUNC'}[$module.'\\'.$func]=end($module_hook_list[$func]).'\\'.$func;
		array_push($module_hook_list[$func],$module);
	}
}

function __INIT_MODULE__($modname,$modpath)
{
	$modpath .= '/'; 
	$modpath=substr($modpath,strlen(GAME_ROOT));
	$modpath=substr($modpath,strlen('include/modules/'));
	$modpath='__MOD_DIR__.\''.$modpath.'\'';
	$modpath_suf=str_replace('\\','/',$modpath);//都是斜杠的锅
	$suf=substr(md5($modpath_suf),0,8);
	$tplfile = GAME_ROOT.'./include/modules/modules.init.template.php';
	
	global $___MOD_CODE_ADV1;
	if ($___MOD_CODE_ADV1 && !defined('IN_MODULEMNG') && $modname!='input')
		$objfile = GAME_ROOT.'./gamedata/modinit/1_mod'.$modname.'.'.$suf.'.init.adv.php';
	else  $objfile = GAME_ROOT.'./gamedata/modinit/1_mod'.$modname.'.'.$suf.'.init.php';
	
	global $___MOD_CODE_ADV2;
	if ($___MOD_CODE_ADV2 && !defined('IN_MODULEMNG'))
	{
		global $___MODULE_codelist;
		$rs='';
		foreach (explode(' ',$___MODULE_codelist) as $key) if ($key!="") $rs.=substr($key,0,-4).'.adv.php ';
		if ($rs!='') $rs=substr($rs,0,-1);
		$___MODULE_codelist=$rs;
	}
	
	if ((!$___MOD_CODE_ADV1 && filemtime($tplfile)>filemtime($objfile)) || defined('IN_MODULEMNG'))
	{
		$str=file_get_contents($tplfile);
		$str=str_replace('_____TEMPLATE_MODULE_NAME_____',$modname,$str);
		$str=str_replace('_____TEMPLATE_MODULE_PATH_____',$modpath,$str);
		writeover($objfile,$str);
	}
	return $objfile;
}

function import_module()
{
	$lis=func_get_args(); $ret='';
	if (defined('IN_MODULEMNG') && defined('IN_MODULE_ACTIVATE'))
	{
		//modulemng里确定init模块中没有引用sys或input，惟一的例外是player，这个情况比较特殊，而且使用的内容是静态的，所以可以接受……
		global $___TEMP_MOD_INITING_FLAG, $___TEMP_CUR_INITING_MODULE_NAME;
		if ($___TEMP_MOD_INITING_FLAG && strtoupper($___TEMP_CUR_INITING_MODULE_NAME)!='SYS' && strtoupper($___TEMP_CUR_INITING_MODULE_NAME)!='PLAYER')
		{
			foreach ($lis as $key) 
			{
				if (strtoupper($key)=='INPUT' || strtoupper($key)=='SYS')
				{
					global $faillog;
					$faillog = "<span class=\"red b\">你在{$___TEMP_CUR_INITING_MODULE_NAME}的init函数中import了input或sys模块，这是不允许的。</span>";
					die();
				}
			}
		}
	}
	foreach ($lis as $key)
	{
		global $___MOD_SRV;
		if ($___MOD_SRV && strtoupper($key)=='INPUT')
		{
			global $___LOCAL_INPUT__VARS__INPUT_VAR_LIST;
			$ret.='global $___LOCAL_INPUT__VARS__INPUT_VAR_LIST;';
			foreach ($___LOCAL_INPUT__VARS__INPUT_VAR_LIST as $keyc => $valuec)
			{
				$keyc=(string)$keyc;
				$ret.='if (!isset($'.$keyc.')) $'.$keyc.'=&$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[\''.$keyc.'\'];';
			}
		}
		if (!defined('IMPORT_MODULE_'.strtoupper($key).'_GLOBALS')) 
			throw new Exception('undefined module '.$key);
		$ret.=constant('IMPORT_MODULE_'.strtoupper($key).'_GLOBALS');
	}
	return $ret;
}

//从模块中获取单个变量，用于大量循环调用eval(import_module())会降低性能的情况
function & get_var_in_module($varname, $modulename)
{
	if(empty($varname) || !defined('MOD_'.strtoupper($modulename))) return NULL;
	global $___MOD_SRV;
	$ret = NULL;
	//DAEMON模式并且来源是input模块，先判定有没有被初始化过，如果没有，就去$___LOCAL_INPUT__VARS__INPUT_VAR_LIST里找
	if(isset($GLOBALS['___LOCAL_'.strtoupper($modulename).'__VARS__'.$varname])) {
		$ret = & $GLOBALS['___LOCAL_'.strtoupper($modulename).'__VARS__'.$varname];
	}
	elseif($___MOD_SRV && defined('IN_DAEMON') && $modulename == 'input'){
		global $___LOCAL_INPUT__VARS__INPUT_VAR_LIST;
		if(isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$varname]))
			$ret = & $___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$varname];
	}
	return $ret;
}

function __MODULE_GET_TEMPLATE__($file, $templateid=NULL)
{
	if (!defined($file)) throw new Exception('undefined template constant '.$file);
	$file=constant($file);
	$templateid = $templateid ? $templateid : TEMPLATEID;
	global $___MOD_CODE_ADV2;
	if ($___MOD_CODE_ADV2) 
	{
		$file=substr($file,strlen(GAME_ROOT));
		$file=substr($file,strlen('./include/modules/'));
		$file=GAME_ROOT.__MOD_DIR__.$file;
	}
	return $file;
}

function __MODULE_NULLFUNCTION__() 
{
	return NULL;
}

?>

