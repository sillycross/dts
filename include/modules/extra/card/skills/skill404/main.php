<?php

namespace skill404
{
	$paneldesc=array('底力','底力','底力','底力','底力','斗魂');
	$extdmg=array(0,120,300,600,1200,45000);
	
	function init() 
	{
		define('MOD_SKILL404_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[404] = '底力';
	}
	
	function acquire404(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(404,'lvl','0',$pa);
	}
	
	function lost404(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(404,'lvl',$pa);
	}

	function check_unlocked404(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_skill404_extdmg(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill404','player','logger'));
		if (!\skillbase\skill_query(404, $pa) || !check_unlocked404($pa)) return 0;
		if ($active) $log .= "<span class=\"red b\">身负重伤的你反而越战越勇！</span><br>";
			else $log .= "<span class=\"red b\">身负重伤的{$pa['name']}反而越战越勇！</span><br>";
		$r = $extdmg[\skillbase\skill_getvalue(404,'lvl',$pa)];
		return $r;
	}

	function get_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(404,$pa) || !check_unlocked404($pa) ||($pa['hp']>($pa['mhp']/2))) return $chprocess($pa,$pd,$active);
		$var_404=get_skill404_extdmg($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)+$var_404;
	}
}

?>
