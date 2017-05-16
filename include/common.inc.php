<?php

//set_magic_quotes_runtime(0);

@ini_set('xdebug.max_nesting_level',5000);
ignore_user_abort(1);

defined('IN_GAME') || define('IN_GAME', TRUE);
defined('GAME_ROOT') || define('GAME_ROOT', substr(dirname(__FILE__), 0, -7));
define('GAMENAME', 'bra');

if(PHP_VERSION < '5.5.0') {
	exit('PHP version must >= 5.5.0!');
}

require GAME_ROOT.'./include/global.func.php';

$___TEMP_pagestart_time=getmicrotime();

error_reporting(E_ALL);
set_error_handler('gameerrorhandler');
$magic_quotes_gpc = get_magic_quotes_gpc();

require GAME_ROOT.'./include/modules/modules.func.php';

define('STYLEID', '1');
define('TEMPLATEID', '1');
define('TPLDIR', './templates/default');

///////////////////Load Module Start///////////////////////

if (defined('IN_MODULEMNG')) return;

//be sure to unset everything before loading modules!!

if (!defined('LOAD_CORE_ONLY'))
{
	$file=GAME_ROOT.'./gamedata/modules.list.php';
}
else
{
	$file=GAME_ROOT.'./include/modules/core/core.list.php';
}

if (!file_exists($file)) throw new Exception('module list file not found');
$content=openfile($file);
$in=sizeof($content); $___TEMP_MOD_LIST_n=0;

if (defined('NO_MOD_LOAD'))
{
	for ($i=0; $i<$in; $i++)
	{
		list($modname,$modpath,$inuse) = explode(',',$content[$i]);
		if ($inuse==1) define('MOD_'.strtoupper($modname),TRUE);
	}
	return;
}

for ($i=0; $i<$in; $i++)
{
	list($modname,$modpath,$inuse) = explode(',',$content[$i]);
	if ($inuse==1)
	{
		$___TEMP_MOD_LIST_n++; 
		$___TEMP_MOD_LOAD_CMD[$___TEMP_MOD_LIST_n]=GAME_ROOT.'./include/modules/'.$modpath.'module.inc.php';
		$___TEMP_MOD_NAME[$___TEMP_MOD_LIST_n]=$modname;
	}
	unset($modname); unset($modpath); unset($inuse);
}
unset($i); unset($content); unset($in); unset($file);

for ($___TEMP_MOD_LOAD_i=1; $___TEMP_MOD_LOAD_i<=$___TEMP_MOD_LIST_n; $___TEMP_MOD_LOAD_i++)
	require $___TEMP_MOD_LOAD_CMD[$___TEMP_MOD_LOAD_i];

/////////////////////Load Module End////////////////////////

if ((CURSCRIPT == 'game' || CURSCRIPT =='winner' || CURSCRIPT =='news' || CURSCRIPT =='alive') && !defined('LOAD_CORE_ONLY'))
{
	eval(import_module('sys','map','player','logger','itemmain','input'));
}
else
{
	eval(import_module('sys','map','input'));
}

if (defined('NO_SYS_UPDATE')) return;

if (CURSCRIPT == 'index') {//首页，所有房间刷新
	sys\routine();
//	sys\routine();
//	$o_room_prefix=$room_prefix;
//	
//	$result = $db->query("SELECT groomid FROM {$tablepre}game WHERE groomstatus=2");
//	while($rid = $db->fetch_array($result)['groomid']){
//		
//	}
//	unset($result,$rid,$o_room_prefix);
}
elseif (CURSCRIPT != 'chat' && CURSCRIPT != 'help') sys\routine();//其余调用，


?>