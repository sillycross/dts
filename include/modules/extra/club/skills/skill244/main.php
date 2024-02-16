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
		if ($pa['bskill']!=244) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(244,$pa) || !check_unlocked244($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost244($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,244) )
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「归约」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「归约」！</span><br>";
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
	
	function sk244_get_factor_sum(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$z = $pa['dmg_dealt'];
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
	
	//特殊变化次序注册
	function apply_total_damage_modifier_special_set_sequence(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ($pa['bskill']==244 && \skillbase\skill_query(244,$pa) && check_unlocked244($pa)) 
			$pd['atdms_sequence'][50] = 'skill244';
		return;
	}
	
	//特殊变化生效判定，建议采用或的逻辑关系
	function apply_total_damage_modifier_special_check(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $akey);
		if('skill244' == $akey && $pa['dmg_dealt'] > 0) $ret = 1;
		return $ret;
	}
	
	//特殊变化执行
	function apply_total_damage_modifier_special_core(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd, $active, $akey);
		if('skill244' == $akey){
			$pa['dmg_dealt']=sk244_get_factor_sum($pa);
			eval(import_module('logger'));
			$log.='<span class="yellow b">「归约」使最终伤害变为'.$pa['dmg_dealt'].'点！</span><br>';
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill244') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「归约」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
