<?php

namespace skill380
{
	//各级要完成的成就名，如果不存在则取低的
	$ach380_name = array(
		1=>'踏足迷雾',
		2=>'斩断荆棘',
		3=>'冲出硝烟',
		4=>'拥抱黑暗',
		5=>'？？？？',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach380_desc= array(
		1=>'在<span class="vermilion b">试炼模式</span>的进阶<:threshold:>难度中完成结局幻境解离',
	);
	
	$ach380_proc_words = '最高纪录';
	
	$ach380_unit = '层';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach380_threshold = array(
		1 => 1,
		2 => 10,
		3 => 20,
		4 => 25,
		5 => 30,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach380_qiegao_prize = array(
		1 => 300,
		2 => 800,
		3 => 1500,
		4 => 2400,
		5 => 3600,
	);
	
	//各级给的卡片奖励
	$ach380_card_prize = array(
		2 => 26,
		3 => 199,
		4 => 282,
		5 => 380,
	);
	
	//卡片奖励的碎闪等级
	$ach380_card_prize_blink = array(
		3 => 10,
		4 => 20,
		5 => 20,
	);
	
	function init() 
	{
		define('MOD_SKILL380_INFO','achievement;');
		define('MOD_SKILL380_ACHIEVEMENT_ID','80');
	}
	
	function acquire380(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost380(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 380){
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && ($winmode == 7))
			{
				$alvl = (int)\skillbase\skill_getvalue(1003,'instance3_lvl',$pa);
				if ($alvl > $ret)
				{
					$ret = $alvl;
				}
			}
		}
		return $ret;
	}
}

?>