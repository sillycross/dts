<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

if($subcmd=='edit' && !$gamestate){
	$settime = mktime((int)$_POST['sethour'],(int)$_POST['setmin'],0,(int)$_POST['setmonth'],(int)$_POST['setday'],(int)$_POST['setyear']);
	if($settime <= $now){
		echo '开始时间不能早于当前时间。<br>';
	} else {
		$starttime = $settime;
		save_gameinfo();
		echo '游戏开始时间设置成功。<br>';
	}
}

if($gamestate) {
	echo '本局游戏尚未结束，不能设置时间。<br>';
} elseif($starttime) {
	list($stsec,$stmin,$sthour,$stday,$stmonth,$styear,$stwday,$styday,$stisdst) = localtime($starttime);
	$stmonth++;
	$styear += 1900;
} else {
	list($stsec,$stmin,$sthour,$stday,$stmonth,$styear,$stwday,$styday,$stisdst) = localtime($now+3600);
	$stmin = $startmin;
	$stmonth++;
	$styear += 1900;
}



?>

下局游戏开始时间：<br>
<form method="post" name="sttimemng" onsubmit="admin.php">
<input type="hidden" name="mode" value="gamemng">
<input type="hidden" name="command" value="sttimemng">
<input type="hidden" name="subcmd" value="">
<input type="text" name="setyear" size="4" value="<?=$styear?>"><?=$lang['year']?>
<input type="text" name="setmonth" size="2" value="<?=$stmonth?>"><?=$lang['month']?>
<input type="text" name="setday" size="2" value="<?=$stday?>"><?=$lang['day']?>
<input type="text" name="sethour" size="2" value="<?=$sthour?>"><?=$lang['hour']?>
<input type="text" name="setmin" size="2" value="<?=$stmin?>"><?=$lang['min']?>
<br>
<input type="button" value="修改" onclick="javascript:document.sttimemng.subcmd.value='edit';document.sttimemng.submit();">
<input type="button" value="返回" onclick="javascript:document.sttimemng.mode.value='';document.sttimemng.submit();">
