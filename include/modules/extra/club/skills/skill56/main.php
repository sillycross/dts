<?php

namespace skill56
{
	//召唤NPC需要的金钱
	$skill56_need = 1200;
	
	//召唤次数限制
	$skill56_lim = 2;
	
	function init() 
	{
		define('MOD_SKILL56_INFO','club;upgrade;limited;');
		global $skill56_npc;
		eval(import_module('player','addnpc','npc'));
		$typeinfo[25]='佣兵';
		$npcinfo[25]=$skill56_npc;
		$anpcinfo[25]=$skill56_npc;
	}
	
	function acquire56(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		global $skill56_lim;
		//已经发动技能次数
		\skillbase\skill_setvalue(56,'t','0',$pa);
		for ($i=1; $i<=$skill56_lim; $i++)
		{
			//佣兵PID
			\skillbase\skill_setvalue(56,'p'.$i,'0',$pa);
			//上次发工资时间
			\skillbase\skill_setvalue(56,'l'.$i,'0',$pa);
			//佣兵种类
			\skillbase\skill_setvalue(56,'s'.$i,'0',$pa);
		}
	}
	
	function lost56(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked56(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function upgrade56()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill56','map','player','logger','input'));
		if (!\skillbase\skill_query(56) || !check_unlocked56($sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		
	}
}

?>
