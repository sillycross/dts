<?php

namespace dualwep
{
	//双系武器默认无法发动任何战斗技能，但以下技能可以发动
	$dualwep_allowed_bskill = array();
	
	function init() 
	{
		eval(import_module('itemmain'));
		global $dualwep_iteminfo;
		$iteminfo+=$dualwep_iteminfo;
	}
	
	function get_sec_attack_method(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		extract($pdata);
		eval(import_module('weapon'));
		$w2 = substr($wepk,2,1);
		if (is_numeric($w2)) $w2='';
		if ($w2)
		{
			if(in_array($w2, array('G','J')) && $weps==$nosta) $w2 = 'P';
		}
		return $w2;
	}
	
	function check_attack_method(&$pdata, $wm)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($wm && get_sec_attack_method($pdata)==$wm) return 1;
		return $chprocess($pdata,$wm);
	}
	
	function weapon_break(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('weapon','logger'));
		
		if ('G'==get_sec_attack_method($pa) || 'J'==get_sec_attack_method($pa))	//如果第二系别是射系，道具耗尽会变为无穷耐
		{
			$pa['wep_kind'] = get_sec_attack_method($pa);
		}
		$chprocess($pa,$pd,$active);
	}
	
	//多重武器消耗规则：
	//无限耐投爆灵 > 射 > 殴斩 > 有限耐投爆灵
	function get_dualwep_imp_kind(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$w1 = \weapon\get_attack_method($pa);
		$w2 = get_sec_attack_method($pa);
		$w1t = get_dualwep_imp_kind_tier($w1, $pa['weps']);
		$w2t = get_dualwep_imp_kind_tier($w2, $pa['weps']);
		if($w2t > $w1t) return $w2;
		else return $w1;
	}
	
	function get_dualwep_imp_kind_tier($ak, $weps){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if(in_array($ak, array('C','D','F')) && $nosta === $weps) return 40;
		elseif(in_array($ak, array('G','J'))) return 30;
		elseif(in_array($ak, array('N','P','K'))) return 20;
		return 10;
	}
	
	//仅在计算消耗时替代掉武器类别
	function calculate_wepimp(&$pa, &$pd, $active, $is_hit)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$w2 = get_sec_attack_method($pa);
		if($w2){
			$pa['dw_o_wep_kind'] = $pa['wep_kind'];
			$pa['wep_kind'] = get_dualwep_imp_kind($pa, $pd, $active);
		}
		$chprocess($pa,$pd,$active,$is_hit);
	}
	
	//应用消耗后恢复武器类别
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if(isset($pa['dw_o_wep_kind'])){
			if($pa['wep_kind'] != $pa['dw_o_wep_kind'])
				$pa['wep_kind'] = $pa['dw_o_wep_kind'];
			unset($pa['dw_o_wep_kind']);
		}		
	}
	
	//改变双重武器主攻系别
	function dualwep_change_am()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmain'));
		if(substr($wepk,0,1) != 'W' || $wepk == 'WN'){
			$log .= '请先装备武器！';
			$mode = 'command';
			return;
		}elseif(!get_sec_attack_method($sdata)) {
			$log .= '你并未装备双重系别的武器。';
			$mode = 'command';
			return;
		}
		list($wepk[1], $wepk[2]) = array($wepk[2], $wepk[1]);//玩个杂技
		$log .= '武器主攻系别已经切换为<span class="yellow">'.$iteminfo['W'.$wepk[1]].'</span>。';
		$mode = 'command';
		return;
	}
	
	function act()	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','input'));
		
		if ($mode == 'command' && $command=='special' && $sp_cmd == 'sp_dualwep_am')
		{
			dualwep_change_am();
			$mode = 'command';
			return;
		}
		
		$chprocess();
	}
	
	//双系武器忽略战斗技能
	//这个函数应该是“与”的关系
	function check_battle_skill_available(&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','dualwep'));
		if (get_sec_attack_method($sdata) && !in_array($skillno, $dualwep_allowed_bskill)) return false;
		else return $chprocess($edata,$skillno);
	}

	//双系武器忽略战斗技能
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('dualwep'));
		if (get_sec_attack_method($pa) && !in_array($pa['bskill'], $dualwep_allowed_bskill)) $pa['bskill']=0;
		
		return $chprocess($pa, $pd, $active);
	}
}

?>