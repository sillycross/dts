<?php

namespace skill262
{
	function init() 
	{
		define('MOD_SKILL262_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[262] = '蓄力';
	}
	
	function acquire262(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		\skillbase\skill_setvalue(262,'ct',0,$pa);
	}
	
	function lost262(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked262(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=8;
	}
	
	function findenemy(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(262,$sdata) && check_unlocked262($sdata))
		{
			$ct = floor(getmicrotime()*1000);
			//eval(import_module('logger'));
			\skillbase\skill_setvalue(262,'ct',$ct,$sdata);
			//$log .= '$ct='.$ct.'<br>';
		}
		return $chprocess($edata);
	}
	
	function skill262_get_pretime(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['club']==19) return 1000; else return 2000;
	}
	
	function skill262_get_dmgperc(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['club']==19) return 17; else return 5;
	}
	
	function skill262_get_maxdmgperc(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['club']==19) return 100; else return 50;
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(262,$pa) && check_unlocked262($pa) && $pa['user_commanded']==1 && $active && !$pa['is_counter'] && (!isset($pa['sk262flag']) || !$pa['sk262flag'])) 
		{
			eval(import_module('logger'));
			$ct = floor(getmicrotime()*1000);
			$st = floor(\skillbase\skill_getvalue(262,'ct',$pa)); 
			$t = $ct - $st;
			//$log.='ct='.$ct.' '.'st='.$st.'<br>';
			$pretime = skill262_get_pretime($pa);
			$dmgperc = skill262_get_dmgperc($pa);
			$maxdmgperc = skill262_get_maxdmgperc($pa);
			
			//$log.=' pretime='.$pretime.' dmgperc='.$dmgperc.' maxdmgperc='.$maxdmgperc;
			if ($t<$pretime) 
				$z=0;
			else
			{
				$z=($t-$pretime)/1000*$dmgperc;
				$z=min($z,$maxdmgperc);
				$z=round($z);
				$log.='<span class="lime b">你积蓄力量打出了强力的一击！伤害增加了'.$z.'%！</span><br>';
			}
			$r=Array(1+$z/100);
		}
		return array_merge($r,$chprocess($pa, $pd, $active));
	}
}

?>
