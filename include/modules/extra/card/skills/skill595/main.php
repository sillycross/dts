<?php

namespace skill595
{
	$ragecost = 80;
	$skill595_phydmg_gain = 20;
	$skill595_stuntime = 2000;
	//依次为晕眩、头部受伤、经验值增加、全系熟练度增加
	$skill595_rate = array(70,60,10,10);
	
	function init() 
	{
		define('MOD_SKILL595_INFO','card;battle;');
		eval(import_module('clubbase'));
		$clubskillname[595] = '劝学';
	}
	
	function acquire595(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost595(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked595(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl'] >= 10;
	}	

	function get_rage_cost595(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill595'));
		return $ragecost;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 595) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(595,$pa) || !check_unlocked595($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill'] = 0;
		}
		else
		{
			$rcost = get_rage_cost595($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,595))
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"lime b\">你对{$pd['name']}发动了技能「劝学」！</span><br>";
				else $log .= "<span class=\"lime b\">{$pa['name']}对你发动了技能「劝学」！</span><br>";
				$pa['rage'] -= $rcost;
				addnews ( 0, 'bskill595', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log .= '怒气不足或其他原因不能发动。<br>';
				}
				$pa['bskill'] = 0;
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if ($pa['bskill'] == 595)
		{
			eval(import_module('logger','skill595'));
			$r = array((1+$skill595_phydmg_gain/100));
			if ($active) $log .= "<span class=\"yellow b\">你用头槌撞向了{$pd['name']}！</span><br>";
			else $log .= "<span class=\"yellow b\">{$pd['name']}用头槌撞向了你！</span><br>";
		}
		return array_merge($r,$chprocess($pa, $pd, $active));
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==595 && $pa['is_hit'])
		{
			eval(import_module('logger','skill595'));
			$sk595_flag = 0;
			//晕眩2秒
			if (rand(0,99) < $skill595_rate[0])
			{
				$sk595_flag = 1;
				\skill602\set_stun_period($skill595_stuntime, $pd);
				\skill602\send_stun_battle_news($pa['name'],$pd['name']);
			}
			//头部受伤
			if (rand(0,99) < $skill595_rate[1])
			{
				$sk595_flag = 1;
				if (strpos($pd['inf'],'h')===false) $pd['inf'] .= 'h';
			}
			//经验增加
			if (rand(0,99) < $skill595_rate[2])
			{
				$sk595_flag = 1;
				//冰雪聪明的彩蛋
				if (isset($pd['card']) && ($pd['card'] == 299)) $expup = 99;
				else $expup = rand(5,15);
				//挨打就不判定升级了
				$pd['exp'] += $expup;
			}
			//全熟增加
			if (rand(0,99) < $skill595_rate[3])
			{
				$sk595_flag = 1;
				if (isset($pd['card']) && ($pd['card'] == 299)) $skillup = 99;
				else $skillup = rand(10,20);
				$pd['wp'] += $skillup;
				$pd['wk'] += $skillup;
				$pd['wg'] += $skillup;
				$pd['wc'] += $skillup;
				$pd['wf'] += $skillup;
				$pd['wd'] += $skillup;
			}
			if ($sk595_flag == 1)
			{
				if ($active) $log .= '<span class="yellow b">敌人被猛烈的头槌砸得开了窍！</span><br>';
				else $log .= '<span class="yellow b">你被猛烈的头槌砸得开了窍！</span><br>';
			}
		}
		$chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'bskill595')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「劝学」</span></span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}

}

?>