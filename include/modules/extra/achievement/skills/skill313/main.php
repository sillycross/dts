<?php

namespace skill313
{
	function init() 
	{
		define('MOD_SKILL313_INFO','achievement;');
		define('MOD_SKILL313_ACHIEVEMENT_ID','13');
	}
	
	function acquire313(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(313,'max_money',0,$pa);
	}
	
	function lost313(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (($gametype==15)&&(!\skillbase\skill_query(313,$pa))) 
			\skillbase\skill_acquire(313,$pa);
		$chprocess($pa);
	}

	function finalize313(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($data=='')					
			$last_a_money=0;						
		else $last_a_money=base64_decode_number($data);
		
		if($areanum >= $areaadd) {//一禁以后，身上金额不计入判定，只判定一禁时的数据
			$x=\skillbase\skill_getvalue(313,'max_money',$pa);
			//writeover('skill313.txt','skill'.$x);
		} else {
			$x=max($pa['money'], \skillbase\skill_getvalue(313,'max_money',$pa));
			//writeover('skill313.txt','nowmoney'.$x);
		}
		
		if (($last_a_money<30000)&&($x>=30000)){
			\cardbase\get_qiegao(300,$pa);
		}
		if (($last_a_money<60000)&&($x>=60000)){
			\cardbase\get_qiegao(400,$pa);
			\cardbase\get_card(23,$pa);
		}
		if (($last_a_money<100000)&&($x>=100000)){
			\cardbase\get_qiegao(500,$pa);
			\cardbase\get_card(89,$pa);
		}
		if (($last_a_money<360000)&&($x>=360000)){
			\cardbase\get_qiegao(3600,$pa);
			\cardbase\get_card(118,$pa);
		}
		
		if($x < $last_a_money) $x=$last_a_money;
		
		return base64_encode_number($x,5);		
	}
	
	function show_achievement313($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p313=0;
		else	$p313=base64_decode_number($data);	
		$c313=0;
		if ($p313>=30000){
			$c313=1;
		}
		if ($p313>=60000){
			$c313=2;
		}
		if ($p313>=100000){
			$c313=3;
		}
		if ($p313>=360000){
			$c313=999;
		}
		include template('MOD_SKILL313_DESC');
	}
}

?>
