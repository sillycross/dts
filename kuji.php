<?php

define('CURSCRIPT', 'user');

require './include/common.inc.php';
require './include/user.func.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }

$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }
if($udata['gold'] < 100) { gexit($_ERROR['not_enough_gold'], __file__, __line__); }

extract($udata);

if ($cardlist=="") $cardlist="0";
require config('card',$gamecfg);

//weight
$sw=1;
$aw=6;
$bw=26;
$cw=100;

$r=rand(1,$cw);
if ($r<=$sw){
	$arr=$cardindex['S'];
}else if($r<=$aw){
	$arr=$cardindex['A'];
}else if($r<=$bw){
	$arr=$cardindex['B'];
}else{
	$arr=$cardindex['C'];
}
$c=count($arr)-1;
$r=$arr[rand(0,$c)];
$carr = explode('_',$cardlist);
$clist = Array();
foreach($carr as $key => $val){
	$clist[$key] = $val;
}
$cflag=false;
if (in_array($r,$clist)){
	$gold-=70;
}else{
	$gold-=100;
	$cflag=true;
	$cardlist.="_".$r;
}
$db->query("UPDATE {$gtablepre}users SET gold='$gold',cardlist='$cardlist' WHERE username='$cuser'");
include template('kujiresult');
?> 