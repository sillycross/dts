<?php

namespace sys
{
	function updategame()
	{	
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function reset_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		
		//重设游戏局数（顺便+1）
		$gamenum ++;
		$result = $db->query("SELECT gid FROM {$wtablepre}history ORDER BY gid DESC LIMIT 1");
		$wgamenum = $db->result($result, 0);
		if( $db->num_rows($result) && $gamenum <= $wgamenum ) {
			$gamenum = $wgamenum + 1;
		}
		
		$dir = GAME_ROOT.'./gamedata/';
		$sqldir = GAME_ROOT.'./gamedata/sql/';
		
		//0号房恒为经典房，重置房间参数
		if(!$groomid) {
			$groomtype = 0;
			$groomstatus = 40;
			$roomvars = array();
		}
		reset_gametype();
		$gamestate = 5;//正在重设游戏
		save_gameinfo(0);
		
		//只保留$registered_gamevars定义过的游戏参数
		if(!is_array($gamevars)) $gamevars = array();
		else {
			$registered_gamevars = user_set_gamevars_list_init();
			$n_gamevars = array();
			foreach($gamevars as $key => $val){
				if(in_array($key, $registered_gamevars)) $n_gamevars[$key] = $val;
			}
			$gamevars = $n_gamevars;
		}
		
		//重设玩家互动信息、聊天记录、地图道具、地图陷阱、进行状况的数据库
		$sql = file_get_contents("{$sqldir}reset.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		$db->queries($sql);
		
		//重设游戏进行状况的时间
		writeover(GAME_ROOT.'./gamedata/tmp/news/newsinfo_'.$room_prefix.'.php',$checkstr);
		
		//清空战斗信息
		$hdamage = 0;
		$hplayer = '';
		
		save_combatinfo();
		
		//清空临时文件夹
		clear_dir(GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/',1);
		clear_dir(GAME_ROOT.'./gamedata/tmp/playerlock/room'.$groomid.'/',1);
		global $___MOD_TMP_FILE_DIRECTORY;
		clear_dir($___MOD_TMP_FILE_DIRECTORY.$room_id.'_/',1);
		clear_dir($___MOD_TMP_FILE_DIRECTORY.'_/',1,21600); //遗留问题，现在每次开局会清除4小时以前的无房间编号的显示记录（主要是看首页之类的）
	}
	
	function reset_gametype(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if(!$groomid) $gametype = 0;//经典房gametype恒为0
	}
	
	function prepare_new_game() {
		if (eval(__MAGIC__)) return $___RET_VALUE; 
	}
	
	function rs_game($xmode = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE; 
	}

	function rs_sttime() {
		//echo " - 游戏开始时间初始化 - ";
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		list($sec,$min,$hour,$day,$month,$year,$wday,$yday,$isdst) = localtime($now);
		$month++;
		$year += 1900;
		
		if (room_check_subroom($room_prefix))	//小房间不会自动开启下一局
		{
			$starttime = 0;
		}
		else
		{
			if($disable_newgame || !$startmode) {//更新模式或者手动设定开始时间
				$starttime = 0;
			} elseif ($startmode == 1) {//每日定时
				if($hour >= $starthour){ $nextday = $day+1;}
				else{$nextday = $day;}
				$nexthour = $starthour;
				$starttime = mktime($nexthour,$startmin,0,$month,$nextday,$year);
			} elseif($startmode == 2) {//整点开始
				$starthour = $starthour> 0 ? $starthour : 1;
				$startmin = $startmin> 0 ? $startmin : 1;
				$nexthour = $hour + $starthour;
				$starttime = mktime($nexthour,$startmin,0,$month,$day,$year);
			} elseif($startmode == 3) {//间隔开始
				$startmin = $startmin> 0 ? $startmin : 1;
				$nextmin = $min + $startmin;
				$nexthour = $hour;
		//		if($nextmin % 60 >= 40){//回避速1禁
		//			$nextmin+=20;
		//		}
				if($nextmin % 60 == 0){
					$nextmin +=1;
				}
				$starttime = mktime($nexthour,$nextmin,0,$month,$day,$year);
			} else {
				$starttime = 0;
			}
		}
		save_gameinfo();
		return;
	}


	function post_gameover_events()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function post_winnercheck_events($wn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//------游戏结束------
	//模式：0保留：程序故障；1：全部死亡；2：最后幸存；3：禁区解除；4：无人参加；5：核爆全灭；6：GM中止；7：幻境解离；8：挑战结束；9：教程结束；
	function gameover($time = 0, $gmode = '', $winname = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
//		startmicrotime();
		//先加锁以阻塞对game表的读取，免得这个进程还在执行时，别的请求穿透到数据库造成各种各样的脏数据问题
		process_lock();
		load_gameinfo();
		//游戏未准备的情况下直接返回
		if($gamestate < 5) return;
		//在没提供游戏结束模式的情况下，自行判断模式
		if((!$gmode)||(($gmode=='end2')&&(!$winname))) {
			if($validnum <= 0) {//无激活者情况下，无人参加
				$alivenum = 0;
				$winnum = 0;
				$winmode = 4;
				$winner = '';
			} else {//判断谁是最后幸存者
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0");
				$alivenum = $db->num_rows($result);
				//先缓存全部存活玩家
				$alive_pool = array();
				while($pd = $db->fetch_array($result)){
					//如果是自己，则用自己
					if(!empty($sdata) && $pd['pid'] == $sdata['pid']){
						$alive_pool[] = &$sdata;
					}else{
						//如果玩家池里没这个pid，就储存；如果有，就用玩家池里的（热优先于冷）
						if(!isset($pdata_pool[$pd['pid']])) {
							$pdata_pool[$pd['pid']] = $pd;
						}
						$pd = \player\playerdata_construct_process($pd);
						$alive_pool[] = &$pdata_pool[$pd['pid']];
					}
				}
				if(!$alivenum) {//全部死亡
					$winmode = 1;
					$winnum = 0;
					$winner = '';
				} else {
					if (!in_array($gametype,$teamwin_mode))//非团队模式
					{
						//判断是否最后幸存
						if($alivenum == 1) {
							$winmode = 2;
							$winnum = 1;
							$wdata = &$alive_pool[0];
							$winner = $wdata['name'];
							$wdata['state'] = 5;
							\player\player_save($wdata);
							//$db->query("UPDATE {$tablepre}players SET state='5' where pid='{$wdata['pid']}'");
						} 
						else
						{	//不满足游戏结束条件，返回
							save_gameinfo();
							return;
						}
					}
					else//团队模式
					{
						$flag=1; $first=1; $firstteamID = '';
						foreach($alive_pool as $ai => $data){
							if($first) {
								$first=0;
								$firstteamID=$data['teamID'];
							}elseif($firstteamID!=$data['teamID'] || !$data['teamID']){
								//如果有超过一种teamID，或有超过一个人没有teamID，则游戏还未就结束
								$flag=0; break;
							}
						}
						if ($flag && !$first)
						{
							if (!$firstteamID)	//单人胜利
							{	
								$wdata = &$alive_pool[0];
								$wdata['state'] = 5;
								\player\player_save($wdata);
								$winnum = 1;
								$winner = $wdata['name'];
							}
							else				//团队胜利，要记录已经死掉的玩家的名字，所以重新读1次数据库
							{
								foreach($alive_pool as &$wdata){
									if($wdata['teamID'] == $firstteamID){
										$wdata['state'] = 5; //实际上只是处理热数据，并没有在这里存数据库
									}
								}
								$db->query("UPDATE {$tablepre}players SET state='5' WHERE type = 0 AND teamID = '$firstteamID'");
								$result = $db->query("SELECT name FROM {$tablepre}players WHERE type = 0 AND teamID = '$firstteamID'");
								$winnum=$db->num_rows($result);
								if ($winnum == 1)
								{
									$data = $db->fetch_array($result);
									$winner = $data['name'];
								}elseif($winnum > 1){
									$namelist=''; $gdlist=''; $iconlist=''; $weplist='';//回头应该
									while($data = $db->fetch_array($result)) 
									{
										$namelist.=$data['name'].',';
									}
									$winner = $namelist = substr($namelist,0,-1);
								}
							}
							
						}
						else
						{	//不满足游戏结束条件，返回
							save_gameinfo();
							return;
						}
						
						if ($winnum > 1)
						{	
							
						}
						$winmode = 2;
					}
				}
			}
		} else {//提供了游戏结束模式的情况下
			$winmode = substr($gmode,3,1);
			$winnum = 1;
			$winner = $winname;
		}
		
		//需要先判定获胜者的成就请重载这里
		post_winnercheck_events($winner);
		
		$gamestate = 0;
		$gamevars['o_starttime'] = $starttime; $starttime = 0; //偶尔会发生穿透事故，先这么一修看看情况
		save_gameinfo();
		$starttime = $gamevars['o_starttime'];
//		logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-模式判断');
		//以下开始真正处理gameover的各种数据修改
		$time = $time ? $time : $now;
		//计算当前是哪一局，以优胜列表为准
		$result = $db->query("SELECT gid FROM {$wtablepre}history ORDER BY gid DESC LIMIT 1");
		if($db->num_rows($result)&&($gamenum <= $db->result($result, 0))) {
			$gamenum = $db->result($result, 0) + 1;
		}
		$winnerdata = array(//基本资料
			'gid' => $gamenum,
			'gametype' => $gametype,
			'wmode' => $winmode,
			'vnum' => $validnum,
			'gstime' => $starttime,
			'getime' => $time,
			'gtime' => $time - $starttime,
		);
		if($winmode != 4 && $winmode != 0){//非无人参加/程序故障，需要记录杀人最多和最高伤害，并记录参与玩家
			$winnerdata['hdmg'] = $hdamage;
			$winnerdata['hdp'] = $hplayer;
			$result = $db->query("SELECT name,killnum FROM {$tablepre}players WHERE type=0 order by killnum desc, lvl desc limit 1");
			$hk = $db->fetch_array($result);
			$winnerdata['hkill'] = $hk['killnum'];
			$winnerdata['hkp'] = $hk['name'];
			$validlist = array();
			$result2 = $db->query("SELECT name FROM {$tablepre}players WHERE type=0");
			while($data = $db->fetch_array($result2)) {
				$validlist[] = $data['name'];
			}
			$winnerdata['validlist'] = gencode($validlist);
		}
		if($winmode != 1 && $winmode != 6){//非全部死亡/GM中止，需要记录优胜者
			$winnerdata['winner'] = $winner;
			$winnerdata['winnernum'] = $winnum;
			if ($winnum == 1){
				if(isset($alive_pool)){
					$pdata = NULL;
					foreach($alive_pool as &$av){
						if($av['name'] == $winner) {
							$pdata = &$av;
							break;
						}
					}
				}else{
					$result = $db->query("SELECT * FROM {$tablepre}players WHERE name='$winner' AND type=0");
					$pdata = $db->fetch_array($result);
				}
				$winnerdata['winnerpdata'] = gencode($pdata);
				$result2 = $db->query("SELECT motto FROM {$gtablepre}users WHERE username='$winner'");
				$winnerdata['motto'] = $db->result($result2, 0);
			}else{
				//其实这里也可以把组队玩家资料全部丢进$winnerdata['winnerpdata']，再说吧
				$winnerdata['winnerteamID'] = $firstteamID;
				$winnerdata['winnerlist'] = $namelist;
			}
		}
		//这里真正记录
		$db->array_insert("{$wtablepre}history", $winnerdata);
		//$insert_id = $db->insert_id();
		$insert_gid = $gamenum;
//		logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-优胜记录修改');
		//发放切糕工资
		set_credits();
//		logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-切糕发放');
		//重置游戏开始时间和当前游戏状态
		rs_sttime();
		
		//进行天梯积分计算、录像处理之类的后期工作
		post_gameover_events();
//		logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-录像等后续处理');
		//echo '**游戏结束**';
		addnews($time, "end$winmode",$winner);
		addnews($time, 'gameover' ,$gamenum);
		systemputchat($time,'gameover');
		$newsinfo = load_news();
		$newsinfo = '<ul>'.implode('',$newsinfo).'</ul>';
//		logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-读取和渲染消息');
		$newsinfo = gencode($newsinfo);
		if($hnewsstorage) 
		{//如果设置数据库储存
			$offset = 1024*1024-200;//单句长度，一般应该略小于1M（max_allowed_packet默认值）
			do{
				if($offset < strlen($newsinfo)){
					$tmp_newsinfo = substr($newsinfo, 0, $offset);
					$newsinfo = substr($newsinfo, $offset);
				}else{
					$tmp_newsinfo = $newsinfo;
					$newsinfo = '';
				}
				$db->query("UPDATE {$wtablepre}history SET hnews=CONCAT(hnews, '$tmp_newsinfo') WHERE gid='$insert_gid'");
			} while (!empty($newsinfo));
		}
		else
		{//否则文件储存
			$room_gprefix = '';
			if (room_check_subroom($room_prefix)) $room_gprefix = (substr($room_prefix,0,1)).'.';
			writeover(GAME_ROOT."./gamedata/bak/{$room_gprefix}{$gamenum}_newsinfo.dat",$newsinfo,'wb+');
		}
		//至此解锁，保证所有处理都不出错
		process_unlock();
		
//		logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-写入消息并结束');
		return;
	}

	function routine()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(CURSCRIPT !== 'chat') 
		{
			process_lock();
			load_gameinfo();
			updategame();
			save_gameinfo();
			process_unlock();
		}
	}
}

?>