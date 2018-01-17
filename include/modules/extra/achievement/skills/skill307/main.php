<?php

namespace skill307
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach307_name = array(
		0=>'幻境解离',
		1=>'奇迹的篝火',
	);
	
	function init() 
	{
		define('MOD_SKILL307_INFO','achievement;');
		define('MOD_SKILL307_ACHIEVEMENT_ID','7');
//		eval(import_module('achievement_base'));
//		$ach_allow_mode[307] = array(0, 4, 16);
	}
	
	function acquire307(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(307,'cnt','0',$pa);
	}
	
	function lost307(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function finalize307(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(307,'cnt',$pa);
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			//\cardbase\get_qiegao(500,$pa);
			\achievement_base\ach_create_prize_message($pa, 307, 0, 500);
		}
		if (($ox<8)&&($x>=8)){
			//\cardbase\get_qiegao(2000,$pa);
			//\cardbase\get_card(81,$pa);
			\achievement_base\ach_create_prize_message($pa, 307, 1, 2000, 81);
		}
		
		return $x;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($theitem['itm']=="『G.A.M.E.O.V.E.R』"){
			if (\skillbase\skill_query(307)){
				\skillbase\skill_setvalue(307,'cnt',1);
				\player\player_save($sdata);//gameover的时候是不会多存一次玩家数据的，所以要加这一句卧槽
			}
		}
		$chprocess($theitem);
	}

	function show_achievement307($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p307=0;
		else	$p307=$data;	
		$c307=0;
		if ($p307>=8){
			$c307=999;
		}else if ($p307>=1){
			$c307=1;
		}
		include template('MOD_SKILL307_DESC');
	}
}

?>
