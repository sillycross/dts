<?php

namespace skill229
{
	$alternate_skillno229 = 226;//互斥技能编号
	$unlock_lvl229 = 11;//解锁等级
	
	
	function init() 
	{
		define('MOD_SKILL229_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[229] = '神功';
	}
	
	function acquire229(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(229,'unlocked','0',$pa);	//是否已经被解锁。这个效果只有在$alternate_skillno229编号的技能存在时才有效
	}
	
	function lost229(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(229,'unlocked',$pa);
	}
	
	function check_unlocked229(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\clubbase\skill_check_unlocked_state(229,$pa) > 0) return 0;
		else return 1;
	}
	
	//0 解锁 1 等级不够 2 存在互斥技能且尚未选择 4 互斥技能解锁
	function check_unlocked_state229(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill229'));
		$ret = 0;
		if($pa['lvl'] < $unlock_lvl229) $ret += 1;
		if(\skillbase\skill_query($alternate_skillno229, $pa)){
			if(\skillbase\skill_getvalue(229,'unlocked',$pa)==0 ) $ret += 2;
			if(\skillbase\skill_getvalue($alternate_skillno229,'unlocked',$pa)>0) $ret += 4;
		}
		return $ret;
	}
	
	function upgrade229()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill229','player','logger','clubbase'));
		if (!\skillbase\skill_query(229))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (check_unlocked229($sdata))
		{
			$log .= '你已经选择了这个技能<br>';
			return;
		}
		if(\clubbase\skill_check_unlocked_state(229) & 4)
		{
			$log .= '你已经选择了互斥的技能！<br>';
			return;
		}

		\skillbase\skill_setvalue(229,'unlocked',1);
		
		$log.='技能「'.$clubskillname[229].'」选择成功。<br>';
	}
	
	function calculate_attack_weapon_skill_gain_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if ( \skillbase\skill_query(229,$pa) && check_unlocked229($pa) ) $ret += 1;
		return $ret;
	}
}

?>