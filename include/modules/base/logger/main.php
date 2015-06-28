<?php

namespace logger
{
	global $log;
	
	function init() {}
	
	function logsave($pid,$time,$log = '',$type = 's'){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$ldata['toid']=$pid;
		$ldata['type']=$type;
		$ldata['time']=$time;
		$ldata['log']=$log;
		$db->array_insert("{$tablepre}log", $ldata);
		return;	
	}
	
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$result = $db->query("SELECT time,log FROM {$tablepre}log WHERE toid = '$pid' ORDER BY time,lid");
		while($logtemp = $db->fetch_array($result)){
			$log .= date("H:i:s",$logtemp['time']).'，'.$logtemp['log'].'<br />';
		}
		$db->query("DELETE FROM {$tablepre}log WHERE toid = '$pid'");
		
		$chprocess();
	}
}

?>
