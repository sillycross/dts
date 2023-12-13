<?php

namespace skill380
{
	//各级要完成的成就名，如果不存在则取低的
	$ach380_name = array(
		1=>'踏足迷雾',
		2=>'斩断荆棘',
		3=>'冲出硝烟',
		4=>'拥抱黑暗',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach380_desc= array(
		1=>'在<span class="red b">试炼模式</span>中完成结局幻境解离',
	);
	
	$ach380_proc_words = '最高纪录';
	
	$ach380_unit = '层';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach380_threshold = array(
		1 => 1,
		2 => 5,
		3 => 10,
		4 => 15,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach380_qiegao_prize = array(
		1 => 300,
		2 => 800,
		3 => 1500,
		4 => 3000,
	);
	
	//各级给的卡片奖励
	$ach380_card_prize = array(
		2 => 26,
		4 => 282,
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
				if ($alvl > $ret) $ret = $alvl;
			}
		}
		return $ret;
	}
}

?>