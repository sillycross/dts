<?php

namespace skill462
{
	function init() 
	{
		define('MOD_SKILL462_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[462] = '抗性';
	}
	
	function acquire462(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost462(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked462(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (isset($pa['bskill']) && $pa['bskill']>0 && \skillbase\skill_query(462,$pd))	
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="yellow">「抗性」使敌人受到的最终伤害降低了35%！</span><br>';
			else  $log.='<span class="yellow">「抗性」使你受到的最终伤害降低了35%！</span><br>';
			$r=Array(0.65);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
