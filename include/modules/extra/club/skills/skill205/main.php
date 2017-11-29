<?php

namespace skill205
{

	$ragecost=75;
	
	$alternate_skillno205 = 265;//互斥技能编号
	$u_lvl205 = 15;//解锁等级
	
	function init() 
	{
		define('MOD_SKILL205_INFO','club;battle;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[205] = '咆哮';
	}

	function acquire205(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(205,'unlocked','0',$pa);	//是否已经被解锁
	}
	
	function unlock205(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(205,'unlocked','1',$pa);
	}
	
	function lost205(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked205(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(check_unlocked_state205($pa) > 0) return 0;
		else return 1;
	}
	
	//0 解锁 1 等级不够 2 存在互斥技能且尚未选择 4 互斥技能解锁
	function check_unlocked_state205(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill205'));
		$ret = 0;
		if($pa['lvl'] < $u_lvl205) $ret += 1;
		if(\skillbase\skill_query($alternate_skillno205, $pa)){
			if(\skillbase\skill_getvalue(205,'unlocked',$pa)==0 ) $ret += 2;
			if(\skillbase\skill_getvalue($alternate_skillno205,'unlocked',$pa)>0) $ret += 4;
		}
		return $ret;
	}
	
	function get_rage_cost205(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill205'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=205) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(205,$pa) || !check_unlocked205($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost205($pa);
			if (($pa['rage']>=$rcost)&&($pa['wep_kind']=="G"))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime\">你对{$pd['name']}发动了技能「咆哮」！</span><br>";
				else  $log.="<span class=\"lime\">{$pa['name']}对你发动了技能「咆哮」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill205', $pa['name'], $pd['name'] );
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
	
	function upgrade205()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill205','player','logger','clubbase'));
		if (!\skillbase\skill_query(205))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (check_unlocked205($sdata))
		{
			$log .= '你已经选择了这个技能<br>';
			return;
		}

		\skillbase\skill_setvalue(205,'unlocked',1);
		
		$log.='技能「'.$clubskillname[205].'」选择成功。<br>';
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ($pa['bskill']==205) 
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="yellow">「咆哮」使你造成的物理伤害提高了20%！</span><br>';
			else  $log.='<span class="yellow">「咆哮」使敌人造成的物理伤害提高了20%！</span><br>';
			$r=Array(1.2);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function calculate_ex_attack_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==205) 
		{
			if (count(\attrbase\get_ex_attack_array($pa,$pd,$active))>0)
			{
				eval(import_module('logger'));
				if ($active)
					$log.='<span class="yellow">「咆哮」使你造成的属性伤害提高了80%！</span><br>';
				else  $log.='<span class="yellow">「咆哮」使敌人造成的属性伤害提高了80%！</span><br>';
			}
		}
		return $chprocess($pa, $pd, $active);
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==205) 
		{
			return $chprocess($pa, $pd, $active, $key)*1.8;
		}
		return $chprocess($pa, $pd, $active, $key);
	}
	
	function calculate_weapon_wound_multiplier(&$pa, &$pd, $active, $hurtposition) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=205) return $chprocess($pa, $pd, $active, $hurtposition);
		return $chprocess($pa, $pd, $active, $hurtposition)*2;
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill205') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}对{$b}发动了技能<span class=\"yellow\">「咆哮」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
