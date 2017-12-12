<?php

namespace apm
{
	function init() 
	{
	}
	
	function add_actionnum()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$actionnum++;
	}
	
	function move($moveto = 99) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($moveto);
		add_actionnum();
	}
	
	function search() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		add_actionnum();
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
			add_actionnum();
	}
	
	function calc_apm($pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($pa['hp'] > 0 && $gamestate >= 20) $judgetime = $now;
		else $judgetime = $pa['endtime'];
		$apm = $pa['actionnum'] / (($judgetime - $pa['validtime']) / 60);
		return round($apm*10)/10;
	}
}

?>