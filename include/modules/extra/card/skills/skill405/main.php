<?php

namespace skill405
{
	
	function init() 
	{
		define('MOD_SKILL405_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[405] = '疾走';
	}
	
	function acquire405(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost405(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked405(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(405,$pa))&&(check_unlocked405($pa))&&(($pa['wep_kind']=='K')||($pa['wep_kind']=='D')))
		{
			eval(import_module('logger'));
			if ($active)
				$log.="<span class=\"yellow b\">「疾走」使你造成的最终伤害提高了20%！</span><br>";
			else  $log.="<span class=\"yellow b\">「疾走」使敌人造成的最终伤害提高了20%！</span><br>";
			$r=Array(1.2);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	//隐蔽率+15%
	function calculate_hide_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(405,$edata) || !check_unlocked405($edata)) return $chprocess($edata);
		return $chprocess($edata)+15;
	}
	
	//先攻率+10%
	function calculate_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess($ldata,$edata);
		if (\skillbase\skill_query(405,$ldata) && check_unlocked405($ldata)) $r += 10;
		return $r;
	}
}

?>