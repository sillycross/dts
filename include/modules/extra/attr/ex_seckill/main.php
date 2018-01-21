<?php

namespace ex_seckill
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['V'] = '弑神';
		$itemspkdesc['V']='攻击命中时可能无视伤害值，直接杀死对方';
		$itemspkremark['V']='30%概率生效';
	}
	
	function get_ex_seckill_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 30;
	}
	
	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['is_hit'] && in_array('V', \attrbase\get_ex_attack_array($pa, $pd, $active)) && rand(0,99) < get_ex_seckill_proc_rate($pa, $pd, $active)){
			$pa['dmg_dealt']=$pd['hp'];
			eval(import_module('logger'));
			if ($active) $log .= "<span class=\"red\">一股比希望更炽热、比绝望更深邃的魔力将敌人的生命改写成了虚无！</span><br>";
			else $log .= "<span class=\"red\">一股比希望更炽热、比绝望更深邃的魔力将你的生命改写成了虚无！</span><br>";
			$pa['seckill'] = 1;
		}
		$chprocess($pa,$pd,$active);
	}
}

?>