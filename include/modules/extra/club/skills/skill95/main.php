<?php

namespace skill95
{
	function init()
	{
		define('MOD_SKILL95_INFO','club;debuff;');
		eval(import_module('clubbase'));
		$clubskillname[95] = '倾心';
	}
	
	function acquire95(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(95, 'spid', '0', $pa);
	}
	
	function lost95(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(95, 'spid', $pa);
	}
	
	function check_unlocked95(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//NPC生命值低于歌姬生命值3倍时对歌姬先制率为0
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(95, $edata) && $edata['type'] && (\skillbase\skill_getvalue(95, 'spid', $edata) == $ldata['pid']) && ($edata['hp'] < 3*$ldata['hp'])) return 1;
		else return $chprocess($ldata,$edata);
	}
	
	//NPC伤害变化
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(95, $pa) && $pa['type'] && (\skillbase\skill_getvalue(95, 'spid', $pa) != $pd['pid']))
		{
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"red b\">「倾心」使你造成的最终伤害增加了40%！</span><br>";
			else $log .= "<span class=\"red b\">「倾心」使{$pa['name']}造成的最终伤害增加了40%！</span><br>";
			$r = array(1.4);
		}
		elseif (\skillbase\skill_query(95, $pd) && $pd['type'] && (\skillbase\skill_getvalue(95, 'spid', $pd) != $pa['pid']))
		{
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"red b\">「倾心」使{$pd['name']}受到的最终伤害降低了20%！</span><br>";
			else $log .= "<span class=\"red b\">「倾心」使你受到的最终伤害降低了20%！</span><br>";
			$r = array(0.8);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	//玩家先攻率降低
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(95,$edata) && !$edata['type'] && (\skillbase\skill_getvalue(95, 'spid', $edata) == $ldata['pid'])) $r *= 1.3;
		if (\skillbase\skill_query(95,$ldata) && !$ldata['type'] && (\skillbase\skill_getvalue(95, 'spid', $ldata) == $edata['pid'])) $r /= 1.3;
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
	
}

?>