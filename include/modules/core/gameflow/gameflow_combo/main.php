<?php

namespace gameflow_combo
{
	function init() {}
	
	function reset_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		eval(import_module('sys','gameflow_combo'));
		//重设连斗判断死亡数
		$combonum = calculate_combonum(1);
		//save_gameinfo();//已改到外侧
	}
	
	function checkcombo($time = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','gameflow_combo'));
		if(!$time) $time = $now;
		if($gamestate < 40 && $gamestate >= 30 && $alivenum <= $combolimit) {//判定进入连斗条件1：停止激活时玩家数少于特定值
			$gamestate = 40;
			addnews($time,'combo');
			systemputchat($time,'combo');
		}elseif($gamestate < 40 && $gamestate >= 20 && $combonum && $deathnum >= $combonum){//判定进入连斗条件2：死亡人数超过特定公式计算出的值
			$real_combonum = calculate_combonum();
			if($deathnum >= $real_combonum){
				$gamestate = 40;
				addnews($time,'combo');
				systemputchat($time,'combo');
			}else{
				$combonum = $real_combonum;
				addnews($time,'comboupdate',$combonum,$deathnum);
				systemputchat($time,'comboupdate',$combonum);
			}		
		}
	}
	
	//每次增加禁区之后都判定是否连斗
	function post_addarea_process($atime, $areaaddlist)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($atime, $areaaddlist);
		checkcombo($atime);
	}	
	
	function calculate_combonum($reset = false){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','gameflow_combo'));
		$dlimit = $deathlimit_by_gtype[0];
		if(!empty($deathlimit_by_gtype[$gametype])) $dlimit = $deathlimit_by_gtype[$gametype];
		if(!$reset)	return $dlimit + ceil($validnum/$deathdeno) * $deathnume;
		else return $dlimit;
	}
	
	function gamestateupdate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		checkcombo();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'combo') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red\">游戏进入连斗阶段！</span></li>";
		elseif($news == 'comboupdate') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">连斗判断死亡数修正为{$a}人，当前死亡数为{$b}人！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	//连斗以后摸不到尸体
	function discover_player_filter_corpse(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($edata);
		eval(import_module('sys'));
		if ($gamestate >= 40) 
			$ret = false;	
		return $ret;
	}
	
//	function check_corpse_discover(&$edata)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys'));
//		if ($gamestate>=40) return 0;	//连斗后无尸体
//		return $chprocess($edata);
//	}

	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gamestate >= 40) //连斗以后遇敌率上升
		{
			if ($schmode == 'search') return $chprocess($schmode) + 20;//*1.2;
			if ($schmode == 'move') return $chprocess($schmode) + 10;//*1.1;
		}
		return $chprocess($schmode);
	}
	
	function senditem_check($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (defined('MOD_TEAM'))
		{
			if($gamestate>=40 && !in_array($gametype,$teamwin_mode)){
				$log .= '<span class="yellow">连斗阶段无法赠送物品！</span><br>';
				return false;
			}
		}
		return $chprocess($edata);
	}
	
	function findteam(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (defined('MOD_TEAM'))
		{
			if($gamestate>=40 && !in_array($gametype,$teamwin_mode)){
				$log .= '<span class="yellow">连斗阶段所有队伍取消！</span><br>';
				
				$mode = 'command';
				return;
			}
			else  $chprocess($edata);
		}
	}
	
	function teammake($tID,$tPass) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (defined('MOD_TEAM'))
			if($gamestate >= 40) {
				$log .= '连斗时不能组建队伍。<br>';
				$mode = 'command';
				return;
			}
			else  $chprocess($tID,$tPass);
	}
	
	function teamjoin($tID,$tPass)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (defined('MOD_TEAM'))
		{
			if($gamestate >= 40) {
				$log .= '连斗时不能加入队伍。<br>';
				$mode = 'command';
				return;
			}
			else  $chprocess($tID,$tPass);
		}
	}
	
	function teamquit() 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (defined('MOD_TEAM'))
		{
			if($gamestate>=40){
				$log .= '你不在队伍中。<br>';
				$mode = 'command';
			}
			else  $chprocess();
		}
	}
	
	function check_team_button_exist()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (defined('MOD_TEAM'))
		{
			if($gamestate>=40) return 0; else return $chprocess();
		}
	}
	
	function calculate_real_trap_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$r=0;
		if($gamestate >= 40) $r=2.5;	//连斗以后略容易踩陷阱
		return $chprocess()+$r;
	}
}		

?>
