<?php

namespace instance9
{
	function init() {
		eval(import_module('map','gameflow_combo'));
		$areainterval[19] = 5;
		$deathlimit_by_gtype[19] = 100;
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance9'));
		if (19 == $gametype){
			return $npcinfo_instance9;
		}else return $chprocess();
	}
	
	function get_shoplist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance9'));
		if (19 == $gametype){
			$file = __DIR__.'/config/shopitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (19 == $gametype){
			$file = __DIR__.'/config/mapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_startingitemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (19 == $gametype){
			$file = __DIR__.'/config/stitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_startingwepfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (19 == $gametype){
			$file = __DIR__.'/config/stwep.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (19 == $gametype){
			$file = __DIR__.'/config/trapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//急速模式怒气获得效率加倍
	function calculate_attack_rage_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$rageup = $chprocess($pa, $pd, $active);
		eval(import_module('sys'));
		if (19 == $gametype){
			$rageup *= 2;
		}
		return $rageup;
	}
	
	//急速模式，玩家熟练度获得效率加倍
	function calculate_attack_weapon_skill_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skillup = $chprocess($pa,$pd,$active);
		eval(import_module('sys'));
		if (19 == $gametype && !$pa['type']){
			$skillup *= 2;
		}
		return $skillup;
	}
	
	//急速模式，玩家经验获得效率加倍
	function calculate_attack_exp_gain(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$expup = $chprocess($pa,$pd,$active);
		eval(import_module('sys'));
		if (19 == $gametype && !$pa['type']){
			$expup *= 2;
		}
		return $expup;
	}
	
	//急速模式开局禁区时间不会取整
	function rs_areatime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(19==$gametype)	return $starttime + \map\get_area_interval() * 60; 
		return $chprocess();
	}
	
	
}

?>