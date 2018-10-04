<?php

namespace skill84
{	
	function init() 
	{
		define('MOD_SKILL84_INFO','club;hidden;debuff;');
	}
	
	function acquire84(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost84(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_skill84_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill84'));
		if (!\skillbase\skill_query(84,$pa)) return 0;
		$e=\skillbase\skill_getvalue(84,'end',$pa);
		if ($now<$e) return 1;
		return 0;
	}
	
	function get_skill84_time(&$pa){//单位是秒
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(7 == $pa['club']) return 20;
		return 15;
	}
	
	function get_skill84_effect(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(7 == $pa['club']) return 1.05;
		return 1.03;
	}
	
	//先攻率+3%
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (check_skill84_state($ldata)) $r*=get_skill84_effect($ldata);
		elseif (check_skill84_state($edata)) $r/=get_skill84_effect($edata);
		if($r != 1) $ldata['active_words'] = \attack\multiply_format($r, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)*$r;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(84,$sdata))
		{
			eval(import_module('skill84','skillbase'));
			$skill84_start = (int)\skillbase\skill_getvalue(84,'start'); 
			$skill84_end = (int)\skillbase\skill_getvalue(84,'end'); 
			$z=Array(
				'disappear' => 1,
				'clickable' => 0,
				'hint' => '探测器开启中！<br>你的先制攻击率+3%',
			);
			if ($now<$skill84_end)
			{
				$z['style']=1;
				$z['totsec']=$skill84_end-$skill84_start;
				$z['nowsec']=$now-$skill84_start;
			}
			else 
			{
				$z['style']=4;
			}
			\bufficons\bufficon_show('img/skill84.gif',$z);
		}
		$chprocess();
	}
}

?>
