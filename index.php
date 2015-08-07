<?php

define('CURSCRIPT', 'index');

require './include/common.inc.php';
require './include/roommng.func.php';

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

$roomlist = Array();
$result = $db->query("SELECT * FROM {$gtablepre}rooms WHERE status > 0");
while ($data = $db->fetch_array($result))
{
	if ($data['status']==1)
	{
		$flag = 0; $cnt=0;
		if (file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['roomid'].'.txt'))
		{
			$roomdata = json_decode(mgzdecode(base64_decode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['roomid'].'.txt'))),1);
			
			$infochanged = 0;
			if (update_roomstate($roomdata,0)) $infochanged = 1;
			
			//自动踢人
			if ($roomdata['roomstat']==1 && time()>=$roomdata['kicktime'])
			{
				for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
					if (!$roomdata['player'][$i]['forbidden'] && !$roomdata['player'][$i]['ready'] && $roomdata['player'][$i]['name']!='')
					{
						room_new_chat($roomdata,"<span class=\"grey\">{$roomdata['player'][$i]['name']}因为长时间未准备，被系统踢出了房间。</span><br>");
						$roomdata['player'][$i]['name']='';
						$infochanged = 1;
					}
			}
			if ($infochanged) room_save_broadcast($data['roomid'],$roomdata);
			
			for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
			{
				if ($roomdata['player'][$i]['name']!='')
				{
					$flag=1; $cnt++;
				}
				else  if ($roomdata['player'][$i]['forbidden'])
				{
					$cnt++;
				}
			}
		}
		if (!$flag)
		{
			$db->query("UPDATE {$gtablepre}rooms SET status = 0 WHERE roomid='{$data['roomid']}'");
			unlink(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['roomid'].'.txt');
			$data['status']=0;
		}
		else 
		{
			$roomlist[$data['roomid']]['id'] = $data['roomid'];
			$roomlist[$data['roomid']]['status'] = $data['status'];
			$roomlist[$data['roomid']]['nowplayer'] = $cnt;
			$roomlist[$data['roomid']]['maxplayer'] = $roomtypelist[$roomdata['roomtype']]['pnum'];
			$roomlist[$data['roomid']]['roomtype'] = $roomdata['roomtype'];
			$roomlist[$data['roomid']]['roomdata'] = $roomdata;
		}
	}
	else  if ($data['status']==2)
	{
		if (file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['roomid'].'.txt'))
		{
			$roomdata = json_decode(mgzdecode(base64_decode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['roomid'].'.txt'))),1);
			if (update_roomstate($roomdata,1)) room_save_broadcast($data['roomid'],$roomdata);
			$roomlist[$data['roomid']]['id'] = $data['roomid'];
			$roomlist[$data['roomid']]['status']=$data['status'];
			$roomlist[$data['roomid']]['roomtype'] = $roomdata['roomtype'];
			$roomlist[$data['roomid']]['roomdata'] = $roomdata;
		}
		else
		{
			$db->query("UPDATE {$gtablepre}rooms SET status = 0 WHERE roomid='{$data['roomid']}'");
			$data['status']=0;
		}
	}
}

//var_dump($roomlist);
//die();

//排序方式：等待中优于游戏中，人越接近满越优先，人已满放最后，依然相同按ID
$troomlist = $roomlist;
ksort($roomlist);
$tmp=Array();
foreach ($roomlist as $key => $value)
{
	if ($value['status']==2)
	{
		$wg = 10000;
	}
	else 
	{
		if ($value['maxplayer']==$value['nowplayer'])
			$wg = 5000;
		else  $wg = $value['maxplayer']-$value['nowplayer'];
	}
	if (!isset($tmp[$wg])) $tmp[$wg]=Array();
	array_push($tmp[$wg],$value);
}
ksort($tmp);
$roomlist=Array();
foreach ($tmp as $key => $value)
	foreach ($value as $data)
		array_push($roomlist,$data);

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