<?php

namespace skill400
{
	$paneldesc=array('重击','重击','重击','重击','重击','烈击','烈击');
	$attgain=array(0,20,30,50,100,75,100);
	$procrate=array(0,20,25,30,35,60,75);

	function init() 
	{
		define('MOD_SKILL400_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[400] = '重击';
	}
	
	function acquire400(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(400,'lvl','0',$pa);
	}
	
	function lost400(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(400,'lvl',$pa);
	}
	
	function check_unlocked400(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_skill400_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill400','player','logger'));
		if (!\skillbase\skill_query(400, $pa) || !check_unlocked400($pa)) return Array();
		$l400=\skillbase\skill_getvalue(400,'lvl',$pa);
		if (rand(0,99)<$procrate[$l400])
		{
			//调用NPC必杀技宣言
			if ($pa['type'] && defined('MOD_NPCCHAT')) \npcchat\npcchat($pa, $pd, $active, 'critical');
			
			if ($active){
				if ($l400>=5)
					$log.="<span class=\"yellow b\">你朝{$pd['name']}打出了猛烈的一击！</span><br>";
				else
					$log.="<span class=\"yellow b\">你朝{$pd['name']}打出了重击！</span><br>";
			}else{
				if ($l400>=5)
					$log.="<span class=\"yellow b\">{$pa['name']}朝你打出了猛烈的一击！</span><br>";
				else
					$log.="<span class=\"yellow b\">{$pa['name']}朝你打出了重击！</span><br>";
			}
			
			$dmggain = (100+$attgain[$l400])/100;
			return Array($dmggain);
		}
		return Array();
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = check_skill400_proc($pa,$pd,$active);
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>
