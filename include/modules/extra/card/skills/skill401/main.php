<?php

namespace skill401
{
	$paneldesc=array('硬化','硬化','硬化','硬化','硬化','钢化');
	$dmgreduce=array(0,10,20,30,50,90);
	
	function init() 
	{
		define('MOD_SKILL401_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[401] = '硬化';
	}
	
	function acquire401(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(401,'lvl','0',$pa);
	}
	
	function lost401(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(401,'lvl',$pa);
	}
	
	function check_unlocked401(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function check_skill401_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill401','player','logger'));
		if (!\skillbase\skill_query(401, $pd) || !check_unlocked401($pd)) return Array();
		$l401=\skillbase\skill_getvalue(401,'lvl',$pd);
		if ($l401>0){
			if ($active){
				if ($l401==5)
					$log.="<span class=\"yellow b\">{$pd['name']}的护甲使其几乎刀枪不入！</span><br>";
				else  
					$log.="<span class=\"yellow b\">{$pd['name']}坚硬的护甲减少了你造成的物理伤害！</span><br>";
			}else{
				if ($l401==5)
					$log.="<span class=\"yellow b\">你的护甲使你几乎刀枪不入！</span><br>";
				else  
					$log.="<span class=\"yellow b\">你坚硬的护甲减少了{$pa['name']}造成的物理伤害！</span><br>";
			}
			$dmggain = (100-$dmgreduce[$l401])/100;
			return Array($dmggain);
		}
		return Array();
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = check_skill401_proc($pa,$pd,$active);
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>
