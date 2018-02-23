<?php

namespace halloween2017
{	
	$treat_options = array(
		array('万圣节彩色糖果', 'HB', 13, 1, ''),
		array('万圣节紫色糖果', 'WC', 100, 1, 'f'),
		array('万圣节红色糖果', 'MA', 4, 1, ''),
		array('万圣节橙色糖果', 'MD', 4, 1, ''),
		array('万圣节青色糖果', 'MS', 4, 1, ''),
		array('万圣节黄色糖果', 'ME', 1, 1, ''),
		array('万圣节蓝色糖果', 'MV', 1, 1, ''),
		array('万圣节绿色糖果', 'MH', 1, 1, ''),
	);
	
	function init() 
	{
		eval(import_module('pose','tactic'));
		if(check_available_h2017()){
			$poseinfo[1] = '要糖姿态';
			$posedesc[1] = '以备战为目的，提升攻防，略微提升角色发现率，并且向对方要糖';
			$poseremark[1] = '陷阱遭遇率+1%，对方防御-25%';
			$poseinfo[2] = '索糖姿态';
			$posedesc[2] = '-';
			$poseremark[2] = '-';//NPC索糖姿态对方-10%防御
			$tacinfo[3] = '重视发糖';
			$tacticdesc[3] = '随时准备反击，并且乐于向对方发糖的姿态';
			$tacticremark[3] = '反击率+30%；面对要糖和索糖姿态时自己防御不减，发出的糖数加倍';
		}
	}
	
	
	function check_available_h2017(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//if($now >= 0 && $now <= 1510012799) return true;
		if($now >= 1509465600 && $now <= 1510012799) return true;
		else return false;
	}
	
	
	function create_treat_h2017($tnum){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!check_available_h2017()) return false;
		eval(import_module('sys','player','halloween2017'));
		//先确定获得什么类型的糖
		shuffle($treat_options);
		list($titm, $titmk, $titme, $titms, $titmsk) = $treat_options[0];
		$titms = $tnum;
		//直接拿吧……
		$itm0 = $titm; $itmk0 = $titmk; $itme0 = $titme; $itms0 = $titms; $itmsk0 = $titmsk;
		\itemmain\itemget();
		return true;
	}
	
	function calc_treat_num_h2017($damage){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = round(sqrt($damage/100));//获得数目是(伤害/100)开根号
		if($ret > 13) $ret = 13;//不会超过13，避免刷糖。对应的伤害上限是16900
		if($ret < 1) $ret = 1;//保底至少1
		return $ret;
	}
	
	//记录是不是爆系武器
	function get_attack_method(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pdata);
		if($ret === 'D'){
			$pdata['h2017_wd_flag'] = 1;
			$pdata['h2017_wd_e'] = $pdata['wepe'];
		}
		return $ret;
	}
	
	function battle_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		$pa['h2017_wd_flag'] = $pa['h2017_wd_e'] = 0;
	}
	
	//玩家主动攻击的场合才会拿到糖，也不会发糖了，省点力气，反正是活动
	function battle_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if(check_available_h2017() && $pa['hp'] > 0 && $pd['hp'] > 0){//双方都存活才会拿糖
			eval(import_module('logger'));
			
			$treat_num = 0;
			//要糖姿态，主动攻击且对方有伤害的场合，或者爆系武器攻击没命中的场合能拿糖
			if($active && (1==$pa['pose'] || 2==$pa['pose']) && ($pd['dmg_dealt'] > 0 || ($pa['h2017_wd_flag'] && $pa['dmg_dealt'] <= 0)))
			{
				if($pa['h2017_wd_flag'] && $pa['dmg_dealt'] <= 0){
					$treat_num = calc_treat_num_h2017($pa['h2017_wd_e']);
					$log .= '<span class="lime">对方的惊吓转化为了万圣节的糖果！</span>';
				}else{
					$treat_num = calc_treat_num_h2017($pd['dmg_dealt']);
					$log .= '<span class="lime">对方的伤害转化为了万圣节的糖果！</span>';
				}
				if(3 == $pd['tactic']) {
					$treat_num *= 2;
					$log .= '<span class="lime">而且量好多哦！</span>';
				}
				$log .= '<br>';
			}
//			elseif(!$active && (1==$pd['pose'] || 2==$pd['pose']) && $pa['dmg_dealt'] > 0)
//			{
//				$treat_num = calc_treat_num_h2017($pa['dmg_dealt']);
//				if(3 == $pa['tactic']) $treat_num *= 2;
//			}
			if($treat_num > 0){
				//先全部都凭空发糖算了
				create_treat_h2017($treat_num);
			}
			unset($pa['h2017_wd_flag'], $pa['h2017_wd_e']);
		}
	}
	
	function get_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if(check_available_h2017()){
			eval(import_module('logger'));
			if((1 == $pa['pose'] || 2 == $pa['pose']) && !$pa['is_counter']){
				if(3 != $pd['tactic']) {
					$log .= \battle\battlelog_parser($pa, $pd, $active, "<:pa_name:>索要糖果的无辜眼神让<:pd_name:>的防御力下降了！<br>");
					if(1 == $pa['pose']) $ret *= 0.75;//要糖姿态减对方25%防御
					else $ret *= 0.9;//索糖姿态减对方10%防御，否则PVE要炸
				}else{
					$log .= \battle\battlelog_parser($pa, $pd, $active, "<:pa_name:>索要糖果的无辜眼神对慷慨大方的<:pd_name:>不起作用！<br>");
				}
			}
		}
		return $ret;
	}
	
	//万圣节特殊攻击/反击通告
	function player_attack_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$flag = 0;
		if(check_available_h2017()) {
			eval(import_module('logger'));
			if($active && !$pa['is_counter'] && 1 == $pa['pose']) {
				$log .= "你向<span class=\"red\">{$pd['name']}</span>索要糖果！<br>";
				$pd['battlelog'] .= "手持<span class=\"red\">{$pa['wep']}</span>的<span class=\"yellow\">{$pa['name']}</span>向你要糖！";
				$flag = 1;
			}
			elseif(!$active && !$pa['is_counter'] && 1 == $pa['pose']){
				$log .= "<span class=\"red\">{$pa['name']}</span>突然向你要糖！<br>";
				$pa['battlelog'] .= "你发现了手持<span class=\"red\">{$pd['wep']}</span>的<span class=\"yellow\">{$pd['name']}</span>并且抢先要糖！";
				$flag = 1;
			}
		}
		if(!$flag)
		{
			$chprocess($pa,$pd,$active);
		}		
	}
}

?>