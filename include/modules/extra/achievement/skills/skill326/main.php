<?php

namespace skill326
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach326_name = array(
		0=>'全能骑士 LV10',
		1=>'全能骑士 LV25',
		2=>'全能骑士 LV50',
		3=>'全能骑士 LV75',
		4=>'全能骑士 LV100',
	);
	
	function init() 
	{
		define('MOD_SKILL326_INFO','achievement;');
		define('MOD_SKILL326_ACHIEVEMENT_ID','26');
	}
	
	function acquire326(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost326(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//解码已用卡片
	function cardlist_decode326($data){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		for ($i=0; $i<strlen($data); $i+=3)
		{
			$r[]=base64_decode_number(substr($data,$i,3));
		}
		return $r;
	}
	
	function finalize326(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$flag = 0;
		foreach (Array(301,305,306,307) as $sk)
			if (\skillbase\skill_query($sk,$pa) && (int)\skillbase\skill_getvalue($sk,'cnt',$pa)==1)
				$flag = 1;
			
		if (!$flag)
			return $data;
		
		$c=(int)$pa['card'];
		if(!is_array($data)) return $data;
		if(in_array($c, $data)) return $data;
		$data[] = $c;
		
		$o=sizeof($data);
		
		if ($o==10) {
			//\cardbase\get_qiegao(888,$pa);
			\achievement_base\ach_create_prize_message($pa, 326, 0, 888);
		}
		if ($o==25) { 
			//\cardbase\get_card(81,$pa); //\cardbase\get_qiegao(1200,$pa);
			\achievement_base\ach_create_prize_message($pa, 326, 1, 1200, 81);
		}
		if ($o==50) {
			//\cardbase\get_qiegao(1600,$pa);
			\achievement_base\ach_create_prize_message($pa, 326, 2, 1600);
		}
		if ($o==75) {
			//\cardbase\get_qiegao(2000,$pa);
			\achievement_base\ach_create_prize_message($pa, 326, 3, 2000);
		}
		if ($o==100) {
			//\cardbase\get_qiegao(2500,$pa);
			\achievement_base\ach_create_prize_message($pa, 326, 4, 2500);
		}
		
		return $data;
	}
	
	function show_achievement326($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$ca326=$data;
		$cn326='';
		foreach($ca326 as $val){
			$cn326 .= $cards[$val]['name'].' ';
		}
		$cn326 = str_replace('"','&quot;',substr($cn326,0,-1));
		$p326=sizeof($data);
		$c326=0;
		if ($p326>=100)
			$c326=999;
		else if ($p326>=75)
			$c326=4;
		else if ($p326>=50)
			$c326=3;
		else if ($p326>=25)
			$c326=2;
		else if ($p326>=10)
			$c326=1;
		else  $c326=0;
		include template('MOD_SKILL326_DESC');
	}
}

?>
