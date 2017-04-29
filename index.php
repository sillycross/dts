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
while ($data = $db->fetch_array($roomresult))
{
	if ($data['groomstatus']==1)
	{
		$flag = 0; $cnt=0;
		if (file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'))
		{
			$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt'),1);
			
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
			if ($infochanged) room_save_broadcast($data['groomid'],$roomdata);
			
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
			$db->query("UPDATE {$gtablepre}game SET groomstatus = 0 WHERE groomid='{$data['groomid']}'");
			unlink(GAME_ROOT.'./gamedata/tmp/rooms/'.$data['groomid'].'.txt');
			$data['groomstatus']=0;
		}
		else 
		{
			$roomlist[$data['groomid']]['id'] = $data['groomid'];
			$roomlist[$data['groomid']]['status'] = $data['groomstatus'];
			$roomlist[$data['groomid']]['nowplayer'] = $cnt;
			$roomlist[$data['groomid']]['maxplayer'] = $roomtypelist[$roomdata['roomtype']]['pnum'];
			$roomlist[$data['groomid']]['roomtype'] = $roomdata['roomtype'];
			$roomlist[$data['groomid']]['roomdata'] = $roomdata;
		}
	}
	elseif ($data['groomstatus']==2)
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
			$roomlist[$data['groomid']]['continuous'] = $roomtypelist[$roomdata['roomtype']]['continuous'];
			if($roomlist[$data['groomid']]['continuous']){
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
$troomlist = $roomlist;
ksort($roomlist);
$tmp=Array();
foreach ($roomlist as $key => $value)
{
	if ($value['continuous'])
	{
		$wg = 0;
	}
	elseif ($value['status']==2)
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