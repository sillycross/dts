<?php

namespace skill270
{
	$skill270active = 9;//先制率加成
	$skill270hitrate = 9;//命中率加成
	
	function init() 
	{
		define('MOD_SKILL270_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[270] = '神影';
	}
	
	function acquire270(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost270(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked270(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=9;
	}
	
	//注意，这里检查的是$pa（技能持有者）在面对$pd时，本技能是否符合发动条件
	//战斗内和战斗外所用函数是不同的
	function check_skill270_proc(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		
		foreach(array('pa', 'pd') as $p){
			if(empty($$p['wep_kind'])){
				$$p['wep_kind']=\weapon\get_attack_method($$p);
				$$p['wep_kind_setting'] = 1;
			}
		}

		$o_log = $log;//某些技能（比如天威）会在获取熟练度时写log，要屏蔽掉这种消息
		$s1 = \weapon\get_skill($pa, $pd, $active);
		$s2 = \weapon\get_skill($pd, $pa, 1-$active);
		$log = $o_log;
		//测试用语句
		//$log .= '你的熟练度'.$s1.' 敌人熟练度'.$s2.'<br>';
		foreach(array('pa', 'pd') as $p){
			if(isset($$p['wep_kind_setting'])){
				unset($$p['wep_kind'],$$p['wep_kind_setting']);
			}
		}
		
		if ($s1 > $s2) return 1;
		else  return 0;
	}
	
	function get_hitrate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(270,$pa) && check_unlocked270($pa) && check_skill270_proc($pa, $pd, $active)){
			eval(import_module('skill270'));
			$ret *= 1+$skill270hitrate/100;
		}
		//echo 'hitrate'.$ret;
		return $ret;
	}
	
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(270,$ldata) && check_unlocked270($ldata) && check_skill270_proc($ldata, $edata, 1)) {
			eval(import_module('skill270'));
			$r += $skill270active/100;
		}
		return $chprocess($ldata,$edata)*$r;
	}
	
//	function calculate_active_obbs(&$ldata,&$edata)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$a = 0;
//		if (\skillbase\skill_query(270,$ldata) && check_unlocked270($ldata) && check_skill270_proc($ldata, $edata, 1)) {
//			eval(import_module('skill270'));
//			$a += $skill270active;
//		}
//		//echo 'activeadd'.$a;
//		return $chprocess($ldata,$edata)+$a;
//	}
	
}

?>