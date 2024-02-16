<?php

namespace weapon
{
	function init() 
	{
		eval(import_module('player'));
		global $wep_equip_list;
		$equip_list=array_merge($equip_list,$wep_equip_list);
		$battle_equip_list=array_merge($battle_equip_list,$wep_equip_list);
	}
	
	function parse_itmk_desc($k_value, $sk_value) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($k_value, $sk_value);
		if(strpos($k_value,'W')===0) {
			$wep_kind = substr($k_value,1,1);
			eval(import_module('weapon'));
			$ret .= '攻击方式为'.$attinfo[$wep_kind].'，依赖'.$skilltypeinfo[$skillinfo[$wep_kind]].'熟';
		}
		return $ret;
	}
	
	//武器耐久值低下的提示
	function parse_item_words($edata, $simple = 0, $elli = 0)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($edata, $simple, $elli);
		eval(import_module('weapon'));
		//非枪、弓武器
		if(strpos($edata['wepk'], 'G')===false && strpos($edata['wepk'], 'J')===false && strpos($edata['wepk'], 'B')===false){
			if(is_numeric($edata['weps'])){
				if($edata['weps'] <= 3) $ret['weps_words'] = '<span class="red b">'.$ret['weps_words'].'</span>';
				elseif($edata['weps'] <= 6) $ret['weps_words'] = '<span class="yellow b">'.$ret['weps_words'].'</span>';
			}
		//枪和弓
		}else{
			if($nosta == $edata['weps']) $ret['weps_words'] = '<span class="red b">'.$ret['weps_words'].'</span>';
		}
		return $ret;
	}
	
	function get_internal_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['att'];
	}
	
	function get_external_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['wepe']*2;		//维持奇葩的老设定，实际计算效果是面板数值*2
	}
	
	//攻击力计算基础值，会自动生成$pa['att_words']也就是攻击力计算的式子
	function get_att_base(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$internal_att = get_internal_att($pa,$pd,$active);
		$external_att = get_external_att($pa,$pd,$active);
		$pa['att_words'] = $internal_att;
		$pa['att_words'] = \attack\add_format($external_att, $pa['att_words'],0);
		return $internal_att + $external_att;
	}
	
	//攻击力计算加成值，返回一个数组，这个数组应该是乘算
	function get_att_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!isset($pa['att_m_words'])) $pa['att_m_words'] = '';
		return Array();
	}
	
	//攻击力计算变化值
	function get_att_change(&$pa,&$pd,$active,$att)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $att;
	}
	
	function get_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = get_att_base($pa,$pd,$active);
		list($ret, $null, $pa['att_m_words']) = \attack\apply_multiplier($ret, get_att_multiplier($pa,$pd,$active), NULL, $pa['att_words']);
		$ret = get_att_change($pa,$pd,$active,$ret);
		return $ret;
	}
	
	function get_internal_def(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pd['def'];
	}
	
	//防御力计算基础值

	function get_def_base(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pd['internal_def'] = get_internal_def($pa,$pd,$active);
		$pd['def_words'] = $pd['internal_def'];
		return $pd['internal_def'];
	}
	
	//防御力计算加成值，返回一个数组，这个数组应该是乘算
	function get_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!isset($pd['def_m_words'])) $pd['def_m_words'] = '';
		return Array();
	}
	
	//防御力计算变化值
	function get_def_change(&$pa,&$pd,$active,$def)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $def;
	}
	
	function get_def(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = get_def_base($pa,$pd,$active);
		list($ret, $null, $pd['def_m_words']) = \attack\apply_multiplier($ret, get_def_multiplier($pa,$pd,$active), NULL, $pd['def_words']);
		$ret = get_def_change($pa,$pd,$active,$ret);
		return $ret;
	}
	
	function get_skill_by_kind(&$pa, &$pd, $active, $wep_skillkind)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return $pa[$wep_skillkind];
	}
	
	function get_skill(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return get_skill_by_kind($pa, $pd, $active, substr(get_skillkind($pa,$pd,$active),0,2));//使双系武器能直接回避系别战斗技的判定，但又能正常判断熟练度
	}
	
	function get_skillkind(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if(!empty($pa['wep_kind'])) $wep_kind = $pa['wep_kind'];
		else $wep_kind = get_attack_method($pa);
		return $skillinfo[$wep_kind];
	}
	
	function get_attack_method(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return substr($pdata['wepk'],1,1);
	}
	
	function check_attack_method(&$pdata, $wm)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return get_attack_method($pdata)==$wm;
	}
	
	function load_user_combat_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$command = get_var_in_module('command', 'sys');
		if (check_attack_method($pdata,$command))
			$pdata['wep_kind']=$command;
		else  $pdata['wep_kind']=get_attack_method($pdata);
		$chprocess($pdata);
	}
	
	function load_auto_combat_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pdata['wep_kind']=get_attack_method($pdata);
		$pdata['bskill'] = $pdata['bskillpara'] = '';
		$chprocess($pdata);
	}
	
	//命中率基础值，这个函数应该是加算
	function get_hitrate_base(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$hitrate = $hitrate_obbs[$pa['wep_kind']];
		$hitrate += round($pa['fin_skill'] * $hitrate_r[$pa['wep_kind']]); 
		$hitrate = min($hitrate, $hitrate_max_obbs[$pa['wep_kind']]);
		return $hitrate;
	}
	
	//命中率加成值，这个函数应该是乘算
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//命中率修正值
	function get_hitrate_change(&$pa,&$pd,$active,$hitrate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $hitrate;
	}
	
	function get_hitrate(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$hitrate = get_hitrate_base($pa,$pd,$active);
		//echo '命中率基础值'.$hitrate;
		$hitrate_r = get_hitrate_multiplier($pa,$pd,$active);
		$hitrate *= $hitrate_r;
		//echo '命中率加成值'.$hitrate;
		$hitrate_c = get_hitrate_change($pa,$pd,$active,$hitrate);
		$hitrate = $hitrate_c;
		//echo '命中率修正值'.$hitrate;
		
		return $hitrate;
	}
	
	//浮动最大值
	function get_weapon_fluc_max_range(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return $dmg_fluc[$pa['wep_kind']];
	}
	
	//浮动
	function get_weapon_fluc_percentage(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$x=get_weapon_fluc_max_range($pa,$pd,$active);
		if ($x>99) $x=99;
		return rand(-$x,$x);
	}
	
	//基础伤害
	function get_primary_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$pa['fin_att']=get_att($pa,$pd,$active);
		$pd['fin_def']=get_def($pa,$pd,$active);
		$att_pow=$pa['fin_att']; $def_pow=$pd['fin_def']; $ws=$pa['fin_skill']; $wp_kind=$pa['wep_kind'];
		if($def_pow <= 0) $def_pow = 1;
		$damage = ($att_pow/$def_pow)*$ws*$skill_dmg[$wp_kind];
		$fluc = get_weapon_fluc_percentage($pa, $pd, $active);
		$dmg_factor = (100+$fluc)/100;
		$damage = round ( $damage * $dmg_factor * rand ( 4, 10 ) / 10 );
		if ($damage<1) $damage=1;
		
		//基础伤害的固定值，只跟武器有关
		$pa['mult_words_pridmgbs'] = $damage;
		$primary_dmg_fixed = get_primary_fixed_dmg($pa, $pd, $active);
		if($primary_dmg_fixed) {
			$damage += $primary_dmg_fixed;
			$pa['mult_words_pridmgbs'] = \attack\add_format($pa['mult_words_pridmgfxdbs'],$pa['mult_words_pridmgbs']);
		}
		return $damage;
	}
	
	//基础固定伤害（灵和重枪之类）
	function get_primary_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['mult_words_pridmgfxdbs'] = '';
		$fxddmg = get_primary_fixed_dmg_base($pa, $pd, $active);
		if(!$fxddmg) return 0;
		list($fxddmg, $mult_words, $pa['mult_words_pridmgfxdbs']) = \attack\apply_multiplier($fxddmg, get_primary_fixed_dmg_multiplier($pa, $pd, $active), '', $pa['mult_words_pridmgfxdbs']);
		return $fxddmg;
	}
	
	//基础固定伤害（灵和重枪之类）
	function get_primary_fixed_dmg_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0;
	}
	
	//基础固定伤害加成值（金刚）
	function get_primary_fixed_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array();
	}
	
	//基础伤害加成系数
	function get_primary_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array();
	}
	
	//固定伤害
	function get_fixed_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0;
	}
	
	//固定伤害加成系数
	function get_fixed_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array();
	}
	
	//物理伤害
	function get_physical_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$primary_dmg_base = get_primary_dmg($pa, $pd, $active);
		list($primary_dmg, $mult_words, $mult_words_prmdmg) = \attack\apply_multiplier($primary_dmg_base, get_primary_dmg_multiplier($pa, $pd, $active), '<:primary_dmg:>', $pa['mult_words_pridmgbs']);

		$fixed_dmg=get_fixed_dmg($pa, $pd, $active);
		if ($fixed_dmg>0) {
			$o_fixed_dmg = $fixed_dmg;
			list($fixed_dmg, $mult_words, $mult_words_fxddmg) = \attack\apply_multiplier($fixed_dmg, get_fixed_dmg_multiplier($pa, $pd, $active), 'yellow b');

		}
		if(!empty($mult_words_fxddmg)) $pa['mult_words_phydmgbs'] = $mult_words_prmdmg.'+'.$mult_words_fxddmg;
		else $pa['mult_words_phydmgbs'] = $mult_words_prmdmg;
