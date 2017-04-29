<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
//if($mygroup < 5){
//	exit($_ERROR['no_power']);
//}

eval(import_module('weather'));

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
		adminlog('wthedit',$iweather);
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
		adminlog('hackedit',$ihack);
		addnews($now,'syshackchg',$ihack);		
		\map\movehtm();
	}
}elseif(strpos($command, 'gsedit')===0){
	$igamestate = explode('_',$command);
	$igamestate = $igamestate[1];
	
	if(!isset($gstate[$igamestate])){
		$cmd_info = '游戏状态数据错误，请重新输入！';
	}elseif($gamestate == $igamestate){
		$cmd_info = '游戏当前已经处于此状态，请重新输入！';
	}elseif($gamestate == 0 && $igamestate != 10){
		$cmd_info = '游戏未准备，不可进入后期状态！';
	}elseif($gamestate == 10 && $igamestate > 20){
		$cmd_info = '游戏未开始，不可进入后期状态！';
	}elseif($igamestate && $igamestate < $gamestate){
		$cmd_info = '游戏已开始，状态不可回溯！';
	}elseif($igamestate > 20){
		$cmd_info = '当前游戏状态修改为：'.$gstate[$igamestate];
		$gamestate = $igamestate;
		save_gameinfo();
		adminlog('gsedit',$igamestate);
		addnews($now,'sysgschg',$igamestate);	
	}elseif($igamestate == 20){
		$cmd_info = '游戏立即开始！请访问任意游戏页面以刷新游戏状态。';
		$starttime = $now;
		save_gameinfo();
		adminlog('gsedit',$igamestate);
		addnews($now,'sysgschg',$igamestate);	
	}elseif($igamestate == 10){
		$cmd_info = '游戏立即进入准备状态！请访问任意游戏页面以刷新游戏状态。';
		$readymin = $readymin > 0 ? $readymin : 1;
		$starttime = $now + $readymin * 60;
		save_gameinfo();
		adminlog('gsedit',$igamestate);
	}else{
		$cmd_info = "第 $gamenum 局大逃杀紧急中止";
		\sys\gameover($now,'end6');
		save_gameinfo();
		adminlog('gameover');
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
	if($gamestate <= 10){
		$cmd_info = "本局游戏尚未开始，不能增加禁区。";
	}elseif((!$areanum && $starttime + 30 > $now) || ($areanum && $areatime - $areahour*60 + 30 > $now)){
		$cmd_info = "禁区到来后30秒内不能增加禁区。";
	}else{
		$areatime = $now;
		save_gameinfo();
		$areatime += $areahour * 60;
		$cmd_info = '下一次禁区时间提前到来。请访问任意游戏页面以刷新游戏状态。';
		addnews($now,'sysaddarea');	
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
$areaarr = array_slice($arealist,0,$areanum+1);
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
$nareaarr = array_slice($arealist,0,$areanum+$areaadd);
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

