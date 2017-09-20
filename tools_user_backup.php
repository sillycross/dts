<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
header('Content-Type: text/HTML; charset=utf-8');
header( 'Content-Encoding: none; ' );
define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();
if(!isset($_GET['cmd'])){	
	writeover('db_backup.dat', '');
	$r = $db->query("SELECT * FROM {$gtablepre}users");
	$i = 0;
	while($rr = $db->fetch_array($r)){
		writeover('db_backup.dat', json_encode($rr)."\r\n", 'ab+');
		$i ++;
	}
	echo $i.' records stored.';
}
elseif('load' == $_GET['cmd']){
	$r = openfile('db_backup.dat');
	$i = 0;
	foreach($r as $v){
		//var_dump(json_decode(trim($v),1));
		$v = json_decode(trim($v),1);
		$id = $v['uid'];
		unset($v['uid']);
		if(isset($v['alt_pswd'])) $v['alt_pswd'] = 0;
		$rr = $db->array_update("{$gtablepre}users", $v, "uid='$id'");
		if($rr) $i ++;
	}
	echo $i.' records recovered.';
}