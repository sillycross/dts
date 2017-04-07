<?php

error_reporting(0);
ignore_user_abort(1);

define('CURSCRIPT', 'roomcmd');

$not_ready_command_flag = 0;

if ($_POST['command']!='ready' && $_GET['command']!='ready')
{
	//define('LOAD_CORE_ONLY',TRUE);
	//这个只是为了防某些无聊玩家注入，本来不是ready命令，但过滤掉特殊字符后就成了ready……
	$not_ready_command_flag = 1;
}

define('NO_SYS_UPDATE',TRUE);

require './include/common.inc.php';

require './include/roommng/roommng.config.php';
require './include/roommng/roommng.func.php';

if(!$cuser||!$cpass) 
{ 
	ob_clean();
	header('Location: index.php');
	$gamedata['url']='index.php';
	echo gencode($gamedata);
	die();
} 

$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

if (isset($_GET['command'])) $command = $_GET['command'];
if (isset($_GET['para1'])) $para1 = $_GET['para1'];
if (isset($_GET['para2'])) $para2 = $_GET['para2'];
if (isset($_GET['para3'])) $para3 = $_GET['para3'];

$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username = '$cuser'");
if(!$db->num_rows($result)) 
{ 
	ob_clean();
	header('Location: index.php');
	$gamedata['url']='index.php';
	echo gencode($gamedata);
	die();
} 

$pdata = $db->fetch_array($result);
if($pdata['password'] != $cpass) 
{ 
	ob_clean();
	header('Location: index.php');
	$gamedata['url']='index.php';
	echo gencode($gamedata);
	die();
} 

if ($command=='newroom')
{
	if ($room_prefix=='' || $room_prefix[0]!='s') room_enter(room_create($para1));
	ob_clean();
	header('Location: index.php');
	$gamedata['url']='index.php';
	echo gencode($gamedata);
	die();
}

if ($command=='enterroom')
{
	$para1=(int)$para1;
	if ($room_prefix=='' || $room_prefix[0]!='s') room_enter($para1);
	ob_clean();
	header('Location: index.php');
	$gamedata['url']='index.php';
	echo gencode($gamedata);
	die();
}

if ($room_prefix=='' || $room_prefix[0]!='s') 
{
	ob_clean();
	header('Location: index.php');
	$gamedata['url']='index.php';
	echo gencode($gamedata);
	die();
}

$room_id_r = substr($room_prefix,1);
if (!file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$room_id_r.'.txt')) 
{
	ob_clean();
	header('Location: index.php');
	$gamedata['url']='index.php';
	echo gencode($gamedata);
	die();
}

$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$room_id_r.'.txt'),1);

$result = $db->query("SELECT groomstatus FROM {$gtablepre}game WHERE groomid = '$room_id_r'");
if(!$db->num_rows($result)) 
{
	ob_clean();
	die();
}

//房间命令只对处于等待状态的房间有效，除了退出房间命令
$rarr=$db->fetch_array($result);
if ($rarr['groomstatus']!=1 && $command!='leave')
{
	ob_clean();
	die();
}

//进入即将开始状态后，任何房间命令均无效，包括退出房间命令
if ($roomdata['roomstat']==2)
{
	ob_clean();
	die();
}

if ($rarr['groomstatus']==2) $runflag = 1; else $runflag = 0;
update_roomstate($roomdata,$runflag);

