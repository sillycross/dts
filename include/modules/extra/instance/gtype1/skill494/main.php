<?php

namespace skill494
{
	$dmglimit = Array(33,50,80,99);
	$upgradecost = Array(6,10,15,-1);
	
	function init() 
	{
		define('MOD_SKILL494_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[494] = '摸鱼';
	}
	
	function acquire494(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(494,'lvl','0',$pa);
	}
	
	function lost494(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked494(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade494()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill494','player','logger'));
		if (!\skillbase\skill_query(494))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(494,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(494,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill494_dmg($pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill494','player'));
		if (!\skillbase\skill_query(494,$pa)) return 100;
		$rate = $dmglimit[(int)\skillbase\skill_getvalue(494,'lvl',$pa)];
		return $rate;
	}

	function apply_total_damage_modifier_limit(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (!\skillbase\skill_query(494,$pa) || !check_unlocked494($pa)) return;
		$var_494=get_skill494_dmg($pa);
		if ( $var_494>0 && $var_494<100 ){
			$d494=round($pd['mhp']*$var_494/100);
			if ($pa['dmg_dealt']>$d494){
				$pa['dmg_dealt']=$d494;
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"yellow b\">你造成的伤害被限制为<span class=\"red b\">{$d494}</span>点。</span><br>";
				else $log .= "<span class=\"yellow b\">敌人造成的伤害被限制为<span class=\"red b\">{$d494}</span>点。</span><br>";
			}
		}
	}
}

?>