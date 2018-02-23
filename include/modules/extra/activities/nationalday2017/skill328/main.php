<?php

namespace skill328
{
	$ach328_name = array(
		1=>'血染乐园 LV1',
		2=>'血染乐园 LV2',
		3=>'血染乐园 LV3',
	);
	
	$ach328_threshold = array(
		1 => 5,
		2 => 20,
		3 => 60,
		999 => NULL
	);
	$ach328_qiegao_prize = array(
		1 => 400,
		2 => 700,
		3 => 500,
		999 => NULL
	);
	
	function init() 
	{
		define('MOD_SKILL328_INFO','achievement;spec-activity;');
		define('MOD_SKILL328_ACHIEVEMENT_ID','28');
//		$ach_allow_mode[328] = array(18);
	}
	
	function acquire328(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(328,'cnt','0',$pa);
	}
	
	function lost328(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(328,'cnt',$pa);
	}
	
//	function player_kill_enemy(&$pa,&$pd,$active){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$chprocess($pa, $pd, $active);//最后执行，确保复活不会触发
//		eval(import_module('sys'));
//		if (18 == $gametype && \skillbase\skill_query(328,$pa) && !$pd['type'] && $pd['money']>=2000 && $pd['lvl']>=10 && $pd['hp'] <= 0)
//		{
//			$x=(int)\skillbase\skill_getvalue(328,'cnt',$pa);
//			$x+=1;
//			\skillbase\skill_setvalue(328,'cnt',$x,$pa);
//		}
//	}	
	
	function kill(&$pa, &$pd) 
	{	
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$ret = $chprocess($pa, $pd);
		if(18 == $gametype && \skillbase\skill_query(328,$pa) && !$pa['type'] && !$pd['type'] && $pd['money']>=2000 && $pd['lvl']>=10 && $pd['hp'] <= 0)
		{
			$x=(int)\skillbase\skill_getvalue(328,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(328,'cnt',$x,$pa);
		}
		return $ret;
	}
	
	function finalize328(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(328,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		$cardprize = array(200, 201, 202, 203, 204);
		eval(import_module('sys', 'skill328'));
		$result = $db->query("SELECT cardlist FROM {$gtablepre}users WHERE username='{$pa['name']}'");
		$cardlist = $db->fetch_array($result);
		$cardlist = $cardlist['cardlist'];
		$nowcards = explode('_', $cardlist);
		$cardprize = array_diff($cardprize, $nowcards);
		if(empty($cardprize)) $cardprize[] = 200;
		
		if ( $ox < $ach328_threshold[1] && $x >= $ach328_threshold[1] ){
			\cardbase\get_qiegao($ach328_qiegao_prize[1], $pa);
		}
		if ( $ox < $ach328_threshold[2] && $x >= $ach328_threshold[2] ){
			\cardbase\get_qiegao($ach328_qiegao_prize[2],$pa);
		}
		if ( $ox < $ach328_threshold[3] && $x >= $ach328_threshold[3]){
			\cardbase\get_qiegao($ach328_qiegao_prize[3],$pa);
			shuffle($cardprize);
			$pcard = $cardprize[0];
			\cardbase\get_card($pcard,$pa);
		}

		return $x;
	}
	
	function show_achievement328($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill328'));
		if ($data=='')
			$p328=0;
		else	$p328=$data;	
		$c328=0;
		if ($p328 >= $ach328_threshold[3])
			$c328=999;
		elseif ($p328 >= $ach328_threshold[2])
			$c328=2;
		elseif ($p328 >= $ach328_threshold[1])
			$c328=1;
		include template('MOD_SKILL328_DESC');
	}
}

?>