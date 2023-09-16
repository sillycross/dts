<?php

namespace skill416
{
	$paneldesc=array('团结','独斗');
	
	function init() 
	{
		define('MOD_SKILL416_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[416] = '团结';
	}
	
	function acquire416(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(416,'lvl','0',$pa);
	}
	
	function lost416(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked416(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(416,$pa))&&(check_unlocked416($pa)))
		{
			eval(import_module('skill416','logger'));
			$l416=\skillbase\skill_getvalue(416,'lvl',$pa);
			if ($pd['type']==0){
				if ($l416==0){
					if ($active)
						$log.="<span class=\"yellow b\">「团结」使你造成的最终伤害降低了50%！</span><br>";
					else  $log.="<span class=\"yellow b\">「团结」使敌人造成的最终伤害降低了50%！</span><br>";
					$r=Array(0.5);
				}else if ($l416==1){
					if ($active)
						$log.="<span class=\"yellow b\">「独斗」使你造成的最终伤害提高了10%！</span><br>";
					else  $log.="<span class=\"yellow b\">「独斗」使敌人造成的最终伤害提高了10%！</span><br>";
					$r=Array(1.1);
				}
			}else{
				if ($l416==0){
					if ($active)
						$log.="<span class=\"yellow b\">「团结」使你造成的最终伤害提高了30%！</span><br>";
					else  $log.="<span class=\"yellow b\">「团结」使敌人造成的最终伤害提高了30%！</span><br>";
					$r=Array(1.3);
				}else if ($l416==1){
					if ($active)
						$log.="<span class=\"yellow b\">「独斗」使你造成的最终伤害降低了10%！</span><br>";
					else  $log.="<span class=\"yellow b\">「独斗」使敌人造成的最终伤害降低了10%！</span><br>";
					$r=Array(0.9);
				}
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
