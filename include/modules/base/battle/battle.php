<?php

namespace battle
{
	//注意，这个函数是判定是否跳过反击阶段
	//由于很多技能描述中“必定反击”和“不能反击”这两个天生的矛盾
	//决定明晰化如下：
	//////////////////////////////////////
	//////“不能反击”优先级高于“必定反击”//////
	//////////////////////////////////////
	//因此接管这个函数时，如果你想返回0，应该不调用$chprocess，直接返回0
	//但如果你想返回1，应当返回$chprocess($pa,$pd,$active)
	//上述是没有LOG的情况，对于有LOG的情况更麻烦一点：
	//为了保证LOG不会自相矛盾，如果你想返回0，可以直接发LOG
	//但如果你想返回1，LOG不能在这个函数里发送，请自己开一个标记记下来（这个标记应该在battle_prepare里初始化）
	//然后接管counter_assault，如果存在标记则发对应log
	//这样才能保证不会出现你认为可以反击发了log然后其他模块认为不可以反击，导致log自相矛盾的情况
	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function attack_wrapper(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//这个函数在存活了敌人的反击后被调用
	function assault_end(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function counter_assault(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$pa['is_counter']=1;
		attack_wrapper($pa, $pd, $active);
		unset($pa['is_counter']);
		
		if ($pd['hp']>0)
		{
			assault_end($pd, $pa, 1-$active);
		}
	}
	
	function player_cannot_counter(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if ($active)
		{
			$log .= "<span class=\"red\">你处于无法反击的状态，逃跑了！</span><br>";
			$pd['battlelog'] .= "其无法反击，逃跑了。<br>";
		}
		else
		{
			$log .= "<span class=\"red\">敌人处于无法反击的状态，逃跑了！</span><br>";
			$pa['battlelog'] .= "你无法反击，逃跑了。<br>";
		}
	}
	
	function cannot_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		player_cannot_counter($pa,$pd,$active);
	}
	
	function assault_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//user_commanded代表是否是当前玩家主动发起的攻击（如果是主动则可能有其设置的攻击参数）
		if ($active) $pa['user_commanded']=1; else $pa['user_commanded']=0;
		$pd['user_commanded']=0;
	}
	
	function counter_assault_wrapper(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['hp']>0 && $pd['hp']>0)
		{
			$pa['is_counter']=1;
			if (check_can_counter($pa, $pd,$active)) 
			{
				$pa['counter_assaulted']=1;
				counter_assault($pa,$pd,$active);
			}
			else
			{
				$pa['counter_assaulted']=0;
				cannot_counter($pa,$pd,$active);
			}
			unset($pa['is_counter']);
		}
	}
	
	function assault(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		foreach(array('pa','pd') as $val){
			${$val}['is_counter'] = ${$val}['physical_dmg_dealt'] = ${$val}['wepimp'] = ${$val}['actual_rapid_time'] = 0;
			${$val}['battlelog'] = '';
		}
//		$pa['is_counter']=$pd['is_counter']=0;
//		$pa['physical_dmg_dealt']=$pd['physical_dmg_dealt']=0;
//		$pa['wepimp'] = $pd['wepimp'] = 0;
//		$pa['battlelog'] = $pd['battlelog'] = '';
		attack_wrapper($pa, $pd, $active);
		counter_assault_wrapper($pd, $pa, 1-$active);
	}
	
	function assault_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		foreach(array('pa','pd') as $val){
			unset(${$val}['is_counter'],${$val}['physical_dmg_dealt'],${$val}['wepimp'],${$val}['actual_rapid_time'],${$val}['battlelog']);
		}
	}
}

?>