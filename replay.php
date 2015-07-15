<?php

define('CURSCRIPT', 'game');
define('IN_REPLAY', TRUE);
require './include/common.inc.php';

$repid=$_GET['repid'];

if (!isset($repid)) { header("Location: winner.php"); exit(); } 

//过滤repid
for ($i=0; $i<strlen($repid); $i++)
	if (!(('0'<=$repid[$i] && $repid[$i]<='9') || $repid[$i]=='.'))
	{ 
		header("Location: winner.php"); exit(); 
	} 

$cn=0;
for ($i=0; $i<strlen($repid); $i++)
	if ($repid[$i]=='.') 
		$cn++;

if ($cn!=1) { header("Location: winner.php"); exit(); } 

if (strlen($repid)>20) { header("Location: winner.php"); exit(); } 

if (!file_exists(GAME_ROOT.'./gamedata/replays/'.$repid.'.rep')) 
{ 
	include template('no_replay'); exit(); 
} 

list($repdatalib,$repgnum,$repname,$repsz,$repopcnt) = explode(',',file_get_contents(GAME_ROOT.'./gamedata/replays/'.$repid.'.rep'));
$repdatalib=base64_decode($repdatalib);
$repname=base64_decode($repname);

$repbg = 'gamedata/replays/'.$repid.'.rep.bmp';

\player\init_playerdata();
\player\init_profile();

$log = '';

include template('replay');

?>