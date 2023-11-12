<?php

define('CURSCRIPT', 'game');
define('IN_REPLAY', TRUE);
require './include/common.inc.php';

$repid=$_GET['repid'];
if (!isset($repid)) { header("Location: winner.php"); exit(); } 

//过滤repid
if (!(('0'<=$repid[0] && $repid[0]<='9') || ('a'<=$repid[0] && $repid[0]<='z') || $repid[0]=='.'))
{
	header("Location: winner.php"); exit(); 
}

for ($i=1; $i<strlen($repid); $i++)
	if (!(('0'<=$repid[$i] && $repid[$i]<='9') || $repid[$i]=='.'))
	{ 
		header("Location: winner.php"); exit(); 
	} 

if ('a'<=$repid[0] && $repid[0]<='z')
{
	if (strpos($repid,'.')===false)
	{
		header("Location: winner.php"); exit(); 
	} 
	$prefix = substr($repid,0,strpos($repid,'.')+1);
	$repid = substr($repid,strpos($repid,'.')+1);
}
else  $prefix = '';

$cn=0;
for ($i=0; $i<strlen($repid); $i++)
	if ($repid[$i]=='.') 
		$cn++;

if ($cn>1) { header("Location: winner.php"); exit(); } 

if (strlen($repid)>20) { header("Location: winner.php"); exit(); } 

$repid = $prefix.$repid;
if ($cn==1)
{
	if (!file_exists(GAME_ROOT.'./gamedata/replays/'.$repid.'.rep')) 
	{ 
		if(!file_exists(GAME_ROOT.'./gamedata/replays/'.$repid.'.dat')){
			$flag = \replay\get_replay_remote($repid);
			if(!$flag) {
				include template('no_replay');
				exit(); 
			}
		}else{
			unfold(GAME_ROOT.'./gamedata/replays/'.$repid.'.dat');
		}
	} 
	$rgnum=substr($repid,0,strpos($repid,'.'));
	$arr=Array(substr($repid,strpos($repid,'.')+1));
}
else
{
	if (!file_exists(GAME_ROOT.'./gamedata/replays/'.$repid.'.rep.index')) 
	{ 
		if(!file_exists(GAME_ROOT.'./gamedata/replays/'.$repid.'.dat')){
			include template('no_replay');
			exit(); 
		}else{
			unfold(GAME_ROOT.'./gamedata/replays/'.$repid.'.dat');
		}
	} 
	$rgnum=$repid;
	$arr=explode(',',file_get_contents(GAME_ROOT.'./gamedata/replays/'.$repid.'.rep.index'));
	$narr=Array(); 
	foreach ($arr as $key)
	{
		if ($key=='') continue;
		$x=(int)$key;
		if (file_exists(GAME_ROOT.'./gamedata/replays/'.$repid.'.'.$x.'.rep'))
			array_push($narr,((string)$x));
	}
	$arr=$narr;
}
$replay_player_num_tot=count($arr);

if ($replay_player_num_tot==0)
{
	include template('no_replay'); exit(); 
} 

$rfullsz=0;
$repindexdata=Array();
foreach ($arr as $key) 
{
	$d=Array();
	list($repdatalib,$repgnum,$repname,$repsz,$repopcnt,$jdata) = explode(',',file_get_contents(GAME_ROOT.'./gamedata/replays/'.$rgnum.'.'.$key.'.rep'));
	$d=json_decode(base64_decode($jdata));
	$d['pid']=$key;
	$d['repname']=base64_decode($repname);
	$d['repsz']=$repsz;
	$d['repopcnt']=$repopcnt;
	$d['repfileid']=$rgnum.'.'.$key;
	if (!isset($d['color'])) $d['color']=\replay\get_ident_textcolor($d['repname']);
	array_push($repindexdata,$d);
	$rfullsz += ((double)substr($d['repsz'],0,-2));
}

//获取history表的基本数据备查
$wtablepre = $gtablepre.(!is_numeric(substr($prefix,0,1)) ? substr($prefix,0,1) : '');
$hgnum = 
$result = $db->query("SELECT winner,gstime FROM {$wtablepre}history WHERE gid='$repgnum'");
$result = $db->fetch_array($result);
$rwinner = $result['winner'];
$rgstime = $result['gstime'];
//由于replay_header被装进了闭包性质的东西里面，gstime很难直接塞进去，只能放外面了。有兴趣者可以看一下录像部分的数据结构有多么的迷宫爱好者

$jrepindexdata=gencode($repindexdata);

$repdatalib=base64_decode($repdatalib);

//$repbg = 'gamedata/replays/'.$repid.'.rep.bmp';
$sdata = \player\create_dummy_playerdata();
$sdata['gd'] = 'f';
$sdata['icon'] = 0;
\player\init_playerdata();
\player\parse_interface_profile();

$log = '';

include template('replay');

?>