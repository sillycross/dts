<?php

namespace skill324
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach324_name = array(
		0=>'我还可以变身两次',
	);
	
	function init() 
	{
		define('MOD_SKILL324_INFO','achievement;daily;');
		define('MOD_SKILL324_ACHIEVEMENT_ID','24');
		define('DAILY_TYPE324',1);
	}
	
	function acquire324(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost324(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function finalize324(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x=$pa['lvl'];
		$x=max($x,$ox);
		
		if (($ox<21)&&($x>=21)){
			//\cardbase\get_qiegao(140,$pa);
			\achievement_base\ach_create_prize_message($pa, 324, 0, 140);
		}
		
		return $x;
	}
	
	function show_achievement324($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p324=0;
		else	$p324=$data;	
		$c324=0;
		if ($p324>=21){
			$c324=999;
		}
		include template('MOD_SKILL324_DESC');
	}
}

?>
