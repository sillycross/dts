<?php

namespace skill39
{
	function init() 
	{
		define('MOD_SKILL39_INFO','club;upgrade;locked;');
	}
	
	function acquire39(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['att']+=200; $pa['def']+=200;
	}
	
	function lost39(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked39(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade39()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(39))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		if ($skillpoint<1) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$att += 7; $def += 11;
		$log.='消耗了<span class="lime">1</span>点技能点，你的攻击力提升了<span class="yellow">7</span>点，防御力提升了<span class="yellow">11</span>点。<br>';
		$skillpoint--;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('lvlctl'));
		if (\skillbase\skill_query(39,$pa)) 
		{
			$lvupatt += 2;	
			$lvupdef += rand(2,3);	
		}
		$chprocess($pa);
	}
	
}

?>
