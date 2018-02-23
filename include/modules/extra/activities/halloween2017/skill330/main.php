<?php

namespace skill330
{
	$ach330_name = array(
		1=>'噩梦之夜 LV1',
		2=>'噩梦之夜 LV2',
		3=>'噩梦之夜 LV3',
	);
	
	$ach330_threshold = array(
		1 => 30,
		2 => 100,
		3 => 300,
		999 => NULL
	);
	$ach330_qiegao_prize = array(
		1 => 999,
		2 => 500,
		3 => 6000,
		999 => NULL
	);
	
	function init() 
	{
		define('MOD_SKILL330_INFO','achievement;spec-activity;');
		define('MOD_SKILL330_ACHIEVEMENT_ID','30');
	}
	
	function acquire330(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(330,'cnt','0',$pa);
	}
	
	function lost330(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(330,'cnt',$pa);
	}
	
//	function act(){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$chprocess();
//		eval(import_module('sys', 'player'));
//		if(\skillbase\skill_query(330) && $hp > 0){
//			$num330 = check_itemnum330();
//			if($num330 > \skillbase\skill_getvalue(330,'cnt',$sdata)){
//				\skillbase\skill_setvalue(330,'cnt',$num330,$sdata);
//			}
//			if(0 == $gamestate){
//				\player\player_save($sdata);
//			}
//		}
//	}
	
	//计算糖数，包裹+武器都算
	function check_itemnum330($pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys', 'weapon'));
		$num_arr = array();
		foreach(array(1,2,3,4,5,6) as $v){
			if($pa['itms'.$v] && check_itemname330($pa['itm'.$v])){
				$num_arr[] = $pa['itms'.$v];
			}
		}
		if($pa['weps'] && check_itemname330($pa['wep'])){
			$num_arr[] = $pa['weps'];
		}
		$num = 0;
		foreach($num_arr as $nv){
			if($nosta == $nv) $num += 100;//无限耐算100，虽然一般不会出现吧
			elseif(is_numeric($nv)) $num += $nv;
		}
		return $num;
	}
	
	//判定物品名是不是糖
	function check_itemname330($itm){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(preg_match ( "/^万圣节.*色糖果$/s", $itm )){
			return true;
		}
		else return false;
	}
	
	//游戏结束时判定
	function post_winnercheck_events($wn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($wn);
		if(!$wn || strpos($wn,',')!==false) return;//无人获胜或者团队获胜则不判定
		$pa = \player\fetch_playerdata($wn,0,1);
		if(!$pa) return;
		if (\skillbase\skill_query(330,$pa)){
			$num330 = check_itemnum330($pa);
			\skillbase\skill_setvalue(330,'cnt',$num330,$pa);
			\player\player_save($pa);
		}
	}	
	
	function finalize330(&$pa, $data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($data=='')					
			$x=0;						
		else $x=$data;
		$ox=$x;
		$x+=\skillbase\skill_getvalue(330,'cnt',$pa);		
		$x=min($x,(1<<30)-1);
		
		eval(import_module('sys', 'skill330'));
		
		if ( $ox < $ach330_threshold[1] && $x >= $ach330_threshold[1] ){
			\cardbase\get_qiegao($ach330_qiegao_prize[1], $pa);
		}
		if ( $ox < $ach330_threshold[2] && $x >= $ach330_threshold[2] ){
			\cardbase\get_qiegao($ach330_qiegao_prize[2],$pa);
			\cardbase\get_card(160,$pa);
		}
		if ( $ox < $ach330_threshold[3] && $x >= $ach330_threshold[3]){
			\cardbase\get_qiegao($ach330_qiegao_prize[3],$pa);
		}

		return $x;
	}
	
	function show_achievement330($data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill330'));
		if ($data=='')
			$p330=0;
		else	$p330=$data;	
		$c330=0;
		if ($p330 >= $ach330_threshold[3])
			$c330=999;
		elseif ($p330 >= $ach330_threshold[2])
			$c330=2;
		elseif ($p330 >= $ach330_threshold[1])
			$c330=1;
		include template('MOD_SKILL330_DESC');
	}
}

?>