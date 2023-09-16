<?php

namespace skill402
{
	$paneldesc=array('直死','直死','直死','直死','直死','抹杀');
	$procrate=array(0,1,3,10,30,100);
	
	function init() 
	{
		define('MOD_SKILL402_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[402] = '直死';
	}
	
	function acquire402(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(402,'lvl','0',$pa);
	}
	
	function lost402(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(402,'lvl',$pa);
	}
	
	function check_unlocked402(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_skill402_procrate(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill402','player','logger'));
		if (!\skillbase\skill_query(402, $pa) || !check_unlocked402($pa)) return 0;
		$r = $procrate[\skillbase\skill_getvalue(402,'lvl',$pa)];
		return $r;
	}

	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(402,$pa) && check_unlocked402($pa)){
			$var_402=get_skill402_procrate($pa,$pd,$active);
			if ( rand(0,99) < $var_402 && $pa['is_hit'] && ( $pd['mhp'] < 5000000 || $var_402 >= 10 )){
				$pa['dmg_dealt']=$pd['hp'];
				//if ($pa['dmg_dealt']<100000000) $pa['dmg_dealt']=100000000;
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"red b\">一股来自东方的神秘力量直接杀死了你的敌人！</span><br>";
				else $log .= "<span class=\"red b\">一股来自东方的神秘力量直接杀死了你！</span><br>";
				$pa['seckill'] = 1;
			}
		}
		$chprocess($pa,$pd,$active);
	}
}

?>