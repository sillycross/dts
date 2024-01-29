<?php

namespace skill424
{
	function init() 
	{
		define('MOD_SKILL424_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[424] = '除错';
	}
	
	function acquire424(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(424,'lvl',0,$pa);
		\skillbase\skill_setvalue(424,'cur1','电池',$pa);
		\skillbase\skill_setvalue(424,'cur2','探测器电池',$pa);
		\skillbase\skill_setvalue(424,'cur3','',$pa);
		\skillbase\skill_setvalue(424,'cur4','',$pa);
		\skillbase\skill_setvalue(424,'npclvlsum','0',$pa);
		//此外本技能还有储存这局奖励的功能
		\skillbase\skill_setvalue(424,'maxlvl','0',$pa);
		\skillbase\skill_setvalue(424,'rank','0',$pa);
		\skillbase\skill_setvalue(424,'prize','0',$pa);
	}
	
	function lost424(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(424,'lvl',$pa);
		\skillbase\skill_delvalue(424,'cur1',$pa);
		\skillbase\skill_delvalue(424,'cur2',$pa);
		\skillbase\skill_delvalue(424,'cur3',$pa);
		\skillbase\skill_delvalue(424,'cur4',$pa);
	}
	
	function check_unlocked424(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function wdebug_check(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if(!\skillbase\skill_query(424)) return false;
		$req1=\skillbase\skill_getvalue(424,'cur1');
		$req2=\skillbase\skill_getvalue(424,'cur2');
		$req3=\skillbase\skill_getvalue(424,'cur3');
		$req4=\skillbase\skill_getvalue(424,'cur4');
		$clv=\skillbase\skill_getvalue(424,'lvl');
		$position = 0;
		$reqarr = array();
		for($i=1;$i<=4;$i++){
			if(${'req'.$i}) $reqarr[] = ${'req'.$i};
		}
		for($i=1;$i<=6;$i++){
			global ${'itm'.$i};
			if( in_array(${'itm'.$i}, $reqarr) ){
				$position = $i;
				break;
			}
		}

		return $position;
	}
	
	function wdebug_reset(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if(!\skillbase\skill_query(424)) return false;
		$aready=array(\skillbase\skill_getvalue(424,'cur1'), \skillbase\skill_getvalue(424,'cur2'), \skillbase\skill_getvalue(424,'cur3'), \skillbase\skill_getvalue(424,'cur4'));
		$aready = array_filter($aready);
		$clv=\skillbase\skill_getvalue(424,'lvl');
		$req1 = $req2 = $req3 = $req4 = '';
		
		
		if(0==$clv) {//第一次必定是电池或者探测器电池
			$req1='电池';$req2='探测器电池';
		}elseif($clv <= 10){//前10层只产生个数在40以上的地图道具或30以上的商店道具，且各1枚
			$req1 = wdebug_getreq('mapitem', 40, 499, $aready);
			$req2 = wdebug_getreq('shopitem', 30, 499, $aready);
		}elseif($clv <= 20){//10-20层产生个数在15-60的地图道具或10-40的地图/商店道具，有可能两个都是地图道具
			$req1 = wdebug_getreq('mapitem', 15, 60, $aready);
			$req2 = wdebug_getreq(array('mapitem', 'shopitem'), 10, 40, $aready);
		}elseif($clv <= 30){//20-30层产生个数在5-30的地图道具或3-20的地图商店道具（有可能两个都是地图道具）和个数在20-200的合成物、NPC道具
			$req1 = wdebug_getreq('mapitem', 5, 20, $aready);
			$req2 = wdebug_getreq(array('mapitem'), 3, 15, $aready);
			$req3 = wdebug_getreq(array('mixitem','syncitem','overlayitem'), 20, 200, $aready);
		}elseif($clv <= 40){//30-40层产生个数在2-10的地图道具或1-8的地图商店道具（有可能两个都是地图道具）和个数在3-20的合成物、NPC道具
			$req1 = wdebug_getreq('mapitem', 2, 10, $aready);
			$req2 = wdebug_getreq(array('mapitem'), 1, 8, $aready);
			$req3 = wdebug_getreq(array('mixitem','syncitem','overlayitem'), 3, 20, $aready);
		}elseif($clv <= 50){//40-50层产生个数在1-6的地图道具或0.5-5的地图商店道具（有可能两个都是地图道具）、个数在1-10的合成物、NPC道具以及所有个数在8以下的玩意儿
			$req1 = wdebug_getreq('mapitem', 1, 6, $aready);
			$req2 = wdebug_getreq(array('mapitem'), 0.5, 5, $aready);
			$req3 = wdebug_getreq(array('mixitem','syncitem','overlayitem'), 1, 10, $aready);
			$req4 = wdebug_getreq(array('mapitem','mixitem','syncitem','overlayitem','presentitem','ygoitem'), 0, 8, $aready);
		}else{//50层以上全部浮云物，哈哈哈哈！而且不再能买了
			$req1 = wdebug_getreq(array('mapitem','mixitem','syncitem','overlayitem','presentitem','ygoitem'), 0, 5, $aready);
			$req2 = wdebug_getreq(array('mapitem','mixitem','syncitem','overlayitem','presentitem','ygoitem'), 0, 5, $aready);
			$req3 = wdebug_getreq(array('mapitem','mixitem','syncitem','overlayitem','presentitem','ygoitem'), 0, 5, $aready);
			$req4 = wdebug_getreq(array('mapitem','mixitem','syncitem','overlayitem','presentitem','ygoitem'), 0, 5, $aready);
		}
		\skillbase\skill_setvalue(424,'cur1',$req1,$pa);
		\skillbase\skill_setvalue(424,'cur2',$req2,$pa);
		\skillbase\skill_setvalue(424,'cur3',$req3,$pa);
		\skillbase\skill_setvalue(424,'cur4',$req4,$pa);
	}
	
	//在类别为$kind、数量在$min, $max之间的道具里随机选择1个并返回名字，且禁区符合要求
	function wdebug_getreq($kind, $minnum, $maxnum=-1, &$aready=''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$nowarea = \map\get_area_wavenum();
		//daemon模式下，include_once会出问题
		global $cont_mapitem,$cont_shopitem,$cont_mixitem,$cont_syncitem,$cont_overlayitem,$cont_presentitem,$cont_ygoitem,$cont_fyboxitem,$cont_npcinfo_gtype1;
		if(empty($cont_mapitem)) {
			$file = GAME_ROOT.'/gamedata/cache/gtype1item.config.php';
			$func = '\gtype1\prepare_new_game_gtype1';
			if(!file_exists($file) && function_exists($func)) {
				$func();
			}
			include $file;
		}
		if(!is_array($kind)) $kind = array($kind);
		if(!is_array($aready)) $aready = array($aready);
		$nowkindarr = array();
		foreach($kind as $kv){
			if('npc' == $kv) $kv = 'npcinfo_gtype1';
			if(!isset(${'cont_'.$kv})) return NULL;
			$nowkindarr = array_merge($nowkindarr, ${'cont_'.$kv});
		}
		for($i=0;$i<9999;$i++){//执行循环最多9999次。这会导致某些选项被漏过去，但是暂时懒得改……
			if(empty($nowkindarr)) break;
			$iname = array_rand($nowkindarr);
			//四种情况下，删掉不符合要求的元素并重新进行循环，否则跳出
			if(in_array($iname,$aready) || $nowkindarr[$iname][0] < $minnum || //1、元素已经在$aready变量中 2、不符合最小数目要求
				($maxnum > 0 && $nowkindarr[$iname][0] > $maxnum) || $nowkindarr[$iname][1] > $nowarea) //3、不符合最大数目要求 4、禁区数不符合
			{
				unset($nowkindarr[$iname]);
			}else{
				break;
			}
		}
		$aready[] = $iname;
		return $iname;
	}
	
	function wdebug_showreq(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$req1=\skillbase\skill_getvalue(424,'cur1');
		$req2=\skillbase\skill_getvalue(424,'cur2');
		$req3=\skillbase\skill_getvalue(424,'cur3');
		$req4=\skillbase\skill_getvalue(424,'cur4');
		$reqarr = array();
		for($i=1;$i<=4;$i++){
			if(${'req'.$i}) $reqarr[] = ${'req'.$i};
		}
		foreach($reqarr as &$req){
			$req = "<span class=\"yellow b\">{$req}</span>";
		}
		return implode('或', $reqarr);
	}
	
	//初始100，每10级+100
	function get_wdebug_money(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(424)){
			$clv=\skillbase\skill_getvalue(424,'lvl');
			$prize=100+floor($clv/10)*100;
			return $prize;
		}
		return 0;
	}
	
	//每级1个技能点
	function get_wdebug_skillpoint(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(424)){
			return 1;
		}
		return 0;
	}
	
