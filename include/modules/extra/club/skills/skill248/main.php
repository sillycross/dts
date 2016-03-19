<?php

namespace skill248
{
	function init() 
	{
		define('MOD_SKILL248_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[248] = '天助';
	}
	
	function acquire248(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(248,'lvl1','0',$pa);
		\skillbase\skill_setvalue(248,'lvl2','0',$pa);
		\skillbase\skill_setvalue(248,'lvl3','0',$pa);
		\skillbase\skill_setvalue(248,'spent','0',$pa);
	}
	
	function lost248(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked248(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//闪避率曲线（基础闪避）
	function calculate_skill248_dodge_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-4 每级2.5; 5-16 每级2; 17-20 每级1.5
		$z=min(20,$lv)*1.5;
		$z+=min(16,$lv)*0.5;
		$z+=min(4,$lv)*0.5;
		return $z;
	}
	
	//闪避率曲线（连击惩罚）
	function calculate_skill248_dodge_r_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-6 每级2; 7-14 每级1.5; 15-20 每级1
		$z=min(20,$lv)*1;
		$z+=min(14,$lv)*0.5;
		$z+=min(6,$lv)*0.5;
		return $z;
	}
	
	//反击率曲线
	function calculate_skill248_counter_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-5 每级3; 6-15 每级2.5; 16-20 每级2
		$z=min(20,$lv)*2;
		$z+=min(15,$lv)*0.5;
		$z+=min(5,$lv)*0.5;
		return $z;
	}
	
	//隐蔽率曲线
	function calculate_skill248_hide_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-4 每级2; 5-16 每级1.5; 17-20 每级1
		$z=min(20,$lv)*1;
		$z+=min(16,$lv)*0.5;
		$z+=min(4,$lv)*0.5;
		return $z;
	}
	
	//先攻率曲线
	function calculate_skill248_obbs_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-5 每级2; 6-20 每级1
		$z=min(20,$lv)*1;
		$z+=min(5,$lv)*1;
		return $z;
	}
	
	//命中率曲线（基础命中）
	function calculate_skill248_hitrate_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-5 每级2.5; 6-15 每级2; 16-20 每级1.5
		$z=min(20,$lv)*1.5;
		$z+=min(15,$lv)*0.5;
		$z+=min(5,$lv)*0.5;
		return $z;
	}
	
	//命中率曲线（连击惩罚）
	function calculate_skill248_hitrate_r_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-10 每级1; 11-20 每级0.5
		$z=min(20,$lv)*0.5;
		$z+=min(10,$lv)*0.5;
		return $z;
	}
	
	function upgrade248()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill248','player','logger','input'));
		if (!\skillbase\skill_query(248))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$which = (int)$skillpara1;
		if ($which<1 || $which>3)
		{
			$log.='参数错误。<br>';
			return;
		}
		$clv = (int)\skillbase\skill_getvalue(248,'lvl'.$which);
		if ($clv == 20)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<1) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint--; \skillbase\skill_setvalue(248,'lvl'.$which,$clv+1);
		$log.='升级成功。<br>';
	}
	
}

?>
