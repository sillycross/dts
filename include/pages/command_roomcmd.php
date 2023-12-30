<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

//error_reporting(0);
//ignore_user_abort(1);
define('CURSCRIPT', 'roomcmd');
//$not_ready_command_flag = 0;
include './include/roommng/roommng.config.php';
include_once './include/roommng/roommng.func.php';

if ($command!='ready')
{
	//define('LOAD_CORE_ONLY',TRUE);
	//这个只是为了防某些无聊玩家注入，本来不是ready命令，但过滤掉特殊字符后就成了ready……
	//现在永续房间需要用这个入口，估计不能load_core_only了
	$not_ready_command_flag = 1;
}

//新建和进入房间，只有不在房间内的情况下才能进行
if ($command=='newroom' || $command=='enterroom')
{
	if(!room_check_subroom($room_prefix)) {
		if($command=='newroom') {
			$rn = room_create($para1);
			if($rn > 0) room_enter($rn, 1);//第二个参数表示触发非准备房的重载
			else return;
		}elseif($command=='enterroom') room_enter($para1);
		return;
	} else {
		gexit('你已在房间内，请先退出房间', __file__, __line__);
		return;
	}
}
//其他命令的情况下，如果不在房间内则出错退出
elseif (!room_check_subroom($room_prefix)) 
{
	if ($command=='leave')
	{
		set_current_roomid(0);
		//update_udata_by_username(array('roomid' => 0), $cuser);
		if ($not_ajax)
			echo 'redirect:index.php';
		else
		{
			$gamedata['url']='index.php';
			echo gencode($gamedata);
		}
	}else{
		gexit('你不在房间内，请先进入房间', __file__, __line__);
	}
	return;

}

$room_id_r = room_prefix2id($room_prefix);
if (!file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$room_id_r.'.txt')) 
{
	gexit('房间开关文件不存在。', __file__, __line__);
}

//$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$room_id_r.'.txt'),1);

//$result = $db->query("SELECT groomid,groomstatus,groomtype,roomvars FROM {$gtablepre}game WHERE groomid = '$room_id_r'");
$rarr = fetch_roomdata($room_id_r);
//if(!$db->num_rows($result)) 
if(empty($rarr)) 
{
	gexit('房间数据记录不存在。', __file__, __line__);
	return;
}

