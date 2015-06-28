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
		$combonum = $deathlimit;
		save_gameinfo();
	}
	
	function gamestateupdate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		eval(import_module('sys','gameflow_combo'));
		if($gamestate < 40 && $gamestate >= 30 && $alivenum <= $combolimit) {//判定进入连斗条件1：停止激活时玩家数少于特定值
			$gamestate = 40;
			addnews($now,'combo');
			systemputchat($now,'combo');
		}elseif($gamestate < 40 && $gamestate >= 20 && $combonum && $deathnum >= $combonum){//判定进入连斗条件2：死亡人数超过特定公式计算出的值
			$real_combonum = $deathlimit + ceil($validnum/$deathdeno) * $deathnume;
			if($deathnum >= $real_combonum){
				$gamestate = 40;
				addnews($now,'combo');
				systemputchat($now,'combo');
			}else{
				$combonum = $real_combonum;
				addnews($now,'comboupdate',$combonum,$deathnum);
				systemputchat($now,'comboupdate',$combonum);
			}		
		}
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'combo') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">游戏进入连斗阶段！</span><br>\n";
		if($news == 'comboupdate') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">连斗判断死亡数修正为{$a}人，当前死亡数为{$b}人！</span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
	
	function check_corpse_discover($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($gamestate>=40) return 0;	//连斗后无尸体
		return $chprocess($edata);
	}

	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gamestate >= 40) 
		{
			if ($schmode == 'search') return $chprocess($schmode)+20;
			if ($schmode == 'move') return $chprocess($schmode)+10;
		}
		return $chprocess($schmode);
	}
	
	function senditem()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (defined('MOD_TEAM'))
		{
			if($gamestate>=40){
				$log .= '<span class="yellow">连斗阶段无法赠送物品！</span><br>';
				$action = '';
				$mode = 'command';
				return;
			}
			else  $chprocess();
		}
	}
	
	function findteam($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (defined('MOD_TEAM'))
		{
			if($gamestate>=40){
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
		if($gamestate >= 40) $r=3;	//连斗以后略容易踩陷阱
		return $chprocess()+$r;
	}
}		

?>
