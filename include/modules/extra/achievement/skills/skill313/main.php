<?php

namespace skill313
{
	//各级要完成的成就名，如果不存在则取低的
	$ach313_name = array(
		1=>'伐木达人',
		2=>'幻境清道夫',
		3=>'片甲不留',
		4=>'模拟死斗',
		5=>'幻境的百万富翁',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach313_desc= array(
		1=>'<span class="yellow b">伐木挑战</span>一禁前所持金钱数最高时达到<:threshold:>元',
	);
	
	$ach313_proc_words = '目前纪录';
	
	$ach313_unit = '元';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach313_threshold = array(
		1 => 30000,
		2 => 60000,
		3 => 100000,
		4 => 360000,
		5 => 1000000,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach313_qiegao_prize = array(
		1 => 300,
		2 => 400,
		3 => 500,
		4 => 3600,
		5 => 5000,
	);
	
	//各级给的卡片奖励
	$ach313_card_prize = array(
		2 => 251,
		3 => 89,
		4 => 118,
		5 => 156,
	);
	
	function init() 
	{
		define('MOD_SKILL313_INFO','achievement;');
		define('MOD_SKILL313_ACHIEVEMENT_ID','13');
		eval(import_module('achievement_base'));
		$ach_allow_mode[313] = array(15);
	}
	
	function acquire313(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(313,'max_money',0,$pa);
	}
	
	function lost313(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act(){//一禁之前每次行动后判断并记录最大金钱数
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map'));
		$chprocess();
		if(\skillbase\skill_query(313) && !\map\get_area_wavenum()){
			if($money > \skillbase\skill_getvalue(313,'max_money')) 
				\skillbase\skill_setvalue(313,'max_money',$money);
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 313){
			eval(import_module('sys','map'));
			$var=(int)\skillbase\skill_getvalue($achid,'max_money',$pa);
			if(!\map\get_area_wavenum()) {
				$var=max($pa['money'], $var);//防止最后那一步没记录
			}
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>