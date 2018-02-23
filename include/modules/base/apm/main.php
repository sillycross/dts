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
	
	function move($moveto = 99) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($moveto);
		add_v_actionnum();
	}
	
	function search() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		add_v_actionnum();
	}
	
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
	
	//返回值：0=>有效vapm；1=>所有apm
	function calc_apm($pa, $use_endtime=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!$use_endtime && $pa['hp'] > 0 && $gamestate >= 20) $judgetime = $now;
		else $judgetime = $pa['endtime'];
		$judgemin = ($judgetime - $pa['validtime']) / 60;
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