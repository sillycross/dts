<?php

namespace skill301
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach301_name = array(
		0=>'锁定解除',
		1=>'最后的荣光',
	);
	
	function init() 
	{
		define('MOD_SKILL301_INFO','achievement;');
		define('MOD_SKILL301_ACHIEVEMENT_ID','1');
	}
	
	function acquire301(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(301,'cnt','0',$pa);
	}
	
	function lost301(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize301(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(301,'cnt',$pa);
		$x=min($x,(1<<30)-1);
		
		if (($ox<1)&&($x>=1)){
			//\cardbase\get_qiegao(300,$pa);
			\achievement_base\ach_create_prize_message($pa, 301, 0, 300);
		}
		if (($ox<10)&&($x>=10)){
			//\cardbase\get_qiegao(1600,$pa);
			\achievement_base\ach_create_prize_message($pa, 301, 1, 1600);
		}
		
		return $x;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($theitem['itm']=="游戏解除钥匙"){
			if (\skillbase\skill_query(301)){
				\skillbase\skill_setvalue(301,'cnt',1);
				\player\player_save($sdata);//gameover的时候是不会多存一次玩家数据的，所以要加这一句卧槽
			}
		}
		$chprocess($theitem);
	}

	function show_achievement301($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p301=0;
		else	$p301=$data;	
		$c301=0;
		if ($p301>=10){
			$c301=999;
		}else if ($p301>=1){
			$c301=1;
		}
		include template('MOD_SKILL301_DESC');
	}
}

?>
