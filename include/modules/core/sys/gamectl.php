<?php

namespace sys
{
	global $gameover_plist, $gameover_ulist;
	
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
		eval(import_module('sys','player'));
//		startmicrotime();
		//先加锁以阻塞对game表的读取，免得这个进程还在执行时，别的请求穿透到数据库造成各种各样的脏数据问题
		process_lock();
		load_gameinfo();
		//游戏未准备的情况下直接返回
		if($gamestate < 5) return;
		
		//提取所有入场玩家的pdata和udata数据备用
		$gameover_plist = $gameover_alivelist = array();
		$result = $db->query("SELECT name FROM {$tablepre}players WHERE type=0");
		while($r = $db->fetch_array($result)){
			if(!empty($sdata) && $r['pid'] == $sdata['pid']){
				$gameover_plist[$r['name']] = $sdata;
			}else{
				$gameover_plist[$r['name']] = \player\fetch_playerdata($r['name']);//可能的性能瓶颈1号，循环中锁玩家并读数据库
			}
			if($gameover_plist[$r['name']]['hp'] > 0) $gameover_alivelist[$r['name']] = &$gameover_plist[$r['name']];
		}
		if(!empty($gameover_plist)) {
			$gameover_ulist = fetch_udata_multilist('*', array('username' => array_keys($gameover_plist)));
			if(empty($gameover_ulist)) return; //如果对应的玩家都不存在（一般是远端数据库无响应），直接返回，避免污染数据库
		}else{
			$gameover_ulist = array();
		}
		$validnum = count($gameover_plist);
		$alivenum = count($gameover_alivelist);
		
