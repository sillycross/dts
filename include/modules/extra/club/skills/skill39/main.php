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
		eval(import_module('player','logger','input'));
		if (!\skillbase\skill_query(39))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$skillpara1 = (int)$skillpara1;
		if ($skillpara1 <= 0)
		{
			$log.='技能点指令错误！<br>';
			return;
		}
		if ($skillpoint<1) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$att_dice = $skillpara1*7;
		$att += $att_dice;
		$def_dice = $skillpara1*11;
		$def += $def_dice;
		$log.='消耗了<span class="lime">'.$skillpara1.'</span>点技能点，你的攻击力提升了<span class="yellow">'.$att_dice.'</span>点，防御力提升了<span class="yellow">'.$def_dice.'</span>点。<br>';
		$skillpoint-=$skillpara1;
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