if(!$roomtypelist[$rarr['groomtype']]['continuous']){//非永续房间才进行下列判定
	//更新踢人状态
	if ($roomdata['roomstat']==1 && time()>=$roomdata['kicktime'])
	{
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
			if (!$roomdata['player'][$i]['forbidden'] && !$roomdata['player'][$i]['ready'] && $roomdata['player'][$i]['name']!='')
			{
				room_new_chat($roomdata,"<span class=\"grey\">{$roomdata['player'][$i]['name']}因为长时间未准备，被系统踢出了房间。</span><br>");
				$roomdata['player'][$i]['name']='';
			}
		room_save_broadcast($room_id_r,$roomdata);
	}
	
	if ($command=='newchat')
	{
		room_new_chat($roomdata,"<span class=\"white\"><span class=\"yellow\">{$cuser}:</span>&nbsp;{$para1}</span><br>");
		room_save_broadcast($room_id_r,$roomdata);
		die();
	}
	
	if ($command=='enterpos')
	{
		$para1=(int)$para1;
		$upos = -1;
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
			if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$cuser)
				$upos = $i;
		
		if (	$upos!=$para1 
			&& 0<=$para1 && $para1<$roomtypelist[$roomdata['roomtype']]['pnum'] 
			&& !$roomdata['player'][$para1]['forbidden'] 
			&& $roomdata['player'][$para1]['name']=='')
			{
				if ($upos!=-1) 
				{
					$roomdata['player'][$upos]['name']='';
					$roomdata['player'][$upos]['ready']=0;
					//移动位置时，如为队长，该队所有位置重新回到启用状态
					if ($roomtypelist[$roomdata['roomtype']]['leader-position'][$upos]==$upos)
					{
						for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
							if (	$roomtypelist[$roomdata['roomtype']]['leader-position'][$i]==$upos
								&& $roomdata['player'][$i]['forbidden'])
								{
									$roomdata['player'][$i]['forbidden']=0;
									$roomdata['player'][$i]['name']='';
									$roomdata['player'][$i]['ready']=0;
								}
					}
				}
								
				$roomdata['player'][$para1]['name']=$cuser;
				$roomdata['player'][$para1]['ready']=0;
				if ($upos==-1)
					room_new_chat($roomdata,"<span class=\"grey\">{$cuser}进入了一个空位置</span><br>");
				else  room_new_chat($roomdata,"<span class=\"grey\">{$cuser}移动了位置</span><br>");
				room_save_broadcast($room_id_r,$roomdata);
			}
			
		die();
	}
	
	if ($command=='disablepos')
	{
		$para1=(int)$para1;
		$upos = -1;
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
			if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$cuser)
				$upos = $i;
		
		if (	$upos>=0 && $upos!=$para1 
			&& 0<=$para1 && $para1<$roomtypelist[$roomdata['roomtype']]['pnum'] 
			&& !$roomdata['player'][$para1]['forbidden'] 
			&& $roomdata['player'][$para1]['name']==''
			&& $roomtypelist[$roomdata['roomtype']]['leader-position'][$para1]==$upos)
			{
				$roomdata['player'][$para1]['forbidden']=1;
				room_new_chat($roomdata,"<span class=\"grey\">{$cuser}禁用了其队伍的一个位置</span><br>");
				room_save_broadcast($room_id_r,$roomdata);
			}
			
		die();
	}
	
	if ($command=='enablepos')
	{
		$para1=(int)$para1;
		$upos = -1;
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
			if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$cuser)
				$upos = $i;
		
		if (	$upos>=0 && $upos!=$para1 
			&& 0<=$para1 && $para1<$roomtypelist[$roomdata['roomtype']]['pnum'] 
			&& $roomdata['player'][$para1]['forbidden'] 
			&& $roomtypelist[$roomdata['roomtype']]['leader-position'][$para1]==$upos)
			{
				$roomdata['player'][$para1]['forbidden']=0;
				$roomdata['player'][$para1]['name']='';
				$roomdata['player'][$upos]['ready']=0;
				room_new_chat($roomdata,"<span class=\"grey\">{$cuser}重新启用了其队伍的一个位置</span><br>");
				room_save_broadcast($room_id_r,$roomdata);
			}
			
		die();
	}
	
	if ($command=='kickpos')
	{
		$para1=(int)$para1;
		$upos = -1;
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
			if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$cuser)
				$upos = $i;
		
		if (	$upos==0 && $upos!=$para1 
			&& 0<=$para1 && $para1<$roomtypelist[$roomdata['roomtype']]['pnum'] 
			&& !$roomdata['player'][$para1]['forbidden'] 
			&& $roomdata['player'][$para1]['name']!='')
			{
				$tmp=$roomdata['player'][$para1]['name'];
				$roomdata['player'][$para1]['name']='';
				$roomdata['player'][$para1]['ready']=0;
				//如为队长，该队所有位置重新回到启用状态
				if ($roomtypelist[$roomdata['roomtype']]['leader-position'][$para1]==$para1)
				{
					for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
						if (	$roomtypelist[$roomdata['roomtype']]['leader-position'][$i]==$para1
							&& $roomdata['player'][$i]['forbidden'])
							{
								$roomdata['player'][$i]['forbidden']=0;
								$roomdata['player'][$i]['name']='';
								$roomdata['player'][$i]['ready']=0;
							}
				}
				room_new_chat($roomdata,"<span class=\"grey\">{$cuser}将{$tmp}踢出了房间</span><br>");
				room_save_broadcast($room_id_r,$roomdata);
			}
			
		die();
	}
	
	if ($command=='rmsetmode')
	{
		$para1=(int)$para1;
		$upos = -1;
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
			if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$cuser)
				$upos = $i;
		
		if (	$upos==0 
			&& 0<=$para1 && $para1<count($roomtypelist) && $para1!=$roomdata['roomtype'])
			{
				$tot=0;
				$nroomdata=room_init($para1);
				$nroomdata['chatdata']=$roomdata['chatdata'];
				for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
					if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']!='')
					{
						if ($tot<$roomtypelist[$nroomdata['roomtype']]['pnum'])
						{
							$nroomdata['player'][$tot]['name']=$roomdata['player'][$i]['name'];
							$tot++;
						}
					}
				$nroomdata['timestamp']=$roomdata['timestamp'];
				$roomdata=$nroomdata;
				room_new_chat($roomdata,"<span class=\"grey\">{$cuser}将房间模式修改为了{$roomtypelist[$roomdata['roomtype']]['name']}</span><br>");
				room_save_broadcast($room_id_r,$roomdata);
			}
		die();
	}
	
	if ($command=='leave')
	{
		$upos = -1;
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
			if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$cuser)
				$upos = $i;
		
		//如为队长，该队所有位置重新回到启用状态
		if ($upos>=0)
		{
			if ($roomtypelist[$roomdata['roomtype']]['leader-position'][$upos]==$upos)
			{
				for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
					if (	$roomtypelist[$roomdata['roomtype']]['leader-position'][$i]==$upos
						&& $roomdata['player'][$i]['forbidden'])
						{
							$roomdata['player'][$i]['forbidden']=0;
							$roomdata['player'][$i]['name']='';
							$roomdata['player'][$i]['ready']=0;
						}
			}
			$roomdata['player'][$upos]['name']='';
			$roomdata['player'][$upos]['ready']=0;
		}
		room_new_chat($roomdata,"<span class=\"grey\">{$cuser}离开了房间</span><br>");
		room_save_broadcast($room_id_r,$roomdata);
		
		$db->query("UPDATE {$gtablepre}users SET roomid='' WHERE username='$cuser'");
		
		if (isset($_GET['command']))
			header('Location: index.php');
		else
		{
			$gamedata['url']='index.php';
			echo gencode($gamedata);
		}
		die();
	}
	
	if ($command=='ready' && !$not_ready_command_flag)
	{
		$upos = -1;
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
			if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$cuser)
				$upos = $i;
		
		if ($upos>=0 && $roomdata['roomstat']==1 && !$roomdata['player'][$upos]['ready'])
		{
			$roomdata['player'][$upos]['ready']=1;
			$flag=1;
			for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
				if (!$roomdata['player'][$i]['forbidden'] && !$roomdata['player'][$i]['ready'])
					$flag = 0;
			
			room_new_chat($roomdata,"<span class=\"grey\">{$cuser}点击了准备</span><br>");
			if ($flag) 
			{
				$roomdata['roomstat']=2;
				room_new_chat($roomdata,"<span class=\"grey\">所有人均已准备，游戏即将开始..</span><br>");
			}
			room_save_broadcast($room_id_r,$roomdata);
			if ($flag)
			{
				include_once GAME_ROOT.'./include/valid.func.php';
				//开始游戏，并设置好游戏模式类型（2v2和3v3为队伍胜利模式）
				//$gametype = 10 + $roomdata['roomtype'];
				$gamestate = 0;
				$gametype = $roomtypelist[$roomdata['roomtype']]['gtype'];//hao蠢
				$starttime = $now;
				save_gameinfo();
				\sys\routine();
				//发送游戏模式新闻
				if ($roomdata['roomtype']==0)	//1v1
				{	
					addnews($now,'roominfo',$roomtypelist[$roomdata['roomtype']]['name'],'对决者:&nbsp;'.room_getteamhtml($roomdata,0).'&nbsp;<span class="yellow">VS</span>&nbsp;'.room_getteamhtml($roomdata,1).'！');
				}
				else  if ($roomdata['roomtype']==1)	//2
				{
					addnews($now,'roominfo',$roomtypelist[$roomdata['roomtype']]['name'],'对决者:&nbsp;<span style="color:#ff0022">红队&nbsp;'.room_getteamhtml($roomdata,0).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#5900ff">蓝队 '.room_getteamhtml($roomdata,5).'</span>！');
				}
				else  if ($roomdata['roomtype']==2)	//3
				{
					addnews($now,'roominfo',$roomtypelist[$roomdata['roomtype']]['name'],'对决者:&nbsp;<span style="color:#ff0022">红队&nbsp;'.room_getteamhtml($roomdata,0).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#5900ff">蓝队 '.room_getteamhtml($roomdata,5).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#8cff00">绿队 '.room_getteamhtml($roomdata,10).'</span>！');
				}
				else  if ($roomdata['roomtype']==3)	//4
				{
					addnews($now,'roominfo',$roomtypelist[$roomdata['roomtype']]['name'],'对决者:&nbsp;<span style="color:#ff0022">红队&nbsp;'.room_getteamhtml($roomdata,0).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#5900ff">蓝队 '.room_getteamhtml($roomdata,5).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#8cff00">绿队 '.room_getteamhtml($roomdata,10).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#ffc700">黄队 '.room_getteamhtml($roomdata,15).'</span>！');
				}
				else  if ($roomdata['roomtype']==4)	//5
				{
					addnews($now,'roominfo',$roomtypelist[$roomdata['roomtype']]['name'],'对决者:&nbsp;<span style="color:#ff0022">红队&nbsp;'.room_getteamhtml($roomdata,0).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#5900ff">蓝队 '.room_getteamhtml($roomdata,5).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#8cff00">绿队 '.room_getteamhtml($roomdata,10).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#ffc700">黄队 '.room_getteamhtml($roomdata,15).'</span>&nbsp;<span class="yellow">VS</span>&nbsp;<span style="color:#fefefe">白队 '.room_getteamhtml($roomdata,20).'</span>！');
				}
				else if ($roomdata['roomtype']==5)	//单人挑战
				{	
					addnews($now,'roominfo',$roomtypelist[$roomdata['roomtype']]['name'],'挑战者:&nbsp;'.room_getteamhtml($roomdata,0).'！');
				}
				else if ($roomdata['roomtype']==6)	//PVE
				{	
					addnews($now,'roominfo',$roomtypelist[$roomdata['roomtype']]['name'],'挑战者:&nbsp;'.room_getteamhtml($roomdata,0).'！');
				}
				//所有玩家进入游戏
				for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
					if (!$roomdata['player'][$i]['forbidden'])
					{
						$pname = $roomdata['player'][$i]['name'];
						$pname = (string)$pname;
						$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username = '$pname'");
						if($db->num_rows($result)!=1) continue;
						$pdata = $db->fetch_array($result);
						$pcard = $pdata['card'];
						if (isset($roomtypelist[$roomdata['roomtype']]['card'])){
							$pcard=$roomtypelist[$roomdata['roomtype']]['card'][$i];
						}
						enter_battlefield($pdata['username'],$pdata['password'],$pdata['gender'],$pdata['icon'],$pcard);
						$db->query("UPDATE {$tablepre}players SET teamID='{$roomtypelist[$roomdata['roomtype']]['teamID'][$roomtypelist[$roomdata['roomtype']]['leader-position'][$i]]}' WHERE name='$pname'");
					}
				//进入连斗
				if (in_array($roomdata['roomtype'],array(0,1,2,3,4))){
					$gamestate = 40;
					addnews($now,'combo');
					systemputchat($now,'combo');
				}else{
					$gamestate = 30;
				}
				save_gameinfo();
				
				//再次广播信息，这次让所有玩家跳转到游戏中
				$roomdata['roomstat']=0;
				$db->query("UPDATE {$gtablepre}game SET groomstatus=2 WHERE groomid='$room_id_r'");
				$roomdata['timestamp']++;
				$roomdata['chatdata']=room_init($roomdata['roomtype'])['chatdata'];
				room_save_broadcast($room_id_r,$roomdata);
			}
		}
	die();
	}
}

/* End of file roomcmd.php */
/* Location: /roomcmd.php */