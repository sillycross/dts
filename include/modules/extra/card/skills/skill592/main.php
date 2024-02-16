<?php

namespace skill592
{
	$sk592_debuff_time = array('A'=>30,'S'=>120);
	$sk592_debuff_time_blink_rate = array(10=>3,20=>6);

	function init() 
	{
		define('MOD_SKILL592_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[592] = '嫉妒';
	}
	
	function acquire592(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost592(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked592(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(592, $pa) && check_unlocked592($pa) && ($pd['type'] == 0))
		{
			$debuff_time = get_skill592_debuff_time($pd);
			if ($debuff_time > 0)
			{
				eval(import_module('sys','player','logger'));
				if ($active) $log.="<span class=\"lime b\">你向{$pd['name']}施加了怨恨，并唤起了对方的嫉妒心！</span><br>";
				else $log.="<span class=\"lime b\">{$pa['name']}向你施加了怨恨，并唤起了你的嫉妒心！</span><br>";
				\skillbase\skill_acquire(478, $pd);
				\skillbase\skill_setvalue(478, 'tsk_expire', $now + $debuff_time, $pd);
				\skillbase\skill_acquire(592, $pd);
				\skillbase\skill_setvalue(592, 'tsk_expire', $now + $debuff_time, $pd);
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	function get_skill592_debuff_time($pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill592','cardbase'));
		$debuff_time = 0;
		if (isset($pa['card']))
		{
			$cid = $pa['card'];
			$rare = $cards[$cid]['rare'];
			if (isset($sk592_debuff_time[$rare]))
			{
				$debuff_time = $sk592_debuff_time[$rare];
				$blink = (int)\skillbase\skill_getvalue(1003, 'nowcard_blink', $pa);
				if (isset($sk592_debuff_time_blink_rate[$blink])) $debuff_time *= $sk592_debuff_time_blink_rate[$blink];
			}
		}
		return $debuff_time;
	}

}

?>
