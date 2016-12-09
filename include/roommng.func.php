<?php

function update_roomstate(&$roomdata, $runflag)
{
	eval(import_module('sys'));
	
	global $roomtypelist;
	$flag=1;
	for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
		if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']=='')
			$flag = 0;
	
	$changeflag = 0;
	if (!$runflag && $flag && $roomdata['roomstat']==0)
	{
		$roomdata['roomstat']=1;
		$roomdata['kicktime']=time()+30;
		$roomdata['timestamp']++;
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++) $roomdata['player'][$i]['ready']=0;
		$changeflag = 1;
	}
	
	if (!$flag) { $roomdata['roomstat']=0; $changeflag = 1; }
	return $changeflag;
}

function room_save_broadcast($roomid, &$roomdata)
{
	//保存数据并广播
	eval(import_module('sys'));
	$result = $db->query("SELECT status FROM {$gtablepre}rooms WHERE roomid = '$roomid'");
	$runflag = 0;
	if ($db->num_rows($result)) 
	{ 
		$zz=$db->fetch_array($result); 
		if ($zz['status']==2) $runflag = 1; 
	}
	
	update_roomstate($roomdata,$runflag);
	
	writeover(GAME_ROOT.'./gamedata/tmp/rooms/'.$roomid.'.txt', base64_encode(gzencode(compatible_json_encode($roomdata))));
	$result = $db->query("SELECT * FROM {$gtablepre}roomlisteners WHERE roomid = '$roomid' AND timestamp < '{$roomdata['timestamp']}'");
	if ($db->num_rows($result))
	{
		$str='('; $lis=Array();
		while ($data=$db->fetch_array($result))
		{
			$str.="('".$data['port']."','".$data['roomid']."','".$data['timestamp']."','".$data['uniqid']."'),";
			array_push($lis,$data['port']);
		}
		$str=substr($str,0,-1).')';
		$db->query("DELETE FROM {$gtablepre}roomlisteners WHERE (port,roomid,timestamp,uniqid) IN $str");
		foreach ($lis as $port)
		{
			$___TEMP_socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);  
			if ($___TEMP_socket===false) continue;
			$___TEMP_connected=socket_connect($___TEMP_socket,'127.0.0.1',$port);
			if (!$___TEMP_connected) continue;
			socket_shutdown($___TEMP_socket);
		}
	}
}
	
function room_init($roomtype)
{
	$a['roomtype']=$roomtype;
	//数据库中的status字段意义：
	//0 房间不存在
	//1 房间存在，游戏未开始
	//2 房间存在，游戏已开始
	//3 房间存在且为永续房间，跳过等待过程直接开始
	
	//roomstat在数据库status字段为1时才有意义
	//0 等待玩家
	//1 人数已满（等待所有玩家点击准备，并进入踢人倒计时）
	//2 即将开始（正在进行游戏初始化工作）
	
	$a['roomstat']=0;
	
	//踢人时间，由使roomstat进入1的操作者负责设置
	$a['kicktime']=0;
	
	//各位置信息
	global $roomtypelist;
	for ($i=0; $i<$roomtypelist[$roomtype]['pnum']; $i++)
	{
		//在该位置的玩家名
		$s['name']='';
		//准备状态（roomstat=1有效，由使roomstat进入1的操作者负责重置）
		$s['ready']=0;
		//该位置是否被禁入
		$s['forbidden']=0;
		//该位置所属队伍（如等于该位置自身编号，则为队长，队长可以将本队其他位置设置禁入）
		$s['leader']=$roomtypelist[$roomtype]['leader-position'][$i];
		$a['player'][$i]=$s;
		unset($s);
	}
	
	//时间戳，用于更新
	$a['timestamp']=1;
	
	//最近10条聊天信息
	$a['chatdata']=Array();
	for ($i=0; $i<10; $i++)
	{
		$s['cid'] = -1;
		$s['data'] = '';
		$a['chatdata'][$i]=$s;
		unset($s);
	}
	return $a;
}

