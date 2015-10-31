<?php

namespace skill26
{
	//升级所需技能点数值
	$upgradecost = 10;
	//怒气消耗
	$ragecost[1] = 20; $ragecost[2] = 25;
	
	function init() 
	{
		define('MOD_SKILL26_INFO','club;battle;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[26] = '聚能';
	}
	
	function acquire26(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(26,'lvl','1',$pa);
	}
	
	function lost26(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(26,'lvl',$pa);
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked26(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function get_rage_cost26(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill26'));
		return $ragecost[\skillbase\skill_getvalue(26,'lvl',$pa)];
	}
	
	//聚能执行机理：
	//物理伤害部分：
	//接管get_ex_attack_array让其只返回火焰（保证属抹可以判定）
	//接管calculate_physical_dmg
	//调用属抹判断check_ex_dmg_nullify
	//停止接管get_ex_attack_array（用标记实现）
	//如果属抹成功，直接输出结束
	//因为这时武器输出的是火焰伤害，任何物理伤害加减成均失效，但属性伤害加减成均有效
	//先调用calculate_ex_single_dmg_multiple计算属性加成
	//然后调用get_physical_dmg计算物理伤害
	//最后相乘拼出总物理伤害
	//属性伤害部分：
	//接管get_ex_attack_array，令其不返回属抹，然后如有防火属性，改为属性防御属性
	//接管check_ex_dmg_nullify，令其跳过判断（只是保险，防止以后有人又搞出个强制属抹的属性然后忘了加可选依赖确定次序）
	//这样当calculate_ex_attack_dmg被正常调用的时候生成的就是正确的判定了
	
	function check_ex_inf_infliction(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active,$key);
		if ($pa['skill26_flag3']==1) return;
		$chprocess($pa, $pd, $active, $key);
	}
	
	function calculate_ex_inf_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active, $key);
		$attack_type = 'u';
		if (\skillbase\skill_getvalue(26,'lvl',$pa)==2) $attack_type = 'f';
		if ($pa['skill26_flag3']==1) return $chprocess($pa, $pd, $active, $attack_type);
		return $chprocess($pa, $pd, $active, $key);
	}
	
	function check_ex_single_dmg_def_attr(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active, $key);
		$attack_type = 'u';
		if (\skillbase\skill_getvalue(26,'lvl',$pa)==2) $attack_type = 'f';
		if ($pa['skill26_flag3']==1) return $chprocess($pa, $pd, $active, $attack_type);
		return $chprocess($pa, $pd, $active, $key);
	}
	
	function get_ex_attack_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active);
		if ($pa['skill26_flag2']==1) 
		{
			$ret = $chprocess($pa, $pd, $active); array_push($ret,'u');
			return $ret;
		}
		return $chprocess($pa, $pd, $active);
	}
	
	function check_ex_dmg_nullify(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active);
		if ($pa['skill26_flag1']==2) return $pd['exdmg_nullify_success'];
		return $chprocess($pa, $pd, $active);
	}
	
	function calculate_physical_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active);
		
		eval(import_module('logger','itemmain'));
		$attack_type = 'u';
		if (\skillbase\skill_getvalue(26,'lvl',$pa)==2) $attack_type = 'f';	//2级时是灼焰
		$log .= '在技能的作用下，伤害全部转化为了<span class="red">'.$itemspkinfo[$attack_type].'</span>伤害！<br>';
		
		//连击和灵系体力是唯一需要考虑的特殊“物理伤害加成”
		//（因为这不是加成，只是被做到加成里去了……）
		
		$r = \wep_f\get_WF_dmg_multiplier($pa, $pd, $active);
		$r = array_merge($r,\ex_rapid_attr\get_rapid_dmg_multiplier($pa, $pd, $active));
		
		//判断属抹
		$flag=check_ex_dmg_nullify($pa, $pd, $active);
		
		$pa['skill26_flag1'] = 2;	//跳过之后的属抹判断
		$pa['skill26_flag2'] = 2;	//攻击属性判断开始正常返回
		
		if ($flag)
		{
			$log .= "<span class=\"red\">属性攻击的力量完全被防具吸收了！</span>只造成了<span class=\"red\">".round($zdmg)."</span>点伤害！<br>";
			$pa['physical_dmg_dealt'] += $pa['ex_dmg_dealt'];
			$pa['dmg_dealt'] += $pa['ex_dmg_dealt'];
			$pa['ex_dmg_dealt'] = 0;
			return;
		}
		
		$multiple = \ex_dmg_att\calculate_ex_single_dmg_multiple($pa, $pd, $active, $attack_type);
		$dmg = \weapon\get_physical_dmg($pa, $pd, $active);
		$dmg *= $multiple; $dmg = round($dmg); if ($dmg<1) $dmg = 1;
		eval(import_module('ex_dmg_att'));
		
		$fin_dmg = $dmg; $mult_words='';
		foreach ($r as $key)
		{
			$fin_dmg=$fin_dmg*$key;
			$mult_words.="×{$key}";
		}
		$fin_dmg=round($fin_dmg);
		if ($fin_dmg < 1) $fin_dmg = 1;

		if ($mult_words=='')
		{
			$log.="武器攻击造成了<span class=\"red\">{$dmg}</span>点".$itemspkinfo[$attack_type]."伤害！<br>";
		}
		else
		{
			$tdmg = round($dmg * $zdmg);
			$log.="武器攻击造成了{$dmg}{$mult_words}＝<span class=\"red\">{$fin_dmg}</span>点".$itemspkinfo[$attack_type]."伤害！<br>";
			$dmg = $tdmg;
		}
		$pa['physical_dmg_dealt'] += $fin_dmg;
		$pa['dmg_dealt'] += $fin_dmg;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(26,$pa) || !check_unlocked26($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost26($pa);
			if ($pa['rage']>=$rcost)
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime\">你对{$pd['name']}发动了技能「聚能」！</span><br>";
				else  $log.="<span class=\"lime\">{$pa['name']}对你发动了技能「聚能」！</span><br>";
				$pa['rage']-=$rcost;
				$pa['skill26_flag1']=1;
				$pa['skill26_flag2']=1;
				$pa['skill26_flag3']=1;
				$pa['skill26_flag4']=1;
				addnews ( 0, 'bskill26', $pa['name'], $pd['name'] );
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log.='怒气不足。<br>';
				}
				$pa['bskill']=0;
			}
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active);
		if ($pa['is_hit'])
		{
			//进行一次火焰受伤判定
			$pa['skill26_flag3']=2;
			$attack_type = 'u';
			if (\skillbase\skill_getvalue(26,'lvl',$pa)==2) $attack_type = 'f';	
			\ex_dmg_att\check_ex_inf_infliction($pa, $pd, $active, $attack_type);
		}
		unset($pa['skill26_flag1']);
		unset($pa['skill26_flag2']);
		unset($pa['skill26_flag3']);
		unset($pa['skill26_flag4']);
		$chprocess($pa, $pd, $active);
	}
	
	function upgrade26()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill26','player','logger'));
		if (!\skillbase\skill_query(26))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(26,'lvl');
		if ($clv == 2)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<$upgradecost) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint-=$upgradecost; \skillbase\skill_setvalue(26,'lvl',2);
		$log.='升级成功。<br>';
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill26') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}对{$b}发动了技能<span class=\"yellow\">「聚能」</span></span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
	
}

?>
