<?php

namespace dualwep
{
	function init() 
	{
		eval(import_module('itemmain'));
		global $dualwep_iteminfo;
		$iteminfo+=$dualwep_iteminfo;
	}
	
	function get_sec_attack_method(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		extract($pdata);
		eval(import_module('weapon'));
		$w2 = substr($wepk,2,1);
		if (($w2=='0')||($w2=='1')) {$w2='';}
		if ($w2!='')
		{
			if((($w2 == 'G')||($w2=='J'))&&($weps==$nosta)){ $w2 = 'P'; }
		}
		return $w2;
	}
	
	function check_attack_method(&$pdata, $wm)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($wm!='' && get_sec_attack_method($pdata)==$wm) return 1;
		return $chprocess($pdata,$wm);
	}
}

?>
