<?php

namespace skill429
{
	$dmglimit = Array(33,50,80,99);
	$trapdmgdown = Array(0,10,25,45);
	$upgradecost = Array(6,7,8,-1);
	
	function init() 
	{
		define('MOD_SKILL429_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[429] = '谨慎';
	}
	
	function acquire429(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(429,'lvl','0',$pa);
	}
	
	function lost429(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked429(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade429()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill429','player','logger'));
		if (!\skillbase\skill_query(429))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(429,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(429,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill429_dmg($pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill429','player'));
		if (!\skillbase\skill_query(429,$pa)) return 100;
		$rate = $dmglimit[\skillbase\skill_getvalue(429,'lvl',$pa)];
		return $rate;
	}
	
	function get_skill429_trapdmg_down($pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill429','player'));
		if (!\skillbase\skill_query(429,$pa)) return 0;
		$rate = $trapdmgdown[\skillbase\skill_getvalue(429,'lvl',$pa)];
		return $rate;
	}
	
	function get_trap_damage_multiplier(&$pa, &$pd, $trap, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(429,$pa)) 
		{
			eval(import_module('logger'));
			$var_429=get_skill429_trapdmg_down($pd);
			if($var_429 > 0) {
				$log .= '「谨慎」让陷阱伤害减少了'.$var_429.'%！';
				if($var_429 > 100) $var_429 = 100;
				$r=Array((100-$var_429)/100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$trap,$damage));
	}

	function apply_total_damage_modifier_down(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(429,$pa) || !check_unlocked429($pa)) return $chprocess($pa,$pd,$active);
		$var_429=get_skill429_dmg($pa);
		if (($var_429>0)&&($var_429<100)){
			$d429=round($pd['mhp']*$var_429/100);
			if ($pa['dmg_dealt']>$d429){
				$pa['dmg_dealt']=$d429;
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"yellow\">你造成的伤害被限制为<span class=\"red\">{$d429}</span>点。</span><br>";
				else $log .= "<span class=\"yellow\">敌人造成的伤害被限制为<span class=\"red\">{$d429}</span>点。</span><br>";
			}
		}
		$chprocess($pa,$pd,$active);
	}
}

?>