<?php

namespace instance8
{
	function init() {
		eval(import_module('skillbase', 'cardbase'));
		$valid_skills[18] = array(1001,181);
		$card_force_different_gtype[] = 18;
		$card_need_charge_gtype[] = 18;
		$card_cooldown_discount_gtype[18] = 0.5;
	}
	
	//入场时，如果是荣耀模式，配发生命探测器和异常药
	function init_enter_battlefield_items($ebp){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ebp = $chprocess($ebp);
		eval(import_module('sys'));
		if(18==$gametype){
			$ebp['itm4'] = '生命探测器'; $ebp['itmk4'] = 'ER'; $ebp['itme4'] = 3; $ebp['itms4'] = 1;$ebp['itmsk4'] = '';
			$ebp['itm5'] = '全恢复药剂'; $ebp['itmk5'] = 'Ca'; $ebp['itme5'] = 1; $ebp['itms5'] = 3;$ebp['itmsk5'] = '';
		}
		return $ebp;
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance8'));
		if (18 == $gametype){
			return $npcinfo_instance8;
		}else return $chprocess();
	}
	
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance8'));
		if (18 == $gametype){
			$file = __DIR__.'/config/shopitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (18 == $gametype){
			$file = __DIR__.'/config/mapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_startingitemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (18 == $gametype){
			$file = __DIR__.'/config/stitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_startingwepfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (18 == $gametype){
			$file = __DIR__.'/config/stwep.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (18 == $gametype){
			$file = __DIR__.'/config/trapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//荣耀模式测试：高速、肌肉、铁拳变为常规称号
	function club_choice_probability_process($clublist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (18 == $gametype){
			foreach(Array(10, 14, 19) as $i) {
				if(isset($clublist[$i])) $clublist[$i]['type'] = 0;
				if(19 == $i) $clublist[$i]['probability'] = 100;
			}
			return $clublist;
		}else return $chprocess($clublist);
	}
}

?>