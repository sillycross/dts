<?php

namespace skill318
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach318_name = array(
		0=>'风祭之旅',
	);
	
	function init() 
	{
		define('MOD_SKILL318_INFO','achievement;daily;');
		define('MOD_SKILL318_ACHIEVEMENT_ID','18');
		define('DAILY_TYPE318',3);
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
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(318,'cnt',$pa);
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			//\cardbase\get_qiegao(573,$pa);
			\achievement_base\ach_create_prize_message($pa, 318, 0, 573);
		}
		
		return $x;
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
		else	$p318=$data;	
		$c318=0;
		if ($p318>=1){
			$c318=999;
		}
		include template('MOD_SKILL318_DESC');
	}
}

?>
