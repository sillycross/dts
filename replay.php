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
			$remote_rdata = '';
			if(!empty($replay_remote_storage)){
				//获取录像文件和对应的datalib.js
				$rpurl = $replay_remote_storage;
				$context = array('sign'=>$replay_remote_storage_sign, 'cmd'=>'loadrep', 'filename'=>$repid.'.dat');
				$ret = send_post($rpurl, $context);
				if(strpos($ret, 'does not exist')===false && strpos($ret, 'Bad command')===false && strpos($ret, 'Invalid Sign')===false){
					$ret = gdecode($ret,1);
					$remote_rdata = $ret['rdata'];
					$remote_datalibname = $ret['datalib_name'];
					$datalibpath = GAME_ROOT.'./gamedata/javascript/'.$remote_datalibname;
					if(!file_exists($datalibpath)) {
						$context = array('sign'=>$replay_remote_storage_sign, 'cmd'=>'loaddatalib', 'filename'=>$remote_datalibname);
						$ret2 = send_post($rpurl, $context);
						if(strpos($ret2, 'does not exist')===false && strpos($ret2, 'Bad command')===false && strpos($ret2, 'Invalid Sign')===false){
							file_put_contents($datalibpath, $ret2);
						}
					}
				}
			}
			if(!empty($remote_rdata)) {
				file_put_contents(GAME_ROOT.'./gamedata/replays/'.$repid.'.dat', $remote_rdata);
				unfold(GAME_ROOT.'./gamedata/replays/'.$repid.'.dat');
			}else{
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

$jrepindexdata=gencode($repindexdata);

$repdatalib=base64_decode($repdatalib);

//$repbg = 'gamedata/replays/'.$repid.'.rep.bmp';

\player\init_playerdata();
\player\parse_interface_profile();

$log = '';

include template('replay');

?>