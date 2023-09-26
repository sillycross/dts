<?php

namespace ex_seckill
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['v'] = '直死';
		$itemspkdesc['v']='攻击命中时，有较小可能性直接杀死对方';
		$itemspkremark['v']='10%概率生效';
		$itemspkinfo['V'] = '弑神';
		$itemspkdesc['V']='攻击命中时，有一定可能性直接杀死对方';
		$itemspkremark['V']='30%概率生效';
	}
	
	function get_ex_seckill_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = 0;
		if(\attrbase\check_in_itmsk('V', \attrbase\get_ex_attack_array($pa, $pd, $active))) $ret = 30;
		elseif(\attrbase\check_in_itmsk('v', \attrbase\get_ex_attack_array($pa, $pd, $active))) $ret = 10;		
		return $ret;
	}
	
	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['is_hit'] && rand(0,99) < get_ex_seckill_proc_rate($pa, $pd, $active)){
			$pa['dmg_dealt']=$pd['hp'];
			eval(import_module('logger'));
			if(\attrbase\check_in_itmsk('V', \attrbase\get_ex_attack_array($pa, $pd, $active))) {
				if ($active) $log .= "<span class=\"red b\">一股比希望更炽热、比绝望更深邃的魔力将敌人的生命改写成了虚无！</span><br>";
				else $log .= "<span class=\"red b\">一股比希望更炽热、比绝望更深邃的魔力将你的生命改写成了虚无！</span><br>";
			}
			elseif(\attrbase\check_in_itmsk('v', \attrbase\get_ex_attack_array($pa, $pd, $active))) {
				if ($active) $log .= "<span class=\"red b\">你的攻击直接击碎了敌人的死线！</span><br>";
				else $log .= "<span class=\"red b\">敌人的攻击直接击碎了你的死线！</span><br>";
			}
			
			$pa['seckill'] = 1;
		}
		$chprocess($pa,$pd,$active);
	}
}

?>