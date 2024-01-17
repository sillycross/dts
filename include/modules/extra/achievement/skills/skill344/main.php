<?php

namespace skill344
{
	//各级要完成的成就名，如果不存在则取低的
	$ach344_name = array(
		1=>'文化冲击',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach344_desc= array(
		1=>'使用【KEY系】武器击杀<:threshold:>名<span class="yellow b" title=\''.POSITIVE_PLAYER_DESC.'\'>活跃玩家</span>。伐木、解离和试炼模式不能完成',
	);
	
	$ach344_proc_words = '击杀总数';
	
	$ach344_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach344_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach344_qiegao_prize = array(
		1 => 300,
	);
	
	function init() 
	{
		define('MOD_SKILL344_INFO','achievement;daily;');
		define('MOD_SKILL344_ACHIEVEMENT_ID','44');
		define('DAILY_TYPE344',3);
	}
	
	function acquire344(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(344,'cnt',0,$pa);
	}
	
	function lost344(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_wep344($wep){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return strpos($wep,'KEY系')!==false;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(344,$pa) && !$pd['type'])
		{
			//武器为KEY系，且对方为活跃玩家
			if(check_wep344($pa['o_wep']) && \achievement_base\ach_check_positive_player($pa,$pd)){
				$x=(int)\skillbase\skill_getvalue(344,'cnt',$pa);
				$x+=1;
				\skillbase\skill_setvalue(344,'cnt',$x,$pa);
			}			
		}
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 344){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>