	//如果杀死NPC等级数/5大于当前除错等级，每多1则扣50，扣到0为止
	//简单说就是每2层允许杀1个兵，每5层允许杀1个全息，每超过1个兵扣100，每超过1个全息扣250
	function get_wdebug_money_punish(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(424)){
			$clv=\skillbase\skill_getvalue(424,'lvl');
			$sum=\skillbase\skill_getvalue(424,'npclvlsum');
			$diff = max(round($sum/5) - $clv, 0);
			$punish = $diff*50;
			return $punish;
		}else return 0;
	}
	
	//除错主函数，成功除错返回true，否则返回false
	function wdebug(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger','skill424','skillbase','map'));
		if(\skillbase\skill_query(424)){
			$clv=(int)\skillbase\skill_getvalue(424,'lvl');
			$position = wdebug_check();
			if($position){
				$itm = ${'itm'.$position};
				$log .= "<span class=\"yellow b\">除错成功。</span><br />";
				$log .= "<span class=\"red b\">$itm</span>用光了。<br />";
				addnews ( 0, 'skill424', $name, $clv+1, $itm);
				${'itm'.$position} = ${'itmk'.$position} = ${'itmsk'.$position} = '';
				${'itme'.$position} =${'itms'.$position} =0;
				$gdice=rand(1,3);
				//判定是否已经到过这个层
				$prizeflag = 1;
				$mlv = (int)\skillbase\skill_getvalue(424,'maxlvl',$pa);
				if($clv < $mlv && $clv < 20) $prizeflag = 0;
				//开始实际判定
				if($prizeflag) {
					$money_prize = get_wdebug_money();
					$money_punish = get_wdebug_money_punish();
					if($money_punish) $log .="<span class=\"red b\">你不顾任务只顾伐木的行为让你的奖励下降了！</span><br />";
					$money_prize = max(0, $money_prize-$money_punish);
					$money += $money_prize;
					
					$skillpoint_prize = get_wdebug_skillpoint();
					$skillpoint += $skillpoint_prize;
					//提示
					$log .= '<span class="yellow b">';
					if(!empty($money_prize)) {
						$log .="获得了{$money_prize}元";
						if($skillpoint_prize) $log .="和{$skillpoint_prize}个技能点。";
					}elseif(!empty($skillpoint_prize)) {
						$log .="获得了{$skillpoint_prize}个技能点。";
					}
					$log .= "</span><br />";
					if ($gdice==1){
						$wp+=10;$wk+=10;$wc+=10;$wd+=10;$wg+=10;$wf+=10;
						$log .="<span class=\"yellow b\">获得了10点全熟练。</span><br />";
					}
					if ($gdice==2){
						$att+=10;$def+=10;
						$log .="<span class=\"yellow b\">获得了10点基础攻防。</span><br />";
					}	
					if ($gdice==3){
						$mhp+=10;$msp+=10;$hp+=10;$sp+=10;
						$log .="<span class=\"yellow b\">获得了10点命体上限。</span><br />";
					}
				}else{
					$log .='<span class="yellow b">你已经获得过这一层的奖励了。</span><br />';
				}
				$clv++;
				\skillbase\skill_setvalue(424,'lvl',$clv);
				if($clv > $mlv) \skillbase\skill_setvalue(424,'maxlvl',$clv);
				
				wdebug_reset();
				$log .='下次除错需要物品'.wdebug_showreq().'<br />';
				
				$mode = 'command';
				return true;
			}else{
				$log .= '本次除错需要物品'.wdebug_showreq().'。你没有进行除错所需的物品。<br />';
				$mode = 'command';
				return false;
			}
		}else{
			$log .= '<span class="red b">你没有这个技能！</span><br />';
			$mode = 'command';
			return false;
		}
	}
	
	//杀死NPC时记录NPC等级总和
	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(424,$pa) && check_unlocked424($pa) && $pd['type'] && $pd['hp'] <= 0)
		{
			$sum = \skillbase\skill_getvalue(424,'npclvlsum',$pa);
			\skillbase\skill_setvalue(424,'npclvlsum',$sum+$pd['lvl'],$pa);		
		}
	}	
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill424_special' && get_var_input('subcmd')=='wdebug') 
		{
			wdebug();
			return;
		}
			
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'skill424') 
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}</span>提交了<span class=\"yellow b\">{$c}</span>，完成了<span class=\"cyan b\">第{$b}次「除错」尝试</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>