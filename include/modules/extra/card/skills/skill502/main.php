<?php

namespace skill502
{
	function init() 
	{
		define('MOD_SKILL502_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[502] = '唯快';
	}
	
	function acquire502(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost502(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked502(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//敌方命中率的下降值
	function get_skill502_hitrate_effect(&$pdata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($vapm, $apm) = \apm\calc_apm($pdata);
		return $vapm/2;
	}
	
	//自己先制攻击率的上升值
	function get_skill502_active_effect(&$pdata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($vapm, $apm) = \apm\calc_apm($pdata);
		return $vapm/5;
	}
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(502,$pd) || !check_unlocked502($pd)) return $ret;
		return $ret*(1-get_skill502_hitrate_effect($pd)/100);
	}
	
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(502,$ldata) && check_unlocked502($ldata)) {
			$r += get_skill502_active_effect($ldata)/100;
		}
		if (\skillbase\skill_query(502,$edata) && check_unlocked502($edata)) {
			$r -= get_skill502_active_effect($edata)/100;
		}
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
}

?>