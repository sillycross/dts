<?php

namespace ex_direct
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['L'] = '直击';
		$itemspkdesc['L']='攻击时可能无视敌方一切技能';
		$itemspkremark['L']='30%概率生效';
	}
	
	function get_ex_direct_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 30;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$pd['direct_ignore_skills'] = 0;
		if (\attrbase\check_in_itmsk('L', \attrbase\get_ex_attack_array($pa, $pd, $active)) && rand(0,99) < get_ex_direct_proc_rate($pa, $pd, $active))
		{
			eval(import_module('logger'));
			$log .= \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b"><:pa_name:>的攻击无视了<:pd_name:>的一切技能！</span><br>');
			$pd['direct_ignore_skills']=1;
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function skill_enabled_core($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillid=(int)$skillid;
		if ($pa!=NULL && !empty($pa['direct_ignore_skills']))
		{
			//所有技能失效（称号特性不失效）
			if (!\skillbase\check_skill_info($skillid,'achievement') && !\skillbase\check_skill_info($skillid,'hidden') && !\skillbase\check_skill_info($skillid,'feature'))
				return 0;
		}
		return $chprocess($skillid,$pa);
	}
	
}

?>