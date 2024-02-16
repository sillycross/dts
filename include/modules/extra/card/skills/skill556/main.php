<?php

namespace skill556
{
	$ragecost = 30;
	$skill556stateinfo = array(1 => '关闭', 2 => '开启');
	
	function init() 
	{
		define('MOD_SKILL556_INFO','card;battle;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[556] = '追猎';
	}
	
	function acquire556(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(556, 'choice', 2, $pa);
		\skillbase\skill_setvalue(556, 'targetpid', 0, $pa);
	}
	
	function lost556(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(556, 'choice', $pa);
		\skillbase\skill_delvalue(556, 'targetpid', $pa);
	}
	
	function check_unlocked556(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl'] >= 10;
	}	

	function get_rage_cost556(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill556'));
		return $ragecost;
	}

	function check_battle_skill_unactivatable(&$ldata, &$edata, $skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata, $edata, $skillno);
		if (556 == $skillno && 0 == $ret)
		{
			if ($edata['type'] > 0) $ret = 8;
		}
		return $ret;
	}

	function upgrade556()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(556) || !check_unlocked556($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val < 1 || $val > 2)
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(556,'choice',$val);
		if(1 == $val) $log .= '现在不会显示标记目标位置。<br>';
		else $log .= '现在会显示标记目标位置。<br>';
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 556) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(556,$pa) || !check_unlocked556($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill'] = 0;
		}
		else if (0 == $pd['type'])
		{
			$rcost = get_rage_cost556($pa);
			if ($pa['rage'] >= $rcost)
			{
				eval(import_module('logger'));
				if ($active)
					$log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「追猎」！</span><br>";
				else  $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「追猎」！</span><br>";
				$pa['rage'] -= $rcost;
				\skillbase\skill_setvalue(556, 'targetpid', $pd['pid'], $pa);
			}
		}
		else
		{
			if ($active)
			{
				eval(import_module('logger'));
				$log .= '怒气不足或其他原因不能发动。<br>';
			}
			$pa['bskill'] = 0;
		}
		$chprocess($pa, $pd, $active);
	}
	
	function discover($schmode){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(556) && check_unlocked556($sdata) && 2 == \skillbase\skill_getvalue(556,'choice'))
		{
			$tarpid = (int)\skillbase\skill_getvalue(556, 'targetpid', $pa);
			if (0 != $tarpid)
			{
				$edata = \player\fetch_playerdata_by_pid($tarpid);
				eval(import_module('logger'));
				if ($edata['player_dead_flag']) $log .= "<span class=\"red b\">你感应到目标已经失去了气息。没有人能逃离地狱！</span><br>";
				else
				{
					eval(import_module('map'));
					$log .= "<span class=\"red b\">你感应到目标正位于{$plsinfo[$edata['pls']]}！</span><br>";
				}
			}
		}
		return $chprocess($schmode);
	}
	

}

?>