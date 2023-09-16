<?php

namespace skill507
{
	$skill507_intv = 6;//每被攻击多少次触发
	
	$skill507_display_effect = array(//斩杀时的特殊文字显示
		'一一五' => array(
			'type' => 42,
			'icon' => 'avatar_rek/115_s.png',
		)
	);
	
	function init() 
	{
		define('MOD_SKILL507_INFO','unique;');
		eval(import_module('clubbase'));
		$clubskillname[507] = '断罪';
	}
	
	function acquire507(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		init_countdown507($pa);
	}
	
	function lost507(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked507(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skill507_i = \skillbase\skill_getvalue(507,'i',$pa);
		return $skill507_i <= 0;
	}
	
	function init_countdown507(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill507'));
		\skillbase\skill_setvalue(507,'i',$skill507_intv,$pa);
	}
	
	//被攻击时计数
	function apply_damage(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(507,$pd) && $pa['dmg_dealt']>0 && $pd['hp']>0)
		{
			$skill507_i = \skillbase\skill_getvalue(507,'i',$pd);
			$skill507_i = max(0, $skill507_i - 1);
			\skillbase\skill_setvalue(507,'i',$skill507_i,$pd);
		}
		return $ret;
	}

	//解锁后，武器攻击力x66666，攻击最终伤害x666，命中率+66%，双穿概率+66%，必定超射程反击
	function attack_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(507,$pa) && check_unlocked507($pa))
		{
			eval(import_module('sys','logger'));
			$pa['skill507_flag'] = 1;
			//由于$pa里的乱七八糟可能会被洗掉，记录在$uip里吧
			$uip['skill507_flag'] = $pa['name'];
			$pa['state'] = 1;
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys', 'logger'));
		
		if (!empty($pa['skill507_flag'])) {
			$log.=\battle\battlelog_parser($pa, $pd, $active, "<span class=\"red b\"><:pa_name:>出其不意地施展出华丽的攻击！<:pd_name:>觉得你大限将至！</span><br>");
		}
		$chprocess($pa, $pd, $active);
	}
	
	function get_external_att(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if ( !empty($pa['skill507_flag']) )
		{
			$ret *= 66666;
		}
		return $ret;
	}
	
	function get_hitrate_base(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		$a = 0;
		if (!empty($pa['skill507_flag'])) 
		{
			$a = 66;
		}
		return $ret+$a;
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if ( !empty($pa['skill507_flag']) )
		{
			$r=Array(666);	
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function get_ex_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if ( !empty($pa['skill507_flag']) ) {
			$ret += 66;
		}
		return $ret;
	}
	
	function get_attr_pierce_proc_rate(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if ( !empty($pa['skill507_flag']) ) {
			$ret += 66;
		}
		return $ret;
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (!empty($pa['skill507_flag'])) 
		{
			$ret = array_merge($ret, array('n','y'));
		}
		return $ret;
	}
	
	function check_counterable_by_weapon_range(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (!empty($pa['skill507_flag'])) 
			$ret = 1;
		return $ret;
	}
	
	//攻击结束时，已解锁变为未解锁状态
	function attack_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!empty($pa['skill507_flag']))
		{
			init_countdown507($pa);
			$pa['state'] = 1;
		}
		$chprocess($pa, $pd, $active);
	}
	
	function metman_load_playerdata($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pdata);
		if(\skillbase\skill_query(507,$pdata) && check_unlocked507($pdata)){
			eval(import_module('sys'));
			$uip['skill507_flag'] = $pdata['name'];
		}
	}
	
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(507,$edata) && check_unlocked507($edata)){
			eval(import_module('sys'));
			$uip['skill507_flag'] = $edata['name'];
		}
		$chprocess($edata);
	}
	
	function init_battle($ismeet = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($ismeet);
		eval(import_module('sys','metman'));
		if(!empty($uip['skill507_flag']) && $uip['skill507_flag']==$w_name){
			eval(import_module('skill507','player'));
			if(!empty($skill507_display_effect[$w_name])) {
				$ddata = $skill507_display_effect[$w_name];
				if(!empty($ddata['type'])) $tdata['sNoinfo'] = $typeinfo[$ddata['type']];
				if(!empty($ddata['icon'])) list($tdata['iconImg'], $tdata['iconImgB'], $tdata['iconImgBwidth']) = \player\icon_parser($w_type, $w_gd, $ddata['icon']);
			}
		}
	}
	
	function npcchat_tag_process(&$pa, &$pd, $active, $situation, $flag, $nchat){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($chattag, $sid) = $chprocess($pa, $pd, $active, $situation, $flag, $nchat);
		eval(import_module('sys'));
		if(!empty($uip['skill507_flag']) && in_array($uip['skill507_flag'], array($pa['name'], $pd['name']))){
			if(isset($nchat[$chattag.'_sp'])) $chattag .= '_sp';
		}
		return array($chattag, $sid);
	}
}

?>