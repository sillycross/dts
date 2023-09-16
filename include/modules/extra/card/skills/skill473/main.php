<?php

namespace skill473
{
	function init() 
	{
		define('MOD_SKILL473_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[473] = '神蚀';
	}
	
	function acquire473(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost473(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked473(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(473,$pa) && $pa['is_hit']) 
		{
			$spcost=floor($pa['sp']*0.09);
			$pa['sp']-=$spcost;
			eval(import_module('logger'));
			if ($active)
				$log.='<class span="yellow b">你的技能「神蚀」消耗了'.$spcost.'点体力，并对敌人造成了相同的伤害！</span><br>';
			else  $log.='<class span="yellow b">敌人的技能「神蚀」消耗了'.$spcost.'点体力，并对你造成了相同的伤害！</span><br>';
			$ret+=$spcost;
			$pa['mult_words_fdmgbs'] = \attack\add_format($spcost, $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
//	function strike_finish(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (\skillbase\skill_query(473,$pa) && $pa['dmg_dealt']>0)
//		{
//			$spcost=floor($pa['sp']*0.09);
//			$pa['sp']-=$spcost;
//			$pa['dmg_dealt']+=$spcost;
//			eval(import_module('logger'));
//			if ($active)
//				$log.='<class span="yellow b">你的技能「神蚀」消耗了'.$spcost.'点体力，并对敌人造成了相同的伤害！</span><br>';
//			else  $log.='<class span="yellow b">敌人的技能「神蚀」消耗了'.$spcost.'点体力，并对你造成了相同的伤害！</span><br>';
//			$pa['dmg_dealt']+=$dmg;
//		}
//		$chprocess($pa, $pd, $active);
//	}
}

?>
