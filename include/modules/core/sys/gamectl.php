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
		$dir = GAME_ROOT.'./gamedata/';
		$sqldir = GAME_ROOT.'./gamedata/sql/';
		
		//0号房恒为经典房，重置房间参数
		if(!$groomid) {
			$groomtype = 0;
			$gametype = 0;
			$groomstatus = 2;
		}
		
		//重设玩家互动信息、聊天记录、地图道具、地图陷阱、进行状况
		$sql = file_get_contents("{$sqldir}reset.sql");
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		$db->queries($sql);
		
		//重设游戏进行状况的时间
		writeover(GAME_ROOT.'./gamedata/tmp/news/newsinfo_'.$room_prefix.'.php',$checkstr);
		
		//清空战斗信息
		$hdamage = 0;
		$hplayer = '';
		$noisetime = 0;
		$noisepls = 0;
		$noiseid = 0;
		$noiseid2 = 0;
		$noisemode = '';
		//$gametype = 0;
		$gamestate = 10;
		
		save_combatinfo();
		save_gameinfo(0);
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
		
		if ($room_prefix!='' && $room_prefix[0]=='s')	//小房间不会自动开启下一局
		{
			$starttime = 0;
		}
		else
		{
			if($startmode == 1) {
				if($hour >= $starthour){ $nextday = $day+1;}
				else{$nextday = $day;}
				$nexthour = $starthour;
				$starttime = mktime($nexthour,$startmin,0,$month,$nextday,$year);
			} elseif($startmode == 2) {
				$starthour = $starthour> 0 ? $starthour : 1;
				$startmin = $startmin> 0 ? $startmin : 1;
				$nexthour = $hour + $starthour;
				$starttime = mktime($nexthour,$startmin,0,$month,$day,$year);
			} elseif($startmode == 3) {
				$starthour = $starthour> 0 ? $starthour : 1;
				$nextmin = $min + $starthour;
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
	
	//------游戏结束------
	//模式：0保留：程序故障；1：全部死亡；2：最后幸存；3：禁区解除；4：无人参加；5：核爆全灭；6：GM中止；7：幻境解离；8：挑战结束；9：教程结束；
	function gameover($time = 0, $gmode = '', $winname = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//游戏未准备的情况下直接返回
		if($gamestate < 10) return;
		//其他情况下，先加锁以阻塞对game表的读取，免得这个进程还在执行时，别的请求穿透到数据库造成各种各样的脏数据问题
		process_lock();
		if((!$gmode)||(($gmode=='end2')&&(!$winname))) {//在没提供游戏结束模式的情况下，自行判断模式
			if($validnum <= 0) {//无激活者情况下，无人参加
				$alivenum = 0;
				$winnum = 0;
				$winmode = 4;
				$winner = '';
			} else {//判断谁是最后幸存者
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0");
				$alivenum = $db->num_rows($result);
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
							$wdata = $db->fetch_array($result);
							$winner = $wdata['name'];
							$db->query("UPDATE {$tablepre}players SET state='5' where pid='{$wdata['pid']}'");
						} 
						else
						{	//不满足游戏结束条件，返回
							save_gameinfo();
							return;
						}
					}
					else//团队模式
					{
						$result = $db->query("SELECT teamID FROM {$tablepre}players WHERE type = 0 AND hp > 0");
						$flag=1; $first=1; 
						while($data = $db->fetch_array($result)) 
						{
							if ($first) 
							{ 
								$first=0; $firstteamID=$data['teamID'];
							}
							else  if ($firstteamID!=$data['teamID'] || !$data['teamID'])
							{
								//如果有超过一种teamID，或有超过一个人没有teamID，则游戏还未就结束
								$flag=0; break;
							}
						}
						if ($flag && !$first)
						{
							if (!$firstteamID)	//单人胜利
							{	
								$db->query("UPDATE {$tablepre}players SET state='5' WHERE type = 0 AND hp > 0");
								$result = $db->query("SELECT name,gd,icon,wep FROM {$tablepre}players WHERE type = 0 AND hp > 0");
								$zz = $db->fetch_array($result);
								$winner = $zz['name'];
								$winnum = 1;
							}
							else				//团队胜利
							{
								$db->query("UPDATE {$tablepre}players SET state='5' WHERE type = 0 AND teamID = '$firstteamID'");
								$result = $db->query("SELECT name FROM {$tablepre}players WHERE type = 0 AND teamID = '$firstteamID'");
								$winnum=$db->num_rows($result);
								if ($winnum == 1)
								{
									$result = $db->query("SELECT name,gd,icon,wep FROM {$tablepre}players WHERE type = 0 AND teamID = '$firstteamID'");
									$zz = $db->fetch_array($result);
									$winner = $zz['name'];
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
							$namelist=''; $gdlist=''; $iconlist=''; $weplist='';
							while($data = $db->fetch_array($result)) 
							{
								$namelist.=$data['name'].',';
							}
							$winner = substr($namelist,0,-1);
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
		//以下开始真正处理gameover的各种数据修改
		$time = $time ? $time : $now;
		//计算当前是哪一局，以优胜列表为准
		$result = $db->query("SELECT gid FROM {$wtablepre}winners ORDER BY gid DESC LIMIT 1");
		if($db->num_rows($result)&&($gamenum <= $db->result($result, 0))) {
			$gamenum = $db->result($result, 0) + 1;
		}
		if($winmode == 4 || $winmode == 0){//无人参加；不需要记录任何资料
			$getime = $time;
			$db->query("INSERT INTO {$wtablepre}winners (gid,gametype,wmode,vnum,getime) VALUES ('$gamenum','$gametype','$winmode','$validnum','$getime')");
		}	elseif(($winmode == 0)||($winmode == 1)||($winmode == 6)){//程序故障、全部死亡、GM中止，不需要记录优胜者资料
			$gstime = $starttime;
			$getime = $time;
			$gtime = $time - $starttime;
			$result = $db->query("SELECT name,killnum FROM {$tablepre}players WHERE type=0 order by killnum desc, lvl desc limit 1");
			$hk = $db->fetch_array($result);
			$hkill = $hk['killnum'];
			$hkp = $hk['name'];
			$db->query("INSERT INTO {$wtablepre}winners (gid,gametype,wmode,vnum,gtime,gstime,getime,hdmg,hdp,hkill,hkp) VALUES ('$gamenum','$gametype','$winmode','$validnum','$gtime','$gstime','$getime','$hdamage','$hplayer','$hkill','$hkp')");
		} else {//最后幸存、锁定解除、核爆全灭，需要记录优胜者资料
			if ($winnum == 1)
			{
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE name='$winner' AND type=0");
				$pdata = $db->fetch_array($result);
				$result2 = $db->query("SELECT motto FROM {$gtablepre}users WHERE username='$winner'");
				$pdata['motto'] = $db->result($result2, 0);
				$result3 = $db->query("SELECT name,killnum FROM {$tablepre}players WHERE type=0 order by killnum desc, lvl desc limit 1");
				$hk = $db->fetch_array($result3);
				$pdata['hkill'] = $hk['killnum'];
				$pdata['hkp'] = $hk['name'];
				$pdata['wmode'] = $winmode;
				$pdata['vnum'] = $validnum;
				$pdata['gtime'] = $time - $starttime;
				$pdata['gstime'] = $starttime;
				$pdata['getime'] = $time;
				$pdata['hdmg'] = $hdamage;
				$pdata['hdp'] = $hplayer;
				$db->query("INSERT INTO {$wtablepre}winners (gid,gametype,name,pass,type,endtime,gd,sNo,icon,club,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,itm6,itmk6,itme6,itms6,motto,wmode,vnum,gtime,gstime,getime,hdmg,hdp,hkill,hkp,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5,itmsk6,cardname) VALUES ('".$gamenum."','".$gametype."','".$pdata['name']."','".$pdata['pass']."','".$pdata['type']."','".$pdata['endtime']."','".$pdata['gd']."','".$pdata['sNo']."','".$pdata['icon']."','".$pdata['club']."','".$pdata['hp']."','".$pdata['mhp']."','".$pdata['sp']."','".$pdata['msp']."','".$pdata['att']."','".$pdata['def']."','".$pdata['pls']."','".$pdata['lvl']."','".$pdata['exp']."','".$pdata['money']."','".$pdata['bid']."','".$pdata['inf']."','".$pdata['rage']."','".$pdata['pose']."','".$pdata['tactic']."','".$pdata['killnum']."','".$pdata['state']."','".$pdata['wp']."','".$pdata['wk']."','".$pdata['wg']."','".$pdata['wc']."','".$pdata['wd']."','".$pdata['wf']."','".$pdata['teamID']."','".$pdata['teamPass']."','".$pdata['wep']."','".$pdata['wepk']."','".$pdata['wepe']."','".$pdata['weps']."','".$pdata['arb']."','".$pdata['arbk']."','".$pdata['arbe']."','".$pdata['arbs']."','".$pdata['arh']."','".$pdata['arhk']."','".$pdata['arhe']."','".$pdata['arhs']."','".$pdata['ara']."','".$pdata['arak']."','".$pdata['arae']."','".$pdata['aras']."','".$pdata['arf']."','".$pdata['arfk']."','".$pdata['arfe']."','".$pdata['arfs']."','".$pdata['art']."','".$pdata['artk']."','".$pdata['arte']."','".$pdata['arts']."','".$pdata['itm0']."','".$pdata['itmk0']."','".$pdata['itme0']."','".$pdata['itms0']."','".$pdata['itm1']."','".$pdata['itmk1']."','".$pdata['itme1']."','".$pdata['itms1']."','".$pdata['itm2']."','".$pdata['itmk2']."','".$pdata['itme2']."','".$pdata['itms2']."','".$pdata['itm3']."','".$pdata['itmk3']."','".$pdata['itme3']."','".$pdata['itms3']."','".$pdata['itm4']."','".$pdata['itmk4']."','".$pdata['itme4']."','".$pdata['itms4']."','".$pdata['itm5']."','".$pdata['itmk5']."','".$pdata['itme5']."','".$pdata['itms5']."','".$pdata['itm6']."','".$pdata['itmk6']."','".$pdata['itme6']."','".$pdata['itms6']."','".$pdata['motto']."','".$pdata['wmode']."','".$pdata['vnum']."','".$pdata['gtime']."','".$pdata['gstime']."','".$pdata['getime']."','".$pdata['hdmg']."','".$pdata['hdp']."','".$pdata['hkill']."','".$pdata['hkp']."','".$pdata['wepsk']."','".$pdata['arbsk']."','".$pdata['arhsk']."','".$pdata['arask']."','".$pdata['arfsk']."','".$pdata['artsk']."','".$pdata['itmsk0']."','".$pdata['itmsk1']."','".$pdata['itmsk2']."','".$pdata['itmsk3']."','".$pdata['itmsk4']."','".$pdata['itmsk5']."','".$pdata['itmsk6']."','".$pdata['cardname']."')");
			}
			else
			{
				$gstime = $starttime;
				$getime = $time;
				$gtime = $time - $starttime;
				$result = $db->query("SELECT name,killnum FROM {$tablepre}players WHERE type=0 order by killnum desc, lvl desc limit 1");
				$hk = $db->fetch_array($result);
				$hkill = $hk['killnum'];
				$hkp = $hk['name'];
				$db->query("INSERT INTO {$wtablepre}winners (gid,gametype,wmode,vnum,gtime,gstime,getime,hdmg,hdp,hkill,hkp,winnum,namelist,teamID) VALUES ('$gamenum','$gametype','$winmode','$validnum','$gtime','$gstime','$getime','$hdamage','$hplayer','$hkill','$hkp','$winnum','$namelist','$firstteamID')");
			}
		}
		//发放切糕工资
		set_credits();
		//进行天梯积分计算、录像处理之类的后期工作
		post_gameover_events();
		//重置游戏开始时间和当前游戏状态
		rs_sttime();
		$gamestate = 0;
		save_gameinfo();
		//至此解锁，后面是消息记录、历史记录之类的事
		process_unlock();
		
		//echo '**游戏结束**';
		addnews($time, "end$winmode",$winner);
		addnews($time, 'gameover' ,$gamenum);
		systemputchat($time,'gameover');
		$newsinfo = load_news(0,-1);
		$room_gprefix = '';
		if ($room_prefix!='') $room_gprefix = (substr($room_prefix,0,1)).'.';
		writeover(GAME_ROOT."./gamedata/bak/{$room_gprefix}{$gamenum}_newsinfo.html",$newsinfo,'wb+');
		
		return;
	}

	function routine()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(CURSCRIPT !== 'chat') 
		{
//			$plock=fopen(GAME_ROOT.'./gamedata/process.lock','ab');
//			flock($plock,LOCK_EX);
			process_lock();
			load_gameinfo();
			updategame();
			save_gameinfo();
			process_unlock();
//			fclose($plock); 
		}
	}
}

?>
