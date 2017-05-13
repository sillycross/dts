<?php

namespace skill244
{

	$ragecost=70;
	
	function init() 
	{
		define('MOD_SKILL244_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[244] = '归约';
	}
	
	function acquire244(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost244(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked244(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=19;
	}
	
	function get_rage_cost244(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill244'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=244) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(244,$pa) || !check_unlocked244($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost244($pa);
			if ($pa['rage']>=$rcost)
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime\">你对{$pd['name']}发动了技能「归约」！</span><br>";
				else  $log.="<span class=\"lime\">{$pa['name']}对你发动了技能「归约」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill244', $pa['name'], $pd['name'] );
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
	
	function sk244_get_factor_sum($z)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($z>1e11) return $z;	
		$z=(int)$z; $x=2; $ret=1;
		if ($z==0) return $z;
		while ($x<=$z/$x+2)
		{
			if ($z%$x==0)
			{
				$t=1; $s=$x;
				while ($z%$x==0)
				{
					$t+=$s; $s*=$x; $z/=$x; 
				}
				$ret*=$t;
			}
			$x++;
		}
		if ($z>1) $ret*=(1+$z);
		return $ret;
	}
	
	function apply_total_damage_modifier_up(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if ($pa['bskill']==244 && $pa['dmg_dealt']>0)
		{
			$pa['dmg_dealt']=sk244_get_factor_sum($pa['dmg_dealt']);
			$log.='<span class="yellow">「归约」使最终伤害变为'.$pa['dmg_dealt'].'点！</span><br>';
		}
		$chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill244') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}对{$b}发动了技能<span class=\"yellow\">「归约」</span></span></li>\n";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
