<?php

namespace skill318
{
	function init() 
	{
		define('MOD_SKILL318_INFO','achievement;daily;');
		define('MOD_SKILL318_ACHIEVEMENT_ID','18');
		eval(import_module('achievement_base'));
		$ach_allow_mode[318] = array(0, 4, 16);
	}
	
	function acquire318(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(318,'cnt','0',$pa);
	}
	
	function lost318(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize318(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(318,'cnt',$pa);
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			\cardbase\get_qiegao(573,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($theitem['itm']=="『G.A.M.E.O.V.E.R』"){
			if (\skillbase\skill_query(318)){
				\skillbase\skill_setvalue(318,'cnt',1);
				\player\player_save($sdata);//gameover的时候是不会多存一次玩家数据的，所以要加这一句卧槽
			}
		}
		$chprocess($theitem);
	}

	function show_achievement318($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p318=0;
		else	$p318=base64_decode_number($data);	
		$c318=0;
		if ($p318>=1){
			$c318=999;
		}
		include template('MOD_SKILL318_DESC');
	}
}

?>
