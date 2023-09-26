<?php

namespace skill222
{

	$ragecost=50;
	
	function init() 
	{
		define('MOD_SKILL222_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[222] = '催化';
	}
	
	function acquire222(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost222(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked222(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=12;
	}
	
	function get_rage_cost222(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill222'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=222) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(222,$pa) || !check_unlocked222($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost222($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,222) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「催化」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「催化」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill222', $pa['name'], $pd['name'] );
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
	
	function ex_attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==222) 
		{
			$ex_att_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
			if (\attrbase\check_in_itmsk('p', $ex_att_array))
			{
				eval(import_module('logger'));
				if ($active)
					$log.='<span class="yellow b">「催化」使你造成的毒性伤害提高了50%！</span><br>';
				else  $log.='<span class="yellow b">「催化」使敌人造成的毒性伤害提高了50%！</span><br>';
			}
		}
		return $chprocess($pa, $pd, $active);
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (($key=='p')&&($pa['bskill']==222)) 
		{
			return $chprocess($pa, $pd, $active, $key)*1.5;
		}
		return $chprocess($pa, $pd, $active, $key);
	}

	function get_skill221_lasttime(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==222) return 3*$chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill222') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「催化」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