//		elseif($primary_dmg_base == $primary_dmg) {//特殊的台词顺序，如果既没有基础物伤加成，也没有物伤固定加成，就不显示基础物伤这句话
//			$primary_dmg_log = '';
//			$pa['primary_dmg_log_flag'] = 0;
//		}
//		$log = str_replace('<:primary_dmg_log:>', $primary_dmg_log, $log);
		return round($primary_dmg + $fixed_dmg);
	}
	
	//物理伤害加成系数
	//注意由于物理伤害加成系数会在log里显示出来，所以决定这里返回一个数组，代表各次加成，最后结果是所有元素的乘积
	//请注意array_merge的次序，应该把你的结果放在array的最前面，这样各次加成数值出现次序才是和判定log的出现次序一致的
	//即，应该写return array_merge(Array(数值),$chprocess($pa,$pd,$active));而不是反过来
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return Array();
	}
	
	//变化阶段，如果有需要最后变化物理伤害的技能请继承这里
	function get_physical_dmg_change(&$pa, &$pd, $active, $dmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $dmg;
	}
	
	function calculate_physical_dmg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		
		$multiplier = get_physical_dmg_multiplier($pa, $pd, $active);
		$dmg = get_physical_dmg($pa, $pd, $active);
		
		$primary_dmg_color = 'yellow b';
		list($fin_dmg, $mult_words, $mult_words_phydmg) = \attack\apply_multiplier($dmg, $multiplier, '<:fin_dmg:>', $pa['mult_words_phydmgbs']);
		$mult_words_phydmg = \attack\equalsign_format($fin_dmg, $mult_words_phydmg, '<:fin_dmg:>');
