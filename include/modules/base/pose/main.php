<?php

namespace pose
{
	function init() {}
	
	function calculate_real_trap_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$r = 0;
		if($pose==1)
			$r = 1;
		else if($pose==3)	//攻击和探索姿势略容易踩陷阱
			$r = 3;
		return $r+$chprocess();
	}
	
	function pose_change($npose)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','pose','logger'));
		$npose=(int)$npose;
		if ($pose_player_usable[$npose])
		{
			$log .= "基础姿态变为<span class=\"yellow b\">$poseinfo[$npose]</span>。<br> ";
			$pose = $npose;
		}
		else  $log .= "这个姿势太奇怪啦！<br> ";
	}
	
	function init_battle($ismeet = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','metman'));
		$chprocess($ismeet);
		$tdata['pose']=$w_pose;
	}
	
	function calculate_meetman_rate($schmode)	//遇敌率
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','pose'));
		//$r = 1;
//		$a = 0;
//		if ($schmode == 'search') 
//		{
//			if($pose==3) {//探索姿态不容易遇见敌人
//				$a = -25;
//				//$r = 0.85;	
//			}
//			elseif($pose==4) {//隐藏姿态容易遇见敌人
//				$a = 10;
//				//$r = 1.1;	
//			}
//		}
//		if ($schmode == 'move') 
//		{
//			if($pose==3) {
//				$a = -25;
//				//$r = 0.85;
//			}elseif($pose==4) {
//				$a = 10;
//				//$r = 1.1;
//			}
//		}
		//该姿态人物发现率减去物品发现率，越大越容易找到人
		$a = $pose_findman_obbs[$pose] - $pose_itemfind_obbs[$pose];
		return $chprocess($schmode) + $a;
	}
	
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['pose'] == 5) return 0;	//治疗姿态不能反击
		return $chprocess($pa,$pd,$active);
	}
	
	function calculate_itemfind_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','pose'));
		return $chprocess()+$pose_itemfind_obbs[$pose];
	}
	
	function calculate_hide_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','pose'));
		return $chprocess($edata)+$pose_hide_obbs[$edata['pose']];
	}
	
	function calculate_findman_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','pose'));
		return $chprocess($edata)+$pose_findman_obbs[$pose];
	}
	
	function calculate_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','pose'));
		//echo "姿态修正：+".$pose_active_obbs[$ldata['pose']].'%+'.$pose_dactive_obbs[$edata['pose']].'% <br>';
		$add = $pose_active_obbs[$ldata['pose']]+$pose_dactive_obbs[$edata['pose']];
		$ldata['active_words'] = \attack\add_format($add, $ldata['active_words'],0);
		return $chprocess($ldata,$edata)+$add;
	}
	
	function get_att_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','pose'));
		//改为全部生效
		$ret = $chprocess($pa,$pd,$active);
		$var = 1+$pose_attack_modifier[$pa['pose']]/100;
		array_unshift($ret, $var);
		return $ret;
//		if (!$pa['is_counter'])		//姿态的进攻加成在主动或先制攻击时才有用
//		{
//			return $chprocess($pa,$pd,$active)*(1+$pose_attack_modifier[$pa['pose']]/100);
//		}
//		else  return $chprocess($pa,$pd,$active);
	}
	
	function get_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','pose'));
		//姿态的防御加成是始终生效的
		$ret = $chprocess($pa,$pd,$active);
		$var = 1+$pose_defend_modifier[$pd['pose']]/100;
		array_unshift($ret, $var);
		return $ret;
	}
	
	function calculate_rest_upsp($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($pose == 5) 	//治疗姿态恢复3倍
			return $chprocess($rtime)*3; 
		else  return $chprocess($rtime);
	}
	
	function calculate_rest_uphp($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($pose == 5) 	//治疗姿态恢复3倍
			return $chprocess($rtime)*3; 
		else  return $chprocess($rtime);
	}
	
	function get_wound_recover_time()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($pose == 5) 	//治疗姿态解决异常状态时间降低30秒
			return $chprocess() - 30; 
		else  return $chprocess();
	}
	
	//治疗姿态服用补给效果*1.2
	function get_edible_hpup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','pose'));
		$modifier = 1;
		if($pose_edible_modifier[$pose] != 0){
			$modifier = (100+$pose_edible_modifier[$pose])/100;
		}
		return round($chprocess($theitem)*$modifier);
	}
	
	function get_edible_spup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','pose'));
		$modifier = 1;
		if($pose_edible_modifier[$pose] != 0){
			$modifier = (100+$pose_edible_modifier[$pose])/100;
		}
		return round($chprocess($theitem)*$modifier);
	}
	
	//如果是探物姿态，探索玩家流程结束以后会继续探索道具
	function can_continue_post_discover_player($dpret)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if(!$dpret && $hp>0 && 3==$pose) return true;
		return $chprocess($dpret);
	}	
		
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','pose'));
		if ($mode == 'special' && strpos($command,'pose') === 0) 
		{
			pose_change(substr($command,4,1));
			$mode = 'command';
			return;
		}
		$chprocess();
	}
		
}

?>
