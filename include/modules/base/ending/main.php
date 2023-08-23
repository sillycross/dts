<?php

//结局剧情模块
//这个模块是可选继承set_gametype模块的，因此交替切换下一局类型的功能是在set_gametype模块
namespace ending
{
	function init() {}
	
	//是否允许用分镜方式显示剧情，如果关闭则显示整版网页（注意整版网页的剧情是旧版的）
	function ending_by_shootings_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ending'));
		if($ending_by_shootings) return true;
		return false;
	}
	
	//是否允许在结束时设置下一局游戏模式
	function ending_changing_gamevars_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		return !$groomid && in_array($winmode, array(2,3,5,7)) && ($state == 5 || $state == 6);
	}
	
	function get_gametype_setting_html()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return MOD_ENDING_NEXT_GAMETYPE;
	}
	
	//结尾时生成一些判定用的临时变量
	function init_playerdata()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($gamestate <= 0 && ending_by_shootings_available()) 
		{
			//获胜队伍情况
			if($winner) {
				$result = $db->query("SELECT teamID FROM {$tablepre}players WHERE name='$winner'");
				if($db->num_rows($result)) {
					$uip['winnerteam'] = $winnerteam = $db->fetch_array($result)['teamID'];
					$result2 = $db->query("SELECT name, hp FROM {$tablepre}players WHERE teamID='$winnerteam' AND name!='$winner'");
					$uip['winnerteam_num'] = $uip['winnerteam_alive'] = 1;
					while($rd2 = $db->fetch_array($result2)) {
						$uip['winnerteam_num'] ++;
						if($rd2['hp'] > 0) $uip['winnerteam_alive'] ++;
					}
				}
			}
			//显示胜利者姓名
			if(false!==strpos($winner, ',')) {
				if(\sys\is_winner($name, $winner)) $uip['winner_show'] = $name;
				else $uip['winner_show'] = explode($winner, ',')[0];
				$uip['winner_pronoun'] = '你们';
			}else {
				$uip['winner_show'] = $winner;
				$uip['winner_pronoun'] = '你';
			}
			//攻击过和杀死过的重要NPC
			$uip['attacked_vip'] = explode(',',\skillbase\skill_getvalue(1003,'attacked_vip'));
			$uip['killed_vip'] = explode(',',\skillbase\skill_getvalue(1003,'killed_vip'));
			//BOSS状态
			$boss_type = $gametype == 19 ? 15 : 1;
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE type='$boss_type'");
			$uip['boss_data'] = $db->fetch_array($result);
		}
		$chprocess();
	}
}

?>