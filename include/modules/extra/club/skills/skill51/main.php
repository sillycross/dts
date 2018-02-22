<?php

namespace skill51
{
	function init() 
	{
		define('MOD_SKILL51_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[51] = '百出';
	}
	
	function acquire51(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost51(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function get_skill48_maxbuff(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(48,$pa)) return 0;
		eval(import_module('skill48'));
		$maxx=0;
		foreach ($skill48_ex_kind_list as $key => $value)
		{
			$x=\skillbase\skill_getvalue(48,$key,$pa);
			if ($x>$maxx) $maxx=$x;
		}
		return $maxx;
	}
	
	function check_unlocked51(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return get_skill48_maxbuff($pa)>=120;
	}
	
	function get_skill51_multiplier(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$z=(int)\skillbase\skill_getvalue(48,'tot',$pa); 
		$z*=2;
		return $z;
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(51,$pa) || !check_unlocked51($pa)) return $chprocess($pa, $pd, $active);
		if (\weapon\get_skillkind($pa,$pd,$active) != 'wc') return $chprocess($pa, $pd, $active);
		eval(import_module('logger'));
		$z=get_skill51_multiplier($pa);
		$r=Array((100+$z)/100);
		if ($active)
			$log.='<span class="yellow">你打得敌人落花流水，物理伤害增加了'.$z.'%！</span><br>';
		else  $log.='<span class="yellow">敌人打得你落花流水，物理伤害增加了'.$z.'%！</span><br>';
		return array_merge($r,$chprocess($pa, $pd, $active));
	}
	
}

?>
