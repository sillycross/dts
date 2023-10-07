<?php

namespace skill226
{
	$alternate_skillno226 = 229;//互斥技能编号
	$unlock_lvl226 = 7;//解锁等级
	
	function init() 
	{
		define('MOD_SKILL226_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[226] = '神智';
	}
	
	function acquire226(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(226,'unlocked','0',$pa);	//是否已经被解锁。这个效果只有在$alternate_skillno226编号的技能存在时才有效
	}
	
	function lost226(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(226,'unlocked',$pa);
	}
	
	function check_unlocked226(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\clubbase\skill_check_unlocked_state(226,$pa) > 0) return 0;
		else return 1;
	}
	
	//0 解锁 1 等级不够 2 存在互斥技能且尚未选择 4 互斥技能解锁
	function check_unlocked_state226(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill226'));
		$ret = 0;
		if($pa['lvl'] < $unlock_lvl226) $ret += 1;
		if(\skillbase\skill_query($alternate_skillno226, $pa)){
			if(\skillbase\skill_getvalue(226,'unlocked',$pa)==0 ) $ret += 2;
			if(\skillbase\skill_getvalue($alternate_skillno226,'unlocked',$pa)>0) $ret += 4;
		}
		return $ret;
	}
	
	function upgrade226()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill226','player','logger','clubbase'));
		if (!\skillbase\skill_query(226))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (check_unlocked226($sdata))
		{
			$log .= '你已经选择了这个技能<br>';
			return;
		}
		if(\clubbase\skill_check_unlocked_state(226) & 4)
		{
			$log .= '你已经选择了互斥的技能！<br>';
			return;
		}

		\skillbase\skill_setvalue(226,'unlocked',1);
		
		$log.='技能「'.$clubskillname[226].'」选择成功。<br>';
	}
	
	function calculate_attack_exp_gain_base(&$pa, &$pd, $active, $fixed_val=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active,$fixed_val);
		if ( \skillbase\skill_query(226,$pa) && check_unlocked226($pa) ) 
			$ret += 1;

		return $ret;
	}
	
//	function apply_attack_exp_gain(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		
//		$chprocess($pa,$pd,$active);
//		//不命中没有经验
//		eval(import_module('lvlctl'));
//		if ( $pa['physical_dmg_dealt']>0 && \skillbase\skill_query(226,$pa) && check_unlocked226($pa) ) 
//			\lvlctl\getexp(1,$pa);
//	}
}

?>
