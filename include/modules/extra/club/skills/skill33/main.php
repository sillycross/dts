<?php

namespace skill33
{
	function init() 
	{
		define('MOD_SKILL33_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[33] = '应变';
	}
	
	function acquire33(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(33,'choice','2',$pa);
	}
	
	function lost33(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(33,'choice',$pa);
	}
	
	function check_unlocked33(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function upgrade33()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(33) || !check_unlocked33($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$skillpara1 = get_var_input('skillpara1');
		
		$val = (int)$skillpara1;
		if ($val<1 || $val>3)
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(33,'choice',$val);
		$log.='设置成功。<br>';
	}
	
	function get_external_def(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(33,$pd) || !check_unlocked33($pd)) return $chprocess($pa, $pd, $active);
		$choice = \skillbase\skill_getvalue(33,'choice',$pd);
		$r = \skill32\get_skill32_extra_def_gain($pa, $pd, $active);
		if ($choice==2) $r = 0;
		if ($choice==3) $r *= -1;
		return $chprocess($pa, $pd, $active) + $r;
	}
	
	function get_external_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(33,$pa) || !check_unlocked33($pa)) return $chprocess($pa, $pd, $active);
		$choice = \skillbase\skill_getvalue(33,'choice',$pa);
		//注意这里是$pa获得的防御数值，而那个函数计算的是pd获得的防御数值，因此参数反序
		$r = \skill32\get_skill32_extra_def_gain($pd, $pa, $active);
		if ($choice==1) $r *= -0.7;
		if ($choice==2) $r = 0;
		if ($choice==3) $r *= 0.7;
		return $chprocess($pa, $pd, $active) + $r * 2;
	}
}

?>