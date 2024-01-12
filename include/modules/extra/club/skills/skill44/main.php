<?php

namespace skill44
{
	global $upgradecost,$dmgreduction;
	$upgradecost=Array(4,5,-1);
	$dmgreduction=Array(0.01,0.02,0.03);
	
	function init() 
	{
		define('MOD_SKILL44_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[44] = '金刚';
	}
	
	function acquire44(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(44,'lvl','0',$pa);
		\skillbase\skill_setvalue(44,'choice','0',$pa);
	}
	
	function lost44(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(44,'lvl',$pa);
		\skillbase\skill_delvalue(44,'choice',$pa);
	}
	
	function check_unlocked44(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=11;
	}
	
	function upgrade44()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill44','player','logger'));
		if (!\skillbase\skill_query(44) || !check_unlocked44($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val!=1 && $val!=2)
		{
			$log.='参数不合法。<br>';
			return;
		}
		if ($val==1)
		{
			$clv = \skillbase\skill_getvalue(44,'lvl');
			$clv = (int)$clv;
			if ($upgradecost[$clv]==-1)
			{
				$log.='你已经升到满级了。<br>';
				return;
			}
			if ($skillpoint<$upgradecost[$clv])
			{
				$log.='技能点不足。<br>';
				return;
			}
			$skillpoint -= $upgradecost[$clv];
			$clv++; \skillbase\skill_setvalue(44,'lvl',(string)$clv);
			$log.='升级成功。<br>';
		}
		else
		{
			$choice = \skillbase\skill_getvalue(44,'choice');
			$choice = (int)$choice; 
			$choice = 1-$choice;
			\skillbase\skill_setvalue(44,'choice',(string)$choice);
			$log.='切换成功。<br>';
		}
	}
	
	function get_skill44_effect(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(44,$pd) || !check_unlocked44($pd)) return 1;
		$choice = \skillbase\skill_getvalue(44,'choice',$pd);
		$choice = (int)$choice; 
		if ($choice==0) 
		{
			eval(import_module('skill44','logger'));
			$clv = \skillbase\skill_getvalue(44,'lvl',$pd);
			$r=min(50,$dmgreduction[$clv]*\weapon\get_internal_def($pa,$pd,$active));
			if(empty($pa['skill44_log_flag'])){
				if ($active)
					$log.='<span class="yellow b">敌人健硕的身躯使你的固定伤害降低了'.round($r).'%！</span><br>';
				else  $log.='<span class="yellow b">你健硕的身躯使敌人的固定伤害降低了'.round($r).'%！</span><br>';
				$pa['skill44_log_flag'] = 1;
			}
			$r=1-$r/100;
		}
		else  $r=1;
		return $r;
	}
	
	function get_primary_fixed_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		return array_merge(array(get_skill44_effect($pa, $pd, $active)), $ret);
	}
	
	function get_fixed_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		return array_merge(array(get_skill44_effect($pa, $pd, $active)), $ret);
//		if (!\skillbase\skill_query(44,$pd) || !check_unlocked44($pd)) return $ret;
//		$choice = \skillbase\skill_getvalue(44,'choice',$pd);
//		$choice = (int)$choice; 
//		if ($choice==0) 
//		{
//			eval(import_module('skill44','logger'));
//			$clv = \skillbase\skill_getvalue(44,'lvl',$pd);
//			$r=min(50,$dmgreduction[$clv]*$pd['internal_def']); 
//			if ($active)
//				$log.='<span class="yellow b">敌人健硕的身躯使你的固定伤害降低了'.round($r).'%！</span><br>';
//			else  $log.='<span class="yellow b">你健硕的身躯使敌人的固定伤害降低了'.round($r).'%！</span><br>';
//			$r=1-$r/100;
//		}
//		else  $r=1;
//		return array_merge(array($r), $ret);
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(44,$pd) || !check_unlocked44($pd)) return $chprocess($pa, $pd, $active, $key);
		$choice = \skillbase\skill_getvalue(44,'choice',$pd);
		$choice = (int)$choice; 
		if ($choice==1 && $key=='d') 
		{
			eval(import_module('skill44','logger'));
			$clv = \skillbase\skill_getvalue(44,'lvl',$pd);
			$r=min(50,$dmgreduction[$clv]*\weapon\get_internal_def($pa,$pd,$active));
			if ($active)
				$log.='<span class="yellow b">敌人健硕的身躯抵挡了'.round($r).'%的爆炸伤害！</span><br>';
			else  $log.='<span class="yellow b">你健硕的身躯抵挡了'.round($r).'%的爆炸伤害！</span><br>';
			$r=1-$r/100;
		}
		else  $r=1;
		return $chprocess($pa, $pd, $active, $key)*$r;
	}
}

?>
