<?php

namespace skill223
{

	function init() 
	{
		define('MOD_SKILL223_INFO','club;battle;limited;');
		eval(import_module('clubbase'));
		$clubskillname[223] = '暗杀';
	}
	
	function acquire223(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(223,'rmtime','2',$pa);
	}
	
	function lost223(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked223(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=20;
	}
	
	function get_remaintime223(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return \skillbase\skill_getvalue(223,'rmtime',$pa);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=223) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(223,$pa) || !check_unlocked223($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$remtime = (int)get_remaintime223($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,223) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「暗杀」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「暗杀」！</span><br>";
				$remtime--; 
				\skillbase\skill_setvalue(223,'rmtime',$remtime,$pa);
				addnews ( 0, 'bskill223', $pa['name'], $pd['name'] );
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
	
	function get_hitrate_change(&$pa,&$pd,$active,$hitrate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=223) return $chprocess($pa, $pd, $active,$hitrate);
		return 10000;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ($pa['bskill']==223) 
		{
			eval(import_module('logger'));
			if ($pa['card']==5){
				$var_223=100;
			}else{
				$var_223=50;
			}
			if (isset($pd['original_inf'])){
				$var_223+=(50*strlen($pd['original_inf']));
			}
			if ($active)
				$log.="<span class=\"yellow b\">「暗杀」使你造成的最终伤害提高了{$var_223}%！</span><br>";
			else  $log.="<span class=\"yellow b\">「暗杀」使敌人造成的最终伤害提高了{$var_223}！</span><br>";
			$var_223=($var_223+100)/100;
			$r=Array($var_223);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill223') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"red b\">「暗杀」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
