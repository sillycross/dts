<?php

namespace miracletrap
{
	function init() {}
	
	function trapcheck()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//奇迹陷阱只要进入踩陷阱判断就必踩
		eval(import_module('sys','player'));
		$trapresult = $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls' ORDER BY itmk DESC");
		$xtrp = $db->fetch_array($trapresult);
		if(!empty($xtrp) && $xtrp['itmk'] == 'TOc') {
			\trap\trapget($xtrp);
			return 1;
		}
		return $chprocess();
	}
	
	function trap()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//奇迹陷阱直接命中
		eval(import_module('player'));
		if ($itmk0 == 'TOc') { \trap\trap_hit(); return; }
		$chprocess();
	}
	
	function get_trap_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//奇迹陷阱必死
		eval(import_module('player'));
		if ($itmk0 == 'TOc') return 999983;
		return $chprocess();
	}
	
	function check_trapdef_proc()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//奇迹陷阱不可以被迎击
		eval(import_module('player'));
		if ($itmk0 == 'TOc') return 0;
		return $chprocess();
	}
}

?>
