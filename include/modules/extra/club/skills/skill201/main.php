<?php

namespace skill201
{
	//命中率获得比例
	$accgain = Array(0,2,4,6,8,10,12);
	//连击命中惩罚减少比例
	$rbgain = Array(0,2,4,6,8,10,12);
	//升级所需技能点数值
	$upgradecost = Array(2,2,3,3,4,5,-1);
	
	$upgradecount = Array(0,2,4,7,10,14,19);
	
	function init() 
	{
		define('MOD_SKILL201_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[201] = '静息';
	}
	
	function acquire201(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(201,'lvl','0',$pa);
		\skillbase\skill_setvalue(201,'spent','0',$pa);
	}
	
	function lost201(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked201(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade201()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill201','player','logger'));
		if (!\skillbase\skill_query(201))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(201,'lvl');
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
		$skillpoint-=$ucost; \skillbase\skill_setvalue(201,'lvl',$clv+1);
		\skillbase\skill_setvalue(201,'spent',$upgradecount[$clv+1],$pa);
		$log.='升级成功。<br>';
	}
	
	function get_skill201_extra_acc_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill201','player','logger'));
		if (!\skillbase\skill_query(201, $pa) || !check_unlocked201($pa)) return 1;
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wg') return 1;
		$accgainrate = $accgain[\skillbase\skill_getvalue(201,'lvl',$pa)];
		return 1+($accgainrate)/100;
	}
	
	function get_skill201_extra_rb_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill201','player','logger'));
		if (!\skillbase\skill_query(201, $pa) || !check_unlocked201($pa)) return 1;
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wg') return 1;
		$rbgainrate = $rbgain[\skillbase\skill_getvalue(201,'lvl',$pa)];
		return 1+($rbgainrate)/100;
	}
	
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(201,$pa) || !check_unlocked201($pa)) return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*get_skill201_extra_rb_gain($pa, $pd, $active);
	}
	
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(201,$pa) || !check_unlocked201($pa)) return $ret;
		return $ret*get_skill201_extra_acc_gain($pa, $pd, $active);
	}
}

?>
