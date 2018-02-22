<?php

namespace skill310
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach310_name = array(
		0=>'脚本小子',
		1=>'黑客',
		2=>'幻境解离者？',
	);
	
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
	
	function finalize310(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(310,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		if (($ox<100)&&($x>=100)){
			//\cardbase\get_qiegao(100,$pa);
			\achievement_base\ach_create_prize_message($pa, 310, 0, 100);
		}
		eval(import_module('cardbase'));
		$arr=$cardindex['A'];
		$c=count($arr)-1;
		$cr=$arr[rand(0,$c)];
		if (($ox<2500)&&($x>=2500)){
			//\cardbase\get_qiegao(400,$pa);
			//\cardbase\get_card($cr,$pa);
			\achievement_base\ach_create_prize_message($pa, 310, 1, 400, $cr);
		}
		$cr=$arr[rand(0,$c)];
		if (($ox<10000)&&($x>=10000)){
			//\cardbase\get_qiegao(1500,$pa);
			//\cardbase\get_card($cr,$pa);
			\achievement_base\ach_create_prize_message($pa, 310, 2, 1500, $cr);
		}
		
		return $x;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ( \skillbase\skill_query(310,$pa) && $pd['type']>0 && $pd['hp'] <= 0)
		{
			$x=(int)\skillbase\skill_getvalue(310,'cnt',$pa);
			$x+=1;
			\skillbase\skill_setvalue(310,'cnt',$x,$pa);
		}
		
	}	
	
	function show_achievement310($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')
			$p310=0;
		else	$p310=$data;	
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