//		if(strpos($mult_words_phydmgbs,'+')!==false || strpos($mult_words_phydmgbs,'×')!==false) 
//			$mult_words_phydmgbs = $mult_words_phydmgbs.'=<span class="<:fin_dmg:>">'.$fin_dmg.'</span>';
//		else $mult_words_phydmgbs = '<span class="<:fin_dmg:>">'.$fin_dmg.'</span>';
		$log .= '造成了'.$mult_words_phydmg.'点物理伤害！<br>';
//		if(empty($pa['primary_dmg_log_flag'])) $log .= '造成了'.$mult_words.'点物理伤害！<br>';
//		elseif($fin_dmg != $dmg) $log .= '加成后的物理伤害：'.$mult_words.'点。<br>';
//		else $primary_dmg_color = 'red b';
//		$log = str_replace('<:primary_dmg:>', $primary_dmg_color, $log);
		
		$replace_color = 'red b';
		
		$fin_dmg_change = get_physical_dmg_change($pa, $pd, $active, $fin_dmg);
		if($fin_dmg_change != $fin_dmg) {
			$fin_dmg = $fin_dmg_change;
			$log .= "总物理伤害：<span class=\"red b\">{$fin_dmg}</span>。<br>";
			$replace_color = 'yellow b';
		}
		$log = str_replace('<:fin_dmg:>', $replace_color, $log);//如果有伤害变化，那么前面的台词显示黄色，否则显示红色（最终值）
		
		$pa['physical_dmg_dealt']+=$fin_dmg;
		$pa['dmg_dealt']+=$fin_dmg;
		$pa['mult_words_fdmgbs'] = \attack\add_format($fin_dmg, $pa['mult_words_fdmgbs']);
		return $fin_dmg;
	}
	
	function calculate_wepimp_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return $wepimprate[$pa['wep_kind']];
	}
	
	function calculate_wepimp(&$pa, &$pd, $active, $is_hit)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$z=calculate_wepimp_rate($pa, $pd, $active);
		if (!$is_hit && $z<1000) return;	//没有击中，且非消耗性武器，不会损失耐久
		$dice=rand(0,99);
		if ($dice<$z) {
			if(empty($pa['wepimp'])) $pa['wepimp'] = 1;
			else $pa['wepimp']++;
		}
	}
	
	//武器伤害计算后事件
	//Q: 为什么要写这么一个脑残的函数？不能和计算伤害写到一起么？
	//A: 问那个设计连击的人去，不这么搞没法实现目前版本连击（只计算一次伤害，但武器损伤等特效发动多次）
	function post_weapon_strike_events(&$pa, &$pd, $active, $is_hit)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//判定武器损伤
		calculate_wepimp($pa, $pd, $active, $is_hit);
	}
	
	//这个函数是完整的一次武器攻击函数
	//武器攻击后需要做的事情请要么接管post_weapon_strike_events()，要么接管strike()，不要接管weapon_strike()本体
	function weapon_strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$dice=rand(0,99);
		if ($dice<$pa['fin_hitrate'])	
		{
			$is_hit = 1;
			//计算物理伤害
			calculate_physical_dmg($pa, $pd, $active);
		}
		else  
		{	
			$is_hit = 0;
			$log .= "但是没有击中！<br>";
		}
		$pa['is_hit'] = $is_hit;
		post_weapon_strike_events($pa, $pd, $active, $is_hit);
	}
	
	function strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		weapon_strike($pa,$pd,$active);
	}
	
	//攻击方式宣言和一些基本变量的准备
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		
		$log .= get_attackwords($pa, $pd, $active);
		
		$pd['deathmark']=$wepdeathstate[$pa['wep_kind']];
		$pa['attackwith']=$pa['wep'];
		$pa['fin_skill']=get_skill($pa,$pd,$active);
		$pa['fin_hitrate']=get_hitrate($pa,$pd,$active);
		
		$chprocess($pa, $pd, $active);
	}
	
	//返回攻击方式的$log描述
	function get_attackwords(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon'));
		if(isset($attinfo2[$pa['wep_kind']])) $att_method_words = $attinfo2[$pa['wep_kind']];
		else $att_method_words = $attinfo[$pa['wep_kind']];
		
		if ($active)
		{
			$ret = "使用{$pa['wep']}<span class=\"yellow b\">{$att_method_words}</span>{$pd['name']}！<br>";
		}
		else  
		{
			$ret = "{$pa['name']}使用{$pa['wep']}<span class=\"yellow b\">{$att_method_words}</span>你！<br>";
		}
		return $ret;
	}
	
	function weapon_break(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		if ($active)
			if ($wepimprate[$pa['wep_kind']]<1000)
				$log .= "你的<span class=\"red b\">{$pa['wep']}</span>使用过度，已经损坏，无法再装备了！<br>";
			else  $log .= "你的<span class=\"red b\">{$pa['wep']}</span>用光了！<br>";
		else  if ($wepimprate[$pa['wep_kind']]<1000)
				$log .= "{$pa['name']}的<span class=\"red b\">{$pa['wep']}</span>使用过度，已经损坏，无法再装备了！<br>";
			else  $log .= "{$pa['name']}的<span class=\"red b\">{$pa['wep']}</span>用光了！<br>";
		
		\itemmain\item_destroy_core('wep', $pa);
	}
	
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		if (isset($pa['wepimp']) && $pa['wepimp'] && $pa['weps']!=$nosta)
		{
			$pa['weps']-=$pa['wepimp'];
			if ($active)
				if ($wepimprate[$pa['wep_kind']]<1000)
					$log .= "你的{$pa['wep']}的耐久度下降了{$pa['wepimp']}！<br>";
				else  $log .= "你用掉了{$pa['wepimp']}个{$pa['wep']}。<br>";
			else  if ($wepimprate[$pa['wep_kind']]<1000)
					$log .= "{$pa['name']}的{$pa['wep']}的耐久度下降了{$pa['wepimp']}！<br>";
				else  $log .= "{$pa['name']}用掉了{$pa['wepimp']}个{$pa['wep']}。<br>";
			
			if ($pa['weps']<=0) weapon_break($pa, $pd, $active);
		}
	}
	
	//计算武器熟练获得
	function calculate_attack_weapon_skill_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skillup = calculate_attack_weapon_skill_gain_base($pa, $pd, $active);
		$skillup *= calculate_attack_weapon_skill_gain_multiplier($pa, $pd, $active);
		$skillup = calculate_attack_weapon_skill_gain_change($pa, $pd, $active, $skillup);
		return $skillup;
	}
	
	//计算武器熟练获得基础值为1点
	function calculate_attack_weapon_skill_gain_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//加成值
	function calculate_attack_weapon_skill_gain_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//修正值
	function calculate_attack_weapon_skill_gain_change(&$pa, &$pd, $active, $skillup)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $skillup;
	}
	
	//增加熟练
	function apply_weapon_skill_gain(&$pa, &$pd, $active, $skillup)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon'));
		$pa[$skillinfo[$pa['wep_kind']]]+=$skillup;
	}
	
	//攻击经验基础值
	function calculate_attack_exp_gain_base(&$pa, &$pd, $active, $fixed_val=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($fixed_val) $expup = $fixed_val;//如果设了固定值，则基础值视为这个固定值
		else {
			$expup = round ( (calculate_attack_lvl($pd) - calculate_attack_lvl($pa)) / 3 );
			$expup = $expup > 0 ? $expup : 1;
		}
		return $expup;
	}
	
	function calculate_attack_lvl(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pdata['lvl'];
	}
	
	//加成值
	function calculate_attack_exp_gain_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//修正值
	function calculate_attack_exp_gain_change(&$pa, &$pd, $active, $expup)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $expup;
	}
	
	//计算攻击经验获得
	function calculate_attack_exp_gain(&$pa, &$pd, $active, $fixed_val=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$expup = calculate_attack_exp_gain_base($pa, $pd, $active, $fixed_val);
		$expup *= calculate_attack_exp_gain_multiplier($pa, $pd, $active);
		$expup = calculate_attack_exp_gain_change($pa, $pd, $active, $expup);
		return $expup;
	}
	
	//获取攻击经验
	function apply_attack_exp_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if($pa['hp'])	//存活才能获得经验
			\lvlctl\getexp(calculate_attack_exp_gain($pa, $pd, $active), $pa);
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		apply_weapon_imp($pa, $pd, $active);
		unset($pa['wepimp']);
		
		apply_weapon_skill_gain($pa, $pd, $active, calculate_attack_weapon_skill_gain($pa, $pd, $active));
		
		$chprocess($pa, $pd, $active);
	}
	
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['o_wep'] = $pa['wep']; $pd['o_wep'] = $pd['wep']; 
		$chprocess($pa, $pd, $active);
	}
	
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if($pa['physical_dmg_dealt'] > 0) $exp_flag = 1;//有造成过伤害才增加经验值
		
		$chprocess($pa, $pd, $active);
		
		if(!empty($exp_flag)) apply_attack_exp_gain($pa, $pd, $active);
	}
		
	function calculate_counter_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = calculate_counter_rate_base ($pa, $pd, $active);
		//echo '基础反击率：'.$ret;
		$ret *= calculate_counter_rate_multiplier ($pa, $pd, $active);
		//echo '加成后反击率：'.$ret;
		$ret = calculate_counter_rate_change ($pa, $pd, $active, $ret);
		//echo '修正后反击率：'.$ret;
		return $ret;
	}
	
	//反击率基础值，加算
	function calculate_counter_rate_base(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		return $counter_obbs[$pa['wep_kind']];
	}
	
	//反击率加成值，乘算
	function calculate_counter_rate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1.0;
	}
	
	//反击率修正值，直接变化
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function calculate_counter_rate_change(&$pa, &$pd, $active, $counter_rate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $counter_rate;
	}
	
	function check_counter_dice(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$counter_rate = calculate_counter_rate ($pa, $pd, $active);
		$counter_dice = rand ( 0, 99 );
		if ($counter_dice < $counter_rate) 
			return 1;
		else  return 0;
	}

	//再次注意，这里active代表的是是否是当前玩家
	function get_weapon_range(&$pa, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if (isset($pa['wep_kind']))
			return $rangeinfo[$pa['wep_kind']];
		else  return $rangeinfo[get_attack_method($pa)];
	}
	
	function check_counterable_by_weapon_range(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//$pa反击方有可能因为某些原因改变了攻击方式，从而要重算$wep_kind
		$pa['wep_kind'] = get_attack_method($pa);
		//而$pd原攻击方的攻击已经是既成事实，至少在这个函数里不需要重算$wep_kind
		//$pd['wep_kind'] = get_attack_method($pd);
		$r1 = get_weapon_range($pa, $active);
		$r2 = get_weapon_range($pd, 1-$active);
		if ($r1 >= $r2 && $r1 != 0 && $r2 != 0)
			return 1;
		else  return 0;
	}
	
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (check_counterable_by_weapon_range($pa, $pd, $active))
		{
			if (!$chprocess($pa,$pd,$active)) return 0;
			return check_counter_dice($pa, $pd, $active);
		}
		else
		{
			$pa['out_of_range'] = 1;//标记一下是射程不足所致
			return 0;
		}
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'W' ) === 0)
		{
			$eqp = 'wep';
			$noeqp = 'WN';
			
			if (($noeqp && strpos ( ${$eqp.'k'}, $noeqp ) === 0) || ! ${$eqp.'s'}) {
				${$eqp} = $itm;
				${$eqp.'k'} = $itmk;
				${$eqp.'e'} = $itme;
				${$eqp.'s'} = $itms;
				${$eqp.'sk'} = $itmsk;
				$log .= "装备了<span class=\"yellow b\">$itm</span>。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
			} else {
				swap(${$eqp},$itm);
				swap(${$eqp.'k'},$itmk);
				swap(${$eqp.'e'},$itme);
				swap(${$eqp.'s'},$itms);
				swap(${$eqp.'sk'},$itmsk);
				$log .= "卸下了<span class=\"red b\">$itm</span>，装备了<span class=\"yellow b\">${$eqp}</span>。<br>";
			}
			return;
		}
		$chprocess($theitem);
	}
}

?>
