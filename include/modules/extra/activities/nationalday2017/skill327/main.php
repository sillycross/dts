<?php

namespace skill327
{
	$ach327_name = array(
		1=>'新的战场 LV1',
		2=>'新的战场 LV2',
		3=>'新的战场 LV3',
	);
	
	$ach327_threshold = array(
		1 => 10,
		2 => 30,
		3 => 100,
		999 => NULL
	);
	$ach327_qiegao_prize = array(
		1 => 999,
		2 => 300,
		3 => 2000,
		999 => NULL
	);
	
	function init() 
	{
		define('MOD_SKILL327_INFO','achievement;spec-activity;');
		define('MOD_SKILL327_ACHIEVEMENT_ID','27');
//		$ach_allow_mode[327] = array(18);
	}
	
	function acquire327(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(327,'cnt','0',$pa);
	}
	
	function lost327(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(327,'cnt',$pa);
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);//最后执行，确保复活不会触发
		eval(import_module('sys'));
		if (18 == $gametype && \skillbase\skill_query(327,$pa) && 2 == $pd['type'] && $pd['hp'] <= 0)
		{
			$x=(int)\skillbase\skill_getvalue(327,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(327,'cnt',$x,$pa);
		}
	}	
	
	function finalize327(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(327,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		$cardprize = array(200, 201, 202, 203, 204);
		eval(import_module('sys', 'skill327'));
		$result = $db->query("SELECT cardlist FROM {$gtablepre}users WHERE username='{$pa['name']}'");
		$cardlist = $db->fetch_array($result);
		$cardlist = $cardlist['cardlist'];
		$nowcards = explode('_', $cardlist);
		$cardprize = array_diff($cardprize, $nowcards);
		if(empty($cardprize)) $cardprize[] = 200;
		
		if ( $ox < $ach327_threshold[1] && $x >= $ach327_threshold[1] ){
			\cardbase\get_qiegao($ach327_qiegao_prize[1], $pa);
		}
		if ( $ox < $ach327_threshold[2] && $x >= $ach327_threshold[2] ){
			\cardbase\get_qiegao($ach327_qiegao_prize[2],$pa);
			shuffle($cardprize);
			$pcard = $cardprize[0];
			\cardbase\get_card($pcard,$pa);
		}
		if ( $ox < $ach327_threshold[3] && $x >= $ach327_threshold[3]){
			\cardbase\get_qiegao($ach327_qiegao_prize[3],$pa);
			shuffle($cardprize);
			$pcard = $cardprize[0];
			\cardbase\get_card($pcard,$pa);
		}

		return $x;
	}
	
	function show_achievement327($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill327'));
		if ($data=='')
			$p327=0;
		else	$p327=$data;	
		$c327=0;
		if ($p327 >= $ach327_threshold[3])
			$c327=999;
		elseif ($p327 >= $ach327_threshold[2])
			$c327=2;
		elseif ($p327 >= $ach327_threshold[1])
			$c327=1;
		include template('MOD_SKILL327_DESC');
	}
}

?>
