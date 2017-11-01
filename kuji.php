<?php

define('CURSCRIPT', 'kuji');

require './include/common.inc.php';
require './include/user.func.php';

$_REQUEST = gstrfilter($_REQUEST);
$ktype=$_REQUEST['type'];
if ($ktype=="") $ktype=0;
if (isset($_POST['choice']))
{
	$choice=(int)$_POST['choice'];
	if ($choice<1 || $choice>4) $choice=0;
}
else  $choice=0;

$udata = udata_check();

//if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
//
//$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
//if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
//$udata = $db->fetch_array($result);
//if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
//if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

extract($udata);

$userCardData = \cardbase\get_user_cardinfo($cuser);
$oc = $userCardData['cardlist'];


if ($ktype==1 || $choice>0)
{
	eval(import_module('kujibase'));
	
	$kreq=$kujicost;

	$kres=\cardbase\kuji($ktype,$udata);

	if (is_array($kres)){
		if ($ktype==0 || $ktype==2)	//单抽可以4选1
		{
			$t=Array(); $tmp=Array();
			for ($i=1; $i<=4; $i++)
			{
				if ($i==$choice)
					$t[$i]=$kres[0];
				else	$t[$i]=\cardbase\kuji($ktype,$tmp,true)[0];
			}
			$kres=$t;
		}
		$isnew=array();
		foreach($kres as $key => $val){
			if (($ktype==0 || $ktype==2) && $choice!=$key)	//单抽没有真正获得的卡不显示new字样
			{
				$isnew[$key]=""; continue;
			}
			if (!in_array($val,$oc)){
				$isnew[$key]="<span class=\"L5\">NEW!</span>";
			}else{
				$isnew[$key]="";
			}
		}
		$ishighlight=Array();
		if ($ktype==0 || $ktype==2) 
		{
			for ($i=1; $i<=4; $i++) $ishighlight[$i]=($i==$choice);
		}
		
		include template('kujiresult');
	}else{
		gexit($_ERROR['kuji_failure'], __file__, __line__);
	}
}
else
{
	//翻卡UI
	include template('kujipick');
}
?> 