<?php

namespace skill218
{
	$infrgain = Array(0,10,20,30,40,50,60);
	$extdmg = Array(0,5,10,15,20,25,30);
	$upgradecost = Array(4,4,4,4,5,5,-1);
	
	function init() 
	{
		define('MOD_SKILL218_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[218] = '渗透';
	}
	
	function acquire218(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(218,'lvl','0',$pa);
	}
	
	function lost218(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked218(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade218()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill218','player','logger'));
		if (!\skillbase\skill_query(218))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(218,'lvl');
		$ucost = $upgradecost[$clv];
		if ($clv == -1)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<$ucost) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint-=$ucost; \skillbase\skill_setvalue(218,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill218_extra_inf_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill218','player','logger'));
		if (!\skillbase\skill_query(218, $pa) || !check_unlocked218($pa)) return 1;
		$infrgainrate = $infrgain[\skillbase\skill_getvalue(218,'lvl',$pa)];
		return 1+($infrgainrate)/100;
	}
	
	function calculate_ex_inf_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(218,$pa) || !check_unlocked218($pa)) return $chprocess($pa, $pd, $active,$key);
		$t=get_skill218_extra_inf_rate($pa, $pd, $active);
		return $t*$chprocess($pa, $pd, $active,$key);;
	}
	
	function get_skill218_extra_dmgrate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill218','player','logger'));
		if (!\skillbase\skill_query(218, $pa) || !check_unlocked218($pa)) return 0;
		$extd = $extdmg[\skillbase\skill_getvalue(218,'lvl',$pa)];
		return $extd; 
	}
		
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(218,$pa))&&(check_unlocked218($pa))&&(strlen($pd['original_inf'])>0))
		{
			$var_218=get_skill218_extra_dmgrate($pa,$pd,$active);
			eval(import_module('logger'));
			if ($var_218>0)
			{
				if ($active)
					$log.="<span class=\"yellow\">「渗透」使你造成的最终伤害提高了{$var_218}%！</span><br>";
				else  $log.="<span class=\"yellow\">「渗透」使敌人造成的最终伤害提高了{$var_218}%！</span><br>";
				$r=Array(1+$var_218/100);
			}	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>
