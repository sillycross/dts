<?php

namespace skill266
{
	function init() 
	{
		define('MOD_SKILL266_INFO','club;upgrade;limited;');
		eval(import_module('clubbase'));
		$clubskillname[266] = '枪魂';
	}
	
	function acquire266(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost266(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked266(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade266()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(266) || !check_unlocked266($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val!=1 && $val!=2)
		{
			$log.='参数不合法。<br>';
			return;
		}
		if ($val==1)
		{
			if (!\skillbase\skill_query(265)) \skillbase\skill_acquire(265);
			\skill265\unlock265($sdata);
		}
		else
		{
			if (!\skillbase\skill_query(205)) \skillbase\skill_acquire(205);
			\skill205\unlock205($sdata);
		}
		\skillbase\skill_lost(266);
		$log.='选择成功。<br>';
	}
}

?>