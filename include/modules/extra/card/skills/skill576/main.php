<?php

namespace skill576
{
	function init() 
	{
		define('MOD_SKILL576_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[576] = '寻宝';
	}
	
	function acquire576(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(576,'recipeid','',$pa);
		\skillbase\skill_setvalue(576,'mpid',0,$pa);
		\skillbase\skill_setvalue(576,'completeflag',0,$pa);
	}
	
	function lost576(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(576,'recipeid',$pa);
		\skillbase\skill_delvalue(576,'mpid',$pa);
		\skillbase\skill_delvalue(576,'completeflag',$pa);
	}
	
	function check_unlocked576(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function recipe_mix_proc($seq, $itmp, $minfo)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if (\skillbase\skill_query(576,$sdata) && check_unlocked576($sdata))
		{
			eval(import_module('item_recipe'));
			$recipeid = \skillbase\skill_getvalue(576,'recipeid',$sdata);
			if ($recipe_mixinfo[$recipeid] === $minfo)
			{
				eval(import_module('logger'));
				$log .= "<span class=\"yellow b\">你已经完成了「难题」！</span><br>";
				\skillbase\skill_setvalue(576,'completeflag',1,$sdata);
			}	
		}
		$chprocess($seq, $itmp, $minfo);
	}
	
	function recipe_mix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(576,$sdata) && check_unlocked576($sdata) && 1 === (int)\skillbase\skill_getvalue(576,'completeflag',$sdata))
		{
			eval(import_module('sys','player','logger'));
			$itmstr = $uip['itmstr'];
			$tpstr = $uip['mixtp'];
			
			$log .= "<span class=\"yellow b\">$itmstr</span>{$tpstr}合成了<span class=\"yellow b\">{$itm0}</span><br>";
			addnews($now,'recipe_mix',$name,$itm0,$tpstr);
			
			$mpid = \skillbase\skill_getvalue(576,'mpid');
			$pd = \player\fetch_playerdata_by_pid($mpid);
			
			if ($pd['hp'] > 0)
			{
				$log .= "你将<span class=\"yellow b\">$itm0</span>献给了<span class=\"yellow b\">{$pd['name']}</span>。<br>";
				$theitem = array('itm' => &$itm0, 'itmk' => &$itmk0, 'itme' => &$itme0,'itms' => &$itms0,'itmsk' => &$itmsk0);
				addnews($now,'senditem',$name,$pd['name'],$itm0);
				\searchmemory\searchmemory_senditem($theitem, $sdata, $pd);	
			}
			\skillbase\skill_lost(576,$sdata);			
		}
		else $chprocess();
	}
	
	//持有此技能时无法遇到辉夜
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (\skillbase\skill_query(576,$sdata) && check_unlocked576($sdata))
		{
			$mpid = \skillbase\skill_getvalue(576,'mpid',$sdata);
			if (isset($mpid) && $mpid === $sid)
			{
				eval(import_module('logger'));
				$log .= "<span class=\"yellow b\">要找的东西还没找到呢，还想要找人？</span><br>你一个人也没有碰到。<br>";
			}
			else $chprocess($sid);
		}
		else $chprocess($sid);
	}
	
	//被陷阱击杀时失去此技能
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('sys','logger','trap'));
		
		if(\skillbase\skill_query(576,$pd) && check_unlocked576($pd))
		{
			if ($pd['o_state']==27)
			{
				$log .= "<span class=\"yellow b\">随着你的死亡，「难题」的束缚烟消云散了。</span><br>";
				\skillbase\skill_lost(576,$pd);
			}
		}
		return $ret;
	}
	
	//被战斗击杀时失去此技能
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		if (\skillbase\skill_query(576,$pd) && check_unlocked576($pd))
		{		
			if ($active)
			{
				$log .= '<span class="yellow b">随着敌人的死亡，「难题」的束缚烟消云散了。</span><br>';
				$pd['battlelog'] .= '<span class="yellow b">随着敌人的死亡，「难题」的束缚烟消云散了。</span><br>';
			}
			else
			{
				$log .= '<span class="yellow b">随着你的死亡，「难题」的束缚烟消云散了。</span><br>';
				$pd['battlelog'] .= '<span class="yellow b">随着你的死亡，「难题」的束缚烟消云散了。</span><br>';
			}
			\skillbase\skill_lost(576,$pd);
		}
	}
	
}

?>