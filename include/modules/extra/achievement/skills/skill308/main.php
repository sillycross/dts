<?php

namespace skill308
{
	function init() 
	{
		define('MOD_SKILL308_INFO','achievement;');
		define('MOD_SKILL308_ACHIEVEMENT_ID','8');
	}
	
	function acquire308(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(308,'cnt','0',$pa);
	}
	
	function lost308(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize308(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x=\skillbase\skill_getvalue(308,'cnt',$pa);		
		if ($x==0) $x=$ox;
		if ($ox!=0) $x=min($x,$ox);
		
		if (($x!=0)&&($x<=300)&&(($ox>300)||($ox==0))){
			\cardbase\get_qiegao(666,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		if ($itm0=="【KEY系催泪弹】"){
			if ((int)(\skillbase\skill_getvalue(308,'cnt'))==0)
				\skillbase\skill_setvalue(308,'cnt',$now-$starttime);
		}
		$chprocess();	
	}

	function show_achievement308($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p308=0;
		else	$p308=base64_decode_number($data);	
		$c308=0;
		if (($p308<=300)&&($p308!=0)){
			$c308=999;
		}
		include template('MOD_SKILL308_DESC');
	}
}

?>
