<?php

namespace skill11
{
	function init() 
	{
		define('MOD_SKILL11_INFO','club;upgrade;locked;');
	}
	
	function acquire11(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost11(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked11(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
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
	
	function upgrade11()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(11))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		if ($skillpoint<1) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$att += 4; $def += 6;
		$log.='消耗了<span class="lime">1</span>点技能点，你的攻击力提升了<span class="yellow">4</span>点，防御力提升了<span class="yellow">6</span>点。<br>';
		$skillpoint--;
	}
	
}

?>
