<?php

namespace skill313
{
	function init() 
	{
		define('MOD_SKILL313_INFO','achievement;');
		define('MOD_SKILL313_ACHIEVEMENT_ID','13');
		eval(import_module('achievement_base'));
		$ach_allow_mode[313] = array(15);
	}
	
	function acquire313(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(313,'max_money',0,$pa);
	}
	
	function lost313(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function finalize313(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($data=='')					
			$last_a_money=0;						
		else $last_a_money=$data;
		
		if($areanum >= $areaadd) {//一禁以后，身上金额不计入判定，只判定一禁时的数据
			$x=\skillbase\skill_getvalue(313,'max_money',$pa);
			//writeover('skill313.txt','skill'.$x);
		} else {
			$x=max($pa['money'], \skillbase\skill_getvalue(313,'max_money',$pa));
			//writeover('skill313.txt','nowmoney'.$x);
		}
		
		if (($last_a_money<30000)&&($x>=30000)){
			\cardbase\get_qiegao(300,$pa);
		}
		if (($last_a_money<60000)&&($x>=60000)){
			\cardbase\get_qiegao(400,$pa);
			\cardbase\get_card(23,$pa);
		}
		if (($last_a_money<100000)&&($x>=100000)){
			\cardbase\get_qiegao(500,$pa);
			\cardbase\get_card(89,$pa);
		}
		if (($last_a_money<360000)&&($x>=360000)){
			\cardbase\get_qiegao(3600,$pa);
			\cardbase\get_card(118,$pa);
		}
		if (($last_a_money<1000000)&&($x>=1000000)){
			\cardbase\get_qiegao(5000,$pa);
			\cardbase\get_card(156,$pa);
		}
		if($x < $last_a_money) $x=$last_a_money;
		
		return $x;
	}
	
	function show_achievement313($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p313=0;
		else	$p313=$data;	
		$c313=0;
		if ($p313>=30000){
			$c313=1;
		}
		if ($p313>=60000){
			$c313=2;
		}
		if ($p313>=100000){
			$c313=3;
		}
		if ($p313>=360000){
			$c313=4;
		}
		if ($p313>=1000000){
			$c313=999;
		}
		include template('MOD_SKILL313_DESC');
	}
}

?>
