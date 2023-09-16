<?php

namespace skill470
{
	function init() 
	{
		define('MOD_SKILL470_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[470] = '狙击';
	}
	
	function acquire470(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost470(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked470(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (isset($pa['bskill']) && $pa['bskill']>0 && \skillbase\skill_query(470,$pa))	
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="yellow b">「狙击」使敌人受到的最终伤害增加了12%！</span><br>';
			else  $log.='<span class="yellow b">「狙击」使你受到的最终伤害增加了12%！</span><br>';
			$r=Array(1.12);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>
