<?php

namespace instance9
{
	function init() {
		eval(import_module('map','gameflow_combo','skillbase'));
		$areainterval[19] = 10;
		$deathlimit_by_gtype[19] = 100;
		$valid_skills[19] = array(1001,1002);
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance9'));
		if (19 == $gametype){
			return $npcinfo_instance9;
		}else return $chprocess();
	}
	
//	function get_shopconfig(){
//		if (eval(__MAGIC__)) return $___RET_VALUE; 
//		eval(import_module('sys','instance9'));
//		if (19 == $gametype){
//			$file = __DIR__.'/config/shopitem.config.php';
//			$l = openfile($file);
//			return $l;
//		}else return $chprocess();
//	}
	
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
	function calculate_attack_rage_gain_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		eval(import_module('sys'));
		if (19 == $gametype){
			$ret *= 2;
		}
		return $ret;
	}
	
	//急速模式，玩家熟练度获得效率加倍
	function calculate_attack_weapon_skill_gain_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		eval(import_module('sys'));
		if (19 == $gametype && !$pa['type']){
			$ret *= 2;
		}
		return $ret;
	}
	
	//急速模式，玩家经验获得效率加倍
	function calculate_attack_exp_gain_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		eval(import_module('sys'));
		if (19 == $gametype && !$pa['type']){
			$ret *= 2;
		}
		return $ret;
	}
	
	//急速模式开局禁区时间不会取整
	function rs_areatime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(19==$gametype)	return $starttime + \map\get_area_interval() * 60; 
		return $chprocess();
	}
	
	//急速模式地图防具的效果值翻倍，钉和磨刀石效果值x5
	function mapitem_data_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ret = $chprocess($data);
		eval(import_module('sys'));
		if(19==$gametype){
			if(strpos($ret[4],'D')===0){
				$ret[5] *= 2;
			}elseif((strpos($ret[3], '钉') !==false || strpos($ret[3], '磨刀石') !==false) && strpos($ret[4],'Y')===0){
				$ret[5] *= 5;
			}
		}
		return $ret;
	}
	
	//急速模式商店防具的效果值翻倍，钉和磨刀石效果值、价格x5
	function shopitem_data_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ret = $chprocess($data);
		eval(import_module('sys'));
		if(19==$gametype){
			if(strpos($ret[5],'D')===0){
				$ret[6] *= 2;
			}elseif((strpos($ret[4], '钉') !==false || strpos($ret[4], '磨刀石') !==false) && strpos($ret[5],'Y')===0){
				$ret[6] *= 5;$ret[2] *= 5;
			}
		}
		return $ret;
	}
	
	//急速模式英灵殿、雏菊无事件
	function event_available(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys', 'player'));
		if(19==$gametype && ($pls == 33 || $pls == 34)) return false;
		return $chprocess();
	}
	
	function get_uee_deathlog () {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		eval(import_module('sys'));
		if(19 == $gametype) $ret = '<span class="clan">“那家伙托付给我的东西，怎么能让你随便玷污？”</span>——<span class="clan">狂飙</span><br>';
		return $ret;
	}
}

?>