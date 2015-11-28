<?php

namespace skill306
{
	function init() 
	{
		define('MOD_SKILL306_INFO','achievement;');
		define('MOD_SKILL306_ACHIEVEMENT_ID','6');
	}
	
	function acquire306(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(306,'cnt','0',$pa);
	}
	
	function lost306(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ((!in_array($gametype,Array(10,11,12,13,14)))&&(!\skillbase\skill_query(306,$pa))) 
			\skillbase\skill_acquire(306,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function finalize306(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(306,'cnt',$pa);
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			\cardbase\get_qiegao(200,$pa);
		}
		if (($ox<5)&&($x>=5)){
			\cardbase\get_qiegao(500,$pa);
			\cardbase\get_card(32,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function gameover($time = 0, $gmode = '', $winname = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gamestate < 10) return;
		if($gmode=='end5'){
			$pw=\player\fetch_playerdata($winname);
			if (\skillbase\skill_query(306,$pw)){
				\skillbase\skill_setvalue(306,'cnt',1,$pw);
				\player\player_save($pw);
			} 
		}
		$chprocess($time,$gmode,$winname);
	}
	
	function show_achievement306($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p306=0;
		else	$p306=base64_decode_number($data);	
		$c306=0;
		if ($p306>=5){
			$c306=999;
		}else if ($p306>=1){
			$c306=1;
		}
		include template('MOD_SKILL306_DESC');
	}
}

?>
