<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();
$r = $db->query("SELECT * FROM {$gtablepre}swinners WHERE winnum>1 LIMIT 10");

while($rrr = $db->fetch_array($r)){
	$rr = array();
	$rr['gid'] = $rrr['gid'];
	$rr['name'] = $rrr['name'];
	$rr['namelist'] = $rrr['namelist'];
	$rr['iconlist'] = $rrr['iconlist'];
	$rr['weplist'] = $rrr['weplist'];
	$rr['gdlist'] = $rrr['gdlist'];
	var_dump($rr);
	echo "<br><br>";
}
