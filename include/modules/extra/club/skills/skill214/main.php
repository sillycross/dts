<?php

namespace skill214
{
	function init() 
	{
		define('MOD_SKILL214_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[214] = '专注';
	}
	
	function acquire214(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(214,'choice','2',$pa);
	}
	
	function lost214(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(214,'choice',$pa);
	}
	
	function check_unlocked214(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if(!$pa) $pa = $sdata;
		return $pa['lvl']>=3;
	}
	
	function upgrade214()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(214) || !check_unlocked214($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val<1 || $val>3)
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(214,'choice',$val);
		$log.='设置成功。<br>';
	}
	
	function calculate_itemfind_obbs_multiplier()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(214) || !check_unlocked214()) return $chprocess();
		$choice = \skillbase\skill_getvalue(214,'choice');
		if ($choice==3) return 1.15*$chprocess();
		return $chprocess();
	}
	
	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(214) || !check_unlocked214()) return $chprocess($schmode);
		$choice = \skillbase\skill_getvalue(214,'choice');
		if ($choice==1) return 1.15*$chprocess($schmode);
		return $chprocess($schmode);
	}
}

?>
