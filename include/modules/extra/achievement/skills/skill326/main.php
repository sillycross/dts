<?php

namespace skill326
{
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
	
	//½âÂëÒÑÓÃ¿¨Æ¬
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
		$clist = cardlist_decode326($data);
		if(in_array($c, $clist)) return $data;
//		for ($i=0; $i<strlen($data); $i+=3)
//		{
//			$x=base64_decode_number(substr($data,$i,3));
//			if ($x==$c) return $data;
//		}
		
		$data.=base64_encode_number($c,3);
		$o=ceil(strlen($data)/3);
		
		if ($o==10) \cardbase\get_qiegao(888,$pa);
		if ($o==25) { \cardbase\get_card(81,$pa); \cardbase\get_qiegao(1200,$pa); }
		if ($o==50) \cardbase\get_qiegao(1600,$pa);
		if ($o==75) \cardbase\get_qiegao(2000,$pa);
		if ($o==100) \cardbase\get_qiegao(2500,$pa);
		
		return $data;
	}
	
	function show_achievement326($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		$ca326=\skill326\cardlist_decode326($data);
		$cn326='';
		foreach($ca326 as $val){
			$cn326 .= $cards[$val]['name'].' ';
		}
		$cn326 = str_replace('"','&quot;',substr($cn326,0,-1));
		$p326=ceil(strlen($data)/3);
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
