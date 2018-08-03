<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();

//恢复历史记录
foreach(array('history','shistory') as $tbn){
	$filecnt = explode("\n",file_get_contents('acbra2_'.$tbn.'.dat'));
	foreach($filecnt as $fv){
		$fv = json_decode(trim($fv),1);
		$db->array_insert("{$gtablepre}{$tbn}", $fv, 1, 'gid');
	}
}

//恢复用户
$filecnt = explode("\n",file_get_contents('acbra2_users.dat'));
foreach($filecnt as $fv){
	if(count($db->query_log)> 10000) {
		unset($db);
		$db = init_dbstuff();
	}
	$fv = json_decode(trim($fv),1);
	unset($fv['uid']);
	$db->array_insert("{$gtablepre}users", $fv, 1, 'username');
}

echo 'done';