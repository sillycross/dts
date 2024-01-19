<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
//if($mygroup < 5){
//	exit($_ERROR['no_power']);
//}

eval(import_module('sys','weather'));

$sgtypelist = Array(0,1,4,6,18,19);//主房间允许改出来的游戏类型

if($command == 'wthedit'){
	$iweather = (int)$_POST['iweather'];
	if($iweather == $weather){
		$cmd_info = '当前天气已经为'.$wthinfo[$iweather].'，无需修改天气！';
	}elseif(!isset($wthinfo[$iweather])){
		$cmd_info = '天气数据错误，请重新输入！';
	}else{
		$cmd_info = '当前天气修改为：'.$wthinfo[$iweather];
		$weather = $iweather;
		save_gameinfo();
		adminlog('wthedit',$room_prefix,$gamenum,$iweather);
		addnews($now,'syswthchg',$iweather);		
	}
}elseif($command == 'hackedit'){
	$ihack = $_POST['ihack'] != 0 ? 1 : 0;
	if($ihack == $hack){
		$cmd_info = '当前禁区已经为该状态，无需修改！';
	}else{
		$cmd_info = '当前禁区状态修改为：'.($ihack ? '解除' : '未解除');
		$hack = $ihack;
		save_gameinfo();
		adminlog('hackedit',$room_prefix,$gamenum,$ihack);
		addnews($now,'syshackchg',$ihack);		
		//\map\movehtm();
	}
}elseif(strpos($command, 'gsedit')===0){
	$igamestate = explode('_',$command);
	$igamestate = $igamestate[1];
	
	if(!isset($gstate[$igamestate])){
		$cmd_info = '游戏状态数据错误，请重新输入！';
	}elseif($gamestate == $igamestate){
		$cmd_info = '游戏当前已经处于此状态，请重新输入！';
	}elseif($gamestate < 10 && $igamestate > 20){
		$cmd_info = '游戏未准备，不可进入后期状态！';
	}elseif($gamestate == 10 && $igamestate > 20){
		$cmd_info = '游戏未开始，不可进入后期状态！';
	}elseif($igamestate && $igamestate < $gamestate){
		$cmd_info = '游戏已开始，状态不可回溯！';
	}elseif($igamestate > 20){
		$cmd_info = '当前游戏状态修改为：'.$gstate[$igamestate];
		$gamestate = $igamestate;
		save_gameinfo();
		adminlog('gsedit',$room_prefix,$gamenum,$igamestate);
		addnews($now,'sysgschg',$igamestate);	
	}elseif($igamestate == 20){
		$cmd_info = '游戏立即开始！请访问任意游戏页面以刷新游戏状态。';
		$starttime = $now;
		save_gameinfo();
		adminlog('gsedit',$room_prefix,$gamenum,$igamestate);
		addnews($now,'sysgschg',$igamestate);	
	}elseif($igamestate == 10){
		$cmd_info = '游戏立即进入准备状态！请访问任意游戏页面以刷新游戏状态。';
		$readymin = $readymin > 0 ? $readymin : 1;
		$starttime = $now + $readymin * 60;
		save_gameinfo();
		adminlog('gsedit',$room_prefix,$gamenum,$igamestate);
	}else{
		$cmd_info = "第 $gamenum 局大逃杀紧急中止";
		\sys\gameover($now,'end6');
		save_gameinfo();
		adminlog('gameover',$room_prefix,$gamenum);
	}
}elseif($command == 'sttimeedit'){
	if($gamestate){
		$cmd_info = "本局游戏尚未结束，不能设置时间。";
	}else{
		$settime = mktime((int)$_POST['sethour'],(int)$_POST['setmin'],0,(int)$_POST['setmonth'],(int)$_POST['setday'],(int)$_POST['setyear']);
		if($settime <= $now){
			$cmd_info = '开始时间不能早于当前时间。';
		}else{
			$starttime = $settime;
			save_gameinfo();
			$cmd_info = '游戏开始时间设置成功。';
		}
	}
}elseif($command == 'areaadd'){
	$areawave = \map\get_area_wavenum();
	if($gamestate <= 10){
		$cmd_info = "本局游戏尚未开始，不能增加禁区。";
	}elseif((!$areawave && $starttime + 10 > $now) || ($areawave && $areatime - \map\get_area_interval() * 60 + 10 > $now)){
		$cmd_info = "禁区到来后10秒内不能增加禁区。";
	}else{
		$areatime = $now;
		save_gameinfo();
		$areatime += \map\get_area_interval() * 60;
		$cmd_info = '下一次禁区时间提前到来。请访问任意游戏页面以刷新游戏状态。';
		adminlog('addarea',$room_prefix,$gamenum,'I');
		addnews($now,'sysaddarea');	
	}
}elseif($command == 'areawarn'){
	if($gamestate <= 10){
		$cmd_info = "本局游戏尚未开始，不能增加禁区。";
	}else{
		$areatime = $now+60;
		save_gameinfo();
		$cmd_info = '下一次禁区时间已设为60秒后。请访问任意游戏页面以刷新游戏状态。';
		adminlog('addarea',$room_prefix,$gamenum,'L');
		addnews($now,'sysaddarea');	
	}
}elseif($command == 'gametypeset'){
	//echo $sgtype;
	if(!in_array($sgtype, $sgtypelist)){
		$cmd_info = "游戏类型修改错误！";
	}elseif($groomtype > 0){
		$cmd_info = "房间不允许修改下局类型！";
	}else{
		$gamevars['next_gametype'] = (int)$sgtype;
		save_gameinfo();
		$cmd_info = '游戏类型修改成功。';
		adminlog('gametypeset',$room_prefix,$gamenum,$sgtype);
		addnews($now,'gametypeset',$sgtype);	
	}
}

if($starttime){
	list($stsec,$stmin,$sthour,$stday,$stmonth,$styear,$stwday,$styday,$stisdst) = localtime($starttime);
	$stmonth++;
	$styear += 1900;
}else{
	list($stsec,$stmin,$sthour,$stday,$stmonth,$styear,$stwday,$styday,$stisdst) = localtime($now+3600);
	$stmin = $startmin;
	$stmonth++;
	$styear += 1900;
}

$arealiststr = $nextarealiststr = '';
$col = 0;
$areaarr = \map\get_current_area();
foreach($areaarr as $val){
	if($col == 4){
		$arealiststr .= $plsinfo[$val].'<br>';
		$col = 0;
	}else{
		$arealiststr .= $plsinfo[$val].' ';
		$col ++;
	}	
}
$col = 0;
$nareaarr = \map\get_current_area(1);
foreach($nareaarr as $val){
	if($col == 4){
		$nextarealiststr .= $plsinfo[$val].'<br>';
		$col = 0;
	}else{
		$nextarealiststr .= $plsinfo[$val].' ';
		$col ++;
	}	
}
list($arsec,$armin,$arhour,$arday,$armonth,$aryear,$arwday,$aryday,$arisdst) = localtime($areatime);
$armonth++;
$aryear += 1900;
include template('admin_gameinfomng');
?>

