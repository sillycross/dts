<?php

namespace attack
{
	//最终伤害基础值，返回的是一个数值
	function get_final_dmg_base(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0;
	}
	
	//最终伤害修正接口
	//类似物理伤害修正，返回的是一个数组
	function get_final_dmg_multiplier(&$pa, &$pd, &$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return Array();
	}
	
	
	//无敌判定，最优先
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//特殊变化类，次优先
	function apply_total_damage_modifier_special(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$sequence = apply_total_damage_modifier_special_set_sequence($pa, $pd, $active);
		if(empty($pd['atdms_sequence'])) return;
		ksort($pd['atdms_sequence']);
		foreach($pd['atdms_sequence'] as $akey){
			if(apply_total_damage_modifier_special_check($pa, $pd, $active, $akey)){
				apply_total_damage_modifier_special_core($pa, $pd, $active, $akey);
				if($pa['dmg_dealt'] <= 0) break;
			}
		}
	}
	
	//特殊变化次序注册
	//注意是在$pd上
	function apply_total_damage_modifier_special_set_sequence(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pd['atdms_sequence'] = array();
		return;
	}
	
	//特殊变化生效判定，建议采用或的逻辑关系
	function apply_total_damage_modifier_special_check(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return false;
	}
	
	//特殊变化执行
	function apply_total_damage_modifier_special_core(&$pa, &$pd, $active, $akey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return;
	}
	
	//限制伤害类，第三优先
	function apply_total_damage_modifier_limit(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//保命类，第四优先
	function apply_total_damage_modifier_insurance(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//秒杀，最后判定
	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active)
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
		
		//$dmg=$pa['dmg_dealt'];
		
		eval(import_module('logger'));
		$tmp_dmg = $pa['dmg_dealt'];
		//先加减，后乘除，最终伤害加算阶段
		if($pa['dmg_dealt'] > 0) {
			$dmg_base = get_final_dmg_base($pa, $pd, $active);
			if($dmg_base != 0) {
				$pa['dmg_dealt'] += $dmg_base;
				if($pa['dmg_dealt'] < 0)	$pa['dmg_dealt'] = 0;
			}
		}
		$pa['mult_words_fdmgbs'] = substr($pa['mult_words_fdmgbs'],3);
		//最终伤害乘算阶段
		//获取最终伤害修正系数，类似物理伤害修正系数，这里返回的是一个数组
		if ($pa['dmg_dealt']>0){
			$multiplier = get_final_dmg_multiplier($pa, $pd, $active);
		}else{
			$multiplier= Array();
		}
		if($pa['dmg_dealt'] > 0){//伤害大于零时判定加成值
			list($fin_dmg, $mult_words, $mult_words_fdmg) = apply_multiplier($pa['dmg_dealt'], $multiplier, '<:fin_dmg:>', $pa['mult_words_fdmgbs']);
			$mult_words_fdmg = equalsign_format($fin_dmg, $mult_words_fdmg, '<:fin_dmg:>');
			if($fin_dmg!=$pa['physical_dmg_dealt']) $log .= '<span class="yellow b">造成的总伤害：'.$mult_words_fdmg.'。</span><br>';
			$pa['dmg_dealt'] = $fin_dmg;
		}elseif($tmp_dmg != $pa['dmg_dealt']){//伤害等于零但是是扣成零时也显示一下总伤害
			$log .= '<span class="yellow b">造成的总伤害：<span class="<:fin_dmg:>">'.$pa['dmg_dealt'].'</span>。</span><br>';
		}
		//var_dump($pa['dmg_dealt']);
		$tmp_dmg = $pa['dmg_dealt'];
		//最终伤害修正阶段，应用对总伤害的特殊修正。后判定的会大于先判定的。
		//最先：无敌
		if($pa['dmg_dealt']) apply_total_damage_modifier_invincible($pa,$pd,$active);
		//第二：特殊变化
		if($pa['dmg_dealt']) apply_total_damage_modifier_special($pa,$pd,$active);
		//第三：伤害限制类
		if($pa['dmg_dealt']) apply_total_damage_modifier_limit($pa,$pd,$active);
		//第四：保命类
		if($pa['dmg_dealt']) apply_total_damage_modifier_insurance($pa,$pd,$active);
		//秒杀技，最后判定
		//成功秒杀则$pa['seckill']会是1
		apply_total_damage_modifier_seckill($pa,$pd,$active);
		//var_dump($pa['dmg_dealt']);
		$replace_color = 'red b';
		if($tmp_dmg != $pa['dmg_dealt'] && empty($pa['seckill'])) {
			$log .= "<span class=\"yellow b\">最终伤害：<span class=\"red b\">{$pa['dmg_dealt']}</span>。</span><br>";
			$replace_color = 'yellow b';
		}
		$log = str_replace('<:fin_dmg:>', $replace_color, $log);//如果有伤害变化，那么前面的台词显示黄色，否则显示红色（最终值）
		
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
				$pa['battlelog'] .= "对其做出了<span class=\"yellow b\">{$pa['dmg_dealt']}</span>点反击。<br>";
			else  $pa['battlelog'] .= "你对其做出<span class=\"yellow b\">{$pa['dmg_dealt']}</span>点攻击，";
		else  if ($pa['is_counter'])
				$pd['battlelog'] .= "受到其<span class=\"yellow b\">{$pa['dmg_dealt']}</span>点反击。<br>";
			else  $pd['battlelog'] .= "你受到其<span class=\"yellow b\">{$pa['dmg_dealt']}</span>点攻击，";
			
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
				$log .= "<span class=\"red b\">你的反击！</span><br>";
			}
			else
			{
				$log .= "你向<span class=\"red b\">{$pd['name']}</span>发起了攻击！<br>";
				$pd['battlelog'] .= "手持<span class=\"red b\">{$pa['wep']}</span>的<span class=\"yellow b\">{$pa['name']}</span>向你袭击！";
			}
		else  if ($pa['is_counter'])
			{
				$log .= "<span class=\"red b\">{$pa['name']}的反击！</span><br>";
			}
			else
			{
				$log .= "<span class=\"red b\">{$pa['name']}</span>突然向你袭来！<br>";
				$pa['battlelog'] .= "你发现了手持<span class=\"red b\">{$pd['wep']}</span>的<span class=\"yellow b\">{$pd['name']}</span>并且先发制人！";
			}
	}
	
	//击杀通告
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player'));
		if ($active)
		{
			$log .= "<span class=\"yellow b\">{$pd['name']}</span><span class=\"red b\">被你杀死了！</span><br>";
			$pd['battlelog'] .= "<span class=\"red b\">你被{$pa['name']}杀死了！</span><br>";
		}
		else
		{
			$log .= "<span class=\"red b\">你被{$pa['name']}杀死了！</span><br>";
			$pa['battlelog'] .= "<span class=\"yellow b\">{$pd['name']}</span><span class=\"red b\">被你杀死了！</span><br>";
		}
		
		$pd['state']=$pd['deathmark'];
		
		$kilmsg = \player\kill($pa, $pd);
		show_player_killwords($pa,$pd,$active,$kilmsg);
		
	}
	
	function show_player_killwords(&$pa,&$pd,$active,$kilmsg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if($pd['hp'] <= 0 && !empty($kilmsg))
			if ($active)
				$log.="<br><span class='b'>你对{$pd['name']}说道：</span><span class='yellow b'>“{$kilmsg}”</span><br>";
			else
				$log.="<br><span class='b'>{$pa['name']}对你说道：</span><span class='yellow b'>“{$kilmsg}”</span><br>";
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
	
	//要在player_damaged_enemy之后执行的功能请加载这里
	//attack_finish和player_damaged_enemy耦合得太密切了。
	function post_player_damaged_enemy_event(&$pa,&$pd,$active)
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
		$pa['mult_words_fdmgbs'] = '';
	}
	
	//攻击结束
	//注意：在任一参战方死亡的情况，$sdata会初始化一次，其中储存的非数据库字段的键值会全部丢失！
	//要修改这一点牵扯到的东西比较多，目前先放着吧
	function attack_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		player_damaged_enemy($pa,$pd,$active);
		post_player_damaged_enemy_event($pa,$pd,$active);
