<?php

namespace skill267
{
	$skill267deno = 200;//每有多少点伤害，HP就增加
	$skill267nume = Array(1,2,3);//HP每次增加多少 
	$upgradecost = Array(6,12,-1);//升级所需技能点
	
	function init() 
	{
		define('MOD_SKILL267_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[267] = '代偿';
	}
	
	function acquire267(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(267,'lvl','0',$pa);
	}
	
	function lost267(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(267,'lvl',$pa);
	}
	
	function check_unlocked267(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=9;
	}
	
	function upgrade267()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill267','player','logger'));
		if (!\skillbase\skill_query(267))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(267,'lvl');
		$ucost = $upgradecost[$clv];
		if ($clv == -1)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<$ucost) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint-=$ucost; \skillbase\skill_setvalue(267,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function get_skill267nume(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$clv = \skillbase\skill_getvalue(267,'lvl');
		if(!$clv) $clv = 0;
		eval(import_module('skill267'));
		return $skill267nume[$clv];
	}
	
	function check_effect267(&$pa, $active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($pa['hp'] <= 0) return;//死尸不处理
		if(empty($pa['skill267ohp'])) return;//战斗后才获得技能的，不处理
		$hpdown = $pa['skill267ohp'] - $pa['hp'];
		if($hpdown <= 0) return;//HP没下降的不处理
		eval(import_module('skill267'));
		$mhpup = floor($hpdown/$skill267deno) * get_skill267nume($pa);
		if($mhpup > 0){
			$pa['hp'] += $mhpup;
			$pa['mhp'] += $mhpup;
		}
		return $mhpup;
	}
	
	//战斗前记录HP
	function assault_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if(\skillbase\skill_query(267,$pa) && check_unlocked267($pa)) $pa['skill267ohp'] = $pa['hp'];
		if(\skillbase\skill_query(267,$pd) && check_unlocked267($pd)) $pd['skill267ohp'] = $pd['hp'];
	}
	
	//战斗后处理效果
	function assault_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		$mhpup = 0;
		if(\skillbase\skill_query(267,$pa) && check_unlocked267($pa)) {
			$mhpup = check_effect267($pa, $active);
			unset($pa['skill267ohp']);
			if($mhpup > 0 && $active) {
				eval(import_module('logger'));
				$log .= '「代偿」让你的生命上限增加了<span class="lime">'.$mhpup.'<span>点！<br>';
			}
		}
		if(\skillbase\skill_query(267,$pd) && check_unlocked267($pd)) {
			$mhpup = check_effect267($pd, $active);
			unset($pd['skill267ohp']);
			if($mhpup > 0 && !$active) {
				eval(import_module('logger'));
				$log .= '「代偿」让你的生命上限增加了<span class="lime">'.$mhpup.'<span>点！<br>';
			}
		}
		
		return $ret;
	}
}

?>