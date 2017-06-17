<?php

namespace skill324
{
	function init() 
	{
		define('MOD_SKILL324_INFO','achievement;daily;');
		define('MOD_SKILL324_ACHIEVEMENT_ID','24');
	}
	
	function acquire324(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost324(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize324(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x=$pa['lvl'];
		$x=max($x,$ox);
		
		if (($ox<21)&&($x>=21)){
			\cardbase\get_qiegao(140,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function show_achievement324($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p324=0;
		else	$p324=base64_decode_number($data);	
		$c324=0;
		if ($p324>=21){
			$c324=999;
		}
		include template('MOD_SKILL324_DESC');
	}
}

?>
