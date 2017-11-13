<?php

namespace skill424
{
	function init() 
	{
		define('MOD_SKILL424_INFO','club;active;locked;');
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
		$clv=\skillbase\skill_getvalue(424,'lvl');
		$req1 = $req2 = $req3 = $req4 = '';
		
		
		if(0==$clv) {
			$req1='电池';$req2='探测器电池';
		}elseif($clv <= 10){//前10层只产生个数在30以上的地图道具或个数在40以上的商店道具，且各1枚
			$req1 = wdebug_getreq('mapitem', 30);
			$req2 = wdebug_getreq('shopitem', 40, -1, $req1);
		}elseif($clv <= 20){//10-20层产生个数在15-100的地图道具、商店道具，有可能两个都是地图道具
			$req1 = wdebug_getreq('mapitem', 15, 100);
			$req2 = wdebug_getreq(array('mapitem', 'shopitem'), 15, 100, $req1);
		}elseif($clv <= 30){//20-30层产生个数在5-60的地图道具、商店道具（有可能两个都是地图道具）和个数在25以上的合成物、NPC道具
			$req1 = wdebug_getreq('mapitem', 5, 60);
			$req2 = wdebug_getreq(array('mapitem', 'shopitem'), 5, 60, $req1);
			$req3 = wdebug_getreq(array('mixitem','syncitem','overlayitem','npc'), 25, -1, array($req1, $req2));
		}elseif($clv <= 40){//30-40层产生个数在2-40的地图道具、商店道具（有可能两个都是地图道具）和个数在15-60的合成物、NPC道具
			$req1 = wdebug_getreq('mapitem', 2, 30);
			$req2 = wdebug_getreq(array('mapitem', 'shopitem'), 2, 30, $req1);
			$req3 = wdebug_getreq(array('mixitem','syncitem','overlayitem','npc'), 15, 60, array($req1, $req2));
		}elseif($clv <= 50){//40-50层产生个数在0-10的地图道具、商店道具（有可能两个都是地图道具）、个数在5-20的合成物、NPC道具以及所有个数在10以下的玩意儿
			$req1 = wdebug_getreq('mapitem', 0, 10);
			$req2 = wdebug_getreq(array('mapitem', 'shopitem'), 0, 10, $req1);
			$req3 = wdebug_getreq(array('mixitem','syncitem','overlayitem','npc'), 5, 20, array($req1, $req2));
			$req4 = wdebug_getreq(array('mapitem','shopitem','mixitem','syncitem','overlayitem','npc','presentitem','ygoitem'), 0, 10, array($req1, $req2, $req3));
		}else{//60层以上全部浮云物，哈哈哈哈！
			$req1 = wdebug_getreq(array('mapitem','shopitem','mixitem','syncitem','overlayitem','npc','presentitem','ygoitem'), 0, 5);
			$req2 = wdebug_getreq(array('mapitem','shopitem','mixitem','syncitem','overlayitem','npc','presentitem','ygoitem'), 0, 5, $req1);
			$req3 = wdebug_getreq(array('mapitem','shopitem','mixitem','syncitem','overlayitem','npc','presentitem','ygoitem'), 0, 5, array($req1, $req2));
			$req4 = wdebug_getreq(array('mapitem','shopitem','mixitem','syncitem','overlayitem','npc','presentitem','ygoitem'), 0, 5, array($req1, $req2, $req3));
		}
		\skillbase\skill_setvalue(424,'cur1',$req1,$pa);
		\skillbase\skill_setvalue(424,'cur2',$req2,$pa);
		\skillbase\skill_setvalue(424,'cur3',$req3,$pa);
		\skillbase\skill_setvalue(424,'cur4',$req4,$pa);
	}
	
	//在类别为$kind、数量在$min, $max之间的道具里随机选择1个并返回名字
	function wdebug_getreq($kind, $min, $max=-1, $aready=''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		global $cont_mapitem,$cont_shopitem,$cont_mixitem,$cont_syncitem,$cont_overlayitem,$cont_presentitem,$cont_ygoitem,$cont_fyboxitem,$cont_npc;
		include_once GAME_ROOT.'/gamedata/config/gtype1item.config.php';
		if(!is_array($kind)) $kind = array($kind);
		if(!is_array($aready)) $aready = array($aready);
		$nowkindarr = array();
		foreach($kind as $kv){
			if(!isset(${'cont_'.$kv})) return NULL;
			$nowkindarr = array_merge($nowkindarr, ${'cont_'.$kv});
		}
		$i = 0;
		do{
			$iname = array_rand($nowkindarr);
			$i ++;
		}while ($i > 999 || in_array($iname,$aready) || $nowkindarr[$iname] < $min || ($max > 0 && $nowkindarr[$iname] > $max));
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
			$req = "<span class=\"yellow\">{$req}</span>";
		}
		return implode('或', $reqarr);
	}
		
	function wdebug(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger','skill424','skillbase','map'));
		if(\skillbase\skill_query(424)){
			$clv=\skillbase\skill_getvalue(424,'lvl');
			$position = wdebug_check();
			if($position){
				$itm = ${'itm'.$position};
				$log .= "<span class=\"yellow\">除错成功。</span><br />";
				$log .= "<span class=\"red\">$itm</span>用光了。<br />";
				addnews ( 0, 'skill424', $name, $clv+1);
				${'itm'.$position} = ${'itmk'.$position} = ${'itmsk'.$position} = '';
				${'itme'.$position} =${'itms'.$position} =0;
				$gdice=rand(1,3);
				$money+=200;
				$skillpoint++;
				$log .="<span class=\"yellow\">获得了200元和1个技能点。</span><br />";
				if ($gdice==1){
					$wp+=10;$wk+=10;$wc+=10;$wd+=10;$wg+=10;$wf+=10;
					$log .="<span class=\"yellow\">获得了10点全熟练。</span><br />";
				}
				if ($gdice==2){
					$att+=10;$def+=10;
					$log .="<span class=\"yellow\">获得了10点基础攻防。</span><br />";
				}	
				if ($gdice==3){
					$mhp+=10;$msp+=10;$hp+=10;$sp+=10;
					$log .="<span class=\"yellow\">获得了10点命体上限。</span><br />";
				}
				$clv++;
				\skillbase\skill_setvalue(424,'lvl',$clv);
				wdebug_reset();
				$log .='下次除错需要物品'.wdebug_showreq();
				
				$mode = 'command';
				return;
			}else{
				$log .= '本次除错需要物品'.wdebug_showreq().'。你没有进行除错所需的物品。<br />';
				$mode = 'command';
				return;
			}
		}else{
			$log .= '<span class="red">你没有这个技能！</span><br />';
			$mode = 'command';
			return;
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','input'));
	
		if ($mode == 'special' && $command == 'skill424_special' && $subcmd=='wdebug') 
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
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}完成了第{$b}次<span class=\"yellow\">「除错」</span>尝试</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>