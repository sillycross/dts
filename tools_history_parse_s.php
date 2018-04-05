<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_time_limit(0);
define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();

$ul = array(
'st', 'saphil', '芙兰朵露', 'wy2000', '封剑尘', '御坂美琴', '战斗模式梦美', '镜音铃', 'Anna', 'mxr', '蕾米莉亚·斯卡雷特', '游佐', '573', '芙兰朵露·斯卡雷特', 'a571732256', 'NIKO', '小酱萝卜', '632714783', '3.14159', '我是萌新', '龙神'
);

echo 'started.<br>';
$gid_history = 259;
$gid_shistory = 480;
foreach(array('history', 'shistory') as $val){
	$tmp = ${'gid_'.$val};
	$result = $db->query("SELECT * FROM {$gtablepre}{$val} WHERE gid <= $tmp");
	while($r = $db->fetch_array($result)){
		$qr = array();
		if(in_array($r['winner'], $ul)) {
			echo $val.' game '.$r['gid'].'\'s winner is '.$r['winner'].' and parsed.<br>';
			$qr['winner'] = $r['winner'].'_S';
		}
		if(in_array($r['hdp'], $ul)) {
			echo $val.' game '.$r['gid'].'\'s hdp is '.$r['hdp'].' and parsed.<br>';
			$qr['hdp'] = $r['hdp'].'_S';
		}
		if(in_array($r['hkp'], $ul)) {
			echo $val.' game '.$r['gid'].'\'s hkp is '.$r['hkp'].' and parsed.<br>';
			$qr['hkp'] = $r['hkp'].'_S';
		}
		if(!empty($qr)) {
			echo $db->array_update("{$gtablepre}{$val}", $qr, "gid = '{$r['gid']}'");
		}
	}
}
echo 'done';