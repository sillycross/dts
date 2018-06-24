<?php

namespace ending
{
	function init() {}
	
	function ending_by_shootings_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ending'));
		if($ending_by_shootings && in_array($gametype, $ending_by_shootings_gametype)) return true;
		return false;
	}
	
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