<?php

namespace skill406
{
	
	function init() 
	{
		define('MOD_SKILL406_INFO','card;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[406] = '解离';
	}
	
	function acquire406(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost406(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked406(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_skill406_r(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = 2;
		while($ret < 1024){
			if($pa['hp'] >= $pa['mhp'] / $ret) break;
			$ret *= 2;
		}
		return $ret;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(406,$pa))&&(check_unlocked406($pa)))
		{
			$skill406_r = get_skill406_r($pa);
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"yellow b\">「解离」使你造成的最终伤害变为{$skill406_r}倍！</span><br>";
			else  $log.="<span class=\"yellow b\">「解离」使敌人造成的最终伤害变为{$skill406_r}倍！</span><br>";
			$r=Array($skill406_r);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
