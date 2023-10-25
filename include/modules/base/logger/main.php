<?php

namespace logger
{
	global $log;
	
	function init() {}
	
	//保存敌人的战斗log
	//类型：c对话、t队友、b作战、s系统
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
	
	//大致预估$log会形成的行数
	function log_linenum_counter($str = '', $limit = 48)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$str) {
			eval(import_module('logger'));
			$str = $log;
		}
		$i = 0;
		$lastt = '';
		$tnum = $lnum = 0;
		$tmp_log = strip_tags(strtolower($str), '<br>');
		$text_count = mb_strlen($tmp_log);
		while($i < $text_count){
			$t = mb_substr($tmp_log, $i, 1);
			$lastt .= $t;
			if(mb_strlen($lastt)>4) $lastt = mb_substr($lastt, 1);
			if($lastt=='<br>') {//强制换行
				$tnum = 0;
				$lnum ++;
			}else{
				if(mb_strlen($t) == strlen($t)) $tnum += 1;//英文
				else $tnum += 1.8;//汉字
				
				if($tnum >= $limit) {//超过长度，换行。这里估测一行能塞38个英文字符
					$tnum = 0;
					$lnum ++;
				}
			}
			
			$i ++; 
		}
		return $lnum;
	}
}

?>
