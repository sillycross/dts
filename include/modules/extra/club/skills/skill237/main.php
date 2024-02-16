<?php

namespace skill237
{
	//怒气消耗
	$ragecost = 50; 
	
	//眩晕时间（单位毫秒）
	$stuntime237 = 2000;
	
	//CD延长时间（单位秒）
	$cdtime237 = 40;
	
	function init() 
	{
		define('MOD_SKILL237_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[237] = 'EMP';
	}
	
	function acquire237(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost237(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked237(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=11;
	}
	
	function get_rage_cost237(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill237'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=237) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(237,$pa) || !check_unlocked237($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost237($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,237) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「EMP」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「EMP」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill237', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log.='怒气不足或其他原因不能发动。<br>';
				}
				$pa['bskill']=0;
			}
		}
		$chprocess($pa, $pd, $active);
	}	
	
	//变化阶段，如果有需要最后变化物理伤害的技能请继承这里
	function get_physical_dmg_change(&$pa, &$pd, $active, $dmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $dmg);
		if ($pa['bskill']==237) 
		{
			eval(import_module('logger'));
			$log .=  \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b"><:pa_name:>将武器的伤害转化成了电磁干扰攻击！</span><br>');
			$ret = 0;
		}
		return $ret;
	}
	
	//跳过整个物理伤害判定
//	function calculate_physical_dmg(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('logger'));
//		
//		if ($pa['bskill']==237) 
//		{
//			eval(import_module('logger'));
//			$log .=  \battle\battlelog_parser($pa, $pd, $active, '<span class="yellow b"><:pa_name:>将武器的伤害转化成了电磁干扰攻击！</span><br>');
//		}
//		else return $chprocess($pa, $pd, $active);
//	}
	
//	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$r=Array();
//		if ($pa['bskill']==237) 
//		{
//			eval(import_module('logger'));
//			$r=Array(1.3);
//			if ($active)
//				$log.='<span class="yellow b">你借用武器施展出了电磁干扰攻击！</span><br>';
//			else  $log.='<span class="yellow b">敌人借用武器施展出了电磁干扰攻击！</span><br>';
//		}
//		return array_merge($r,$chprocess($pa, $pd, $active));
//	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==237 && $pa['is_hit'])
		{
			eval(import_module('logger','skill237','skill601','sys'));
			if (!\skillbase\skill_query(601,$pd)){
				\skillbase\skill_acquire(601,$pd);
				$var_237=$now;
			}else{
				$var_237=\skillbase\skill_getvalue(601,'end',$pd);
				if ($var_237<$now) $var_237=$now;
			}
			\skillbase\skill_setvalue(601,'start',$var_237,$pd);
			\skillbase\skill_setvalue(601,'end',$var_237 + $cdtime237,$pd);
			\skill602\set_stun_period($stuntime237,$pd);
			\skill602\send_stun_battle_news($pa['name'],$pd['name']);
		}
		$chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill237') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「EMP」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>