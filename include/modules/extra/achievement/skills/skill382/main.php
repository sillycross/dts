<?php

namespace skill382
{
	//各级要完成的成就名，如果不存在则取低的
	$ach382_name = array(
		1=>'扫黑除恶',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach382_desc= array(
		1=>'击杀<:threshold:>次红暮',
	);
	
	$ach382_proc_words = '击杀次数';
	
	$ach382_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach382_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach382_qiegao_prize = array(
		1 => 300,
	);
	
	function init() 
	{
		define('MOD_SKILL382_INFO','achievement;daily;');
		define('MOD_SKILL382_ACHIEVEMENT_ID','82');
		define('DAILY_TYPE382',2);
	}
	
	function acquire382(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(382,'cnt',0,$pa);
	}
	
	function lost382(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(382,$pa) && $pd['type']==1 && strpos($pd['name'],'红暮')!==false && $pd['hp'] <= 0 )
		{
			$x=(int)\skillbase\skill_getvalue(382,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(382,'cnt',$x,$pa);
		}
		
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 382){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>