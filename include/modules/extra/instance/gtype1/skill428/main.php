<?php

namespace skill428
{
	$dmggain = Array(0,15,30,60);
	$extralvllost = Array(0,0,1,2);
	$skptbonus = Array(0,1,2,3);
	$upgradecost = Array(6,9,12,-1);
	
	function init() 
	{
		define('MOD_SKILL428_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[428] = '阴谋';
	}
	
	function acquire428(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(428,'lvl','0',$pa);
	}
	
	function lost428(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked428(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade428()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill428','player','logger'));
		if (!\skillbase\skill_query(428))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(428,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(428,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill428_dmg_gain($pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill428','player'));
		if (!\skillbase\skill_query(428,$pa)) return 0;
		$rate = $dmggain[\skillbase\skill_getvalue(428,'lvl',$pa)];
		return $rate;
	}
	
	function get_trap_damage_multiplier(&$pa, &$pd, $trap, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(428,$pa)) 
		{
			eval(import_module('logger'));
			$a=get_skill428_dmg_gain($pa);
			if($a > 0) {
				if($pa['pid'] == $pd['pid']) $log .= '你自己的「阴谋」让陷阱伤害增加了'.$a.'%！';
				else $log .= '对方的技能「阴谋」让陷阱伤害增加了'.$a.'%！';
				$r=Array((100+$a)/100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$trap,$damage));
	}
	
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','skill428'));
		//陷阱杀人得奖励
		if (($gametype==1)&&($pd['state']==27)&&($pa['type']==0) && (\skillbase\skill_query(428,$pa))){
			$clv = \skillbase\skill_getvalue(428,'lvl',$pa);
			$pa['skillpoint']+=$skptbonus[$clv];	//获得技能点奖励
			if (\skillbase\skill_query(424,$pd)){	//敌方额外损失破解层数
				$lv=\skillbase\skill_getvalue(424,'lvl',$pd); 
				$lv-=$extralvllost[$clv]; 
				if ($lv<0) $lv=0;
				\skillbase\skill_setvalue(424,'lvl',$lv,$pd); 
			}
		}
		return $chprocess($pa,$pd);	
	}
}

?>