//房间命令只对处于等待状态的房间有效，除了退出房间命令
//$rarr=$db->fetch_array($result);
if (!($rarr['groomstatus'] >= 10 && $rarr['groomstatus'] < 40) && $command!='leave')
{
	gexit('房间不在等待状态，命令无效。', __file__, __line__);
	return;
}
$roomdata = gdecode($rarr['roomvars'] ,1);
//进入即将开始状态后，任何房间命令均无效，包括退出房间命令
if ($roomdata['readystat']==2)
{
	gexit('房间已开始，命令无效。', __file__, __line__);
	return;
}
if ($rarr['groomstatus'] >= 40) $runflag = 1; else $runflag = 0;
update_roomstate($roomdata,$runflag);
if(room_get_vars($roomdata,'soleroom')){//永续房只进行离开判定
	if ($command=='leave')
	{
		set_current_roomid(0);
		//update_udata_by_username(array('roomid' => 0), $cuser);
		if ($not_ajax)
			echo 'redirect:index.php';
		else
		{
			$gamedata['url']='index.php';
			echo gencode($gamedata);
		}
	}
}else{//非永续房间才进行下列判定
	//更新踢人状态
	if(room_auto_kick_check($roomdata)) room_save_broadcast($room_id_r,$roomdata);
	
	if ('newchat'==$command)
	{
		room_new_chat($roomdata,"<span class=\"white b\"><span class=\"yellow b\">{$cuser}:</span>&nbsp;{$para1}</span><br>");
		room_save_broadcast($room_id_r,$roomdata);
	}	
	elseif (strpos($command,'pos')===0)
	{
		$para1= ('all' == $para1) ? $para1 : (int)$para1;//过滤
		$upos = room_upos_check($roomdata);
		if('all' != $para1 && $para1 == $upos) 
			room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图操作他自己的位置</span><br>");
		elseif('all' != $para1 && ($para1 < 0 || $para1 >= room_get_vars($roomdata,'pnum'))) 
			room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图操作一个不存在的位置</span><br>");
		
		//进入位置，任何人都能操作
		elseif($command=='pos_enter'){
			if($roomdata['player'][$para1]['forbidden']) 
				room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图进入一个被禁用的位置</span><br>");
			elseif($roomdata['player'][$para1]['name']) 
				room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图进入一个有人的位置</span><br>");
			else{
				if ($upos >= 0)//已在房间内，换位置
				{
					$roomdata['player'][$upos]['name']='';
					$roomdata['player'][$upos]['ready']=0;
					//移动位置时，如为队长，该队所有位置重新回到启用状态
					if ($upos == room_team_leader_check($roomdata,$upos))
						room_refresh_team_pos($roomdata,$upos);
				}								
				$roomdata['player'][$para1]['name']=$cuser;
				$roomdata['player'][$para1]['ready']=0;
				if ($upos < 0)
					room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}进入了一个空位置</span><br>");
				else  room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}移动了位置</span><br>");
			}
		}else{//进入位置之外的操作必须先在房间内
			if($upos < 0)
				room_new_chat($roomdata,"<span class=\"red b\">不在房间内的{$cuser}试图操作一个位置</span><br>");
			//启用和禁用位置，只有房主或队长可以操作
			elseif($command=='pos_disable' || $command=='pos_enable'){
				$checkpos = is_numeric($para1) ? $para1 : $upos;//如果$para1不是数字（是all之类指令），用$upos来判定是不是队长
				if(!is_numeric($para1) && 'all' != $para1){
					room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图操作一个错误的位置</span><br>");
				}
				elseif(!room_creater_check($upos) && $upos != room_team_leader_check($roomdata,$checkpos))
				{
					room_new_chat($roomdata,"<span class=\"red b\">并非队长的{$cuser}试图操作一个位置</span><br>");
				}
				elseif($roomdata['player'][$para1]['name']) 
				{
					room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图操作一个有人的位置</span><br>");
				}
				else{
					$roomdata['player'][$upos]['ready']=0;//禁用和启用会让自己退出准备状态
					if($command=='pos_disable'){
						if(!empty(room_get_vars($roomdata, 'cannot-forbid')) || room_get_vars($roomdata, 'pnum') <= 2){
							room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图禁用一个位置，但本房间不允许禁用</span><br>");
						}else{
							if('all' == $para1) {//提交all指令，房主则禁用全部空格子，队长禁用本队空格子
								if(room_creater_check($upos)) {
									foreach($roomdata['player'] as $rk => &$rv){
										if(empty($rv['name'])) $rv['forbidden'] = 1;
									}
									room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}禁用了全部空位置</span><br>");
								}else{
									foreach($roomdata['player'] as $rk => &$rv){
										if(empty($rv['name']) && $upos == room_team_leader_check($roomdata,$rk)) $rv['forbidden'] = 1;
									}
									room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}禁用了其队伍中的全部空位置</span><br>");
								}
							}else{//单个位置
								$roomdata['player'][$para1]['name']='';							
								$roomdata['player'][$para1]['forbidden']=1;
								if(!room_creater_check($upos)) room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}禁用了其队伍的一个位置</span><br>");
								else room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}禁用了房间的一个位置</span><br>");
							}
						}
					}elseif($command=='pos_enable'){
						$roomdata['player'][$para1]['name']='';		
						$roomdata['player'][$para1]['forbidden']=0;
						if($upos!=0) room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}重新启用了其队伍的一个位置</span><br>");
						else room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}重新启用了房间的一个位置</span><br>");
					}
				}
			//踢人，房主可以踢任何人，队长可以踢同队队员
			}elseif($command=='pos_kick'){
				if(!$roomdata['player'][$para1]['name']) 
					room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图踢掉一个不存在的玩家</span><br>");
				elseif(!room_creater_check($upos) && $upos != room_team_leader_check($roomdata,$para1))
					room_new_chat($roomdata,"<span class=\"red b\">并非房主或队长的{$cuser}试图踢人</span><br>");
				elseif($upos == $para1)
					room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图踢自己</span><br>");
				else{
					//如踢掉队长，该队所有位置重新回到启用状态
					if ($para1 == room_team_leader_check($roomdata,$para1))
						room_refresh_team_pos($roomdata,$para1);
					$tmp=$roomdata['player'][$para1]['name'];
					$roomdata['player'][$para1]['name']='';
					$roomdata['player'][$para1]['ready']=0;
					room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}将{$tmp}踢出了房间</span><br>");
				}
			}
		}		
		room_save_broadcast($room_id_r,$roomdata);
	}
	elseif ('rmsetmode'==$command)
	{
		$para1=(int)$para1;
		$upos = room_upos_check($roomdata);
//		$upos = -1;
//		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
//			if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$cuser)
//				$upos = $i;
		
		if (	$upos==0 
			&& in_array($para1, array_keys($roomtypelist)) && $para1!=$roomdata['roomtype'] && !$roomtypelist[$para1]['soleroom'])
		{
			//$tot=0;
			$nroomdata=room_init($para1);
			$nroomdata['chatdata']=$roomdata['chatdata'];//复制聊天记录
			
			$rdplist = & room_get_vars($roomdata, 'player');
			$nrdplist = & room_get_vars($nroomdata, 'player');
			$rdpnum = room_get_vars($roomdata, 'pnum');
			$nrdpnum = room_get_vars($nroomdata, 'pnum');
			$inum = min($rdpnum,$nrdpnum);
			for ($i=0; $i < $inum; $i++)
			{
				if (in_array($para1, array(1,2,3,4)) && $rdplist[$i]['forbidden'] && !$rdplist[$i]['name'])//组队模式切换时复制禁用位置
				{
					$nrdplist[$i]['forbidden'] = 1;
				}elseif ($rdplist[$i]['name'])//复制玩家位置
				{
					$nrdplist[$i]['name']=$rdplist[$i]['name'];
				}
			}
			$nroomdata['timestamp']=$roomdata['timestamp'];
			$roomdata=$nroomdata;
			$rname = room_get_vars($roomdata, 'name');
			room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}将房间模式修改为了{$rname}</span><br>");
			room_save_broadcast($room_id_r,$roomdata);
		}else{
			room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图修改房间模式但未成功</span><br>");
		}
	}
	elseif('game-option'==$command)
	{
		$upos = room_upos_check($roomdata);
		if($upos!=0)
			room_new_chat($roomdata,"<span class=\"red b\">并非房主的{$cuser}试图改变游戏设置</span><br>");
		elseif(!room_check_game_option($roomdata['roomtype'], $para1, $para2))
			room_new_chat($roomdata,"<span class=\"red b\">{$cuser}试图设置一个错误的游戏参数</span><br>");
		else {
			$go = room_get_vars($roomdata,'game-option');
			$gokey_words = $go[$para1]['title'];
			$o_oval = room_get_vars($roomdata,'current_game_option')[$para1];
			if ($go[$para1]['type'] == 'number')
			{
				//给提示赋值作准备
				if (empty($o_oval)) $o_oval = 0;
				$o_oval_words = $o_oval;
				$n_oval_words = $para2;
				//实际修改
				room_set_game_option($roomdata, $para1, $para2);
			}
			else
			{
				//给提示赋值作准备
				foreach($go[$para1]['options'] as $ov){
					if($ov['value'] == $o_oval) $o_oval_words = $ov['name'];
					if($ov['value'] == $para2) $n_oval_words = $ov['name'];
					if(isset($o_oval_words) && isset($n_oval_words)) break;
				}
				//实际修改
				room_set_game_option($roomdata, $para1, $para2);
			}
			if(empty($go[$para1]['no-notice-when-modified'])) {//需要公告选项被修改的，发出聊天信息
				room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}将 {$gokey_words} 从 {$o_oval_words} 变为 {$n_oval_words} </span><br>");
			}else{//不需要的，则刷新房间时间计数
				room_timestamp_tick($roomdata);
			}
			//队伍数目特判，改变队伍数目时刷新新增或者删去的队伍位置
			if('group-num'==$para1){
				$range1 = min($para2, $o_oval) * 5; $range2 = max($para2, $o_oval) * 5;
				for($oi=$range1;$oi<$range2;$oi++){
					$roomdata['player'][$oi]['name']='';
					$roomdata['player'][$oi]['ready']=0;
					$roomdata['player'][$oi]['forbidden']=0;
				}
			}
		}
		room_save_broadcast($room_id_r,$roomdata);
	}
	elseif ('leave'==$command)
	{
		//只有房间等待中，离开房间才会更改房间player列表（否则房主在游戏开始后退出会导致部分房间结束后闪退，引起结局看不到等问题）
		if($rarr['groomstatus'] < 40) {
			$upos = room_upos_check($roomdata);
			if ($upos>=0)
			{
				$rdplist = & room_get_vars($roomdata, 'player');
				//如为队长，该队所有位置重新回到启用状态
				if ($upos == room_team_leader_check($roomdata,$upos))
					room_refresh_team_pos($roomdata,$upos);
				$rdplist[$upos]['name']='';
				$rdplist[$upos]['ready']=0;
			}
		}
		room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}离开了房间</span><br>");
		room_save_broadcast($room_id_r,$roomdata);
		set_current_roomid(0);
		if ($not_ajax)
			echo 'redirect:index.php';
		else
		{
			$gamedata['url']='index.php';
			echo gencode($gamedata);
		}
	}
	elseif ('ready' == $command && !$not_ready_command_flag)
	{
		if($disable_newgame || $disable_newroom)
		{
			set_current_roomid(0);
			//update_udata_by_username(array('roomid' => 0), $cuser);
			gexit('系统维护中，暂时不能进入房间。');
			return;
		}
		$upos = room_upos_check($roomdata);
		$rdplist = & room_get_vars($roomdata, 'player');
		$rdpnum = room_get_vars($roomdata, 'pnum');
//		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
//			if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$cuser)
//				$upos = $i;
		
		if ($upos>=0 && $roomdata['readystat']==1 && !$rdplist[$upos]['ready'] && !room_get_vars($roomdata,'without-ready'))
		{
			$rdplist[$upos]['ready']=1;
			$flag=1;
			for ($i=0; $i < $rdpnum; $i++)
				if (!$rdplist[$i]['forbidden'] && !$rdplist[$i]['ready'])
					$flag = 0;
			
			room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}点击了准备</span><br>");
			if ($flag) 
			{
				$roomdata['readystat']=2;
				room_new_chat($roomdata,"<span class=\"grey b\">所有人均已准备，游戏即将开始..</span><br>");
			}
			room_save_broadcast($room_id_r,$roomdata);
			if ($flag)
			{
				include_once GAME_ROOT.'./include/valid.func.php';
				//先关闭房间，避免后面fetch_udata远程调用出问题导致房间卡住
				$roomdata['readystat']=0;
				roomdata_save($room_id_r,$roomdata);

				//开始游戏，并设置好游戏模式类型（2v2和3v3为队伍胜利模式）
				//$gametype = 10 + $roomdata['roomtype'];
				$gamestate = 0;
				$gametype = room_get_vars($roomdata,'gtype');//$roomtypelist[$roomdata['roomtype']]['gtype'];//hao蠢
				$starttime = $now;
				save_gameinfo();
				\sys\routine();
				//发送游戏模式新闻
				if ($roomdata['roomtype']==0)	//1v1
				{	
					addnews($now,'roominfo',room_get_vars($roomdata, 'name'),'对决者:&nbsp;'.room_getteamhtml($roomdata,0).'&nbsp;<span class="yellow b">VS</span>&nbsp;'.room_getteamhtml($roomdata,1).'！');
				}
				elseif ($roomdata['roomtype']==1)	//2 废弃
				{
					addnews($now,'roominfo',room_get_vars($roomdata, 'name'),'对决者:&nbsp;<span style="color:#ff0022">红队&nbsp;'.room_getteamhtml($roomdata,0).'</span>&nbsp;<span class="yellow b">VS</span>&nbsp;<span style="color:#5900ff">蓝队 '.room_getteamhtml($roomdata,5).'</span>！');
				}
				elseif ($roomdata['roomtype']==2)	//3 废弃
				{
					addnews($now,'roominfo',room_get_vars($roomdata, 'name'),'对决者:&nbsp;<span style="color:#ff0022">红队&nbsp;'.room_getteamhtml($roomdata,0).'</span>&nbsp;<span class="yellow b">VS</span>&nbsp;<span style="color:#5900ff">蓝队 '.room_getteamhtml($roomdata,5).'</span>&nbsp;<span class="yellow b">VS</span>&nbsp;<span style="color:#8cff00">绿队 '.room_getteamhtml($roomdata,10).'</span>！');
				}
				elseif ($roomdata['roomtype']==3)	//4 废弃
				{
					addnews($now,'roominfo',room_get_vars($roomdata, 'name'),'对决者:&nbsp;<span style="color:#ff0022">红队&nbsp;'.room_getteamhtml($roomdata,0).'</span>&nbsp;<span class="yellow b">VS</span>&nbsp;<span style="color:#5900ff">蓝队 '.room_getteamhtml($roomdata,5).'</span>&nbsp;<span class="yellow b">VS</span>&nbsp;<span style="color:#8cff00">绿队 '.room_getteamhtml($roomdata,10).'</span>&nbsp;<span class="yellow b">VS</span>&nbsp;<span style="color:#ffc700">黄队 '.room_getteamhtml($roomdata,15).'</span>！');
				}
				elseif ($roomdata['roomtype']==4)	//组队模式
				{
					$groupnum = room_get_vars($roomdata,'group-num');
					$newsarr = array();
					//组队模式玩家情况的字符串较长，放$e
					for($gi=0;$gi<$groupnum;$gi++){
						$newsarr[] = '<span style="color:'.(room_get_vars($roomdata, 'color')[$gi*5]).'">'.(room_get_vars($roomdata, 'teamID')[$gi*5]).'&nbsp;'.room_getteamhtml($roomdata,$gi*5).'</span>';
					}
					addnews($now,'roominfo',room_get_vars($roomdata, 'name'),'对决者:&nbsp;','','',implode('&nbsp;<span class="yellow b">VS</span>&nbsp;', $newsarr).'！');
				}
				elseif ($roomdata['roomtype']==5)	//单人挑战
				{	
					addnews($now,'roominfo',room_get_vars($roomdata, 'name'),'挑战者：'.room_getteamhtml($roomdata,0).'！');
				}
				elseif ($roomdata['roomtype']==6)	//PVE
				{	
					addnews($now,'roominfo',room_get_vars($roomdata, 'name'),'挑战者：'.room_getteamhtml($roomdata,0).'！');
				}
				elseif ($roomdata['roomtype']==11)	//试炼模式
				{	
					$alvl = (int)room_get_vars($roomdata, 'current_game_option')['lvl'];
					addnews($now,'roominfo',room_get_vars($roomdata, 'name'),'挑战者：'.room_getteamhtml($roomdata,0).'，挑战等级：'.$alvl.'。');
				}
				//一次性查询所有玩家
				$namelist = array();
				for ($i=0; $i < $rdpnum; $i++){
					if (!$rdplist[$i]['forbidden'])
					{
						$pname = $rdplist[$i]['name'];
						$namelist[] = (string)$pname;
					}
				}
				$ulist = fetch_udata_multilist('*', array('username' => $namelist));
				if(empty($ulist)) return;
				//所有玩家进入游戏
				$ownercard = $leadercard = 0;
				for ($i=0; $i < $rdpnum; $i++)
					if (!$rdplist[$i]['forbidden'])
					{
						$pname = $rdplist[$i]['name'];
						$pname = (string)$pname;
						if(!isset($ulist[$pname])) continue;
						$udata = $ulist[$pname];
						if(0 == room_get_vars($roomdata,'card-select')){//自选卡片
							$pcard = $udata['card'];
						}elseif(1 == room_get_vars($roomdata,'card-select')){//仅挑战者
							$pcard = 0;
						}elseif(2 == room_get_vars($roomdata,'card-select')){//与房主相同
							if(!$i){
								$pcard = $ownercard = $udata['card'];
							}else{
								$pcard = $ownercard;
							}
						}elseif(3 == room_get_vars($roomdata,'card-select')){//与队长相同
							if($i == room_get_vars($roomdata,'leader-position')[$i]) {
								$pcard = $leadercard = $udata['card'];
							}else{
								$pcard = $leadercard;
							}
						}
						if (isset($roomtypelist[$roomdata['roomtype']]['card'])){
							$pcard=$roomtypelist[$roomdata['roomtype']]['card'][$i];
						}
						enter_battlefield($udata['username'],$udata['password'],$udata['gender'],$udata['icon'],$pcard,$udata['ip']);
						$db->query("UPDATE {$tablepre}players SET teamID='{$roomtypelist[$roomdata['roomtype']]['teamID'][$roomtypelist[$roomdata['roomtype']]['leader-position'][$i]]}' WHERE name='$pname' AND type=0");
					}
				//进入连斗
				$opgamestate = room_get_vars($roomdata,'opening-gamestate');
				$gamestate = 30;
				if (in_array($roomdata['roomtype'],array(0,1,2,3,4)) && (empty($opgamestate) || 40 == $opgamestate)){
					$gamestate = 40;
					addnews($now,'combo');
					systemputchat($now,'combo');
				}elseif(!empty($opgamestate)){
					$gamestate = $opgamestate;
				}elseif ($roomdata['roomtype'] == 11){
					$current_go=room_get_vars($roomdata, 'current_game_option');
					$alvl = (int)$current_go['lvl'];
					if ($alvl >= 18)
					{
						$gamestate = 40;
						addnews($now,'combo');
						systemputchat($now,'combo');
					}
				}
				save_gameinfo();
				
				//再次广播信息，这次让所有玩家跳转到游戏中
				$db->query("UPDATE {$gtablepre}game SET groomstatus=40 WHERE groomid='$room_id_r'");
				$roomdata['timestamp']++;
				$roomdata['chatdata']=room_init($roomdata['roomtype'])['chatdata'];
				room_save_broadcast($room_id_r,$roomdata);
			}
		}
	}
	
	elseif('start' == $command){
		if($disable_newgame || $disable_newroom)
		{
			set_current_roomid(0);
			//update_udata_by_username(array('roomid' => 0), $cuser);
			gexit('系统维护中，暂时不能进入房间。');
			return;
		}
		$upos = room_upos_check($roomdata);
		$rdplist = & room_get_vars($roomdata, 'player');
		$rdpnum = room_get_vars($roomdata, 'pnum');
		//只有房主可以启动不需要准备的模式的房间的游戏
		if ($upos==0 && room_get_vars($roomdata,'without-ready'))
		{
			$rdplist[$upos]['ready'] = 1;//这样才能触发“即将进入游戏”界面
			$gamestate = 0;
			$gametype = room_get_vars($roomdata,'gtype');
			$starttime = $now;
			save_gameinfo();
			\sys\routine();
			$roomdata['readystat']=2;
			$roomdata['timestamp']++;
			room_save_broadcast($room_id_r,$roomdata);
			//usleep(100000);//忘了之前为什么写这句了，先删掉看看会出什么篓子
			$db->query("UPDATE {$gtablepre}game SET groomstatus=40 WHERE groomid='$room_id_r'");
			$roomdata['readystat']=0;
			$roomdata['timestamp']++;
			$roomdata['chatdata']=room_init($roomdata['roomtype'])['chatdata'];
			room_save_broadcast($room_id_r,$roomdata);
		}
	}
}

/* End of file command_roomcmd.php */
/* Location: /include/pages/command_roomcmd.php */