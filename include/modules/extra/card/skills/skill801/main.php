<?php

namespace skill801
{
	function init() 
	{
		define('MOD_SKILL801_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[801] = '团队';
	}
	
	function acquire801(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(801, 'pidlist', '', $pa);
	}
	
	function lost801(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(801, 'pidlist', $pa);
	}
	
	function check_unlocked801(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill801_get_pidlist(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa))
		{
			eval(import_module('player'));
			$pa = $sdata;
		}
		$pidlist = \skillbase\skill_getvalue(801,'pidlist',$pa);
		if (empty($pidlist)) $ls = array();
		else $ls = explode('_',$pidlist);
		return $ls;
	}
	
	function skill801_add_pid(&$pa, $pid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ls = skill801_get_pidlist($pa);
		if (!in_array($pid, $ls))
		{
			$ls[] = $pid;
			$pidlist = implode('_',$ls);
			\skillbase\skill_setvalue(801,'pidlist',$pidlist,$pa);
		}
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		//lvl为1为首领，0为爪牙；首领被击杀后所有爪牙逃走；暂时未加爪牙被击杀的额外处理
		if (\skillbase\skill_query(801,$pd) && check_unlocked801($pd) && ($pd['hp'] <= 0) && \skillbase\skill_getvalue(801,'lvl',$pd))
		{
			$flag = 0;
			$ls = skill801_get_pidlist($pd);
			if (!empty($ls))
			{
				foreach ($ls as $fpid)
				{
					$fdata = \player\fetch_playerdata_by_pid($fpid);
					if (!empty($fdata) && ($fdata['hp'] > 0))
					{
						$flag = 1;
						skill801_npc_escape($fdata);
					}
				}
			}
			if ($flag)
			{
				eval(import_module('logger'));
				$log .= "<span class=\"yellow b\">{$pd['name']}的手下们一哄而散了！</span><br><br>";
			}
		}
	}
	
	//单个爪牙逃走的处理
	function skill801_npc_escape(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//过于粗暴，应该怎么做呢？
		$db->query("DELETE FROM {$tablepre}players WHERE pid = '{$pa['pid']}'");
		addnews($now,'escape801',$pa['name']);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		//暂时没用到
		// if($news == 'defeat801') 
			// return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}被击败后，手下们一哄而散了！</span></li>";
		if($news == 'escape801') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}逃离了战场！</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>