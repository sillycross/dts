<?php

namespace ex_mhp_temp_up
{
	
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^hu'] = '升血';
		$itemspkdesc['^hu']='允许恢复道具把你的生命值最多回复到最大值+<:skn:>点，效果可叠加。';
		$itemspkremark['^hu']='具体增加数值视装备而定。卸下此装备时会扣除所有额外的生命值。';
	}
	
	function itemuse_edible_get_mhp(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess();
		eval(import_module('player'));
		//由于有可能在战斗时回复HP，这里不应该用地图时的属性判断函数
		$dummy = \player\create_dummy_playerdata();
		$flag = \attrbase\check_in_itmsk('^hu', \attrbase\get_ex_def_array($dummp, $sdata, 0), 1);
		//毅重状态下不能升血
		if(\skillbase\skill_query(28) && \skill28\check_unlocked28($sdata) && 2 == \skillbase\skill_getvalue(28,'choice',$sdata))
			$flag = false;
		if(false !== $flag) {
			$ret += $flag;
		}
		
		return $ret;
	}
	
	function ex_mhp_temp_lose(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if($hp > $mhp) {
			$hpdown = $hp - $mhp;
			$hp = $mhp;
			$log .= '<span class="red b">你失去了'.$hpdown.'点生命值。</span><br>';
		}
	}
	
	function itemdrop_valid_check($itm, $itmk, $itme, $itms, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($itm, $itmk, $itme, $itms, $itmsk);
		$flag = \attrbase\check_in_itmsk('^hu',\itemmain\get_itmsk_array($itmsk));
		if(false !== $flag){
			ex_mhp_temp_lose();
		}
		return $ret;
	}
	
	function itemoff_valid_check($itm, $itmk, $itme, $itms, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($itm, $itmk, $itme, $itms, $itmsk);
		$flag = \attrbase\check_in_itmsk('^hu',\itemmain\get_itmsk_array($itmsk));
		if(false !== $flag){
			ex_mhp_temp_lose();
		}
		return $ret;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		$lose_flag = 0;
		if(strpos ( $itmk, 'W' ) === 0 || strpos ( $itmk, 'D' ) === 0 || strpos ( $itmk, 'A' ) === 0) {
			eval(import_module('player','logger'));
			if(strpos ( $itmk, 'W' ) === 0) $obj = 'wep';
			elseif(strpos ( $itmk, 'DB' ) === 0) $obj = 'arb';
			elseif(strpos ( $itmk, 'DH' ) === 0) $obj = 'arh';
			elseif(strpos ( $itmk, 'DA' ) === 0) $obj = 'ara';
			elseif(strpos ( $itmk, 'DF' ) === 0) $obj = 'arf';
			elseif(strpos ( $itmk, 'A' ) === 0) $obj = 'art';
			
			$flag = \attrbase\check_in_itmsk('^hu',\itemmain\get_itmsk_array(${$obj.'sk'}));
			if(false !== $flag){
				$lose_flag = 1;
			}
		}
		$chprocess($theitem);
		if($lose_flag) {
			ex_mhp_temp_lose();
		}
	}
	
	//因为别的原因而在操作以后失去升血属性时也会失去血量
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\attrbase\check_itmsk('^hu')) $tmp_hp_up_flag = 1;
		$chprocess();
		if(!empty($tmp_hp_up_flag) && !\attrbase\check_itmsk('^hu')) ex_mhp_temp_lose();
	}
	
	//毅重开启时自动失去血量
	function upgrade28()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('player'));
		if (\skillbase\skill_query(28) && \skill28\check_unlocked28($sdata))
		{
			if(\attrbase\check_itmsk('^hu')) {
				ex_mhp_temp_lose();
			}
		}
	}
}

?>