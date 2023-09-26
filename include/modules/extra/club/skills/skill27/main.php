<?php

namespace skill27
{
	function init() 
	{
		define('MOD_SKILL27_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[27] = '流火';
	}
	
	function acquire27(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost27(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked27(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
		//return $pa['lvl']>=11;
	}
	
	function armor_break(&$pa, &$pd, $active, $whicharmor)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa, $pd, $active, $whicharmor);
		
		if (\skillbase\skill_query(27,$pa) && check_unlocked27($pa))
		{
			eval(import_module('logger','wound'));
			if (!\skillbase\skill_query(6,$pd))
			{
				if ($active)
					$log .= '流火技能'.$infname['u'].'了敌人！';
				else  $log .= '你被敌人的流火技能'.$infname['u'].'了！';
				\wound\get_inf('u',$pd);
			}
		}
	}
		
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(27,$pa) && check_unlocked27($pa) && $pa['is_hit'])
		{
			eval(import_module('skill27','armor','wound','logger'));
			if ($pa['bskill']==26)	//聚能发动时损伤所有防具
			{
				$target = $armor_equip_list;
				if (2==\skillbase\skill_getvalue(26,'lvl',$pa))
					$damage = 3;
				else  $damage = 2; 
			}
			else  
			{
				$target = Array($armor_equip_list[rand(0,count($armor_equip_list)-1)]);
				$damage = 0;
				if (\attrbase\check_in_itmsk('u',\attrbase\get_ex_attack_array($pa, $pd, $active))) $damage+=1;
				if (\attrbase\check_in_itmsk('f',\attrbase\get_ex_attack_array($pa, $pd, $active))) $damage+=2;
			}
			
			if ($damage > 0)
			{
				foreach ($target as $key)
				{
					if (in_array($key,$armor_equip_list) && isset($pd[$key.'e']) && $pd[$key.'e']>0)
					{
						//有防具
						$pa['attack_wounded_'.substr($key,2)]+=$damage;
					}
					else
					{
						if (!\skillbase\skill_query(6,$pd))
						{
							if ($active)
								$log .= '流火技能'.$infname['u'].'了敌人！';
							else  $log .= '你被敌人的流火技能'.$infname['u'].'了！';
							\wound\get_inf('u',$pd);
						}
					}
				}
			}
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
