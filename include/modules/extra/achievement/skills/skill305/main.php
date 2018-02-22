<?php

namespace skill305
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach305_name = array(
		0=>'最后幸存',
		1=>'不止是运气好而已',
	);
	
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
	
	function finalize305(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(305,'cnt',$pa);
		$x=min($x,(1<<30)-1);
		
		if (($ox<3)&&($x>=3)){
			//\cardbase\get_qiegao(100,$pa);
			\achievement_base\ach_create_prize_message($pa, 305, 0, 100);
		}
		if (($ox<20)&&($x>=20)){
			//\cardbase\get_qiegao(800,$pa);
			\achievement_base\ach_create_prize_message($pa, 305, 1, 800);
		}
		
		return $x;
	}
	
	function gameover($time = 0, $gmode = '', $winname = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gamestate < 5) return;
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
		else	$p305=$data;	
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
