<?php

namespace skill323
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach323_name = array(
		0=>'宛如梦幻',
	);
	
	function init() 
	{
		define('MOD_SKILL323_INFO','achievement;');
		define('MOD_SKILL323_ACHIEVEMENT_ID','23');
//		eval(import_module('achievement_base'));
//		$ach_allow_mode[323] = array(0, 4, 16);
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
	
	function finalize323(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x=\skillbase\skill_getvalue(323,'cnt',$pa);		
		if ($x==0) $x=$ox;
		if ($ox!=0) $x=min($x,$ox);
		eval(import_module('cardbase'));
		$arr=$cardindex['S'];
		$c=count($arr)-1;
		$cr=$arr[rand(0,$c)];
		if (($x!=0)&&($x<=2700)&&(($ox>2700)||($ox==0))){
			//\cardbase\get_qiegao(666,$pa);
			//\cardbase\get_card($cr,$pa);
			\achievement_base\ach_create_prize_message($pa, 323, 0, 666, $cr);
		}
		
		return $x;
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
		else	$p323=$data;	
		$c323=0;
		if (($p323<=2700)&&($p323!=0)){
			$c323=999;
		}
		include template('MOD_SKILL323_DESC');
	}
}

?>
