<?php

define('CURSCRIPT', 'user_profile');

require './include/common.inc.php';

$_REQUEST = gstrfilter($_REQUEST);
if (empty($_REQUEST["playerID"]))
{
	$udata = udata_check();
	$curuser=true;
}
else
{
	$uname=urldecode($_REQUEST["playerID"]);
	$udata = fetch_udata_by_username($uname);
	if(empty($udata)) { gexit($_ERROR['user_not_exists'],__file__,__line__); }
	$curuser=false;
	if ($uname==$cuser) $curuser=true;
}

$udata['u_achievements'] = \achievement_base\decode_achievements($udata);
//判定全局成就
\achievement_base\ach_global_ach_check($udata);

if ($curuser && isset($_REQUEST["action"]) && $_REQUEST["action"]=="refdaily"){
	$refdaily_flag = \achievement_base\refresh_daily_quest($udata);
}
else  $refdaily_flag = false;

$u_acharr = \achievement_base\get_valid_achievements($udata['u_achievements']);
extract($udata);

//$iconarray = get_iconlist($icon);
$select_icon = $icon;
$winning_rate=$validgames?round($wingames/$validgames*100)."%":'0%';

eval(import_module('cardbase'));//总觉得这里是废弃代码啊
$carr=$cards[$card];
$carr['id'] = $card;
$cr=$carr['rare'];
$rarecolor = $card_rarecolor[$cr];
$cf=true;$sf=true;$af=true;$bf=true;$ff=true;
if (($now-$udata['cd_s'])<86400){
	$sf=false;
	$ntime=$udata['cd_s']+86400;
	list($min,$hour,$day,$month,$year)=explode(',',date("i,H,j,n,Y",$ntime));
	$std=$year."年".$month."月".$day."日".$hour."时".$min."分";
}

eval(import_module('achievement_base'));
if (($now-$udata['cd_a1']) < $daily_intv){
	$ff=false;
	$ntime=$udata['cd_a1'] + $daily_intv;
	list($min,$hour,$day,$month,$year)=explode(',',date("i,H,j,n,Y",$ntime));
	list($cmin,$chour,$cday,$cmonth,$cyear)=explode(',',date("i,H,j,n,Y",$now));
	if ($cday==$day && $cmonth==$month && $cyear==$year)
		$ftd="今天".$hour."时".$min."分";
	else  $ftd="明天".$hour."时".$min."分";
}

$userCardData = \cardbase\get_user_cardinfo($udata);
$user_cards = $userCardData['cardlist'];
$card_energy = $userCardData['cardenergy'];
$energy_recover_rate = \cardbase\get_energy_recover_rate($user_cards, $gold);

include template('user_profile');