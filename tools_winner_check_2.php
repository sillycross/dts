<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();
if(!file_exists('winnercheck.dat')) die('file not found.');
$f = gdecode(file_get_contents('winnercheck.dat'), 1);

$r = $db->query("SELECT username,validgames,wingames FROM {$gtablepre}users WHERE validgames>0 LIMIT 500");
$arr = array();
$i = 0;
while($rr = $db->fetch_array($r)){
	$un = strtoupper($rr['username']);
	$vag = $wig = 0;
	if(isset($f[$un])) {
		foreach ($f[$un] as $fv){
			$vag++;
			if($fv['gametype'] != 15 && in_array($fv['wmode'],array(2,3,5,7))){
				$wig++;
			}
		}
	}
	$arr[strtoupper($rr['username'])] = array(
		'o_validgames' => $rr['validgames'],
		'o_wingames' => $rr['wingames'],
		'validgames' => $vag,
		'wingames' => $wig
	);
	$i++;
}
writeover('winnercheck_2.dat', var_export($arr,1));
echo $i.'logs checked.';
//$db->query("ALTER TABLE {$gtablepre}rooms ADD `roomtype` tinyint unsigned NOT NULL DEFAULT 0");
