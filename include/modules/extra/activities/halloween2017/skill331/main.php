<?php

namespace skill331
{
	$ach331_name = array(
		1=>'不给糖就解禁',
	);
	
	$ach331_threshold = array(
		1 => 1,
		999 => NULL
	);
	$ach331_qiegao_prize = array(
		1 => 500,
		999 => NULL
	);
	$ach331_itemnum = 20;
	
	function init() 
	{
		define('MOD_SKILL331_INFO','achievement;spec-activity;');
		define('MOD_SKILL331_ACHIEVEMENT_ID','31');
	}
	
	function acquire331(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(331,'cnt','0',$pa);
	}
	
	function lost331(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(331,'cnt',$pa);
	}
	
	function gameover($time = 0, $gmode = '', $winname = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gamestate < 5) return;
		if($gmode=='end3'){
			$pw=\player\fetch_playerdata($winname);
			if (\skillbase\skill_query(331,$pw)){
				eval(import_module('skill331'));
				$num331 = \skill330\check_itemnum330($pw);
				if($num331 >= $ach331_itemnum && $now - $starttime <= 1200){//身上糖果超过30，游戏时间20分钟之内
					\skillbase\skill_setvalue(331,'cnt',1,$pw);
					\player\player_save($pw);
				}
			} 
		}
		$chprocess($time,$gmode,$winname);
	}
	
	function finalize331(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(331,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		eval(import_module('sys', 'skill331'));
		
		//这也是犯了乱复制黏贴的毛病，不过反正一次性的活动而已……
		$cardprize = array(200, 201, 202, 203, 204);
		$result = $db->query("SELECT cardlist FROM {$gtablepre}users WHERE username='{$pa['name']}'");
		$cardlist = $db->fetch_array($result);
		$cardlist = $cardlist['cardlist'];
		$nowcards = explode('_', $cardlist);
		$cardprize = array_diff($cardprize, $nowcards);
		if(empty($cardprize)) $cardprize[] = 200;		
		
		if ( $ox < $ach331_threshold[1] && $x >= $ach331_threshold[1] ){
			\cardbase\get_qiegao($ach331_qiegao_prize[1], $pa);
			shuffle($cardprize);
			$pcard = $cardprize[0];
			\cardbase\get_card($pcard,$pa);
		}

		return $x;
	}
	
	function show_achievement331($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill331'));
		if ($data=='')
			$p331=0;
		else	$p331=$data;	
		$c331=0;
		if ($p331 >= $ach331_threshold[1])
			$c331=999;
		include template('MOD_SKILL331_DESC');
	}
}

?>