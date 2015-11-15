<?php

define('CURSCRIPT', 'kuji');

require './include/common.inc.php';
require './include/user.func.php';

$_REQUEST = gstrfilter($_REQUEST);
$ktype=$_REQUEST['type'];
if ($ktype=="") $ktype=0;

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }

$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

extract($udata);

if ($udata['cardlist']==""){
	$udata['cardlist']="0";
	$db->query("UPDATE {$gtablepre}users SET cardlist='0' WHERE username='$cuser'");
}
$oc = explode('_',$udata['cardlist']);

$kreq=array(0=>100,1=>1000,2=>250);

$kres=\cardbase\kuji($ktype,$udata);

if ($kres!=-1){
	$isnew=array();
	foreach($kres as $key => $val){
		if (!in_array($val,$oc)){
			$isnew[$key]="<span class=\"L5\">NEW!</span>";
		}else{
			$isnew[$key]="";
		}
	}
	include template('kujiresult');
}else{
	gexit($_ERROR['kuji_failure'], __file__, __line__);
}


?> 