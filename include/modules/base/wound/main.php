<?php

namespace wound
{
	function init() {}
	
	function get_inf($hurtposition, &$pa = NULL)	//返回是否新增了受伤状态（原本没有这个状态）
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('wound'));
		$flag = 1;
 		if ($pa == NULL) 
 		{
			eval(import_module('player'));
			if (strpos($inf,$hurtposition) !== false) $flag = 0;
			$inf = str_replace($hurtposition,'',$inf);
			$inf .= $hurtposition;
		}
		else
		{
			if (strpos($pa['inf'],$hurtposition) !== false) $flag = 0;
			$pa['inf'] = str_replace($hurtposition,'',$pa['inf']);
			$pa['inf'] .= $hurtposition;
		}
		\skillbase\skill_acquire($infskillinfo[$hurtposition],$pa);
		return $flag;
	}
	
	function heal_inf($hurtposition, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('wound'));
 		if ($pa == NULL) 
 		{
			eval(import_module('player'));
			$inf = str_replace($hurtposition,'',$inf);
		}
		else
		{
			$pa['inf'] = str_replace($hurtposition,'',$pa['inf']);
		}
		\skillbase\skill_lost($infskillinfo[$hurtposition],$pa);
	}
	
	function calculate_inf_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('wound'));
		return $wep_infobbs[$pa['wep_kind']];
	}
	
	function calculate_weapon_wound_base(&$pa, &$pd, $active, $hurtposition)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function calculate_weapon_wound_multiplier(&$pa, &$pd, $active, $hurtposition) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//成功触发受伤效果
	function weapon_wound_success(&$pa, &$pd, $active, $hurtposition)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa['attack_wounded_'.$hurtposition])) $pa['attack_wounded_'.$hurtposition] = 0;
		$pa['attack_wounded_'.$hurtposition]+=calculate_weapon_wound_base($pa, $pd, $active, $hurtposition) * calculate_weapon_wound_multiplier($pa, $pd, $active, $hurtposition);
	}
	
	//判定受伤是否发生
	function check_weapon_inf_proc(&$pa, &$pd, $active, $hurtposition)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (check_weapon_inf_rate_hit($pa, $pd, $active)) 
		{
			weapon_wound_success($pa, $pd, $active, $hurtposition);
		}
	}
	
	function check_weapon_inf_rate_hit(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$inf_rate = calculate_inf_rate($pa, $pd, $active);
		$inf_dice = rand(0,99);
		return $inf_dice < $inf_rate;
	}
	
	//判定受伤位置
	function check_weapon_inf(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('wound'));
		$i=rand(0,strlen($inf_place)-1);
		if (strpos($wep_infatt[$pa['wep_kind']], $inf_place[$i])!==false)
			check_weapon_inf_proc($pa, $pd, $active, $inf_place[$i]);
	}
	
	function post_weapon_strike_events(&$pa, &$pd, $active, $is_hit)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active, $is_hit);
		//判定受伤
		if ($is_hit) check_weapon_inf($pa, $pd, $active);
	}
	
	function apply_weapon_wound_real(&$pa, &$pd, $active, $hurtposition)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('wound','logger'));
		if ($active)
			$log .= "{$pd['name']}的<span class=\"red b\">".$infinfo[$hurtposition]."</span>部受伤了！<br>";
		else  $log .= "你的<span class=\"red b\">".$infinfo[$hurtposition]."</span>部受伤了！<br>";
		if (get_inf($hurtposition, $pd))
		{
			addnews(0,'inf',$pa['name'],$pd['name'],$hurtposition);
		}
	}
	
	function apply_weapon_inf(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('wound'));
		for ($i=0; $i<strlen($inf_place); $i++)
		{
			if (isset($pa['attack_wounded_'.$inf_place[$i]]) && $pa['attack_wounded_'.$inf_place[$i]]>0)
			{
				apply_weapon_wound_real($pa, $pd, $active, $inf_place[$i]);	
			}
		}
	}
	
	//一次攻击结束后，应用受伤状态
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//应用受伤
		apply_inf_main($pa, $pd, $active);
		$chprocess($pa, $pd, $active);
	}

	//应用受伤主函数
	function apply_inf_main(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('wound'));
		apply_weapon_inf($pa, $pd, $active);
		for ($i=0; $i<strlen($inf_place); $i++)
			unset($pa['attack_wounded_'.$inf_place[$i]]);
	}
	
	//记录原受伤状态，供assault_finish使用
	function assault_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['original_inf']=$pa['inf'];
		$pd['original_inf']=$pd['inf'];
		$chprocess($pa, $pd, $active);
	}
		
	//整场战斗结束后，更新被动方的battlelog，增加其总新增负伤状态说明
	function assault_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($active) 
		{
			$infold=$pd['original_inf'];
			$infnew=$pd['inf'];
		}
		else
		{
			$infold=$pa['original_inf'];
			$infnew=$pa['inf'];
		}
		
		$str='';
		eval(import_module('wound'));
		foreach ($infname as $key => $value)
			if ((strpos($infold,$key) === false) && (strpos($infnew, $key)!==false))
			{
				if ($str!='') $str.='、';
				$str.=$value;
			}
		if ($str!='') $str='这场战斗导致你'.$str.'了！<br>';
		
		if ($active)
			$pd['battlelog'] .= $str;
		else  $pa['battlelog'] .= $str;
		
		$chprocess($pa, $pd, $active);
	}	
	
	function init_battle($ismeet = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($ismeet);
		
		eval(import_module('sys','player','metman','wound'));
		if($w_inf) {
			$w_infdata = '';
			foreach ($infinfo as $inf_ky => $inf_nm) {
				if(strpos($w_inf,$inf_ky) !== false) {
					$w_infdata .= $inf_nm;
				}
			}
		$tdata['infdata']=$w_infdata;
		} else {
			$tdata['infdata'] = '';
		}
	}
	
	function chginf($infpos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','wound'));
		$normalinf = Array('h','b','a','f');
		if(!$infpos)
		{
			$mode = 'command'; return;
		}
		if(in_array($infpos,$normalinf) && strpos($inf,$infpos) !== false){	//普通伤口
			if($sp <= $inf_recover_sp_cost) {
				$log .= "包扎伤口需要{$inf_recover_sp_cost}点体力，先回复体力吧！";
				$mode = 'command';
				return;
			} else {
				heal_inf($infpos);
				$sp -= $inf_recover_sp_cost;
				$log .= "消耗<span class=\"yellow b\">$inf_recover_sp_cost</span>点体力，{$infinfo[$infpos]}<span class=\"red b\">部</span>的伤口已经包扎好了！";
				$mode = 'command';
				return;
			}
		}elseif(strpos($inf,$infpos) !== false){  //特殊状态
		/*
			if($club == 16){
				if($sp <= $inf_sp_2) {
					$log .= "处理异常状态需要{$inf_sp_2}点体力，先回复体力吧！";
					$mode = 'command';
					return;
				} else {
					$inf = str_replace($infpos,'',$inf);
					$sp -= $inf_sp_2;
					$log .= "消耗<span class=\"yellow b\">$inf_sp_2</span>点体力，{$exdmginf[$infpos]}状态已经完全治愈了！";
					$mode = 'command';
					return;
				}
			}else{
		*/
			$log .= '异常状态不能被直接治疗！';
			$mode = 'command';
			return;
		}else{
			$log .= '你不需要包扎这个伤口！';
			$mode = 'command';
			return;
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'inf') 
		{
			eval(import_module('wound'));
			if (strpos('bhaf',$c)===false)	//普通受伤不显示
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}的攻击致使{$b}</span>{$infname[$c]}<span class=\"red b\">了</span></li>";
			else  return '';
		}
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if ($mode == 'special' && strpos($command,'inf') === 0) 
		{
			$infpos = substr($command,3,1);
			chginf($infpos);
			return;
		} 
		$chprocess();
	}
}

?>
