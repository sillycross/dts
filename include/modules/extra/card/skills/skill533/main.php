<?php

namespace skill533
{
	function init() 
	{
		define('MOD_SKILL533_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[533] = '实名';
	}
	
	function acquire533(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost533(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked533(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//如果存在533号技能，交换姓名和学号
	function get_valid_disp_user_info($pdata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($u, $s) = $chprocess($pdata);
		$flag = 0;
		if(\skillbase\skill_query(533, $pdata)) $flag = 1;
		//调用的时候有可能没有初始化技能参数，因此是直接读nskillpara的（也就是说卡片必须给这个技能的lvl赋值）
		elseif(!empty(\skillbase\skill_getvalue_direct(533,'lvl',$pdata['nskillpara']))) $flag = 1;
		
		if($flag) {
			return Array($s, $u);
		}
		return Array($u, $s);
	}
	
	
}
?>