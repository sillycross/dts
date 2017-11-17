<?php

namespace skill243
{
	function init() 
	{
		define('MOD_SKILL243_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[243] = '证伪';
	}
	
	function acquire243(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(243,'l','',$pa);
	}
	
	function lost243(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(243,'l',$pa);
	}
	
	function check_unlocked243(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=12;
	}
	
	function get_ex_phy_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(243,$pa) || !check_unlocked243($pa)) return $chprocess($pa,$pd,$active);
		eval(import_module('ex_phy_def'));
		$s=\skillbase\skill_getvalue(243,'l',$pa);
		if (strpos($s,$def_kind[$pa['wep_kind']])===false) return $chprocess($pa,$pd,$active);
		return 0;
	}
	
	function check_ex_phy_def_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(243,$pa) || !check_unlocked243($pa)) return $chprocess($pa,$pd,$active); 
		$z=$chprocess($pa, $pd, $active);
		if (!$z)	//没有生效
		{
			eval(import_module('ex_phy_def'));
			$s=\skillbase\skill_getvalue(243,'l',$pa);
			if (strpos($s,$def_kind[$pa['wep_kind']])===false)
			{
				$s.=$def_kind[$pa['wep_kind']];
				\skillbase\skill_setvalue(243,'l',$s,$pa);
			}
		}
		return $z;
	}
	
	function get_ex_dmg_def_proc_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(243,$pa) || !check_unlocked243($pa)) return $chprocess($pa,$pd,$active,$key);
		eval(import_module('ex_dmg_def'));
		$s=\skillbase\skill_getvalue(243,'l',$pa);
		if (strpos($s,$def_kind[$key])===false) return $chprocess($pa,$pd,$active,$key);
		return 0;
	}
	
	function check_ex_dmg_def_proc(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(243,$pa) || !check_unlocked243($pa)) return $chprocess($pa,$pd,$active,$key); 
		$z=$chprocess($pa, $pd, $active, $key);
		if (!$z)	//没有生效
		{
			eval(import_module('ex_dmg_def'));
			$s=\skillbase\skill_getvalue(243,'l',$pa);
			if (strpos($s,$def_kind[$key])===false)
			{
				$s.=$def_kind[$key];
				\skillbase\skill_setvalue(243,'l',$s,$pa);
			}
		}
		return $z;
	}
	
	function get_ex_rapid_def_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess($pa, $pd, $active);
		$s=\skillbase\skill_getvalue(243,'l',$pa);
		if (strpos($s,'R')!==false) $r = 0;
		return $r;
	}
	
	//防连失效
	function check_ex_rapid_def_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$z=$chprocess($pa, $pd, $active);
		if (!$z && \skillbase\skill_query(243,$pa) && check_unlocked243($pa)){
			$s=\skillbase\skill_getvalue(243,'l',$pa);
			if (strpos($s,'R')===false)
			{
				$s.='R';
				\skillbase\skill_setvalue(243,'l',$s,$pa);
			}
		}
		return $z;
	}
}

?>
