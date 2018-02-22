<?php

namespace skill300
{
	//按照如下格式，成就系统会自动生成界面、计算成就
	//301-332成就是历史遗留，无力通改，如果要新增成就请以本成就为准！（获得卡片的成就参照313伐木成就）
	//各级要完成的成就名，如果不存在则取低的
	$ach300_name = array(
		1=>'及时补给',
		2=>'衣食无忧',
		3=>'奥义很爽',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach300_desc= array(
		1=>'使用无毒补给的总效果达到<:threshold:>点',
	);
	
	$ach300_proc_words = '目前进度';
	
	$ach300_unit = '点';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach300_threshold = array(
		1 => 32767,
		2 => 142857,
		3 => 999983,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach300_qiegao_prize = array(
		1 => 200,
		2 => 300,
		3 => 500,
	);
	
	$ach300_card_prize = array(
		3 => 86,
	);
	
	function init() 
	{
		define('MOD_SKILL300_INFO','achievement;');
		define('MOD_SKILL300_ACHIEVEMENT_ID','0');
	}
	
	function acquire300(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(300,'cnt','0',$pa);
	}
	
	function lost300(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 300){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
	
	/*function edible_recover($itm, $hpup, $spup)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(300))
		{
			$x=(int)\skillbase\skill_getvalue(300,'cnt');
			$x+=$hpup+$spup;
			\skillbase\skill_setvalue(300,'cnt',$x);
		}
		$chprocess($itm,$hpup,$spup);
	}*/
	
	function get_edible_spup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(300))
		{
			$x=(int)\skillbase\skill_getvalue(300,'cnt');
			$x+=$theitem['itme'];
			\skillbase\skill_setvalue(300,'cnt',$x);
		}
		return $chprocess($theitem);
	}
	
	function get_edible_hpup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(300))
		{
			$x=(int)\skillbase\skill_getvalue(300,'cnt');
			$x+=$theitem['itme'];
			\skillbase\skill_setvalue(300,'cnt',$x);
		}
		return $chprocess($theitem);
	}
}

?>