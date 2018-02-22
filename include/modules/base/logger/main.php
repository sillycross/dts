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
			$td = $now - $logtemp['time'];
			$td_h = floor($td / 3600);
			$td_m = floor($td % 3600 / 60);
			$td_s = $td % 60;
			$td_word = '';
			if($td_h) $td_word.= $td_h.'小时';
			if($td_m) $td_word.= $td_m.'分钟';
			$td_word.= $td_s.'秒';
			$log .= $td_word.'前，'.$logtemp['log'].'<br />';
		}
		$db->query("DELETE FROM {$tablepre}log WHERE toid = '$pid'");
		
		$chprocess();
	}
}

?>
