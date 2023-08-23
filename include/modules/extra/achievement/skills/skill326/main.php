<?php

namespace skill326
{
	//旧成就精力所限，未全部修改，请以skill300、skill313或skill332之后的成就为模板！
	$ach326_name = array(
		1=>'全能骑士 LV10',
		2=>'全能骑士 LV25',
		3=>'全能骑士 LV50',
		4=>'全能骑士 LV75',
		5=>'全能骑士 LV100',
	);
	
	//各级显示的要求，如果不存在则取低的
	$ach326_desc= array(
		1=>'使用<:threshold:>张不同卡片获得游戏胜利',
	);
	
	$ach326_proc_words = '目前纪录';
	
	$ach326_unit = '张';
	
	$ach326_proc_words2 = '（悬浮查看完成情况）';
	
	//各级阈值，注意是达到这个阈值则升到下一级
	$ach326_threshold = array(
		1 => 10,
		2 => 25,
		3 => 50,
		4 => 75,
		5 => 100,
		999 => NULL
	);
	
	//各级给的切糕奖励
	$ach326_qiegao_prize = array(
		1 => 888,
		2 => 1200,
		3 => 1600,
		4 => 2000,
		5 => 2500,
	);
	
	$ach326_card_prize = array(
		2 => 81,
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
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $data, $achid);
		if($achid == 326){
			if(!is_array($ret)) $ret = array();
			eval(import_module('sys'));
			if(\sys\is_winner($pa['name'],$winner) && !in_array($pa['card'], $ret)) {
				$ret[] = $pa['card'];
				$ret = array_unique($ret);
			}
		}
		return $ret;
	}
	
	//判定数据与阈值的关系，这里是计算$data的元素个数，然后跟阈值相比较
	function ach_finalize_check_progress(&$pa, $t, $data, $achid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(326 == $achid) return sizeof((Array)$data) >= $t;
		else return $chprocess($pa, $t, $data, $achid);
	}
	
	function show_ach_title_3($achid, $adata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($achid, $adata);
		if(326 == $achid) {
			eval(import_module('cardbase'));
			$ret = '';
			foreach($adata as $val){
				$ret .= $cards[$val]['name'].'&nbsp; ';
			}
			$ret =str_replace('"','&quot;',substr($ret,0,-1));
		}
		return $ret;
	}
	
	//成就进度值处理
	function parse_achievement_progress_var($achid, $x){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$x = $chprocess($achid, $x);
		if(326 == $achid) {
			$x = sizeof((Array)$x);
		}
		return $x;
	}
	
	/*
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
	*/
}

?>
