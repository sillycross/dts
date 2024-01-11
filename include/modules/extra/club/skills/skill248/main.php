<?php

namespace skill248
{
	function init() 
	{
		define('MOD_SKILL248_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[248] = '天助';
	}
	
	function acquire248(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(248,'lvl1','0',$pa);
		\skillbase\skill_setvalue(248,'lvl2','0',$pa);
		\skillbase\skill_setvalue(248,'lvl3','0',$pa);
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
	
	//第一项升级 隐蔽与先攻
	
	//隐蔽率曲线
	function calculate_skill248_hide_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-10 每级1; 11-20 每级0.5
		$z=min(20,$lv)*0.5;
		$z+=min(10,$lv)*0.5;
		return $z;
	}
	
	function calculate_hide_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(248,$edata)) return $chprocess($edata);
		$lv=(int)\skillbase\skill_getvalue(248,'lvl1',$edata);
		return $chprocess($edata)+calculate_skill248_hide_gain($lv);
	}
	
	//先攻率曲线
	function calculate_skill248_obbs_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-10 每级1; 11-20 每级0.5
		$z=min(20,$lv)*0.5;
		$z+=min(10,$lv)*0.5;
		return $z;
	}
	
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(248,$ldata)) 
		{
			$lv=(int)\skillbase\skill_getvalue(248,'lvl1',$ldata);
			$r*=1+calculate_skill248_obbs_gain($lv)/100;
		}
		if (\skillbase\skill_query(248,$edata)) 
		{
			$lv=(int)\skillbase\skill_getvalue(248,'lvl1',$edata);
			$r/=1+calculate_skill248_obbs_gain($lv)/100;
		}
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
	
	//第二项升级 闪避与反击
	
	//闪避率曲线（基础闪避）
	function calculate_skill248_dodge_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-20 每级0.5
		$z=min(20,$lv)*0.5;
		return $z;
	}
	
	//闪避率曲线（连击惩罚）
	function calculate_skill248_dodge_r_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-5 每级2; 6-20 每级1
		$z=min(20,$lv)*1;
		$z+=min(5,$lv)*1;
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
	
	function calculate_counter_rate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(248,$pa)) return $ret;
		$lv = (int)\skillbase\skill_getvalue(248,'lvl2',$pa);
		$r=1+calculate_skill248_counter_gain($lv)/100;
		return $ret*$r;
	}
	
	//第三项升级 命中
	
	//命中率曲线（基础命中）
	function calculate_skill248_hitrate_gain($lv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//1-20 每级1
		$z=min(20,$lv)*1;
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
	
	function get_hitrate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$val=$chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(248,$pa))
		{
			$lv = (int)\skillbase\skill_getvalue(248,'lvl3',$pa);
			$r = calculate_skill248_hitrate_gain($lv);
			$val = $val*(1+$r/100);
		}
		if (\skillbase\skill_query(248,$pd))
		{
			$lv = (int)\skillbase\skill_getvalue(248,'lvl2',$pd);
			$r = calculate_skill248_dodge_gain($lv);
			$val = $val*(1-$r/100);
		}
		return $val;
	}
	
	function get_rapid_accuracy_loss(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$val=$chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(248,$pa))
		{
			$lv = (int)\skillbase\skill_getvalue(248,'lvl3',$pa);
			$r = calculate_skill248_hitrate_r_gain($lv);
			$val = $val*(1+$r/100);
		}
		if (\skillbase\skill_query(248,$pd))
		{
			$lv = (int)\skillbase\skill_getvalue(248,'lvl2',$pd);
			$r = calculate_skill248_dodge_r_gain($lv);
			$val = $val*(1-$r/100);
		}
		return $val;
	}
	
	function upgrade248()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill248','player','logger'));
		if (!\skillbase\skill_query(248))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$which = (int)get_var_input('skillpara1');
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
