<?php

namespace ex_dmg_att
{
	function init() 
	{
		eval(import_module('itemmain'));
		
		$itemspkinfo['p'] = '带毒';
		$itemspkdesc['p']='攻击附带毒性伤害';
		$itemspkremark['p']='至少5%概率导致对方中毒；敌人已中毒时本属性伤害+100%';
		
		$itemspkinfo['u'] = '火焰';
		$itemspkdesc['u']='攻击附带火焰伤害';
		$itemspkremark['u']='至少10%概率导致对方烧伤';
		
		$itemspkinfo['i'] = '冻气';
		$itemspkdesc['i']='攻击附带冻气伤害';
		$itemspkremark['i']='至少5%概率导致对方冻结；注意：敌人已冻结时本属性伤害-25%！';
		
		$itemspkinfo['e'] = '电击';
		$itemspkdesc['e']='攻击附带电气伤害';
		$itemspkremark['e']='至少5%概率导致对方身体麻痹';
		
		$itemspkinfo['w'] = '音波';
		$itemspkdesc['w']='攻击附带音波伤害';
		$itemspkremark['w']='至少5%概率导致对方混乱；敌人已混乱时本属性伤害+50%';
		
		$itemspkinfo['d'] = '爆炸';
		$itemspkdesc['d']='攻击附带爆炸伤害，基础值为武器效果的一半';
		$itemspkremark['d']='攻击时会产生爆炸声';
		
		$itemspkinfo['f'] = '灼焰';
		$itemspkdesc['f']='攻击附带火焰伤害，基础值为武器效果的25%';
		$itemspkremark['f']='至少25%概率导致对方烧伤；敌人已烧伤时本属性伤害+50%';
		
		$itemspkinfo['k'] = '冰华';
		$itemspkdesc['k']='攻击附带冻气伤害，基础值为武器效果的20%';
		$itemspkremark['k']='至少40%概率导致对方冻结；敌人已冻结时本属性伤害+50%';
		
		$itemspkinfo['t'] = '音爆';
		$itemspkdesc['t']='攻击附带音波伤害，基础值为武器效果的20%';
		$itemspkremark['t']='至少20%概率导致对方混乱；敌人已混乱时本属性伤害+100%<br>攻击时会产生轰鸣声';
		
		//声音信息
		if (defined('MOD_NOISE')) 
		{
			eval(import_module('noise'));
			$noiseinfo['d'] = '爆炸声';
			$noiseinfo['t'] = '轰鸣声';
		}
	}
	
	//计算单个属性伤害
	function get_basic_ex_dmg(&$pa,&$pd,$active,$key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		$damage = $ex_base_dmg[$key]+$pa['wepe']/$ex_wep_dmg[$key]+$pa['fin_skill']/$ex_skill_dmg[$key];
		return $damage;
	}
	
	function get_ex_dmg_restriction(&$pa,&$pd,$active,$key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		return $ex_max_dmg[$key];
	}
	
	function calculate_ex_single_original_dmg(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		//基础伤害+武器效/属性的武器效果成长+熟练/属性的熟练成长
		$damage=get_basic_ex_dmg($pa,$pd,$active,$key);
		
		//最大伤害限制公式
		$ex_dmg_restriction=get_ex_dmg_restriction($pa,$pd,$active,$key);
		if ($ex_dmg_restriction>0) $damage = $ex_max_dmg[$key] * $damage / ( $damage + $ex_max_dmg[$key] / 2 );
		//得意武器类型翻倍
		if (isset($ex_good_wep[$key]) && $pa['wep_kind']==$ex_good_wep[$key]) $damage *= 2;
		//浮动
		$damage = $damage * rand(100 - $ex_dmg_fluc[$key], 100 + $ex_dmg_fluc[$key]) / 100;
		return $damage;
	}
	
	function get_ex_inf_dmg_punish(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		return $ex_inf_punish[$key];
	}
	
