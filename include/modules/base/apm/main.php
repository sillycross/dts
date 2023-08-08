<?php

namespace apm
{
	function init() 
	{
	}
	
	function add_a_actionnum()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$a_actionnum++;
	}
	
	function add_v_actionnum()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$v_actionnum++;
	}
	
	//改到discover()执行后结算
	function discover($schmode){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($schmode);
		add_v_actionnum();
		return $ret;
	}
	
//	function move($moveto = 99) 
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$chprocess($moveto);
//		add_v_actionnum();
//	}
//	
//	function search() {
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$chprocess();
//		add_v_actionnum();
//	}
	
	function itemuse($theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$tmp_theitems = $theitem['itms'];
		$tmp_hp = $hp; $tmp_sp = $sp;
		$chprocess($theitem);
		//消耗了道具耐久，或者HP、SP有变化（无限耐补给）时才算操作
		if($theitem['itms'] !== $tmp_theitems || $tmp_hp != $hp || $tmp_sp != $sp)
			add_v_actionnum();
	}
	
	//$use_endtime=1则用该玩家最后一次操作的时间作为apm分母，这样反映玩家真正在动的时间段的APM
	//返回值：0=>有效vapm；1=>所有apm
	function calc_apm($pa, $use_endtime=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!$use_endtime && $pa['hp'] > 0 && $gamestate >= 20) $judgetime = $now;
		else $judgetime = $pa['endtime'];
		$judgemin = ($judgetime - $pa['validtime']) / 60;
		if($judgemin < 1) $judgemin = 1;//避免除以0的错误
		$vapm = round($pa['v_actionnum'] / $judgemin * 10) / 10;
		$aapm = round($pa['a_actionnum'] / $judgemin * 10) / 10;
		return array($vapm, $aapm);
	}
	
	//返回值：0=>有效vapm；1=>所有apm
	function calc_winner_apm($pa, $judgetime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!isset($pa['v_actionnum']) && !isset($pa['actionnum'])) return array('-','-');
		if(!isset($pa['v_actionnum']) && isset($pa['actionnum'])) $pa['v_actionnum'] = $pa['actionnum'];
		$judgemin = $judgetime / 60;
		if(isset($pa['a_actionnum'])) $vapm = round($pa['v_actionnum'] / $judgemin * 10) / 10;
		else $vapm = '-';
		if(isset($pa['a_actionnum'])) $aapm = round($pa['a_actionnum'] / $judgemin * 10) / 10;
		else $aapm = '-';
		return array($vapm, $aapm);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		add_a_actionnum();
	}
}

?>