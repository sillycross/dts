<?php

namespace skill329
{
	$ach329_name = array(
		1=>'极光处刑 LV1',
		2=>'极光处刑 LV2',
		3=>'极光处刑 LV3',
	);
	
	$ach329_threshold = array(
		1 => 3,
		2 => 15,
		3 => 50,
		999 => NULL
	);
	$ach329_qiegao_prize = array(
		1 => 900,
		2 => 5000,
		3 => 12000,
		999 => NULL
	);
	
	function init() 
	{
		define('MOD_SKILL329_INFO','achievement;spec-activity;');
		define('MOD_SKILL329_ACHIEVEMENT_ID','29');
//		$ach_allow_mode[329] = array(18);
	}
	
	function acquire329(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(329,'cnt','0',$pa);
	}
	
	function lost329(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(329,'cnt',$pa);
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$ach329_flag = 0;
		if('破灭之诗' == $theitem['itm'] && 'Y' == $theitem['itmk']){
			$ach329_flag = 1;
		}
		$chprocess($theitem);
		if(18 == $gametype && \skillbase\skill_query(329,$sdata) && $ach329_flag) {
			$x=(int)\skillbase\skill_getvalue(329,'cnt',$sdata);
			$x+=1;
			\skillbase\skill_setvalue(329,'cnt',$x,$sdata);
		}
	}
	
	function finalize329(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(329,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		$cardprize = array(200, 201, 202, 203, 204);
		eval(import_module('sys', 'skill329'));
		$result = $db->query("SELECT cardlist FROM {$gtablepre}users WHERE username='{$pa['name']}'");
		$cardlist = $db->fetch_array($result);
		$cardlist = $cardlist['cardlist'];
		$nowcards = explode('_', $cardlist);
		$cardprize = array_diff($cardprize, $nowcards);
		if(empty($cardprize)) $cardprize[] = 200;
		
		if ( $ox < $ach329_threshold[1] && $x >= $ach329_threshold[1] ){
			\cardbase\get_qiegao($ach329_qiegao_prize[1], $pa);
		}
		if ( $ox < $ach329_threshold[2] && $x >= $ach329_threshold[2] ){
			\cardbase\get_qiegao($ach329_qiegao_prize[2],$pa);
		}
		if ( $ox < $ach329_threshold[3] && $x >= $ach329_threshold[3]){
			\cardbase\get_qiegao($ach329_qiegao_prize[3],$pa);
			shuffle($cardprize);
			$pcard = $cardprize[0];
			\cardbase\get_card($pcard,$pa);
		}

		return $x;
	}
	
	function show_achievement329($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill329'));
		if ($data=='')
			$p329=0;
		else	$p329=$data;	
		$c329=0;
		if ($p329 >= $ach329_threshold[3])
			$c329=999;
		elseif ($p329 >= $ach329_threshold[2])
			$c329=2;
		elseif ($p329 >= $ach329_threshold[1])
			$c329=1;
		include template('MOD_SKILL329_DESC');
	}
}

?>