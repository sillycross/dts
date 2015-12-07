<?php

namespace skill323
{
	function init() 
	{
		define('MOD_SKILL323_INFO','achievement;');
		define('MOD_SKILL323_ACHIEVEMENT_ID','23');
	}
	
	function acquire323(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(323,'cnt','0',$pa);
	}
	
	function lost323(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (((!in_array($gametype,$ach_ignore_mode))||($gametype==16))&&(!\skillbase\skill_query(323,$pa))) 
			\skillbase\skill_acquire(323,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function finalize323(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='VWXYZ') $x=0;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x=\skillbase\skill_getvalue(323,'cnt',$pa);		
		if ($x==0) $x=$ox;
		if ($ox!=0) $x=min($x,$ox);
		eval(import_module('cardbase'));
		$arr=$cardindex['S'];
		$c=count($arr)-1;
		$cr=$arr[rand(0,$c)];
		if (($x!=0)&&($x<=2400)&&(($ox>2400)||($ox==0))){
			\cardbase\get_qiegao(666,$pa);
			\cardbase\get_card($cr,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($theitem['itm']=="『G.A.M.E.O.V.E.R』"){
			if (\skillbase\skill_query(323)){
				\skillbase\skill_setvalue(323,'cnt',$now-$starttime);
				\player\player_save($sdata);
			}
		}
		$chprocess($theitem);
	}

	function show_achievement323($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p323=0;
		else	$p323=base64_decode_number($data);	
		$c323=0;
		if (($p323<=2400)&&($p323!=0)){
			$c323=999;
		}
		include template('MOD_SKILL323_DESC');
	}
}

?>
