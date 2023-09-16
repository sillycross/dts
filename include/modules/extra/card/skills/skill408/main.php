<?php

namespace skill408
{
	$paneldesc=array(
		"咕咕咕",
		"战斗中殴系伤害<span class=\"yellow b\">+20%</span>，徒手伤害<span class=\"yellow b\">+40%</span>。<br>",
		"战斗中远程兵器伤害<span class=\"yellow b\">+20%</span>。<br>",
		"战斗中斩系伤害<span class=\"yellow b\">+20%</span>，投系伤害<span class=\"yellow b\">+15%</span>。<br>",
		"战斗中爆系伤害<span class=\"yellow b\">+15%</span>。<br>",
		"敌人的射程越远，造成的伤害越高。<br>",
	);
	function init() 
	{
		define('MOD_SKILL408_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[408] = '菁英';
	}
	
	function acquire408(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(408,'lvl','0',$pa);
	}
	
	function lost408(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked408(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ((\skillbase\skill_query(408,$pa))&&(check_unlocked408($pa)))
		{
			eval(import_module('logger','skill408','weapon'));
			$l408=\skillbase\skill_getvalue(408,'lvl',$pa);
			$var_408=0;
			if ($l408==1){
				if ($pa['wep_kind']=='P') $var_408=20;
				if ($pa['wep_kind']=='N') $var_408=40;
			}else if ($l408==2){
				if ($pa['wep_kind']=='G') $var_408=20;
			}else if ($l408==3){
				if ($pa['wep_kind']=='K') $var_408=20;
				if ($pa['wep_kind']=='C') $var_408=15;
			}else if ($l408==4){
				if ($pa['wep_kind']=='D') $var_408=15;
			}else if ($l408==5){
				$var_408=$rangeinfo[$pd['wepk'][1]]*3;
				if ($var_408==0) $var_408=24;
			}
			if ($var_408>0){
				if ($active)
					$log.="<span class=\"yellow b\">「菁英」使你造成的最终伤害提高了{$var_408}%！</span><br>";
				else  $log.="<span class=\"yellow b\">「菁英」使敌人造成的最终伤害提高了{$var_408}%！</span><br>";
				$r=Array(1+$var_408/100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
}

?>
