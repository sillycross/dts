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
	
	function get_skill26_type(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$attack_type = 'u';
		if (\skillbase\skill_getvalue(26,'lvl',$pa)==2) $attack_type = 'f';
		return $attack_type;
	}
	
	//聚能执行机理：
	//物理伤害部分：
	//接管get_ex_attack_array让其只返回火焰（保证属抹可以判定）
	//调用属抹判断check_ex_dmg_nullify，如果属抹成功，直接结束判定
	//接管calculate_physical_dmg（物理伤害函数）
	//因为这时武器输出的是火焰伤害，任何物理伤害加减成均失效，但属性伤害加减成均有效
	//先调用get_physical_dmg计算物理伤害的武器基础值
	//如果是灵系或者连击武器，判定这两项的加成系数。其他加成不判定
	//然后调用calculate_ex_single_dmg，把武器伤害基础值当做属性基础值传入，正常判定火焰/灼焰的伤害加成和修正
	//然后乘上灵系和连击的加成系数，拼出总物理伤害
	//停止接管get_ex_attack_array好让各属性攻击分别判定而不是只判定1次火焰（用标记实现）
	
	//属性伤害部分：
	//接管check_ex_dmg_nullify，令其跳过判断，直接使用物理伤害时确定的值（只是保险，防止以后有人又搞出个强制属抹的属性然后忘了加可选依赖确定次序）
	//由于get_ex_attack_array正常返回武器的各个攻击属性，冻气之类非火焰伤害的基础值是正常计算的
	//但是接管了ex_attack_key_change函数，令这些属性在加成和修正的时候按火焰/灼焰进行修正
	//这个阶段不会进行任何致伤判定，致伤在攻击结束时统一判定
	//这样当calculate_ex_attack_dmg被正常调用的时候生成的就是正确的判定了
	
	function check_ex_inf_infliction(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) {
			$chprocess($pa, $pd, $active,$key);
			return;
		}
		if ($pa['skill26_flag3']==1) return;
		$chprocess($pa, $pd, $active, $key);
	}
	
	function calculate_ex_inf_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active, $key);
		$attack_type =  get_skill26_type($pa, $pd, $active);
		if ($pa['skill26_flag3']==1) return $chprocess($pa, $pd, $active, $attack_type);
		return $chprocess($pa, $pd, $active, $key);
	}
	
	function check_ex_single_dmg_def_attr(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active, $key);
		$attack_type =  get_skill26_type($pa, $pd, $active);
		if ($pa['skill26_flag3']==1) return $chprocess($pa, $pd, $active, $attack_type);
		return $chprocess($pa, $pd, $active, $key);
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if ($pa['bskill']!=26) return $ret;
		if (!empty($pa['skill26_flag2']) && $pa['skill26_flag2']==1) 
		{
			$attack_type =  get_skill26_type($pa, $pd, $active);
			array_push($ret,$attack_type);
			return $ret;
		}
		return $ret;
	}
	
	function check_attr_pierce_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==26 && $pa['skill26_flag1']==2) return $pa['attr_pierce_success'];
		$chprocess($pa, $pd, $active);
	}
	
	function check_ex_dmg_nullify(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']==26 && $pa['skill26_flag1']==2) return $pd['exdmg_nullify_success'];
		return $chprocess($pa, $pd, $active);
	}
	
	//聚能物理伤害时无视掉ex_single_dmg的提示
	function showlog_ex_single_dmg(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','ex_dmg_att'));
		if ($pa['bskill']==26 && $pa['skill26_flag2'] == 1)  return;
		$chprocess($pa, $pd, $active, $key);
	}
	
	//是否在执行伤害加成值和修正值时转化属性？聚能之类伤害使用
	function ex_attack_key_change(&$pa, &$pd, $active, $key){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active, $key);
		if ($pa['bskill']==26 && $pa['skill26_flag3']==1) {
			$ret = get_skill26_type($pa, $pd, $active);
		}
		return $ret;
	}
	
	function calculate_physical_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) return $chprocess($pa, $pd, $active);
		
		eval(import_module('logger','itemmain'));
		$attack_type =  get_skill26_type($pa, $pd, $active);//2级时是灼焰
		$log .= '在技能的作用下，伤害全部转化为了<span class="red b">'.$itemspkinfo[$attack_type].'</span>伤害！<br>';
		
		//连击和灵系体力是唯一需要考虑的特殊“物理伤害加成”
		//（因为这不是加成，只是被做到加成里去了……）
		
