<?php

namespace skill379
{
	//各级要完成的成就名，如果不存在则取低的
	$ach379_name = array(
		1=>'<:secret:>H4sIAAAAAAAAClOKKTU3SjaKKTUzMrSIKTW1SE2KKTVJNQGSlkkmRkoA0fnKZSAAAAA=',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach379_desc= array(
		1=>'<:secret:>H4sIAAAAAAAACjVPyxHFIAjsJRWACphevESFDuw/y3uTy84O7AeuccRKB+4o45ivAL9pj6O7znF6LBunOREmszA02hs4LYGSp/y2UEbQwlYyTb0mFkKa1T7Tqwq9as/M7dnl2SLZIhyJLRP+Xc3Lk97nxnxOeDtHBV/M9p0ibliopQGn5xuLsoZNv1C1RtcLfacwrecAAAA=',
	);
	
	$ach379_proc_words = '当前纪录';
	
	$ach379_unit = '次';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach379_threshold = array(
		1 => 1,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach379_qiegao_prize = array(
		1 => 573,
	);
	
	//各级给的卡片奖励
	$ach379_card_prize = array(
	);
	
	function init() 
	{
		define('MOD_SKILL379_INFO','achievement;secret;');
		define('MOD_SKILL379_ACHIEVEMENT_ID','79');
	}
	
	function acquire379(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(379,'allowed',0,$pa);
		\skillbase\skill_setvalue(379,'haga','',$pa);
		\skillbase\skill_setvalue(379,'num',0,$pa);
	}
	
	function lost379(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//允许判定
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if(\skillbase\skill_query(379,$pd)) {
			\skillbase\skill_setvalue(379,'allowed',1,$pd);
		}
		return;
	}
	
	//特判
	function skill539_revive_player(&$pdata=NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pdata);
		if(!$pdata) {
			eval(import_module('player'));
			$pdata = &$sdata;
		}
		if(\skillbase\skill_query(379,$pdata)) {
			\skillbase\skill_setvalue(379,'allowed',1,$pdata);
		}
	}
	
	//攻击记录逻辑
	function player_damaged_enemy(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if(!$pd['type'] && \skillbase\skill_query(379,$pa) && !empty(\skillbase\skill_getvalue(379,'allowed',$pa)) && empty(\skillbase\skill_getvalue(379,'cnt',$pa))) 
		{
			//必须连续攻击
			if(\skill598\check_wep_ygo($pa['wepk'], $pa['wepsk']) ){
				if($pd['name'] == \skillbase\skill_getvalue(379,'haga',$pa)) {
					\skillbase\skill_setvalue(379,'num',\skillbase\skill_getvalue(379,'num',$pa)+1,$pa);
				}else{
					\skillbase\skill_setvalue(379,'haga',$pd['name'],$pa);
					\skillbase\skill_setvalue(379,'num',1,$pa);
				}
			}else{
				\skillbase\skill_setvalue(379,'haga','',$pa);
				\skillbase\skill_setvalue(379,'num',0,$pa);
			}
		}
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if(!$pd['type'] && \skillbase\skill_query(379,$pa) && !empty(\skillbase\skill_getvalue(379,'allowed',$pa)) && empty(\skillbase\skill_getvalue(379,'cnt',$pa))) 
		{
			if(\skillbase\skill_getvalue(379,'num',$pa) >= 7 && $pd['name'] == \skillbase\skill_getvalue(379,'haga',$pa))
				\skillbase\skill_setvalue(379,'cnt',1,$pa);
		}
	}	
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 379 && (int)\skillbase\skill_getvalue(379,'cnt',$pa)){
			$ret += 1;
		}
		return $ret;
	}
}

?>