<?php

namespace skill441
{
	
	function init() 
	{
		define('MOD_SKILL441_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[441] = '嗑药';
	}
	
	function acquire441(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost441(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked441(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_skill441_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill441','player','logger'));
		if (!\skillbase\skill_query(441, $pa) || !check_unlocked441($pa) || !isset($pa['inf'])) return Array();
		$l441=strlen($pa['inf'])*35;
		if ($l441>0)
		{
			if ($active){
				$log.="<span class=\"yellow b\">你拖着病体反而越战越勇，造成的物理伤害提高了{$l441}%！</span><br>";
			}else{
				$log.="<span class=\"yellow b\">{$pa['name']}拖着病体反而越战越勇，造成的物理伤害提高了{$l441}%！</span><br>";
			}
			$dmggain = (100+$l441)/100;
			return Array($dmggain);
		}
		return Array();
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = check_skill441_proc($pa,$pd,$active);
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
