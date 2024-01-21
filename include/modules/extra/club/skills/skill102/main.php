<?php

namespace skill102
{
	$ragecost = 70;
	
	function init()
	{
		define('MOD_SKILL102_INFO','club;battle;');
		eval(import_module('clubbase'));
		$clubskillname[102] = '驭器';
	}
	
	function acquire102(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost102(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked102(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function get_rage_cost102(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill102'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=102) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(102,$pa) || !check_unlocked102($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost102($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,102) )
			{
				eval(import_module('logger'));
				if ($active)
					$log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「驭器」！</span><br>";
				else $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「驭器」！</span><br>";
				$pa['rage']-=$rcost;
				addnews ( 0, 'bskill102', $pa['name'], $pd['name'] );
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
	
	//额外攻击力
	function get_internal_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (isset($pa['bskill']) && ($pa['bskill']==102))
		{
			$used_wep_att = array();
			for ($i=1;$i<=6;$i++)
			{
				if ((strpos($pa['itmk'.$i], 'W') === 0) && (rand(0,99) < 70))
				{
					$used_wep_att[] = $pa['itm'.$i];
					$ret += $pa['itme'.$i];
				}
			}
			if (!empty($used_wep_att))
			{
				$wep_count = count($used_wep_att);
				if ($wep_count > 1) $weptxt = implode('、', array_slice($used_wep_att, 0, $wep_count - 1)).'和'.end($used_wep_att);
				else $weptxt = $used_wep_att[0];
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"lime b\">你操控着{$weptxt}增强了武器的力量！</span><br>";
				else $log .= "<span class=\"lime b\">{$pa['name']}操控着{$weptxt}增强了武器的力量！</span><br>";
			}
		}
		return $ret;
	}
	
	//额外属性
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (isset($pa['bskill']) && ($pa['bskill']==102))
		{
			if (!isset($pa['skill102_itmn']))
			{
				$used_wep_sk = array();
				$pa['skill102_itmn'] = array();
				for ($i=1;$i<=6;$i++)
				{
					if ((strpos($pa['itmk'.$i], 'W') === 0) && !empty($pa['itmsk'.$i]) && (rand(0,99) < 30))
					{
						$used_wep_sk[] = $pa['itm'.$i];
						$pa['skill102_itmn'][] = $i;
					}
				}
				if (!empty($used_wep_sk))
				{
					$wep_count = count($used_wep_sk);
					if ($wep_count > 1) $weptxt = implode('、', array_slice($used_wep_sk, 0, $wep_count - 1)).'和'.end($used_wep_sk);
					else $weptxt = $used_wep_sk[0];
					eval(import_module('logger'));
					if ($active) $log .= "<span class=\"lime b\">你让{$weptxt}的光芒附着在了你的武器上！</span><br>";
					else $log .= "<span class=\"lime b\">{$pa['name']}让{$weptxt}的光芒附着在了武器上！</span><br>";
				}
			}
			if (!empty($pa['skill102_itmn']))
			{
				foreach ($pa['skill102_itmn'] as $n)
				{
					if ((strpos($pa['itmk'.$n], 'W') === 0) && !empty($pa['itmsk'.$n]))
					{
						$ret = array_merge($ret,\itemmain\get_itmsk_array($pa['itmsk'.$n]));
					}
				}
			}
		}
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill102')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「驭器」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>