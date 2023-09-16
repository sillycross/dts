<?php

namespace skill518
{
	function init() 
	{
		define('MOD_SKILL518_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[518] = '回归';
	}
	
	function acquire518(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(518,'rmtime','0',$pa);
		\skillbase\skill_setvalue(518,'areanum','0',$pa);
	}
	
	function lost518(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked518(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_remaintime518(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return (int)\skillbase\skill_getvalue(518,'rmtime',$pa);
	}
	
	function check_time_add518()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger'));
		$tmp_skill518_rmtime = get_remaintime518();
		$tmp_skill518_areanum = (int)\skillbase\skill_getvalue(518,'areanum');
		$skill518_addtime = 0;
		if($areanum > $tmp_skill518_areanum) {
			$skill518_addtime = floor(($areanum - $tmp_skill518_areanum)/$areaadd);
			//不会积累超过2次
			if($skill518_addtime + $tmp_skill518_rmtime > 2) $skill518_addtime = max(0, 2 - $tmp_skill518_rmtime);
		}
		if($skill518_addtime > 0) {
			\skillbase\skill_setvalue(518,'rmtime',round(get_remaintime518()+$skill518_addtime));
			$log.= "<span class=\"lime b\">你的回归次数增加了{$skill518_addtime}次。</span><br>";
		}
		\skillbase\skill_setvalue(518,'areanum',$areanum);
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(518) && check_unlocked518()){
			check_time_add518();
		}
		$chprocess();
	}
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		if(\skillbase\skill_query(518,$pd) && check_unlocked518($pd)){
			$pd['revive_sequence'][160] = 'skill518';
		}
		return;
	}	

	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('skill518' == $rkey && in_array($pd['state'],Array(20,21,22,23,24,25,27,29,39,40,41))){
			if(get_remaintime518($pd) > 0)
			$ret = true;
		}
		return $ret;
	}
	
	//发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('skill518' == $rkey){
			//$pd['hp']=$pd['mhp'];
			$pd['skill518_flag']=1;
			$rmtime = (int)\skillbase\skill_getvalue(518,'rmtime',$pd);
			\skillbase\skill_setvalue(518,'rmtime',$rmtime-1,$pd);
			$pd['rivival_news'] = array('revival518', $pd['name']);
		}
		return;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$ret = $chprocess($pa,$pd);
		
		eval(import_module('sys','logger'));
		
		if(!empty($pd['skill518_flag'])){
			if ($pd['o_state']==27)	//陷阱
			{
				$log.= "<span class=\"lime b\">但是，你强烈的执念使你挣脱了死亡的束缚！</span><br>";
				if(!$pd['sourceless']){
					$w_log = "<span class=\"lime b\">但是，{$pd['name']}强烈的执念使其从死亡线上回来了！</span><br>";
					\logger\logsave ( $pa['pid'], $now, $w_log ,'b');
				}
			}
		}
		return $ret;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		if (isset($pd['skill518_flag']) && $pd['skill518_flag'])
		{
			if ($active)
			{
				$log.='<span class="lime b">但是，敌人强烈的执念使其从死亡线上回来了！</span><br>';
				$pd['battlelog'].='<span class="lime b">但是，你强烈的执念使你挣脱了死亡的束缚！</span>';
			}
			else
			{
				$log.='<span class="lime b">但是，你强烈的执念使你挣脱了死亡的束缚！</span><br>';
				$pd['battlelog'].='<span class="lime b">但是，敌人强烈的执念使其从死亡线上回来了！</span>';
			}
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'revival518') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}强烈的执念使其从死亡线上回来了！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>