//这是废弃的更改，不需要每一次都记录wep_kind，仅在有必要的时候（比如双系武器）进行记录
//		$pa_o_wepkind = $pa['wep_kind'];
//		$pd_o_wepkind = $pd['wep_kind'];
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
			//$pa['wep_kind'] = $pa_o_wepkind;$pd['wep_kind'] = $pd_o_wepkind;
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
			//$pa['wep_kind'] = $pa_o_wepkind;$pd['wep_kind'] = $pd_o_wepkind;
		}
		unset($pa['physical_dmg_dealt']);
		foreach(array_keys($pa) as $pak){
			if(strpos($pak, 'battlelogflag') === 0) {
				unset($pa[$pak]);
			}
		}
	}
	
	function attack_wrapper(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		attack_prepare($pa, $pd, $active);
		attack($pa,$pd,$active);
		attack_finish($pa,$pd,$active);		
	}
	
	//在字符串右边加数字/减数字的玩意
	function add_format($var, $str, $space=1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$asign = $var>=0 ? '+' : '-';
		if($space) $asign = ' '.$asign.' ';
		return $str.$asign.abs($var);
	}
	
	//在字符串右边乘数字的玩意。与下面那个算系数的不是一个思路
	function multiply_format($var, $str, $space=1)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($var==1) return $str;
		$msign = '×';
		if($space) $msign = ' '.$asign.' ';
		return $str.$msign.$var;
	}
	
	//乘算伤害，并生成XXX x XXX = XXX这样格式的玩意
	//如果给了$style，$mult_words的等号右边数字会用一个span套住
	//如果$reptxt为真，$mult_words_2的第一个数字会用$reptxt替换，且会自动给$reptxt加括号
	//返回一个数组，请用list()截获
	function apply_multiplier($basedmg, $multiplier, $style=NULL, $reptxt=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$dmg = $basedmg;
		$mult_words = $basedmg;
		$mult_words_2 = $reptxt ? '<:reptxt:>' : $basedmg;
		
		foreach ($multiplier as $key)
		{
			if($key && $key != 1) {
				$dmg *= $key;
				$mult_words .= '×'.$key;
				$mult_words_2 .= '×'.$key;
			}
		}
		if($dmg != $basedmg) {
			$dmg = round($dmg);
			$dmg = max(1, $dmg);
		}
		$mult_words .= equalsign_format($dmg, $mult_words, $style);
		if(strpos($mult_words_2, '×')!==false && strpos($reptxt, '+')!==false) $mult_words_2 = str_replace('<:reptxt:>','(<:reptxt:>)',$mult_words_2);
		if($reptxt) $mult_words_2 = str_replace('<:reptxt:>',$reptxt,$mult_words_2);
		return array($dmg, $mult_words, $mult_words_2);
	}
	
	//在右边添加=xxx格式的玩意
	function equalsign_format($var, $str, $style=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$spanstr = $style ? '<span class="'.$style.'">' : '<span>';
		
		$esign = strpos($str,' + ')!==false ? ' = ' : '=';
		if(strpos($str,'+')!==false || strpos($str,'×')!==false) 
			return $str.$esign.$spanstr.$var.'</span>';
		else return $spanstr.$str.'</span>';
	}
}

?>