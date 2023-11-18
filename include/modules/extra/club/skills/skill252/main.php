<?php

namespace skill252
{
	$skill252_flag = 0;//战斗界面临时记录是否免疫雾天效果
	
	function init() 
	{
		define('MOD_SKILL252_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[252] = '天眼';
	}
	
	function acquire252(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost252(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked252(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		return $pa['lvl']>=7;
	}
	
	//仅在战斗界面中消除雾天的显示影响，主要影响check_fog()
	function init_battle($ismeet = 0) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(252) && check_unlocked252()) {
			
			eval(import_module('skill252'));
			$skill252_flag = 1;
		}
		
		$chprocess($ismeet);
		
		if(!empty($skill252_flag)) {
			$skill252_flag = 0;
			apply_sk252_effect();//改变部分数值的显示
		}
	}
	
	function check_fog()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill252'));
		if(!empty($skill252_flag)) return false;
		
		return $chprocess();
	}
	
	function apply_sk252_effect()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','metman'));
		if($w_hp <= 0) {
			$tdata['hpstate'] = "<span class=\"red b\">$hpinfo[3]</span>";
			$tdata['spstate'] = "<span class=\"red b\">$spinfo[3]</span>";
			$tdata['ragestate'] = "<span class=\"red b\">$rageinfo[3]</span>";
			$tdata['isdead'] = true;
		} else{
			if($w_hp < $w_mhp*0.2) {
				$tdata['hpstate'] = "<span class=\"red b\">$w_hp / $w_mhp</span>";
			} elseif($w_hp < $w_mhp*0.5) {
				$tdata['hpstate'] = "<span class=\"yellow b\">$w_hp / $w_mhp</span>";
			} else {
				$tdata['hpstate'] = "<span class=\"cyan b\">$w_hp / $w_mhp</span>";
			}
			$tdata['spstate'] = "$w_sp / $w_msp";
			if($w_rage >= 100) {
				$tdata['ragestate'] = "<span class=\"red b\">$w_rage</span>";
			} elseif($w_rage >= 30) {
				$tdata['ragestate'] = "<span class=\"yellow b\">$w_rage</span>";
			} else {
				$tdata['ragestate'] = $w_rage;
			}
		}
		$tdata['wepestate'] = $w_wepe;
	}
}

?>