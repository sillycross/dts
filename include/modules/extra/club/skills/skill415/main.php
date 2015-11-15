<?php

namespace skill415
{
	function init() 
	{
		define('MOD_SKILL415_INFO','club;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[415] = '影像';
	}
	
	function acquire415(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(415,'lvl','0',$pa);
	}
	
	function lost415(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(415,'lvl',$pa);
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked415(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function apply_damage(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(415,$pd) || !check_unlocked415($pd)) return $chprocess($pa,$pd,$active);
		eval(import_module('logger'));
		$var_415=\skillbase\skill_getvalue(415,'lvl',$pd);
		if (($var_415==0)&&(($pa['dmg_dealt']%2)==0)){
			while (($pa['dmg_dealt']%2)==0) $pa['dmg_dealt']=floor($pa['dmg_dealt']/2);	
			if ($active) $log .= "<span class=\"yellow\">你的攻击被敌人吸收了！</span><br>";
			else $log .= "<span class=\"yellow\">敌人的攻击被你吸收了！</span><br>";
		}
		if (($var_415==1)&&(($pa['dmg_dealt']%1000)!=666)){
			$pa['dmg_dealt']=0;
			if ($active) $log .= "<span class=\"yellow\">你的攻击被敌人完全吸收了！</span><br>";
			else $log .= "<span class=\"yellow\">敌人的攻击被你完全吸收了！</span><br>";
		}
		$chprocess($pa,$pd,$active);
	}
}

?>
