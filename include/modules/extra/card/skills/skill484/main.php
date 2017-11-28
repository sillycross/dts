<?php

namespace skill484
{	
	function init() 
	{
		define('MOD_SKILL484_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[484] = '被削';
	}
	
	function acquire484(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost484(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked484(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//不能获得见敌和黑衣称号（之后固定获得）
	function club_choice_probability_process($clublist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(484)) return $chprocess($clublist);
		if(isset($clublist[2])) $clublist[2]['probability'] = 0;
		if(isset($clublist[8])) $clublist[8]['probability'] = 0;
		return $clublist;
	}
	
	//固定获得见敌和黑衣称号，会替换一般称号的最后一个、特殊称号的第一个
	function get_club_choice_array()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','clubbase'));
		$ret = $chprocess();
		if(\skillbase\skill_query(484)){
			list($n1, $n2) = $max_club_choice_num;
			$ret[$n1] = 2;
			$ret[$n1+1] = 8;
		}
		return $ret;
	}
}

?>