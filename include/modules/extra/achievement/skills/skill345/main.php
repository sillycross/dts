<?php

namespace skill345
{
	//各级要完成的成就名，如果不存在则取低的
	$ach345_name = array(
		1=>'常磐森林的愤怒',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach345_desc= array(
		1=>'使用【小黄的】武器击杀<:threshold:>名<span class="yellow b" title=\''.POSITIVE_PLAYER_DESC.'\'>活跃玩家</span>。伐木、解离模式不能完成',
	);
	
	$ach345_proc_words = '击杀总数';
	
	$ach345_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach345_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach345_qiegao_prize = array(
		1 => 240,
	);
	
	function init() 
	{
		define('MOD_SKILL345_INFO','achievement;daily;');
		define('MOD_SKILL345_ACHIEVEMENT_ID','45');
		define('DAILY_TYPE345',3);
	}
	
	function acquire345(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(345,'cnt',0,$pa);
	}
	
	function lost345(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_wep345($wep){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return strpos($wep,'小黄的')!==false;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(345,$pa) && !$pd['type'])
		{
			//武器为小黄，且对方为活跃玩家
			if(check_wep345($pa['o_wep']) && \achievement_base\ach_check_positive_player($pa,$pd)){
				$x=(int)\skillbase\skill_getvalue(345,'cnt',$pa);
				$x+=1;
				
				\skillbase\skill_setvalue(345,'cnt',$x,$pa);
			}			
		}
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 345){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>