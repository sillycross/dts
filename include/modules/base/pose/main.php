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
			$log .= "基础姿态变为<span class=\"yellow\">$poseinfo[$npose]</span>。<br> ";
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
	
	function calculate_meetman_rate_by_mode($schmode)	//遇敌率
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$r = 0;
		if ($schmode == 'search') 
		{
			if($pose==3) $r = -15;	//探索姿态不容易遇见敌人
			if($pose==4) $r = 10;	//隐藏姿态容易遇见敌人
		}
		if ($schmode == 'move') 
		{
			if($pose==3) $r = -15;
			if($pose==4) $r = 10;
		}
		return $chprocess($schmode)+$r;
	}
	
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
	
	function calculate_meetman_obbs(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','pose'));
		return $chprocess($edata)+$pose_meetman_obbs[$pose];
	}
	
	function calculate_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','pose'));
		return $chprocess($ldata,$edata)+$pose_active_obbs[$ldata['pose']]+$pose_dactive_obbs[$edata['pose']];
	}
	
	function get_att_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','pose'));
		if (!$pa['is_counter'])		//姿态的进攻加成在主动或先制攻击时才有用
		{
			return $chprocess($pa,$pd,$active)*(1+$pose_attack_modifier[$pa['pose']]/100);
		}
		else  return $chprocess($pa,$pd,$active);
	}
	
	function get_def_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','pose'));
		//姿态的防御加成是始终生效的
		return $chprocess($pa,$pd,$active)*(1+$pose_defend_modifier[$pd['pose']]/100);
	}
	
	function calculate_rest_upsp($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($pose == 5) 	//治疗姿态恢复双倍
			return $chprocess($rtime)*2; 
		else  return $chprocess($rtime);
	}
	
	function calculate_rest_uphp($rtime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($pose == 5) 	//治疗姿态恢复双倍
			return $chprocess($rtime)*2; 
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
