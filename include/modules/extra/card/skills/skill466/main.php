<?php

namespace skill466
{
	function init() 
	{
		define('MOD_SKILL466_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[466] = '死神';
	}
	
	function acquire466(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost466(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked466(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(466,$pa) && $pa['dmg_dealt']>0 && $pd['type']==0) 
		{
			$dmg=round($pd['mhp']*min($pd['lvl'],30)/100.0);
			eval(import_module('logger'));
			if ($active)
				$log.='<class span="yellow b">你的技能「死神」额外造成了'.$dmg.'点伤害！</span><br>';
			else  $log.='<class span="yellow b">敌人的技能「死神」额外造成了'.$dmg.'点伤害！</span><br>';
			$ret += $dmg;
			$pa['mult_words_fdmgbs'] = \attack\add_format($dmg, $pa['mult_words_fdmgbs']);
		}
		return $ret;
	}
	
//	function strike_finish(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (\skillbase\skill_query(466,$pa) && $pa['dmg_dealt']>0 && $pd['type']==0)
//		{
//			$dmg=round($pd['mhp']*min($pd['lvl'],30)/100.0);
//			eval(import_module('logger'));
//			if ($active)
//				$log.='<class span="yellow b">你的技能「死神」额外造成了'.$dmg.'点伤害！</span><br>';
//			else  $log.='<class span="yellow b">敌人的技能「死神」额外造成了'.$dmg.'点伤害！</span><br>';
//			$pa['dmg_dealt']+=$dmg;
//		}
//		$chprocess($pa, $pd, $active);
//	}
}

?>