//		$r = \wep_f\get_WF_dmg_multiplier($pa, $pd, $active);
//		$r = array_merge($r,\ex_rapid_attr\get_rapid_dmg_multiplier($pa, $pd, $active));
//		$pa['skill26_phy_multiplier'] = $r;
		//判断属穿
		$pflag=check_attr_pierce_proc($pa, $pd, $active);
		//判断属抹
		$flag=check_ex_dmg_nullify($pa, $pd, $active);
		
		$pa['skill26_flag1'] = 2;	//跳过之后的属穿和属抹判断
		
		if ($flag)
		{
			//$log .= "<span class=\"red b\">属性攻击的力量完全被防具吸收了！</span>只造成了<span class=\"red b\">".$pa['ex_dmg_dealt']."</span>点伤害！<br>";
			$pa['physical_dmg_dealt'] += $pa['ex_dmg_dealt'];
			$pa['dmg_dealt'] += $pa['ex_dmg_dealt'];
			//$pa['mult_words_fdmgbs'] = \attack\add_format($pa['ex_dmg_dealt'], $pa['mult_words_fdmgbs']);
			$pa['ex_dmg_dealt'] = 0;
			return 0;
		}
		
		//只计算武器基础伤害
		$odmg = $dmg = \weapon\get_physical_dmg($pa, $pd, $active);
		$mult_words_phydmg = \attack\equalsign_format($dmg, $pa['mult_words_phydmgbs'], '<:phy_dmg:>');
		//把基础伤害当做固定伤害载入属性伤害计算
		$dmg = \ex_dmg_att\calculate_ex_single_dmg($pa, $pd, $active, $attack_type, $dmg);
		
		if($dmg == $odmg) {
			$mult_words_phydmg = str_replace('<:phy_dmg:>','red b',$mult_words_phydmg);
			$log.='武器攻击造成了'.$mult_words_phydmg.'点'.$itemspkinfo[$attack_type]."伤害！<br>";
		}else {
			$mult_words_phydmg = str_replace('<:phy_dmg:>','yellow b',$mult_words_phydmg);
			$log.='武器攻击造成了'.$mult_words_phydmg.'点基础伤害，并转化为了<span class="red b">'.$dmg.'</span>点'.$itemspkinfo[$attack_type].'伤害！<br>';
		}
		
		$pa['physical_dmg_dealt'] += $dmg;
		$pa['dmg_dealt'] += $dmg;
		$pa['mult_words_fdmgbs'] = \attack\add_format($dmg, $pa['mult_words_fdmgbs']);
		$pa['skill26_flag2'] = 2;	//攻击属性判断开始正常返回（按次序计算）
		return $dmg;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=26) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(26,$pa) || !check_unlocked26($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost26($pa);
			if (!\clubbase\check_battle_skill_unactivatable($pa,$pd,26))
			{
				eval(import_module('logger'));
				if ($active)
					$log.="<span class=\"lime b\">你对{$pd['name']}发动了技能「聚能」！</span><br>";
				else  $log.="<span class=\"lime b\">{$pa['name']}对你发动了技能「聚能」！</span><br>";
				$pa['rage']-=$rcost;
				$pa['skill26_flag1']=1;//是否正常进行属抹判定，1为是，2为否（沿用已经进行过的判定）
				$pa['skill26_flag2']=1;//物理阶段是否完成（攻击属性判定是否只返回火焰），1为是，2为否
				$pa['skill26_flag3']=1;//属性阶段是否完成（是否跳过属性致伤判定），1为跳过，2为不跳过
				$pa['skill26_flag4']=1;//好像没用到？
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
		if ($pa['bskill']!=26) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if ($pa['is_hit'])
		{
			//进行一次火焰受伤判定
			$pa['skill26_flag3']=2;
			$attack_type =  get_skill26_type($pa, $pd, $active);
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
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill26') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「聚能」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>