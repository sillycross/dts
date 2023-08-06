<?php

namespace skill1001
{
	function init() 
	{
		define('MOD_SKILL1001_INFO','unique;');
		eval(import_module('clubbase'));
		$clubskillname[1001] = '空降';
	}
	
	function acquire1001(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//获得技能（入场）时记录入场时间
		eval(import_module('sys'));
		$t = $now - $starttime;
		//0-5分钟，每分钟10熟
		//5-25分钟，每分钟3熟
		//25-60分钟，每分钟1熟
		$skillup = 0;
		for($i=0;$i<$t;$i++){
			if($i < 300) $skillup += 10/60;
			elseif($i < 1800) $skillup += 3/60;
			elseif($i < 3600) $skillup += 1/60;
		}
		
		$skillup = round($skillup);
		
		if(19 == $gametype) $skillup *= 2;//高速模式2倍熟练
//		$skillup = floor(($now - $starttime) / 12);
//		if($skillup > 300) $skillup = 300;
//		elseif($skillup < 0) $skillup = 0;
		\skillbase\skill_setvalue(1001,'skillup',$skillup,$pa);
	}
	
	function lost1001(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1001(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function process_1001($wkind){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','weapon'));
		if(!in_array($wkind, array_keys($skilltypeinfo))){
			$log.='技能参数错误。';
			$mode = 'command';$command = '';
			return;
		}
		$ref_skill = &$$wkind;
		$skillup = \skillbase\skill_getvalue(1001,'skillup');
		$ref_skill += $skillup;
		$log .= '你的'.$skilltypeinfo[$wkind].'熟增加了'.$skillup.'点。<br>';
		\skillbase\skill_lost(1001);
		$mode = 'command';$command = '';
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','input','logger'));
	
		if ($mode == 'special' && $command == 'skill1001_special') 
		{
			if (!\skillbase\skill_query(1001)) 
			{
				$log.='你没有这个技能。';
				$mode = 'command';$command = '';
				return;
			}
			if(!isset($subcmd)){
				$log.='技能参数丢失。';
				$mode = 'command';$command = '';
				return;
			}
			process_1001($subcmd);
			return;
		}
		$chprocess();
	}
}

?>