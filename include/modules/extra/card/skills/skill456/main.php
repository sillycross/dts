<?php

namespace skill456
{
	$skill456_act_time = 360;
	
	function init() 
	{
		define('MOD_SKILL456_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[456] = '突击';
	}
	
	function acquire456(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost456(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked456(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (\skillbase\skill_query(456,$pa)) 
		{
			eval(import_module('sys','logger','skill456'));
			$x=$now-$starttime; 
			if ($x<$skill456_act_time)
			{
				if ($active)
					$log.='<span class="yellow b">你抱着破釜沉舟之心，对敌人打出致命一击！</span><br>';
				else  $log.='<span class="yellow b">敌人抱着破釜沉舟之心，对你打出致命一击！</span><br>';
				$r=Array(1.4);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(456,$sdata))&&check_unlocked456($sdata))
		{
			eval(import_module('skill456'));
			$skill456_time = $now-$starttime; 
			$z=Array(
				'disappear' => 0,
			);
			if ($skill456_time<$skill456_act_time)
			{
				$z['clickable'] = 1;
				$z['style']=1;
				$z['totsec']=$skill456_act_time;
				$z['nowsec']=$skill456_time;
				$skill456_rm = $skill456_act_time-$skill456_time;
				$z['hint'] = "技能「突击」";
			}
			else  
			{
				$z['clickable'] = 0;
				$z['style']=3;
				$z['activate_hint'] = "技能「突击」生效时间已经结束";
			}
			\bufficons\bufficon_show('img/skill456.gif',$z);
		}
		$chprocess();
	}
}

?>
