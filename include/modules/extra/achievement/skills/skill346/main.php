<?php

namespace skill346
{
	//各级要完成的成就名，如果不存在则取低的
	$ach346_name = array(
		1=>'暗箭难防',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach346_desc= array(
		1=>'使用陷阱击杀<:threshold:>名<span class="yellow b" title=\''.POSITIVE_PLAYER_DESC.'\'>活跃玩家</span>。伐木、解离和试炼模式不能完成',
	);
	
	$ach346_proc_words = '击杀总数';
	
	$ach346_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach346_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach346_qiegao_prize = array(
		1 => 400,
	);
	
	function init() 
	{
		define('MOD_SKILL346_INFO','achievement;daily;');
		define('MOD_SKILL346_ACHIEVEMENT_ID','46');
		define('DAILY_TYPE346',3);
	}
	
	function acquire346(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(346,'cnt',0,$pa);
	}
	
	function lost346(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function trap_hit()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','trap'));
		$skill346_itmsk0 = $itmsk0;
		$chprocess();	
		if(!$selflag && $playerflag && $hp<=0) {
			$edata = \player\fetch_playerdata_by_pid($skill346_itmsk0);
			if ( \skillbase\skill_query(346,$edata) && !$edata['type'] && \achievement_base\ach_check_positive_player($edata, $sdata))
			{
				$x=(int)\skillbase\skill_getvalue(346,'cnt',$edata);
				$x+=1;
				\skillbase\skill_setvalue(346,'cnt',$x,$edata);
				\player\player_save($edata);
			}
		}
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 346){
			$var = (int)\skillbase\skill_getvalue($achid,'cnt',$pa);
			$ret += $var;
		}
		return $ret;
	}
}

?>