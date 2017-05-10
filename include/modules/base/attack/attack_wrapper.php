<?php

namespace attack
{
	//最终伤害修正接口
	//类似物理伤害修正，返回的是一个数组
	function get_final_dmg_multiplier(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return Array();
	}
	
	function apply_total_damage_modifier_up(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function apply_total_damage_modifier_down(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function apply_total_damage_change(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function apply_damage(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pd['hp']-=$pa['dmg_dealt'];
	}
	
	//伤害通告
	function player_damaged_enemy(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$dmg=$pa['dmg_dealt'];
		
		eval(import_module('logger'));
		
		//获取最终伤害修正系数，类似物理伤害修正系数，这里返回的是一个数组
		if ($dmg>0){
			$multiplier = get_final_dmg_multiplier($pa, $pd, $active);
		}else{
			$multiplier= Array();
		}
		
		if ((isset($pa['physical_dmg_dealt']) && $dmg>0 && $dmg!=$pa['physical_dmg_dealt']) 
			|| ($dmg>0 && count($multiplier)>0))	//好吧这个写法有点糟糕……
			{
				$fin_dmg=$dmg; $mult_words='';
				foreach ($multiplier as $key)
				{
					$fin_dmg=$fin_dmg*$key;
					$mult_words.="×{$key}";
				}
				$fin_dmg=round($fin_dmg);
				if ($fin_dmg < 1) $fin_dmg = 1;
				if ($mult_words=='')
					$log .= "<span class=\"yellow\">造成的总伤害：<span class=\"red\">{$dmg}</span>。</span><br>";
				else  $log .= "<span class=\"yellow\">造成的总伤害：</span>{$dmg}{$mult_words}＝<span class=\"red\">{$fin_dmg}</span><span class=\"yellow\">。</span><br>";
				$pa['dmg_dealt']=$fin_dmg;
			}
		
		//应用对总伤害的加算修正
		//先应用降低类，后应用提高类
		apply_total_damage_modifier_down($pa,$pd,$active);
		apply_total_damage_modifier_up($pa,$pd,$active);
		//最后执行变化类修正
		apply_total_damage_change($pa,$pd,$active);
		
		//扣血并更新最高伤害
		apply_damage($pa,$pd,$active);
		
		eval(import_module('sys')); 
		if (!$pa['type'] && $pa['dmg_dealt']>$hdamage)
		{
			$hdamage = $pa['dmg_dealt'];
			$hplayer = $pa['name'];
			\sys\save_combatinfo();
		}
		
		//发log
		if (!$active)
			if ($pa['is_counter'])
				$pa['battlelog'] .= "对其做出了<span class=\"yellow\">{$pa['dmg_dealt']}</span>点反击。<br>";
			else  $pa['battlelog'] .= "你对其做出<span class=\"yellow\">{$pa['dmg_dealt']}</span>点攻击，";
		else  if ($pa['is_counter'])
				$pd['battlelog'] .= "受到其<span class=\"yellow\">{$pa['dmg_dealt']}</span>点反击。<br>";
			else  $pd['battlelog'] .= "你受到其<span class=\"yellow\">{$pa['dmg_dealt']}</span>点攻击，";
			
		//发伤害新闻
		post_damage_news($pa, $pd, $active, $pa['dmg_dealt']);
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
		
		eval(import_module('sys','logger','player'));
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
		$pa['dmg_dealt']=0;
	}
	
	//攻击结束
	function attack_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		player_damaged_enemy($pa,$pd,$active);
		if ($pd['hp']<=0){
			player_kill_enemy($pa, $pd, $active);
			\player\player_save($pa);
			\player\player_save($pd);
			if ($active)
			{
				\player\load_playerdata($pa);
			}
			else
			{
				\player\load_playerdata($pd);
			}
		}
		if ($pa['hp']<=0){
			player_kill_enemy($pd, $pa, 1-$active);
			\player\player_save($pa);
			\player\player_save($pd);
			if ($active)
			{
				\player\load_playerdata($pa);
			}
			else
			{
				\player\load_playerdata($pd);
			}
		}
		unset($pa['physical_dmg_dealt']);
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
