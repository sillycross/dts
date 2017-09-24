<?php

namespace skill486
{	
	$skill486prob = array(0, 10, 100);
	
	function init() 
	{
		define('MOD_SKILL486_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[486] = '无垠';
	}
	
	function acquire486(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(486,'lvl','0',$pa);
		\skillbase\skill_setvalue(486,'rtime','0',$pa);
	}
	
	function lost486(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(486,'lvl',$pa);
		\skillbase\skill_delvalue(486,'rtime',$pa);
	}
	
	function check_unlocked486(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_skill486prob(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(486, $pa)) return 0;
		eval(import_module('skill486'));
		$lvl = \skillbase\skill_getvalue(486,'lvl',$pa);
		$rtime = \skillbase\skill_getvalue(486,'rtime',$pa);
		return round($skill486prob[$lvl] * pow(0.5, $rtime));
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		
		if($pd['hp'] <= 0 && \skillbase\skill_query(486, $pd)){
			$skill486prob = get_skill486prob($pd);
			$dice = rand(0,99);
			if($dice < $skill486prob){
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class='lime'>然而，<:pd_name:>奇迹般从致命一击中活了下来，还剩下一口气。</span><br>");
				$pd['battlelog'] .= \battle\battlelog_parser($pa, $pd, $active, "<span class='lime'>然而，<:pa_name:>奇迹般从致命一击中活了下来，还剩下一口气。</span><br>");
				\skillbase\skill_setvalue(486,'rtime', \skillbase\skill_getvalue(486,'rtime',$pd) + 1,$pd);
				$pd['state']=0; $pd['hp']=1;
				$deathnum--;
				if ($pd['type']==0) $alivenum++;
				save_gameinfo();
					
				addnews ( $now, 'skill486_revival', $pd['name'] );
			}			
		}
	}
}

?>