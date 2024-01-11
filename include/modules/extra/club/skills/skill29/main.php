<?php

namespace skill29
{
	function init() 
	{
		define('MOD_SKILL29_INFO','club;upgrade;feature;');
		eval(import_module('clubbase'));
		$clubdesc_h[13] = $clubdesc_a[13] = '初始生命上限+200，每次升级额外获得5-7点生命上限<br>技能点换取生命上限数值大幅提高';//根性的特性显示是在skill31里
	}
	
	function acquire29(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['hp']+=200; $pa['mhp']+=200;
	}
	
	function lost29(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked29(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade29()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(29))
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
		$dice = $skillpara1 * 6;
		$mhp += $dice; $hp += $dice;
		$log.='消耗了<span class="lime b">'.$skillpara1.'</span>点技能点，你的生命上限提升了<span class="yellow b">'.$dice.'</span>点。<br>';
		$skillpoint-=$skillpara1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('lvlctl'));
		if (\skillbase\skill_query(29,$pa)) $lvuphp += rand ( 5, 7 );
		$chprocess($pa);
	}
	
}

?>