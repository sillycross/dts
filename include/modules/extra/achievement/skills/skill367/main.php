<?php

namespace skill367
{
	//各级要完成的成就名，如果不存在则取低的
	$ach367_name = array(
		1=>'幻境朋克',
		2=>'Lunatic Warlock',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach367_desc= array(
		1=>'让边缘行者的<span class="yellow b">「破解」</span>技能层数达到<:threshold:>层（4禁前有效）',
	);
	
	$ach367_proc_words = '最高纪录';
	
	$ach367_unit = '层';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach367_threshold = array(
		1 => 30,
		2 => 50,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach367_qiegao_prize = array(
		1 => 2000,
		2 => 3000,
	);
	
	//各级给的卡片奖励
	$ach367_card_prize = array(
		1 => 209,
		2 => 63,
	);
	
	function init() 
	{
		define('MOD_SKILL367_INFO','achievement;');
		define('MOD_SKILL367_ACHIEVEMENT_ID','67');
	}
	
	function acquire367(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(367,'cnt',0,$pa);
	}
	
	function lost367(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function wdecode(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys','map'));
		if($areanum/$areaadd < 4 && \skillbase\skill_query(234)) {//第51层会失去234号破解技能，所以这里大概最多只能记录到50层，不过已经够了
			$var = (int)\skillbase\skill_getvalue(234, 'lvl');
			$cnt = (int)\skillbase\skill_getvalue(367, 'cnt');
			\skillbase\skill_setvalue(367,'cnt', max($var, $cnt));
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 367){
			$var=(int)\skillbase\skill_getvalue(367, 'cnt', $pa);
			$ret = max($ret, $var);
		}
		return $ret;
	}
}

?>