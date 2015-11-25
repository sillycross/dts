<?php

namespace skill310
{
	function init() 
	{
		define('MOD_SKILL310_INFO','achievement;');
		define('MOD_SKILL310_ACHIEVEMENT_ID','10');
	}
	
	function acquire310(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(310,'cnt','0',$pa);
	}
	
	function lost310(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ((!in_array($gametype,Array(10,11,12,13,14)))&&(!\skillbase\skill_query(310,$pa))) //也可以做一些只有房间模式有效的成就
			\skillbase\skill_acquire(310,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function finalize310(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else	$x=base64_decode_number($data);		
		$ox=$x;
		$x+=\skillbase\skill_getvalue(310,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<100)&&($x>=100)){
			\cardbase\get_qiegao(100,$pa);
		}
		eval(import_module('cardbase'));
		$arr=$cardindex['A'];
		$c=count($arr)-1;
		$cr=$arr[rand(0,$c)];
		if (($ox<2500)&&($x>=2500)){
			\cardbase\get_qiegao(400,$pa);
			\cardbase\get_card($cr,$pa);
		}
		$cr=$arr[rand(0,$c)];
		if (($ox<10000)&&($x>=10000)){
			\cardbase\get_qiegao(1500,$pa);
			\cardbase\get_card($cr,$pa);
		}
		
		return base64_encode_number($x,5);		
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		eval(import_module('cardbase','sys','logger','map'));
		if ((\skillbase\skill_query(310,$pa))&&($pd['type']>0))
		{
			$x=(int)\skillbase\skill_getvalue(310,'cnt');
			$x+=1;
			\skillbase\skill_setvalue(310,'cnt',$x);
		}
	}	
	
	function show_achievement310($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p310=0;
		else	$p310=base64_decode_number($data);	
		$c310=0;
		if ($p310>=10000){
			$c310=999;
		}else if ($p310>=2500){
			$c310=2;
		}else if ($p310>=100){
			$c310=1;
		}
		include template('MOD_SKILL310_DESC');
	}
}

?>
