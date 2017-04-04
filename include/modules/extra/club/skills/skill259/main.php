<?php

namespace skill259
{
	//附加伤害比例
	$adddmg = array(5,4,3);
	//升级所需技能点数值
	$upgradecost = array(3,3,-1);
	//怒气消耗
	$ragecost = 20;
	
	function init() 
	{
		define('MOD_SKILL259_INFO','club;battle;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[259] = '乱击';
	}
	
	function acquire259(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(259,'lvl','0',$pa);
	}
	
	function lost259(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(259,'lvl',$pa);
	}
	
	function check_unlocked259(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=5;
	}
	
	function get_rage_cost259(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill259'));
		return $ragecost;
	}
	
	function upgrade259()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill259','player','logger'));
		if (!\skillbase\skill_query(259))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(259,'lvl');
		$ucost = $upgradecost[$clv];
		if ($clv == -1)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<$ucost) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint-=$ucost; \skillbase\skill_setvalue(259,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=259) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(259,$pa) || !check_unlocked259($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost259($pa);
			if (($pa['wepk']=="WN")&&($pa['rage']>=$rcost))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime\">你对{$pd['name']}发动了技能「乱击」！</span><br>";
				else  $log.="<span class=\"lime\">{$pa['name']}对你发动了技能「乱击」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill259', $pa['name'], $pd['name'] );
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
	
	function get_skill259_adddmg(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill259'));
		if (\skillbase\skill_query(259,$pa) && check_unlocked259($pa) && $pa['wepk']=="WN"){
			$l259=\skillbase\skill_getvalue(259,'lvl');
			if ($adddmg[$l259]<=0) return 0;
			return 1+floor($pa['wp']/$adddmg[$l259]);
		}
		return 0;
	}
	
	function get_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','skill259'));
		if ($pa['bskill']!=259) return $chprocess($pa,$pd,$active);
		if ($active) $log .= "<span class=\"red\">你对着敌人打出了一屏幕的拳头！</span><br>";
			else $log .= "<span class=\"red\">敌人对着你打出了一屏幕的拳头！</span><br>";
		$r259=get_skill259_adddmg($pa);
		return 1+$r259+$chprocess($pa, $pd, $active);
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill259') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}对{$b}发动了技能<span class=\"yellow\">「乱击」</span></span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
	
}

?>
