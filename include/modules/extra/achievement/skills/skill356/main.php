<?php

namespace skill356
{
	//各级要完成的成就名，如果不存在则取低的
	$ach356_name = array(
		1=>'颁奖典礼',
		2=>'一回四杀',
		3=>'死神来袭',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach356_desc= array(
		1=>'一场游戏中使用<span class="yellow b" title="殴、斩、射、投、爆、灵、陷阱、下毒、DN各算一种">4种或更多方式</span>击杀玩家，总计<:threshold:>场</span>。伐木和解离模式不能完成',
	);
	
	$ach356_proc_words = '完成场次';
	
	$ach356_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach356_threshold = array(
		1 => 1,
		2 => 3,
		3 => 7,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach356_qiegao_prize = array(
		1 => 333,
		2 => 999,
		3 => 2333,
	);
	
	function init() 
	{
		define('MOD_SKILL356_INFO','achievement;');
		define('MOD_SKILL356_ACHIEVEMENT_ID','56');
		define('DAILY_TYPE356',3);
	}
	
	function acquire356(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(356,'cnt',0,$pa);
		\skillbase\skill_setvalue(356,'method','0',$pa);
	}
	
	function lost356(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		if ( \skillbase\skill_query(356,$pa) && !$pd['type'] && $pd['hp'] <= 0)
		{
			$dtype = $pd['state'];
			$methodlist = explode('_',\skillbase\skill_getvalue(356,'method',$pa));
			if(!in_array($dtype, $methodlist)){
				$x=(int)\skillbase\skill_getvalue(356,'cnt',$pa);
				$x+=1;
				\skillbase\skill_setvalue(356,'cnt',$x,$pa);
				$methodlist[] = $dtype;
				\skillbase\skill_setvalue(356,'method',implode('_',$methodlist),$pa);
			}			
		}
		return $ret;
	}
	
//	function player_kill_enemy(&$pa,&$pd,$active){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$chprocess($pa, $pd, $active);		
//		if ( \skillbase\skill_query(356,$pa) && !$pd['type'] && $pd['hp'] <= 0)
//		{
//			$dtype = $pd['state'];
//			$methodlist = explode('_',\skillbase\skill_getvalue(356,'method',$pa));
//			if(!in_array($dtype, $methodlist)){
//				$x=(int)\skillbase\skill_getvalue(356,'cnt',$pa);
//				$x+=1;
//				\skillbase\skill_setvalue(356,'cnt',$x,$pa);
//				$methodlist[] = $dtype;
//				\skillbase\skill_setvalue(356,'method',implode('_',$methodlist),$pa);
//			}			
//		}
//	}	
//	
//	function trap_hit()
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('player','trap'));
//		$skill356_itmsk0 = $itmsk0;
//		$chprocess();	
//		if(!$selflag && $playerflag && $hp<=0) {
//			$edata = \player\fetch_playerdata_by_pid($skill356_itmsk0);
//			if ( \skillbase\skill_query(356,$edata) && !$edata['type'])
//			{
//				$methodlist = explode('_',\skillbase\skill_getvalue(356,'method',$edata));
//				if(!in_array($state, $methodlist)){
//					$x=(int)\skillbase\skill_getvalue(356,'cnt',$edata);
//					$x+=1;
//					\skillbase\skill_setvalue(356,'cnt',$x,$edata);
//					$methodlist[] = $state;
//					\skillbase\skill_setvalue(356,'method',implode('_',$methodlist),$edata);
//					\player\player_save($edata);
//				}
//			}
//		}
//	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 356){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			if($var >= 4) $ret += 1;
		}
		return $ret;
	}
}

?>