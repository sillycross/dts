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
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x=$pa['money'];
		$x=max($x,$ox);
		
		if (($ox<30000)&&($x>=30000)){
			\cardbase\get_qiegao(300,$pa);
		}
		if (($ox<60000)&&($x>=60000)){
			\cardbase\get_qiegao(400,$pa);
			\cardbase\get_card(23,$pa);
		}
		if (($ox<100000)&&($x>=100000)){
			\cardbase\get_qiegao(500,$pa);
			\cardbase\get_card(89,$pa);
		}
		if (($ox<360000)&&($x>=360000)){
			\cardbase\get_qiegao(3600,$pa);
			\cardbase\get_card(118,$pa);
		}
		
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
