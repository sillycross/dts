<?php

namespace skill41
{
	function init() 
	{
		define('MOD_SKILL41_INFO','club;locked;');
		eval(import_module('clubbase'));
		$clubskillname[41] = '神速';
	}
	
	function acquire41(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost41(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(41,'u',$pa);
	}
	
	function unlock41(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(41,'u','1',$pa);
	}
	
	function lock41(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(41,'u','0',$pa);
	}
	
	function check_unlocked41(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skill41_u = \skillbase\skill_getvalue(41,'u',$pa);
		if ($skill41_u === '1') return 1;
		elseif ($skill41_u === '0') return 0;
		if (\skillbase\skill_query(43,$pa)) return 0;
		return 1;
	}
	
	//战斗中基础攻击力增加
	function get_internal_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(41,$pa) || !check_unlocked41($pa)) return $chprocess($pa,$pd,$active);
		return $chprocess($pa,$pd,$active)*1.1;
	}
	
	//每次攻击50%几率增加一点基础攻击
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(41,$pa) && check_unlocked41($pa))
		{
			if (rand(0,99)<50) $pa['att']++;
		}
		$chprocess($pa, $pd, $active);
	}
	
	function battle_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(41,$pd) && check_unlocked41($pd))
		{
			$pd['skill41_proced']=0;
		}
		$chprocess($pa, $pd, $active);
	}
	
	//进行一次判定，60%几率触发反击
//	function check_counter_dice(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if (!\skillbase\skill_query(41,$pa) || !check_unlocked41($pa)) return $chprocess($pa,$pd,$active);
//		if (rand(0,99)<65) 
//		{
//			$pa['skill41_proced']=1; return 1;
//		}
//		return $chprocess($pa, $pd, $active);
//	}
	
	//改为继承反击率变化函数
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function calculate_counter_rate_change(&$pa, &$pd, $active, $counter_rate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active,$counter_rate);
		if (!\skillbase\skill_query(41,$pa) || !check_unlocked41($pa)) return $ret;
		if (rand(0,99)<65) 
		{
			$pa['skill41_proced']=1;
			return 100;
		}
		return $ret;
	}
	
	function counter_assault(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(41,$pa) || !check_unlocked41($pa)) return $chprocess($pa,$pd,$active);
		eval(import_module('logger'));
		if ($pa['skill41_proced'])
		{
			if ($active)
				$log.='<span class="yellow b">你以惊人的速度完成了反击准备，并立即对敌人进行了反击！</span><br>';
			else  $log.='<span class="yellow b">'.$pa['name'].'以惊人的速度完成了反击准备，并立即对你进行了反击！</span><br>';
		}
		$pa['skill41_proced']=0;
		$chprocess($pa, $pd, $active);
	}
	
}

?>
