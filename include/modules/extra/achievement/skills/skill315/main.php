<?php

namespace skill315
{
	//各级要完成的成就名，如果不存在则取低的
	$ach315_name = array(
		1=>'第一滴血',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach315_desc= array(
		1=>'战斗击杀<:threshold:>名<span class="yellow b" title=\''.POSITIVE_PLAYER_DESC.'\'>活跃玩家</span>。伐木、解离和试炼模式不能完成',
	);
	
	$ach315_proc_words = '击杀总数';
	
	$ach315_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach315_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach315_qiegao_prize = array(
		1 => 200,
	);
	
	function init() 
	{
		define('MOD_SKILL315_INFO','achievement;daily;');
		define('MOD_SKILL315_ACHIEVEMENT_ID','15');
		define('DAILY_TYPE315',3);
	}
	
	function acquire315(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(315,'cnt',0,$pa);
	}
	
	function lost315(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(315,$pa) && !$pd['type'])
		{
			//对方为活跃玩家
			if(\achievement_base\ach_check_positive_player($pa,$pd)){
				$x=(int)\skillbase\skill_getvalue(315,'cnt',$pa);
				$x+=1;
				\skillbase\skill_setvalue(315,'cnt',$x,$pa);
			}			
		}
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 315){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>