		//在没提供游戏结束模式的情况下，自行判断模式
		if((!$gmode)||(($gmode=='end2')&&(!$winname))) {
			if($validnum <= 0) {//无激活者情况下，无人参加
				$alivenum = 0;
				$winnum = 0;
				$winmode = 4;
				$winner = '';
			} else {//判断谁是最后幸存者
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
							foreach($gameover_alivelist as &$wdata){ break;}
							$winner = $wdata['name'];
							$wdata['winner_flag'] = $winmode;
							\player\player_save($wdata);
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
						foreach($gameover_alivelist as $ai => $data){
							if($first) {
								$first=0;
								$firstteamID=$data['teamID'];
							}elseif($firstteamID!=$data['teamID'] || !$data['teamID']){
								//如果有超过一种teamID，或有超过一个人没有teamID，则游戏还未结束
								$flag=0; break;
							}
						}
						if ($flag && !$first)
						{
							if (!$firstteamID)	//单人胜利
							{	
								foreach($gameover_alivelist as &$wdata){ break;}
								$wdata['winner_flag'] = 2;
								\player\player_save($wdata);
								$winnum = 1;
								$winner = $wdata['name'];
							}
							else				//团队胜利，要记录已经死掉的玩家的名字，所以重新读1次玩家池
							{
								$teammatelist = array();
								foreach($gameover_plist as &$wdata){
									if($wdata['teamID'] == $firstteamID){
										$wdata['winner_flag'] = 2; //把队伍里所有玩家的状态改为获胜，用于天梯积分等判定。
										$teammatelist[] = $wdata['name'];//保存队友数据
									}
								}
								$db->query("UPDATE {$tablepre}players SET winner_flag='2' WHERE type = 0 AND teamID = '$firstteamID'");
								
								$winnum=count($teammatelist);
								if ($winnum == 1)
								{
									$winner = $teammatelist[0];
								}elseif($winnum > 1){
									$winner = $namelist = implode(',',$teammatelist);//注意，从这里开始，组队模式$winner会是一个用逗号分隔的字符串
								}
							}
							
						}
						else
						{	//不满足游戏结束条件，返回
							save_gameinfo();
							return;
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
		//2023.12.07 如果全灭，额外检查一次，把所有没有死的玩家设为死亡（视为禁死）。不过这会被当前玩家的刷新覆盖，在end.php界面需要单独处理
		if(1==$winmode) {
			$db->query("UPDATE {$tablepre}players SET hp=0 AND state=11 WHERE type=0 AND hp>0");
		}
		
		//需要先判定获胜者的成就请重载这里
		post_winnercheck_events($winner);		
		
		$gamestate = 0;
		$gamevars['o_starttime'] = $starttime; $starttime = 0; //偶尔会发生穿透事故，先这么一修看看情况
		save_gameinfo();
		$starttime = $gamevars['o_starttime'];
//		logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-模式判断');
		//以下开始真正处理gameover的各种数据修改
		$time = !empty($time) ? $time : $now;
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
		if(!in_array($winmode, array(0, 4))){//非无人参加/程序故障，需要记录杀人最多和最高伤害，并记录参与玩家
			$winnerdata['hdmg'] = $hdamage;
			$winnerdata['hdp'] = $hplayer;
			$result = $db->query("SELECT name,killnum FROM {$tablepre}players WHERE type=0 order by killnum desc, lvl desc limit 1");
			$hk = $db->fetch_array($result);
			$winnerdata['hkill'] = $hk['killnum'];
			$winnerdata['hkp'] = $hk['name'];
			$winnerdata['validlist'] = gencode($gameover_plist);
		}
		if(!in_array($winmode, array(0, 1, 4, 6))){//非全部死亡/GM中止，需要记录优胜者
			$winnerdata['winner'] = $winner;
			$winnerdata['winnernum'] = $winnum;
			if ($winnum == 1){
				$winnerdata['winnerpdata'] = gencode($gameover_plist[$winner]);
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
		gameover_set_credits();
//		logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-切糕发放');
		//重置游戏开始时间和当前游戏状态
		rs_sttime();
		
		//进行天梯积分计算、录像处理之类的后期工作
//		startmicrotime();
		post_gameover_events();//录像，可能的性能瓶颈2号
//		logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-录像等处理全部结束');
		//echo '**游戏结束**';
		
		//这里把$gameover_ulist一起更新
		$updatelist = $gameover_ulist;
		foreach($updatelist as &$gv){
			foreach (array_keys($gv) as $fv){
				if(!in_array($fv, get_gameover_udata_update_fields()))
					unset($gv[$fv]);
			}
		}
		update_udata_multilist($updatelist);
		
		//新闻之类
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
	
	//判断是不是获胜者的函数（获胜者可能是一个逗号分隔字符串）
	function is_winner($n,$w){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(false===strpos($w, ',')) {
			return $n==$w;
		}else {
			$w_a = explode(',', $w);
			return in_array($n, $w_a);
		}
	}
	
	function get_gameover_udata_update_fields(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array('username', 'validgames', 'wingames', 'lastwin', 'credits', 'gold', 'u_achievements');
	}

	function routine()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//file_put_contents('a.txt', $GLOBALS['room_id'].'  '.var_export(debug_backtrace(),1)."\r\n\r\n\r\n", FILE_APPEND);
		process_lock();
		load_gameinfo();
		updategame();
		save_gameinfo();
		process_unlock();
	}
	
	//结算积分和切糕
	function gameover_set_credits()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(empty($gameover_plist)) return;
		
		foreach($gameover_plist as $key => $val){
			if(isset($gameover_ulist[$key])){
				$gudata = &$gameover_ulist[$key];
				$gudata['credits'] += gameover_get_credit_up($val,$winner,$winmode);
				$gudata['gold'] += gameover_get_gold_up($val,$winner,$winmode);
				//伐木不算参与次数
				if($gametype != 15) $gudata['validgames']+= 1;
				//非伐木房的幸存、解禁、解离、核爆，或者除错局，才算获胜次数
				if((($gametype != 15 && in_array($winmode, array(2, 3, 5, 7))) || $gametype == 1) && $key == $winner) {
					$gudata['wingames'] += 1;
					$gudata['lastwin'] = $now;
				}
			}
		}
		return;
	}

	//结算积分，注意不能获得切糕的房同样不能获得积分
	function gameover_get_credit_up($data,$winner = '',$winmode = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (in_array($gametype,$qiegao_ignore_mode)) return 0;
		if(is_winner($data['name'],$winner)){//获胜
			if($winmode == 2){$up = 200;}//最后幸存+200
			elseif($winmode == 3){$up = 500;}//解禁+500
			elseif($winmode == 5){$up = 100;}//核弹+100
			elseif($winmode == 7){$up = 1200;}//解离+1200
			else{$up = 50;}//其他胜利方式+50（暂时没有这种胜利方式）
		}
		elseif($data['hp']>0){$up = 25;}//存活但不是获胜者+25
		else{$up = 10;}//死亡+10
		if($data['killnum']){
			$up += $data['killnum'] * 10;//杀一玩家加10
		}
		if($data['npckillnum']){
			$up += $data['npckillnum'] * 2;//杀一NPC加2
		}
		if($data['lvl']){
			$up += round($data['lvl'] /2);//等级每2级加1
		}
		$skill = array ($data['wp'] , $data['wk'] , $data['wg'] , $data['wc'] , $data['wd'] , $data['wf']);
		rsort ( $skill );
		$maxskill = $skill[0];
		$up += round($maxskill / 25);//熟练度最高的系每25点熟练加1
		
		$money_got = gameover_check_money_got($data);
		
		$up += round($money_got/500);//每500点金钱加1
		
		//file_put_contents('a.txt', $up.' ',FILE_APPEND);
		return $up;
	}
	
	//判定玩家金钱数，可供其他模块接管
	function gameover_check_money_got($data){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $data['money'];
	}
	
	//结算切糕
	function gameover_get_gold_up($data, $winner = '',$winmode = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (in_array($gametype,$qiegao_ignore_mode)) return 0;//嘻嘻
		if (is_winner($data['name'],$winner)) {//获胜
			if($winmode == 2) $up = 120;//幸存
			elseif($winmode == 3) $up = 233;//解禁
			elseif($winmode == 7) $up = 514;//解离
			else $up = 100;//其他胜利方式
		}else{
			$up = 10;//参与奖
		}
		return $up;
	}
}

?>