<?php

namespace skill322
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach322_name = array(
		0=>'烈火疾风',
	);
	
	function init() 
	{
		define('MOD_SKILL322_INFO','achievement;');
		define('MOD_SKILL322_ACHIEVEMENT_ID','22');
	}
	
	function acquire322(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(322,'cnt','0',$pa);
	}
	
	function lost322(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize322(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x=\skillbase\skill_getvalue(322,'cnt',$pa);		
		if ($x==0) $x=$ox;
		if ($ox!=0) $x=min($x,$ox);
		
		if (($x!=0)&&($x<=1800)&&(($ox>1800)||($ox==0))){
			//\cardbase\get_qiegao(666,$pa);
			//\cardbase\get_card(78,$pa);
			\achievement_base\ach_create_prize_message($pa, 322, 0, 666, 78);
		}
		
		return $x;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (($theitem['itm']=="杏仁豆腐的ID卡")&&($theitem['itmk']=="Z")&&($gamestate>=30)&&($gamestate<50)){
			if (\skillbase\skill_query(322)){
				\skillbase\skill_setvalue(322,'cnt',$now-$starttime);
				\player\player_save($sdata);
			}
		}
		$chprocess($theitem);
	}

	function show_achievement322($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p322=0;
		else	$p322=$data;	
		$c322=0;
		if (($p322<=1800)&&($p322!=0)){
			$c322=999;
		}
		include template('MOD_SKILL322_DESC');
	}
}

?>
