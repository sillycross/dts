<?php

namespace skill487
{	
	function init() 
	{
		define('MOD_SKILL487_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[487] = '后手';
	}
	
	function acquire487(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(487,'h_flag','0',$pa);
		\skillbase\skill_setvalue(487,'hlist','1',$pa);
	}
	
	function lost487(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(487,'h_flag',$pa);
		\skillbase\skill_delvalue(487,'hlist',$pa);
		
	}
	
	function check_unlocked487(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_hlist487(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(487,$pa)) return false;
		$hlist = \skillbase\skill_getvalue(487,'hlist',$pa);
		return explode('_', (string)$hlist);
	}
	
	function set_hlist487($hlist,&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(487,$pa)) return false;
		$hlist = implode('_', $hlist);
		\skillbase\skill_setvalue(487,'hlist',$hlist,$pa);
		return true;
	}
	
	function show_last_hitem487(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','itemmain'));
		if(!$pa) $pa = $sdata;
		$hlist = get_hlist487($pa);
		$itmn = array_pop($hlist);
		if(!$itmn || !$pa['itm'.$itmn]) $itmname = $iteminfo['N'];
		else $itmname = $pa['itm'.$itmn];
		
		return $itmname;
	}
	
	function eat_until_full487($item, &$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pa) $pa = $sdata;
		if(!$pa['itms'.$item]) return false;
		//模拟itemuse_wrapper
		$theitem=Array();
		$theitem['itm'] = &$pa['itm'.$item];
		$theitem['itmk'] = &$pa['itmk'.$item];
		$theitem['itme'] = &$pa['itme'.$item];
		$theitem['itms'] = &$pa['itms'.$item]; 
		$theitem['itmsk'] = &$pa['itmsk'.$item]; 
		eval(import_module('logger'));
		$tmp_hp = $pa['hp'];
		$tmp_log = $log;//拦截log
		$i = 0;
		while($pa['hp'] > 0 && $pa['hp'] < $pa['mhp'] && $theitem['itms']){
			\edible\itemuse_edible($theitem);
			$i ++;
			if($tmp_hp > $pa['hp']) break;//吃到毒立刻中止
			else $tmp_hp = $pa['hp'];
		}
		$log = $tmp_log;
		return $i;
	}
	
	function auto_recover487(&$pa, &$pd, $active){//$pa是瞪眼方，$pd是回血方，$active来自battle，指是不是玩家视角
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(487,$pd)) return false;
		eval(import_module('logger'));
		$hlist = get_hlist487($pd);
		if(!$hlist) return false;
		$item = array_pop($hlist);
		if(!$item || !$pd['itms'.$item]) return false;
		$tmp_hp = $pd['hp'];
		$tmp_itm = $pd['itm'.$item];
		$r = eat_until_full487($item, $pd);
		if($r) {
			if($pd['hp'] > $tmp_hp){//回复
				$hpup = $pd['hp'] - $tmp_hp;
				$log .= \battle\battlelog_parser($pa, $pd, 1-$active, "幸好<:pd_name:>留有后手，紧急使用了{$tmp_itm}，<span class='lime'>回复了{$hpup}点生命！</span>");
				//$pa['battlelog'] .= \battle\battlelog_parser($pa, $pd, $active, "幸好<:pd_name:>留有后手，紧急使用了{$tmp_itm}，<span class='lime'>回复了{$hpup}点生命！</span>");
			}
		}
	}
	
	function battle_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		//暂时还不能做到被玩家主动攻击时回血
		if ($active && \skillbase\skill_query(487,$pa) && check_unlocked487($pa) && $pa['hp']<$pa['mhp']/2 && $pa['hp']>0)
		{
			auto_recover487($pd, $pa, $active);
		}elseif ($active && \skillbase\skill_query(487,$pd) && check_unlocked487($pd) && $pd['hp']<$pd['mhp']/2 && $pd['hp']>0)
		{
			auto_recover487($pa, $pd, $active);
		}
	}
	
	function post_traphit_events($pa, $sdata, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $sdata, $tritm, $damage);
		if (\skillbase\skill_query(487,$sdata) && check_unlocked487($pd) && $sdata['hp']<$sdata['mhp']/2 && $sdata['hp']>0)
		{
			auto_recover487($pa, $sdata, 1);
		}
	}
	
	//记录道具
	function record_hlist487($item, &$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($item < 0 || $item > 6) return false;
		if(!\skillbase\skill_query(487,$pa)) return false;
		eval(import_module('player', 'skill487'));
		$hlist = get_hlist487($pa);
		del_hlist487($item, $pa);
		$hlist = array_filter($hlist);
		$hlist[] = $item;
		set_hlist487($hlist,$pa);
		return true;
	}
	
	//删掉队列里特定的道具位
	function del_hlist487($item, &$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(487,$pa)) return false;
		$hlist = get_hlist487($pa);
		if(in_array($item, $hlist)) unset($hlist[array_search($item, $hlist)]);
		set_hlist487($hlist,$pa);
		return true;
	}
	
	//检查列表里各项是否还在，如果不在，自动修正列表
	function check_hlist487(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!\skillbase\skill_query(487,$pa)) return false;
		$hlist = get_hlist487($pa);
		$dellist = array();
		foreach($hlist as $iv){
			if(!$iv || !$pa['itms'.$iv]) $dellist[] = $iv;
		}
		foreach($dellist as $dv){
			del_hlist487($dv, $pa);
		}
		return $dellist;
	}
	
	//在判定回复时只记录flag
	function edible_recover($itm, $hpup, $spup){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$chprocess($itm, $hpup, $spup);
		if(!\skillbase\skill_query(487,$sdata)) return;
		if($hpup > 0){
			\skillbase\skill_setvalue(487,'h_flag','1',$sdata);
		}
	}
	
	//在道具未消耗的情况下才记录道具本身。因为要获得编号，必须接管wrapper
	function itemuse_wrapper($itmn) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($itmn);
		eval(import_module('player'));
		if(!\skillbase\skill_query(487,$sdata)) return;
		$itms = ${'itms' . $itmn};
		if(\skillbase\skill_getvalue(487,'h_flag',$sdata)){
			\skillbase\skill_setvalue(487,'h_flag','0',$sdata);
			if($itms){//有耐久的情况下记录该道具				
				record_hlist487($itmn, $sdata);
			}
//			else {//没耐久的情况下删掉该道具
//				del_hlist487($itmn, $sdata);
//			}			
		}
	}
	
	//如果道具耗尽，则检查列表。
	function itms_reduce(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($theitem);
		eval(import_module('player'));
		if(!\skillbase\skill_query(487,$sdata)) return;
		if(!$theitem['itms']){
			check_hlist487($sdata);
		}
	}
	
	//道具丢弃，则检查列表
	function itemdrop($item) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($item);
		eval(import_module('player'));
		if(\skillbase\skill_query(487,$sdata)) check_hlist487($sdata);
		return $ret;
	}
	
	//道具合并，则两个选项都应该从列表里清掉（可能带毒）
	function itemmerge($itn1,$itn2){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($itn1,$itn2);
		eval(import_module('player'));
		if($ret && \skillbase\skill_query(487,$sdata)) {
			del_hlist487($itn1, $sdata);
			del_hlist487($itn2, $sdata);
		}
		return $ret;
	}
	
	//道具交换，记录位置也交换
	function itemmove($from,$to){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($from,$to);
		eval(import_module('player'));
		if($ret && \skillbase\skill_query(487,$sdata)){
			$hlist = get_hlist487($pa);
			if(!$sdata['itms'.$from]) //移动
			{
				$where = array_search($from, $hlist);
				if(false !== $where) $hlist[$where] = $to;
			}
			else //交换
			{
				$where1 = array_search($from, $hlist);
				$where2 = array_search($to, $hlist);
				if(false !== $where1) $hlist[$where1] = $to;
				if(false !== $where2) $hlist[$where2] = $from;
			}
			set_hlist487($hlist, $pa);
		}
		return $ret;
	}
}

?>