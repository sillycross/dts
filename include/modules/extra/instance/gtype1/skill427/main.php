<?php

namespace skill427
{
	//不能续命的NPC
	$skill427ignorelist = array(1, 16, 21, 88);
	
	function init() 
	{
		define('MOD_SKILL427_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[427] = '续命';
	}
	
	function acquire427(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function lost427(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked427(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		if(\skillbase\skill_query(427,$pd) && check_unlocked427($pd)){
			$pd['revive_sequence'][40] = 'skill427';
		}
		return;
	}	
	
	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('skill427' == $rkey && in_array($pd['state'],Array(20,21,22,23,24,25,27,29,39,40,41))){
			eval(import_module('skill427'));
			if(!in_array($pa['type'],$skill427ignorelist)) {
				$ret = true;
			}else{
				$pd['skill427ignore'] = 1;
			}
		}
		return $ret;
	}
	
	//回满血，发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('skill427' == $rkey){
			$pd['hp']=$pd['mhp'];
			$pd['skill427_flag']=1;
			addnews ( 0, 'revival427', $pd['name']);
			
			//死亡之后除错层数-2
			if (\skillbase\skill_query(424,$pd)){
				$clv=\skillbase\skill_getvalue(424,'lvl',$pd); 
				$clv=$clv-2;
				if ($clv<0) $clv=0;
				\skillbase\skill_setvalue(424,'lvl',$clv,$pd); 
			}
			//无技能时陷阱杀人得技能点已被废除
		}
		return;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('sys','logger'));
		
		if(!empty($pd['skill427_flag'])){
			if ($pd['o_state']==27)	//陷阱
			{
				$log.= '<br>后台监工的声音响起：<span class="evergreen">“不准死，你还没有找完BUG呢。”</span><br><span class="lime">你原地满血复活了！</span><br>';
				if(!$pd['sourceless']){
					$w_log = '<span class="lime">'.$pd['name'].'原地满血复活了！</span><br>';
					\logger\logsave ( $pa['pid'], $now, $w_log ,'b');
				}
			}
		}elseif(!empty($pd['skill427ignore'])){
			$log.= "后台监工的声音响起：<span class=\"linen\">“人作死，就会死……快去死吧。”</span><br>";
		}
		return $ret;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		if (isset($pd['skill427_flag']) && $pd['skill427_flag'])
		{
			if ($active)
			{
				$log.='<span class="lime">敌人原地满血复活了！</span><br>';
				$pd['battlelog'].='后台监工的声音响起：<span class="evergreen">“不准死，你还没有找完BUG呢。”</span><span class="lime">你原地满血复活了！</span><br>';
			}
			else
			{
				$log.='后台监工的声音响起：<span class="evergreen">“不准死，你还没有找完BUG呢。”</span><span class="lime">你原地满血复活了！</span><br>';
				$pd['battlelog'].='<span class="lime">敌人原地满血复活了！</span><br>';
			}
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'revival427') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}还没有完成需求，不得不原地满血复活！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>