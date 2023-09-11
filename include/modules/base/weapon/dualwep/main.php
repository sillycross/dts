<?php

namespace dualwep
{
	//双系武器默认无法发动任何战斗技能，但以下技能可以发动
	//已修改，双系武器可以发动本系战斗技能
	$dualwep_allowed_bskill = array();
	
	function init() 
	{
		eval(import_module('itemmain'));
		global $dualwep_iteminfo;
		$iteminfo+=$dualwep_iteminfo;
	}
	
	//如果是双系武器，在重置$sdata前后，记录wep_kind变量
	function load_playerdata($pdata)//其实最早这个函数是显示用的
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!empty(get_sec_attack_method($pdata)) && !empty($pdata['wep_kind'])) {
			$dw_pdata_o_wep_kind = $pdata['wep_kind'];
		}
		$chprocess($pdata);
		//注意由于这个函数对$pdata是传值，而实际修改的是$sdata，后边这里也得是$sdata
		if(!empty($dw_pdata_o_wep_kind)) {
			eval(import_module('player'));
			$sdata['wep_kind'] = $dw_pdata_o_wep_kind;
		}
	}
	
	function check_w2_valid($w2, $weps, $orig=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if (!isset($attinfo[$w2])) $w2='';//非$attinfo里定义的武器类型，全部无效
		elseif(in_array($w2, array('G','J')) && $weps==$nosta && !$orig) $w2 = 'P';//空枪当成殴
		elseif('B' == $w2 && $weps==$nosta && !$orig) $w2 = 'K';//空弓当成斩
		return $w2;
	}
	
	function get_other_attack_method(&$pdata, $w1, $orig=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($w1 == $pdata['wepk'][1]) $wo = $pdata['wepk'][2];
		else $wo = $pdata['wepk'][1];
		return check_w2_valid($wo, $pdata['weps'], $orig);
	}
	
	function get_sec_attack_method(&$pdata, $orig=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$w2 = substr($pdata['wepk'],2,1);
		return check_w2_valid($w2, $pdata['weps'], $orig);
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
	
	//如果选用的攻击方式的熟练度是另一种攻击方式的一半以下，则会自动加到另一种攻击方式的一半
	function get_skill_by_kind(&$pa, &$pd, $active, $wep_skillkind)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		$ret = $chprocess($pa,$pd,$active,$wep_skillkind);
		$w2 = get_sec_attack_method($pa);
		if($w2){
			$wo = get_other_attack_method($pa, $pa['wep_kind']);
			$reto = $chprocess($pa,$pd,$active,$skillinfo[$wo]);
			if($ret < $reto / 2) $ret = round($reto / 2);
		}
		//echo $ret.' ';
		return $ret;
	}
	
	function get_dualwep_imp_kind(&$pa, &$pd, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$w1 = \weapon\get_attack_method($pa);
		$w2 = get_sec_attack_method($pa);
		$w1t = get_dualwep_imp_kind_tier($w1, $pa['weps']);
		$w2t = get_dualwep_imp_kind_tier($w2, $pa['weps']);
		if($w2t > $w1t) return $w2;
		else return $w1;
	}
	
	//多重武器消耗规则：
	//无限耐投爆灵 > 射/弓 > 殴斩 > 有限耐投爆灵
	function get_dualwep_imp_kind_tier($ak, $weps){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('weapon'));
		if(in_array($ak, array('C','D','F')) && $nosta === $weps) return 40;
		elseif(in_array($ak, array('G','J','B'))) return 30;
		elseif(in_array($ak, array('N','P','K'))) return 20;
		return 10;
	}
	
	//仅在计算消耗时替代掉武器类别
	function calculate_wepimp(&$pa, &$pd, $active, $is_hit)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//计算实际的消耗类别
		$w2 = get_sec_attack_method($pa);
		if($w2){
			$pa['dw_o_wep_kind'] = $pa['wep_kind'];
			$pa['wep_kind'] = get_dualwep_imp_kind($pa, $pd, $active);
		}
		$chprocess($pa,$pd,$active,$is_hit);
		//计算消耗方式后恢复武器类别
		if(isset($pa['dw_o_wep_kind'])){
			if($pa['wep_kind'] != $pa['dw_o_wep_kind'])
				$pa['wep_kind'] = $pa['dw_o_wep_kind'];
			unset($pa['dw_o_wep_kind']);
		}		
	}
	
	//仅在应用消耗时替代掉武器类别
	function apply_weapon_imp(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//计算实际的消耗类别
		$w2 = get_sec_attack_method($pa);
		if($w2){
			$pa['dw_o_wep_kind'] = $pa['wep_kind'];
			$pa['wep_kind'] = get_dualwep_imp_kind($pa, $pd, $active);
		}
		$chprocess($pa,$pd,$active);
		//应用消耗后恢复武器类别
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
		$log .= '武器主攻系别已经切换为<span class="yellow b">'.$iteminfo['W'.$wepk[1]].'</span>。';
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
	
	//双系武器不再忽略战斗技能，但是一切跟系别有关的称号战斗技能都无法发动
	function get_skillkind(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		$sm = get_sec_attack_method($pa);
		if($sm) $ret .= $sm;
		return $ret;
	}
	
	//双系武器忽略战斗技能
	//这个函数应该是“与”的关系
//	function check_battle_skill_available(&$edata,$skillno)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('player','dualwep'));
//		if (get_sec_attack_method($sdata) && !in_array($skillno, $dualwep_allowed_bskill)) return false;
//		else return $chprocess($edata,$skillno);
//	}

	//双系武器忽略战斗技能
//	function strike_prepare(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('dualwep'));
//		if (get_sec_attack_method($pa) && !in_array($pa['bskill'], $dualwep_allowed_bskill)) $pa['bskill']=0;
//		
//		return $chprocess($pa, $pd, $active);
//	}
}

?>