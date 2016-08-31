<?php

namespace wjfix
{
	function init() {}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['wepk']=='WJ') $pa['bskill']=0;
		return $chprocess($pa, $pd, $active);
	}
	
	function get_battle_skill_entry(&$edata,$which)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($wepk=='WJ') return;
		return $chprocess($edata,$which);
	}
}

?>