	function calculate_ex_inf_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//计算异常状态影响
		eval(import_module('ex_dmg_att','wound','logger'));
		$r = 1;
		if (isset($ex_inf[$key]))
		{
			if (\skillbase\skill_query($ex_inf[$key], $pd))
			{
				$infkey = array_search($ex_inf[$key], $infskillinfo);
				$punish = get_ex_inf_dmg_punish($pa, $pd, $active, $key);
				if ($punish != 1)
				{
					if ($punish > 1) $punish_word = "加重"; else $punish_word = "减轻";
					if(!isset($pa['battlelogflag_punish_'.$key])){
						$log .= \battle\battlelog_parser($pa, $pd, $active,"{$infname[$infkey]}{$punish_word}了<:pd_name:>受到的{$exdmgname[$key]}伤害！");
						$pa['battlelogflag_punish_'.$key] = 1;
					}
				}
				$r *= $punish;
			}
		}
		return $r;
	}
	
	function calculate_ex_single_dmg_multiple(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return calculate_ex_inf_multiple($pa, $pd, $active, $key);
	}
	
	function calculate_ex_single_dmg_change(&$pa, &$pd, $active, $key, $edmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $edmg;
	}
	
	//计算属性伤害造成异常状态的概率
	function calculate_ex_inf_rate(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ex_dmg_att','wound','logger'));
		if (!isset($ex_inf[$key])) return 0; 	//本属性无可以造成的异常状态
		if (\skillbase\skill_query($ex_inf[$key], $pd)) return 0;	//敌方已经有此异常状态了
		$rate = $ex_inf_r[$key]+$pa['fin_skill']*$ex_skill_inf_r[$key];
		return min($rate,$ex_max_inf_r[$key]);
	}
	
	function get_ex_inf_rate_modifier(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0;
	}
	
	function check_ex_inf_infliction(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//判定造成异常状态
		$inf_rate = calculate_ex_inf_rate($pa, $pd, $active, $key) + get_ex_inf_rate_modifier($pa, $pd, $active, $key);
		$inf_dice = rand(0,99);
		if ($inf_dice < $inf_rate)
		{
			get_ex_inf_main($pa, $pd, $active, $key);
		}
	}

	//执行致异常状态（单个）
	function get_ex_inf_main(&$pa, &$pd, $active, $key, $ignore_log = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ex_dmg_att','wound','logger'));
		$infkey = array_search($ex_inf[$key], $infskillinfo);
		if(!$ignore_log){
			$log .= \battle\battlelog_parser($pa, $pd, $active, '并致使<:pd_name:>'.$infname[$infkey].'了！');
			addnews($now,'inf',$pa['name'],$pd['name'],$infkey);
		}
		\wound\get_inf($infkey,$pd);
	}
	
	function showlog_ex_single_dmg(&$pa, &$pd, $active, $key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','ex_dmg_att'));
		if ($pd['ex_dmg_'.$key.'_defend_success'] == 1)	//恶心一下吧…… 奇怪的log美观修正……
			//这里具体是red还是yellow有待后头决定
			$log .= '造成了<span class="<:ex_single_dmg:>">'.$pa['ex_dmg_'.$key.'_dealt'].'</span>点属性伤害！';
		else  $log .= $exdmgname[$key].'造成了<span class="<:ex_single_dmg:>">'.$pa['ex_dmg_'.$key.'_dealt'].'</span>点属性伤害！';
		if(empty($pa['mult_words_exdmgbs'])) $pa['mult_words_exdmgbs'] = $pa['ex_dmg_'.$key.'_dealt'];
		else $pa['mult_words_exdmgbs'] = \attack\add_format($pa['ex_dmg_'.$key.'_dealt'],$pa['mult_words_exdmgbs']);
	}
	
	//执行单个属性攻击
	function calculate_ex_single_dmg(&$pa, &$pd, $active, $key, $fixed_dmg=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','ex_dmg_att','wound','logger'));
		//计算基础伤害。如果提供了固定值则基础伤害按固定值计算
		if(!$fixed_dmg)	$damage = calculate_ex_single_original_dmg($pa, $pd, $active, $key);
		else $damage = $fixed_dmg;
		//计算加减成
		$c_key = ex_attack_key_change($pa, $pd, $active, $key);
		$damage_multiple = calculate_ex_single_dmg_multiple($pa, $pd, $active, $c_key);
		$damage = round( $damage * $damage_multiple );
		
		if ($damage < 1) $damage = 1;
		//计算修正值
		$pa['ex_dmg_'.$key.'_dealt'] = $damage = calculate_ex_single_dmg_change($pa, $pd, $active, $c_key, $damage);
		
		showlog_ex_single_dmg($pa, $pd, $active, $key);
		
		//判断造成异常状态
		check_ex_inf_infliction($pa, $pd, $active, $key);
		$log.='<br>';
		return $damage;
	}	
	
	//计算属性伤害
	function calculate_ex_attack_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//命中才开始判定属性伤害，枪械作为钝器使用无属性伤害
		if (check_ex_attack_available($pa, $pd, $active))	
		{
			eval(import_module('logger'));
			ex_attack_prepare($pa, $pd, $active);
			//基础值
			$pa['mult_words_exdmgbs'] = ''; 
			$dmg = calculate_ex_attack_dmg_base($pa, $pd, $active);
			if(!$dmg) return;
			//加成值
			$multiplier = calculate_ex_attack_dmg_multiplier($pa, $pd, $active);
			
			list($fin_dmg, $mult_words, $mult_words_exdmg) = \attack\apply_multiplier($dmg, $multiplier, '<:fin_dmg:>',$pa['mult_words_exdmgbs']);
			$mult_words_exdmg = \attack\equalsign_format($fin_dmg, $mult_words_exdmg, '<:fin_dmg:>');
			$ex_dmg_log = '共计造成了'.$mult_words_exdmg.'点属性伤害！<br>';
			
			$replace_color_single = $replace_color = 'red b';
			if($fin_dmg != $dmg || $pa['ex_attack_num'] > 1) {
				$log .= $ex_dmg_log;
				$replace_color_single = 'b';
			}

			$dmg = $fin_dmg;
			
			//修正值
			$dmg_change = calculate_ex_attack_dmg_change($pa, $pd, $active, $dmg);
			if($dmg_change != $dmg) {
				$dmg = $dmg_change;
				$log .= "总属性伤害：<span class=\"red b\">{$dmg}</span>。<br>";
				$replace_color = 'yellow b';
				$replace_color_single = 'b';
			}
			
			//如果有伤害变化，那么前面的台词显示黄色，否则显示红色（最终值）
			$log = str_replace('<:fin_dmg:>', $replace_color, str_replace('<:ex_single_dmg:>', $replace_color_single, $log));
			
			$pa['dmg_dealt'] += $dmg;
			$pa['mult_words_fdmgbs'] = \attack\add_format($dmg, $pa['mult_words_fdmgbs']);

			return $dmg;
		}
	}
	
	//是否执行属性伤害
	function check_ex_attack_available(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['is_hit'] && \attrbase\attr_dmg_check_not_WPG($pa, $pd, $active);
	}
	
	//总属性伤害准备（各种技能提示放这里）
	function ex_attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//总属性伤害基础值
	function calculate_ex_attack_dmg_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		$tot = 0;
		$pa['ex_attack_num'] = 0;
		$ex_attack_array = \attrbase\get_ex_attack_array($pa, $pd, $active);
		foreach ( $ex_attack_list as $key )
			if (\attrbase\check_in_itmsk($key, $ex_attack_array))
			{
				$pa['ex_attack_num'] ++ ;
				$damage = calculate_ex_single_dmg($pa, $pd, $active, $key);
				$pa['ex_dmg_dealt'] +=$damage;
				$tot += $damage;
			}
		return $tot;
	}
	
	//总属性伤害加成值
	//同物理伤害部分，这里返回一个数组，代表各次修正，最后结果是所有元素的乘积
	//请注意array_merge的次序，应该把你的结果放在array的最前面，这样各次修正数值出现次序才是和判定log的出现次序一致的
	//即，应该写return array_merge(Array(数值),$chprocess($pa,$pd,$active));而不是反过来
	function calculate_ex_attack_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return Array();
	}
	
	//是否在执行伤害加成值和修正值时转化属性？聚能之类伤害使用
	function ex_attack_key_change(&$pa, &$pd, $active, $key){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $key;
	}
	
	//总属性伤害修正值
	function calculate_ex_attack_dmg_change(&$pa, &$pd, $active, $tdmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $tdmg;
	}
	
	function strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa, $pd, $active);
		calculate_ex_attack_dmg($pa, $pd, $active);
	}
	
	
	//战斗前清空各类计数器
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_dmg_att'));
		$pa['ex_dmg_dealt'] = 0;
		foreach ( $ex_attack_list as $key ) $pa['ex_dmg_'.$key.'_dealt'] = 0;
		$chprocess($pa, $pd, $active);
	}
	
	function add_ex_att_noise(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (defined('MOD_NOISE') && \attrbase\check_in_itmsk('d',\attrbase\get_ex_attack_array($pa, $pd, $active)))
		{
			\noise\addnoise($pa['pls'],'d',$pa['pid'],$pd['pid']);
		}
		if (defined('MOD_NOISE') && \attrbase\check_in_itmsk('t',\attrbase\get_ex_attack_array($pa, $pd, $active)))
		{
			\noise\addnoise($pa['pls'],'t',$pa['pid'],$pd['pid']);
		}
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		add_ex_att_noise($pa, $pd, $active);
		$chprocess($pa, $pd, $active);
	}
	
	function post_traphit_events(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $tritm, $damage);
		eval(import_module('player','logger','ex_dmg_att','wound'));
		if (strpos($tritm['itm'],'毒性')!==false) 
		{
			\wound\get_inf('p');
			$log.="陷阱还使你{$infname['p']}了！<br>";
		}
		if (strpos($tritm['itm'],'电气')!==false) 
		{
			\wound\get_inf('e');
			$log.="陷阱还使你{$infname['e']}了！<br>";
		}
	}
}

?>