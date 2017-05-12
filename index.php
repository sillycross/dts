<?php

define('CURSCRIPT', 'index');

require './include/common.inc.php';
require './include/roommng/roommng.func.php';

$timing = 0;
if($gamestate > 10) {
	$timing = $now - $starttime;
} else {
	if($starttime > $now) {
		$timing = $starttime - $now;
	} else {
		$timing = 0;
	}
}
$adminmsg = file_get_contents('./gamedata/adminmsg.htm') ;
$systemmsg = file_get_contents('./gamedata/systemmsg.htm') ;

if($disable_newgame) $systemmsg = '<span class="evergreen2" style="color:red">暂停开放新游戏</span>'.$systemmsg;
elseif($disable_newroom) $systemmsg = '<span class="evergreen2" style="color:red">暂停开放新房间</span>'.$systemmsg;

$roomlist = Array();

$roomresult = $db->query("SELECT * FROM {$gtablepre}game WHERE groomstatus > 0");
//从数据库拉取开启的房间数，然后从文件判断房间是不是开启……有点迷
while ($data = $db->fetch_array($roomresult))
{
	if ($data['groomstatus']==1)//数据库中房间在等待状态
	{
		$cnt=$rdpmax=0;
		if (file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'))
		{
			$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'),1);
			
			$infochanged = 0;
			if (update_roomstate($roomdata,0)) $infochanged = 1;
			
			//自动踢人
			if (room_auto_kick_check($roomdata)) $infochanged = 1;
//			if ($roomdata['roomstat']==1 && time()>=$roomdata['kicktime'])
//			{
//				$rdplist = & room_get_vars($roomdata, 'player');
//				$rdpnum = room_get_vars($roomdata, 'pnum');
//				for ($i=0; $i < $rdpnum; $i++)
//					if (!$rdplist[$i]['forbidden'] && !$rdplist[$i]['ready'] && $rdplist[$i]['name']!='')
//					{
//						room_new_chat($roomdata,"<span class=\"grey\">{$rdplist[$i]['name']}因为长时间未准备，被系统踢出了位置。</span><br>");
//						$rdplist[$i]['name']='';
//						$infochanged = 1;
//					}
//			}
			if ($infochanged) room_save_broadcast($data['groomid'],$roomdata);
			
			//人数检测
			list($cnt, $rdpmax) = room_participant_get($roomdata);
//			$rdplist = & room_get_vars($roomdata, 'player');
//			$rdpnum = $rdpmax = room_get_vars($roomdata, 'pnum');
//			for ($i=0; $i < $rdpnum; $i++)
//			{
//				if ($rdplist[$i]['name']!='')
//				{
//					$flag=1; $cnt++;
//				}
//				else  if ($rdplist[$i]['forbidden'])
//				{
//					$rdpmax--;
//				}
//			}
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
			$roomlist[$data['groomid']]['status'] = $data['groomstatus'];
			$roomlist[$data['groomid']]['nowplayer'] = $cnt;
			$roomlist[$data['groomid']]['maxplayer'] = $rdpmax;
			$roomlist[$data['groomid']]['roomtype'] = $roomdata['roomtype'];
			$roomlist[$data['groomid']]['roomdata'] = $roomdata;
			$roomlist[$data['groomid']]['soleroom'] = room_get_vars($roomdata, 'soleroom');
		}
	}
	elseif ($data['groomstatus']==2)//数据库中房间已经进入游戏
	{
		if (file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'))
		{
			$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'),1);
			if (update_roomstate($roomdata,1)) room_save_broadcast($data['groomid'],$roomdata);
			$roomlist[$data['groomid']]['id'] = $data['groomid'];
			$roomlist[$data['groomid']]['status']=$data['groomstatus'];
			$roomlist[$data['groomid']]['maxplayer'] = $roomtypelist[$roomdata['roomtype']]['pnum'];
			$roomlist[$data['groomid']]['roomtype'] = $roomdata['roomtype'];
			$roomlist[$data['groomid']]['roomdata'] = $roomdata;
			$roomlist[$data['groomid']]['soleroom'] = $roomtypelist[$roomdata['roomtype']]['soleroom'];
			if($roomlist[$data['groomid']]['soleroom']){
				$rid = 's'.$data['groomid'];
				$rtablepre = $gtablepre.$rid.'_';
				$endtimelimit = $now-300;
				$result = $db->query("SELECT pid FROM {$rtablepre}players WHERE type=0 AND state <= 3 AND endtime > '$endtimelimit'");
				$roomlist[$data['groomid']]['nowplayer'] = $db->num_rows($result);
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
	elseif ($value['status']==2)
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