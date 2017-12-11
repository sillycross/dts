<?php

namespace skill348
{
	//各级要完成的成就名，如果不存在则取低的
	$ach348_name = array(
		1=>'我弱了不等于你强了',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach348_desc= array(
		0=>'使用C/M级卡片入场，并击杀<:threshold:>名使用S/A级卡片的<span class="yellow" title=\'等级7 金钱1000以上\'>活跃玩家</span>',
	);
	
	$ach348_proc_words = '击杀总数';
	
	$ach348_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach348_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach348_qiegao_prize = array(
		1 => 777,
	);
	
	function init() 
	{
		define('MOD_SKILL348_INFO','achievement;daily;');
		define('MOD_SKILL348_ACHIEVEMENT_ID','48');
		define('DAILY_TYPE348',3);
	}
	
	function acquire348(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(348,'cnt',0,$pa);
	}
	
	function lost348(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_card348(&$pa,&$pd){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = false;
		eval(import_module('cardbase'));
		if($pa['type'] || $pd['type']) return false;
		$rare_a = $cards[$pa['card']]['rare'];
		$rare_d = $cards[$pd['card']]['rare'];
		return in_array($rare_a,array('C','M')) && in_array($rare_d,array('S','A'));
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(348,$pa) && !$pd['type'] && $pd['hp'] <= 0)
		{
			if(check_card348($pa,$pd) && \achievement_base\ach_check_positive_player($pd)){
				$x=(int)\skillbase\skill_getvalue(348,'cnt',$pa);
				$x+=1;
				\skillbase\skill_setvalue(348,'cnt',$x,$pa);
			}			
		}
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 348){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>