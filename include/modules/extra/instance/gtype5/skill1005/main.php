<?php

namespace skill1005
{
	function init() 
	{
		define('MOD_SKILL1005_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[1005] = '修复';
	}
	
	function acquire1005(&$pa)
	{//总之是Reskin破解
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(1005,'lvl',0,$pa);
		\skillbase\skill_setvalue(1005,'cur1','智慧果',$pa);
		\skillbase\skill_setvalue(1005,'cur2','',$pa);
		\skillbase\skill_setvalue(1005,'cur3','',$pa);
		\skillbase\skill_setvalue(1005,'cur4','',$pa);
		\skillbase\skill_setvalue(1005,'npclvlsum','0',$pa);
		//此外本技能还有储存这局奖励的功能
		\skillbase\skill_setvalue(1005,'maxlvl','0',$pa);
//		\skillbase\skill_setvalue(1005,'rank','0',$pa);
//		\skillbase\skill_setvalue(1005,'prize','0',$pa);
	}
	
	function lost1005(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(1005,'lvl',$pa);
		\skillbase\skill_delvalue(1005,'cur1',$pa);
		\skillbase\skill_delvalue(1005,'cur2',$pa);
		\skillbase\skill_delvalue(1005,'cur3',$pa);
		\skillbase\skill_delvalue(1005,'cur4',$pa);
	}
	
	function check_unlocked1005(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function wdebug_check1005(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if(!\skillbase\skill_query(1005)) return false;
		$req1=\skillbase\skill_getvalue(1005,'cur1');
		$req2=\skillbase\skill_getvalue(1005,'cur2');
		$req3=\skillbase\skill_getvalue(1005,'cur3');
		$req4=\skillbase\skill_getvalue(1005,'cur4');
		$clv=\skillbase\skill_getvalue(1005,'lvl');
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
	
	function wdebug_reset1005(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if(!\skillbase\skill_query(1005)) return false;
		$aready=array(\skillbase\skill_getvalue(1005,'cur1'), \skillbase\skill_getvalue(1005,'cur2'), \skillbase\skill_getvalue(1005,'cur3'), \skillbase\skill_getvalue(1005,'cur4'));
		$aready = array_filter($aready);
		$clv=\skillbase\skill_getvalue(1005,'lvl');
		$req1 = '智慧果';
		$req2 = $req3 = $req4 = '';
		
		
/* 		if(0==$clv) {
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
		} */
		\skillbase\skill_setvalue(1005,'cur1',$req1,$pa);
		\skillbase\skill_setvalue(1005,'cur2',$req2,$pa);
		\skillbase\skill_setvalue(1005,'cur3',$req3,$pa);
		\skillbase\skill_setvalue(1005,'cur4',$req4,$pa);
	}
	
	//在类别为$kind、数量在$min, $max之间的道具里随机选择1个并返回名字，且禁区符合要求
/* 	function wdebug_getreq($kind, $minnum, $maxnum=-1, &$aready=''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		$nowarea = floor($areanum/$areaadd);
		//daemon模式下，include_once会出问题
		//为保证同1次执行时不反复调用文件，只能这么办了
		global $cont_mapitem,$cont_shopitem,$cont_mixitem,$cont_syncitem,$cont_overlayitem,$cont_presentitem,$cont_ygoitem,$cont_fyboxitem,$cont_npcinfo_gtype1;
		if(empty($cont_mapitem)) include GAME_ROOT.'/gamedata/cache/gtype1item.config.php';
		if(!is_array($kind)) $kind = array($kind);
		if(!is_array($aready)) $aready = array($aready);
		$nowkindarr = array();
		foreach($kind as $kv){
			if('npc' == $kv) $kv = 'npcinfo_gtype1';
			if(!isset(${'cont_'.$kv})) return NULL;
			$nowkindarr = array_merge($nowkindarr, ${'cont_'.$kv});
		}
		for($i=0;$i<99;$i++){
			if(empty($nowkindarr)) break;
			$iname = array_rand($nowkindarr);
			
			if(in_array($iname,$aready) || $nowkindarr[$iname][0] < $minnum
				 || ($maxnum > 0 && $nowkindarr[$iname][0] > $maxnum) || $nowkindarr[$iname][1] > $nowarea)
			{
				unset($nowkindarr[$iname]);
			}else{
				break;
			}
		}
		$aready[] = $iname;
		return $iname;
	} */
	
	function wdebug_showreq1005(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$req1=\skillbase\skill_getvalue(1005,'cur1');
		$req2=\skillbase\skill_getvalue(1005,'cur2');
		$req3=\skillbase\skill_getvalue(1005,'cur3');
		$req4=\skillbase\skill_getvalue(1005,'cur4');
		$reqarr = array();
		for($i=1;$i<=4;$i++){
			if(${'req'.$i}) $reqarr[] = ${'req'.$i};
		}
		foreach($reqarr as &$req){
			$req = "<span class=\"yellow b\">{$req}</span>";
		}
		return implode('或', $reqarr);
	}
	
/* 	//初始100，每10级+100
	function get_wdebug_money(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(1005)){
			$clv=\skillbase\skill_getvalue(1005,'lvl');
			$prize=100+floor($clv/10)*100;
			return $prize;
		}else return 0;
	}
	
	//如果杀死NPC等级数/5大于当前除错等级，每多1则扣50，扣到0为止
	//简单说就是每2层允许杀1个兵，每5层允许杀1个全息，每超过1个兵扣100，每超过1个全息扣250
	function get_wdebug_money_punish(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(1005)){
			$clv=\skillbase\skill_getvalue(1005,'lvl');
			$sum=\skillbase\skill_getvalue(1005,'npclvlsum');
			$diff = max(round($sum/5) - $clv, 0);
			$punish = $diff*50;
			return $punish;
		}else return 0;
	} */
		
	function wdebug1005(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger','skill1005','skillbase','map'));
		if(\skillbase\skill_query(1005)){
			$clv=\skillbase\skill_getvalue(1005,'lvl');
			$position = wdebug_check1005();
			if($position){
				$itm = ${'itm'.$position};
				$log .= "<span class=\"yellow b\">修复成功。</span><br />";
				$log .= "<span class=\"red b\">$itm</span>用光了。<br />";
				addnews ( 0, 'skill1005', $name, $clv+1, $itm);
				${'itm'.$position} = ${'itmk'.$position} = ${'itmsk'.$position} = '';
				${'itme'.$position} =${'itms'.$position} =0;
				//TODO：59层掉卡
				if ($clv==59){
					$log .="<span class=\"evergreen b\">【FOREST】干的不错，还差一点点了！</span><br /><span class=\"evergreen b\">【FOREST】系统足够稳定时，我似乎能给你们点奖励——但不是现在。</span><br />";
					//TODO：掉卡逻辑
				}
				if ($clv>60){
					$log .="<span class=\"evergreen b\">【FOREST】你的工作已经完成了。</span><br /><span class=\"evergreen b\">再留在这里也没啥意义不是么？</span><br />【FOREST】来，吃个钢蹦儿。<br />从天上掉下了一个硬币，<br />";
					$addmoney=1;
					$money += $addmoney;
					$clv++;
					\skillbase\skill_setvalue(1005,'lvl',$clv);
					return;
				}
				//60层封顶了！
				if ($clv==60){
					$log .="<span class=\"evergreen b\">【FOREST】目前你们能修复的也就这么多了，我来想办法送你们出去。</span><br /><span class=\"evergreen b\">【FOREST】请检查你的背包。</span><br />";
					${'itm'.$position} = '游戏解除钥匙';
					${'itmk'.$position} = 'Y';
					${'itmsk'.$position} = 'Z';
					${'itme'.$position} =1;
					${'itms'.$position} =1;
					$mode = 'command';
					$clv++;
					\skillbase\skill_setvalue(1005,'lvl',$clv);
					return;
				}
				//TODO：Hard难度封顶为80层，60层掉UG让人进英灵殿。
/* 				if ($clv==80){
					$log .="<span class=\"evergreen b\">【FOREST】Your dirty hacker!</span><br /><span class=\"evergreen b\">【FOREST】Why are you still here?</span><br />";
					//TODO：掉卡逻辑
				} */
				//没封顶的话，加一层。
				$gdice=rand(1,3);
				//生成物品，Reskin搬运
				$file=GAME_ROOT."./include/modules/extra/instance/gtype5/skill1005/config/awarditem.config.php";//真是丑陋！
				$itemlist = openfile($file);
				$in = sizeof($itemlist);
				$i=rand(4,$in-1);//妈了个臀
				list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
				while (strpos($iskind,"x")!==false){
					$i=rand(4,$in-1);
					list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
				}
				//送物品
				if ($gdice==1){
					$itm0=$iname;$itme0=$ieff;$itms0=$ista;$itmsk0=$iskind;$itmk0=$ikind;
					\itemmain\itemget();
				}
				//送数值
				if ($gdice==2){
					$att+=15;$def+=15;
					$mhp+=50;$msp+=50;$hp+=50;$sp+=50;
					$log .="【FOREST】感谢你的努力。<br /><span class=\"yellow b\">获得了15点基础攻防和50点命体上限。</span><br />";
				}
				//送钱
				if ($gdice==3){
					$addmoney=386;
					$money += $addmoney;
					$log .="【FOREST】感谢你的努力。<br /><span class=\"yellow b\">获得了{$addmoney}元报酬。</span><br />";
				}
				
				$clv++;
				\skillbase\skill_setvalue(1005,'lvl',$clv);
				$mlv = \skillbase\skill_getvalue(1005,'maxlvl');
				if($clv > $mlv) \skillbase\skill_setvalue(1005,'maxlvl',$clv);
				
				wdebug_reset1005();
				$log .='下次修复需要物品'.wdebug_showreq1005();
				
				$mode = 'command';
				return;
			}else{
				$log .= '本次修复需要物品'.wdebug_showreq1005().'。你没有进行修复所需的物品。<br />';
				$mode = 'command';
				return;
			}
		}else{
			$log .= '<span class="red b">你没有这个技能！</span><br />';
			$mode = 'command';
			return;
		}
	}
	
	//杀死NPC时记录NPC等级总和
/* 	function player_kill_enemy(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);		
		if ( \skillbase\skill_query(1005,$pa) && check_unlocked424($pa) && $pd['type'] && $pd['hp'] <= 0)
		{
			$sum = \skillbase\skill_getvalue(1005,'npclvlsum',$pa);
			\skillbase\skill_setvalue(1005,'npclvlsum',$sum+$pd['lvl'],$pa);		
		}
	}	 */
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill1005_special' && get_var_input('subcmd')=='wdebug') 
		{
			wdebug1005();
			return;
		}
			
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'skill1005') 
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}</span>提交了<span class=\"yellow b\">{$c}</span>，完成了<span class=\"cyan b\">第{$b}次「修复」尝试</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>