<?php

namespace skill252
{
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
	
	function check_unlocked252(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=7;
	}
	
	function apply_fog_meetenemy_effect($ismeet)	//无视雾天影响
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(252)) return;
		return $chprocess($ismeet);
	}
	
	function apply_sk252_effect()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','metman'));
		if($w_hp <= 0) {
			$tdata['hpstate'] = "<span class=\"red\">$hpinfo[3]</span>";
			$tdata['spstate'] = "<span class=\"red\">$spinfo[3]</span>";
			$tdata['ragestate'] = "<span class=\"red\">$rageinfo[3]</span>";
			$tdata['isdead'] = true;
		} else{
			if($w_hp < $w_mhp*0.2) {
				$tdata['hpstate'] = "<span class=\"red\">$w_hp / $w_mhp</span>";
			} elseif($w_hp < $w_mhp*0.5) {
				$tdata['hpstate'] = "<span class=\"yellow\">$w_hp / $w_mhp</span>";
			} else {
				$tdata['hpstate'] = "<span class=\"clan\">$w_hp / $w_mhp</span>";
			}
			$tdata['spstate'] = "$w_sp / $w_msp";
			if($w_rage >= 100) {
				$tdata['ragestate'] = "<span class=\"red\">$w_rage</span>";
			} elseif($w_rage >= 30) {
				$tdata['ragestate'] = "<span class=\"yellow\">$w_rage</span>";
			} else {
				$tdata['ragestate'] = $w_rage;
			}
		}
		$tdata['wepestate'] = $w_wepe;
	}
	
	function init_battle($ismeet)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess($ismeet);
		if (\skillbase\skill_query(252) && check_unlocked252($sdata)) apply_sk252_effect();
	}
}

?>
