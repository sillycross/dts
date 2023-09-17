<?php

define('CURSCRIPT', 'index');

require './include/common.inc.php';
require_once './include/roommng/roommng.func.php';//有可能common.inc.php里已经调用来刷新房间，必须once
$timing = 0;
if($gamestate > 10) {
	$timing = ($now - $starttime)*1000;
} else {
	if($starttime > $now) {
		$timing = ($starttime - $now)*1000;
	} else {
		$timing = 0;
	}
}
$adminmsg = file_get_contents('./gamedata/adminmsg.htm') ;
$systemmsg = file_get_contents('./gamedata/systemmsg.htm') ;

if($disable_newgame) $systemmsg = '<div class="evergreen2" style="color:red">即将维护 新游戏暂停开放</div>'.$systemmsg;
elseif($disable_newroom) $systemmsg = '<div class="evergreen2" style="color:red">即将维护 房间暂停开放</div>'.$systemmsg;

$roomlist = Array();

$roomresult = $db->query("SELECT * FROM {$gtablepre}game WHERE groomid>0 AND groomstatus > 0");
//从数据库拉取开启的房间数，然后从文件判断房间是不是开启……有点迷
while ($data = $db->fetch_array($roomresult))
{
	if ($data['groomstatus'] >= 10 && $data['groomstatus'] < 40)//数据库中房间在等待状态
	{
		$cnt=$rdpmax=0;
		if (file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'))
		{
			//$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'),1);
			$roomdata = gdecode($data['roomvars'] ,1);
			$infochanged = 0;
			if (update_roomstate($roomdata,0)) $infochanged = 1;
			
			//自动踢人
			if (room_auto_kick_check($roomdata)) $infochanged = 1;
			if ($infochanged) room_save_broadcast($data['groomid'],$roomdata);
			
			//人数检测
			list($cnt, $rdpmax) = room_participant_get($roomdata);
		}
		//文件不存在或者房间没人，则数据库中该房间状态改为关闭
		if (!$cnt)
		{
			$db->query("UPDATE {$gtablepre}game SET groomstatus = 0 WHERE groomid='{$data['groomid']}'");
			unlink(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt');
			$data['groomstatus']=0;
		}
		else 
		{
			$roomlist[$data['groomid']]['id'] = $data['groomid'];
			$roomlist[$data['groomid']]['gamestate'] = $data['gamestate'];
			$roomlist[$data['groomid']]['status'] = $data['groomstatus'];
			$roomlist[$data['groomid']]['nowplayer'] = $cnt;
			$roomlist[$data['groomid']]['maxplayer'] = $rdpmax;
			$roomlist[$data['groomid']]['roomtype'] = $roomdata['roomtype'];
			$roomlist[$data['groomid']]['roomdata'] = $roomdata;
			$roomlist[$data['groomid']]['soleroom'] = room_get_vars($roomdata, 'soleroom');
			$roomlist[$data['groomid']]['runningtime'] = $data['starttime'] > 0 ? $now - $data['starttime'] : 0;
		}
	}
	elseif ($data['groomstatus']>=40)//数据库中房间已经进入游戏
	{
		//房间文件存在的房间才是合法的房间，让文件起到一个方便关闭的作用
		if (file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'))
		{
			//如果daemon开启，安排每个房间routine()一下，刷新房间内的游戏状态
			//非daemon模式强行载入每个房间会导致严重的脏数据，考虑到非daemon模式基本用于开发，就不刷新房间了
			if($___MOD_SRV) {
				curl_post(
					url_dir().'command.php',
					array('command'=>'room_routine_single','game_roomid'=>$data['groomid']),
					NULL,
					0.1
				);//相当于异步
			}
			//$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'),1);
			$roomdata = gdecode($data['roomvars'] ,1);
			if (update_roomstate($roomdata,1)) room_save_broadcast($data['groomid'],$roomdata);
			$roomlist[$data['groomid']]['id'] = $data['groomid'];
			$roomlist[$data['groomid']]['gamestate'] = $data['gamestate'];
			$roomlist[$data['groomid']]['status']=$data['groomstatus'];
			$roomlist[$data['groomid']]['maxplayer'] = room_get_vars($roomdata,'pnum');
			$roomlist[$data['groomid']]['roomtype'] = $roomdata['roomtype'];
			$roomlist[$data['groomid']]['roomdata'] = $roomdata;
			$roomlist[$data['groomid']]['soleroom'] = room_get_vars($roomdata,'soleroom');
			$roomlist[$data['groomid']]['without-ready'] = room_get_vars($roomdata,'without-ready');
			$roomlist[$data['groomid']]['runningtime'] = $data['starttime'] > 0 ? $now - $data['starttime'] : 0;
			$rid = 's'.$data['groomid'];
			$rtablepre = $gtablepre.$rid.'_';
			//不需准备的房间，查看300秒内有行动的存活玩家
			if($roomlist[$data['groomid']]['without-ready']){
				
				$endtimelimit = $now-300;
				$result = $db->query("SELECT pid FROM {$rtablepre}players WHERE type=0 AND state <= 3 AND endtime > '$endtimelimit'");
				$roomlist[$data['groomid']]['nowplayer'] = $db->num_rows($result);
			}
			//需要准备的房间，查看入场玩家（显示准备或者旁观用）
			else{
				$result = $db->query("SELECT name FROM {$rtablepre}players WHERE type=0");
				$validlist = array();
				while($vp = $db->fetch_array($result)){
					$validlist[] = $vp['name'];
				}
				$roomlist[$data['groomid']]['validlist'] = $validlist;
			}
		}
		else
		{
			$db->query("UPDATE {$gtablepre}game SET groomstatus = 0 WHERE groomid='{$data['groomid']}'");
			$data['groomstatus']=0;
		}
	}
}

//var_dump($roomlist);
//die();

//排序方式：永续房放最前，等待中优于游戏中，人越接近满越优先，人已满放最后，依然相同按ID
//$troomlist = $roomlist;
//ksort($roomlist);
$tmp=Array();
foreach ($roomlist as $key => $value)
{
	if ($value['soleroom'])
	{
		$wg = 0;
	}
	elseif ($value['status']>=40)
	{
		$wg = 20000;
	}
	else 
	{
		$wg = 10000 - ($value['maxplayer']-$value['nowplayer']) * 100;
	}
	$wg += $value['id'];
	if ($wg < 0) $wg = 0;
	if (!isset($tmp[$wg])) $tmp[$wg]=Array();
	array_push($tmp[$wg],$value);
}
ksort($tmp);
$shroomlist=Array();
foreach ($tmp as $key => $value)
	foreach ($value as $vval)
		array_push($shroomlist,$vval);

if ($gametype==2) $alivenum = $validnum;

$areaintv=\map\get_area_interval();

include template('index');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
 <head>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9744745-2");
pageTracker._trackPageview();
} catch(err) {}</script>
 </head>
</html>