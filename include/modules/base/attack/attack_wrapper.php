<?php

namespace attack
{
	//伤害通告
	function player_damaged_enemy(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$pd['hp']-=$pa['dmg_dealt'];
		
		eval(import_module('sys')); 
		if (!$pa['type'] && $pa['dmg_dealt']>$hdamage)
		{
			$hdamage = $pa['dmg_dealt'];
			$hplayer = $pa['name'];
			\sys\save_combatinfo();
		}
		
		$dmg=$pa['dmg_dealt'];
		
		eval(import_module('logger'));
		
		if (isset($pa['physical_dmg_dealt']) && $dmg>0 && $dmg!=$pa['physical_dmg_dealt']) //好吧这个写法有点糟糕……
			$log .= "<span class=\"yellow\">造成的总伤害：<span class=\"red\">{$dmg}</span>。</span><br>";
		
		if (!$active)
			if ($pa['is_counter'])
				$pa['battlelog'] .= "对其做出了<span class=\"yellow\">{$dmg}</span>点反击。<br>";
			else  $pa['battlelog'] .= "你对其做出<span class=\"yellow\">{$dmg}</span>点攻击，";
		else  if ($pa['is_counter'])
				$pd['battlelog'] .= "受到其<span class=\"yellow\">{$dmg}</span>点反击。<br>";
			else  $pd['battlelog'] .= "你受到其<span class=\"yellow\">{$dmg}</span>点攻击，";
			
		//发伤害新闻
		post_damage_news($pa, $pd, $active);
	}
	
	//攻击/反击通告
	function player_attack_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('logger'));
		if ($active)
			if ($pa['is_counter'])
			{
				$log .= "<span class=\"red\">你的反击！</span><br>";
			}
			else
			{
				$log .= "你向<span class=\"red\">{$pd['name']}</span>发起了攻击！<br>";
				$pd['battlelog'] .= "手持<span class=\"red\">{$pa['wep']}</span>的<span class=\"yellow\">{$pa['name']}</span>向你袭击！";
			}
		else  if ($pa['is_counter'])
			{
				$log .= "<span class=\"red\">{$pa['name']}的反击！</span><br>";
			}
			else
			{
				$log .= "<span class=\"red\">{$pa['name']}</span>突然向你袭来！<br>";
				$pa['battlelog'] .= "你发现了手持<span class=\"red\">{$pd['wep']}</span>的<span class=\"yellow\">{$pd['name']}</span>并且先发制人！";
			}
	}
	
	//击杀通告
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger'));
		if ($active)
		{
			$log .= "<span class=\"yellow\">{$pd['name']}</span><span class=\"red\">被你杀死了！</span><br>";
			$pd['battlelog'] .= "<span class=\"red\">你被{$pa['name']}杀死了！</span><br>";
		}
		else
		{
			$log .= "<span class=\"red\">你被{$pa['name']}杀死了！</span><br>";
			$pa['battlelog'] .= "<span class=\"yellow\">{$pd['name']}</span><span class=\"red\">被你杀死了！</span><br>";
		}
		
		$pd['state']=$pd['deathmark'];
		
		$kilmsg = \player\kill($pa, $pd);
		
		if ($active)
		{
			if ($kilmsg!='') $log.="<span class=\"yellow\">你对{$pd['name']}说：“{$kilmsg}”</span><br>";
		}
		else
		{
			if ($kilmsg!='') $log.="<span class=\"yellow\">{$pa['name']}对你说：“{$kilmsg}”</span><br>";
		}
	}
	
	//当玩家主动发起攻击时，加载玩家提供的攻击参数
	function load_user_combat_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//加载默认攻击参数
	function load_auto_combat_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//攻击准备，发通告，加载攻击参数
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		player_attack_enemy($pa, $pd, $active);
		if ($pa['user_commanded'])
			load_user_combat_command($pa);
		else  load_auto_combat_command($pa);
		load_auto_combat_command($pd);
	}
	
	//攻击结束
	function attack_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		player_damaged_enemy($pa,$pd,$active);
		if ($pd['hp']<=0) player_kill_enemy($pa, $pd, $active);
	}
	
	function attack_wrapper(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		attack_prepare($pa, $pd, $active);
		attack($pa,$pd,$active);
		attack_finish($pa,$pd,$active);		
	}
}

?>
