<?php

namespace gameflow_antiafk
{
	function init() {}
	
	function reset_game()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		
		$chprocess();
		
		eval(import_module('sys','gameflow_antiafk'));
		//修改反挂机间隔
		$afktime = $starttime;
		save_gameinfo();
	}
	
	function gamestateupdate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		eval(import_module('sys','gameflow_antiafk'));
		if (($gamestate >= 40)&&($now > $afktime + $antiAFKertime * 60)) {//判定自动反挂机
			antiAFK();
			$afktime = $now;
		}
		
	}
	
	function antiAFK($timelimit = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','gameflow_antiafk'));
		if(empty($timelimit)){
			$timelimit = $antiAFKertime;
		}
		$timelimit *= 60;
		$deadline=$now-$timelimit;
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE type=0 AND endtime < '$deadline' AND hp>'0' AND state<'10'");
		while($al = $db->fetch_array($result)) {
			$afkerlist[$al['pid']]=Array('name' => $al['name'] ,'pls' => $al['pls']);
		}

		if(empty($afkerlist)){return;}
		foreach($afkerlist as $kid => $kcontent){
			$db->query("UPDATE {$tablepre}players SET hp='0',state='32' WHERE pid='$kid' AND type='0' AND hp>'0' AND state<'10'");
			if($db->affected_rows()){
				addnews($now,'death32',$kcontent['name'],'',$kcontent['pls']);
				$alivenum--;
				$deathnum++;			
			}
		}
		save_gameinfo();
		return;
	}

	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map'));
		if($news == 'death32')
			return "<li>{$hour}时{$min}分{$sec}秒，躲藏于<span class=\"red\">$plsinfo[$c]</span>的<span class=\"yellow\">$a</span><span class=\"red\">挂机时间过长</span>，被在外等待的愤怒的玩家们私刑处死！";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
