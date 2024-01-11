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
	
	function upgrade11()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(11))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$skillpara1 = (int)get_var_input('skillpara1');
		if ($skillpara1 <= 0)
		{
			$log.='技能点指令错误！<br>';
			return;
		}
		if ($skillpoint<1 || $skillpoint < $skillpara1) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$att_dice = $skillpara1*4;
		$att += $att_dice;
		$def_dice = $skillpara1*6;
		$def += $def_dice;
		$log.='消耗了<span class="lime b">'.$skillpara1.'</span>点技能点，你的攻击力提升了<span class="yellow b">'.$att_dice.'</span>点，防御力提升了<span class="yellow b">'.$def_dice.'</span>点。<br>';
		$skillpoint-=$skillpara1;
	}
}

?>
