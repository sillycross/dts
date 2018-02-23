<?php

namespace skill268
{
	function init() 
	{
		define('MOD_SKILL268_INFO','club;limited;');
		eval(import_module('clubbase'));
		$clubskillname[268] = '不屈';
	}
	
	function acquire268(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//剩余次数
		\skillbase\skill_setvalue(268,'rmt',2,$pa);
	}

	function lost268(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(268,'rmt',$pa);
	}
	
	function check_unlocked268(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function apply_total_damage_modifier_insurance(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(268,$pd) && check_unlocked268($pd))
		{
			eval(import_module('logger'));
			$rmt = \skillbase\skill_getvalue(268,'rmt',$pd);
			if($rmt > 0 && $pa['dmg_dealt'] >= $pd['hp'] && $pa['dmg_dealt'] <= $pd['mhp'] / 2){
				$pa['dmg_dealt'] = $pd['hp'] - 1;
				$log .= \battle\battlelog_parser($pa,$pd,$active,'<span class="yellow">然而，<:pd_name:>靠着惊人的毅力扛住了致命的伤害！</span><br>');
				$pa['battlelog'] .= \battle\battlelog_parser($pa,$pd,1-$active,'<span class="yellow">然而，<:pd_name:>靠着惊人的毅力扛住了致命的伤害！</span><br>');
				\skillbase\skill_setvalue(268,'rmt',$rmt-1,$pd);
				$pd['skill268_flag'] = 1;
			}
		}
	}
	
	//抵御伤害的消息在伤害通告函数里实现，否则顺序有点不爽
	function player_damaged_enemy(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if(isset($pd['skill268_flag'])) {
			addnews ( 0, 'skill268_revv', $pd['name'] );
			unset($pd['skill268_flag']);
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'skill268_revv') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}凭借技能「不屈」抵御了致命的一击</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>