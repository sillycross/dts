<?php

namespace skill489
{	
	function init() 
	{
		define('MOD_SKILL489_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[489] = '神眷';
	}
	
	function acquire489(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost489(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked489(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function check_ex_inf_infliction(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys', 'player', 'wound', 'logger'));
		
		if(\skillbase\skill_query(489, $pd)) {
			$o_pd_inf = array();
			foreach($infskillinfo as $ik => $iv){
				if($iv > 4 && (\skillbase\skill_query($iv, $pd) || strpos($pd['inf'], $ik)!==false)){//兼容性判定
					$o_pd_inf[] = $ik;
				}
			}
		}
		$chprocess($pa, $pd, $active, $key);
		if(\skillbase\skill_query(489, $pd)){
			$pd_inf_diff = array();
			foreach($infskillinfo as $ik => $iv){//受异常状态以后才判定是否反弹
				if($iv > 4 && (\skillbase\skill_query($iv, $pd) || strpos($pd['inf'], $ik)!==false) && !in_array($ik, $o_pd_inf)){
					$pd_inf_diff[] = $ik;
				}
			}
			if(!empty($pd_inf_diff)){
				$inf_log = '';
				foreach($pd_inf_diff as $ik){
					eval(import_module('skill'.$infskillinfo[$ik]));
					$inf_log .= $infname[$ik].'_';
					
					\skillbase\skill_lost('skill'.$infskillinfo[$ik], $pd);
					$pd['inf'] = str_replace($ik,'', $pd['inf']);
					\wound\get_inf($ik,$pa);
				}
				$inf_log = str_replace('_', '、', substr($inf_log,0,-1));
				$log .= \battle\battlelog_parser($pa, $pd, $active, '然而，<span class="yellow">「神眷」</span>使<:pd_name:>将'.$inf_log.'<span class="yellow">反弹给了<:pa_name:>！</span>');
				$pa['battlelog'].=\battle\battlelog_parser($pa, $pd, 1-$active, '<pa:name>你的攻击使<:pd_name:>'.$inf_log.'了，却被<:pd_name:><span class="yellow">反弹了回来！</span>');
				addnews($now,'skill489_reflec',$pd['name'],$pa['name'],$inf_log);
			}
		}
	}
	
	function deal_poison_move_damage($damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(489)) $chprocess($damage);
		else{
			eval(import_module('logger'));
			$log .= "你耳边传来一阵女声的低语：“虔信神的人<span class=\"lime\">不会受到<span class=\"purple\">毒</span>的侵害</span>。”<br>";
		}
	}
	
	function deal_burn_move_damage($damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(489)) $chprocess($damage);
		else{
			eval(import_module('logger'));
			$log .= "你耳边传来一阵女声的低语：“虔信神的人<span class=\"lime\">不会遭遇<span class=\"red\">炎</span>的审判</span>。”<br>";
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'skill489_reflec') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}使用「神眷」技能，将{$c}反弹给了{$b}！</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>