function room_create($roomtype)
{
	eval(import_module('sys'));
	global $roomtypelist;
	$roomtype=(int)$roomtype;
	if ($roomtype>=count($roomtypelist))
	{
		gexit('房间参数错误',__file__,__line__);
		die();
	}
	
	global $max_room_num;
	$rchoice = -1;
	for ($i=1; $i<=$max_room_num; $i++)
	{
		$result = $db->query("SELECT status FROM {$gtablepre}rooms WHERE roomid = '$i'");
		if(!$db->num_rows($result)) 
		{
			$db->query("INSERT INTO {$gtablepre}rooms (roomid,status) VALUES ($i,1)");
			$rchoice = $i; break;
		}
		else 
		{
			$zz=$db->fetch_array($result);
			if ($zz['status']==0)
			{
				$db->query("UPDATE {$gtablepre}rooms SET status = 1 WHERE roomid = '$i'");
				$rchoice = $i; break;
			}
		}
	}
	if ($rchoice == -1)
	{
		gexit('房间数目已经达到上限，请加入一个已存在的房间',__file__,__line__);
		die();
	}
	
	$roomdata = room_init($roomtype);
	global $cuser;
	$roomdata['player'][0]['name']=$cuser;
	writeover(GAME_ROOT.'./gamedata/tmp/rooms/'.$rchoice.'.txt', base64_encode(gzencode(compatible_json_encode($roomdata))));
	$db->query("DELETE from {$gtablepre}roomlisteners WHERE roomid = '$rchoice'"); 
	return $rchoice;
}

function room_new_chat(&$roomdata,$str)
{
	for ($i=1; $i<=9; $i++) $roomdata['chatdata'][$i-1]=$roomdata['chatdata'][$i];
	$roomdata['chatdata'][9]['cid']=max($roomdata['chatdata'][8]['cid'],0)+1;
	$roomdata['chatdata'][9]['data']=$str;
	$roomdata['timestamp']++;
}

function room_enter($id)
{
	eval(import_module('sys'));
	$id=(int)$id;
	$result = $db->query("SELECT status FROM {$gtablepre}rooms WHERE roomid = '$id'");
	if(!$db->num_rows($result)) 
	{
		gexit('房间编号不存在',__file__,__line__);
		die();
	}
	$zz=$db->fetch_array($result);
	if ($zz['status']==0)
	{
		gexit('房间编号不存在',__file__,__line__);
		die();
	}
	
	if (!file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$id.'.txt')) 
	{
		gexit('房间编号不存在',__file__,__line__);
		die();
	}
	$roomdata = json_decode(mgzdecode(base64_decode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$id.'.txt'))),1);
	global $cuser;
	room_new_chat($roomdata,"<span class=\"grey\">{$cuser}进入了房间</span><br>");
	$db->query("UPDATE {$gtablepre}users SET roomid = 's{$id}' WHERE username = '$cuser'");
	room_save_broadcast($id,$roomdata);
	header('Location: index.php');
	die();
}
	
function room_showdata($roomdata, $user)
{
	global $roomid;
	include GAME_ROOT.'./include/roommng.config.php';
	$upos = -1;
	for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
		if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$user)
			$upos = $i;
			
	ob_clean();
	ob_start();
	include template('roommain');
	$gamedata['innerHTML']['roommain'] = ob_get_contents();
	if ($roomdata['roomstat']==2) $gamedata['innerHTML']['roomchatarea'] = '<div></div>';
	$gamedata['value']['timestamp'] = $roomdata['timestamp'];
	if ($roomdata['roomstat']!=2) $gamedata['lastchat']=$roomdata['chatdata'];
	ob_clean();
	echo base64_encode(gzencode(compatible_json_encode($gamedata)));
}
	
function room_getteamhtml(&$roomdata, $u)
{
	global $roomtypelist;
	$str='';
	for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
		if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']!='' && $roomtypelist[$roomdata['roomtype']]['leader-position'][$i]==$u)
		{
			$str.=$roomdata['player'][$i]['name'].',';
		}
	if ($str!='') $str=substr($str,0,-1);
	return $str;
}
