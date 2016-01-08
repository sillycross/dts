<?php

namespace skill428
{
	$dmggain = Array(0,20,40,65);
	$extralvllost = Array(0,1,2,2);
	$skptbonus = Array(2,2,3,4);
	$upgradecost = Array(6,7,8,-1);
	
	function init() 
	{
		define('MOD_SKILL428_INFO','club;upgrade;');
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
		if (!\skillbase\skill_query(428,$pa)) return 1;
		$rate = $dmggain[\skillbase\skill_getvalue(428,'lvl',$pa)];
		return (1+$rate/100);
	}
	
	function get_trap_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill428'));
		if (is_numeric($itmsk0)){
			$wdata=\player\fetch_playerdata_by_pid($itmsk0);
			if ((\skillbase\skill_query(428,$wdata))){
				$var_428=get_skill428_dmg_gain($wdata);
				return round($var_428*$chprocess());
			}
		}
		
		return $chprocess();
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
		$chprocess($pa,$pd);	
	}
}

?>
