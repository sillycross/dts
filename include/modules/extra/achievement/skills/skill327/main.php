<?php

namespace skill327
{
	function init() 
	{
		define('MOD_SKILL327_INFO','achievement;spec-activity;');
		define('MOD_SKILL327_ACHIEVEMENT_ID','27');
		$ach_allow_mode[327] = array(18);
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
		$chprocess($pa, $pd, $active);//确保复活不会触发
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
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(327,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		$cardprize = array(200, 201, 202, 203, 204);
		eval(import_module('sys'));
		$res = $db->query("SELECT cardlist FROM {$gtablepre}users WHERE username='{$pa['username']}'");
		$cardlist = $db->fetch_array($res)['cardlist'];
		$nowcards = explode('_', $pa['cardlist']);
		$cardprize = array_diff($cardprize, $nowcards);
		if(empty($cardprize)) $cardprize[] = 200;
		if (($ox<10)&&($x>=10)){
			\cardbase\get_qiegao(999,$pa);
		}
		if (($ox<20)&&($x>=20)){
			\cardbase\get_qiegao(300,$pa);
		}
		if (($ox<50)&&($x>=50)){
			\cardbase\get_qiegao(1000,$pa);
		}
		if (($ox<20&&$x>=20) || ($ox<50&&$x>=50)){
			shuffle($cardprize);
			$pcard = $cardprize[0];
			\cardbase\get_card($pcard,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function show_achievement327($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p327=0;
		else	$p327=base64_decode_number($data);	
		$c327=0;
		if ($p327>=50)
			$c327=999;
		elseif ($p327>=20)
			$c327=2;
		elseif ($p327>=10)
			$c327=1;
		include template('MOD_SKILL327_DESC');
	}
}

?>
