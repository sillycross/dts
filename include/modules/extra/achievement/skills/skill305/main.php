<?php

namespace skill305
{
	function init() 
	{
		define('MOD_SKILL305_INFO','achievement;');
		define('MOD_SKILL305_ACHIEVEMENT_ID','5');
	}
	
	function acquire305(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(305,'cnt','0',$pa);
	}
	
	function lost305(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ((!in_array($gametype,Array(10,11,12,13,14)))&&(!\skillbase\skill_query(305,$pa))) 
			\skillbase\skill_acquire(305,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function finalize305(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(305,'cnt',$pa);
		$x=min($x,(1<<30)-1);
		
		if (($ox<3)&&($x>=3)){
			\cardbase\get_qiegao(100,$pa);
		}
		if (($ox<20)&&($x>=20)){
			\cardbase\get_qiegao(800,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function gameover($time = 0, $gmode = '', $winname = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gamestate < 10) return;
		if(((!$gmode)||(($gmode=='end2')&&(!$winname)))&&(!in_array($gametype,$teamwin_mode))){
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0");
			$alivenum = $db->num_rows($result);
			if($alivenum == 1){
				$pw = $db->fetch_array($result);
				$wn=$pw['name'];
				$pw=\player\fetch_playerdata($wn);
				if (\skillbase\skill_query(305,$pw)){
					\skillbase\skill_setvalue(305,'cnt',1,$pw);
					\player\player_save($pw);
				} 
			}
		}
		$chprocess($time,$gmode,$winname);
	}
	
	function show_achievement305($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p305=0;
		else	$p305=base64_decode_number($data);	
		$c305=0;
		if ($p305>=20){
			$c305=999;
		}else if ($p305>=3){
			$c305=1;
		}
		include template('MOD_SKILL305_DESC');
	}
}

?>
