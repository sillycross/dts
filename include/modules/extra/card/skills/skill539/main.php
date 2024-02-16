<?php

namespace skill539
{
	function init() 
	{
		define('MOD_SKILL539_INFO','card;feature;');//设为feature防止被直击失去记录时间的功能
		eval(import_module('clubbase'));
		$clubskillname[539] = '不死';
	}
	
	function acquire539(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(539,'activated_num','0',$pa);
		\skillbase\skill_setvalue(539,'lastdeath','0',$pa);
	}
	
	function lost539(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked539(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//只有自己的操作才会处理复活，挂机是不会复活的
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(539)) {
			eval(import_module('player','sys','logger'));
			if(check_skill539_revive_available())//满足复活条件则判定复活
			{
				skill539_revive_player();
			}elseif($hp <= 0) {//其他情况下添加一条带倒计时的复活提示
				$countdown = \skillbase\skill_getvalue(539,'lastdeath') + get_skill539_revive_interval() - $now;
				$timing_r = $countdown > 3600 ? date('hh:mm:ss', $countdown) : date('mm:ss', $countdown);
				//$timing_r = sprintf("%02d", floor($countdown/60)).':'.sprintf("%02d", $countdown%60);
				$timing = $countdown * 1000;
				$format = $countdown > 3600 ? 'hh:mm:ss' : 'mm:ss';
				$log .= '你在<span class="yellow b" id="timer_skill539">'.$timing_r.'</span>后可以复活。如果倒计时结束，请刷新游戏界面。<br>';
				//因为game页面不会执行shwData()也就不会触发自动的updateTime()，这里手动触发
				$log .= "<img style=\"display:none;\" type=\"hidden\" src=\"img/blank.png\" onload=\"updateTime('timer_skill539', $timing, 0, 1000, '$format');\">";
			}elseif(!empty(\skillbase\skill_getvalue(539,'need_log'))) {//其他情况下才判断是否显示复活信息
				\skillbase\skill_setvalue(539, 'need_log', 0);
				$log .= '<span class="yellow b">不死鸟的力量唤醒了你，你化为一团火焰重生了！</span><br>';
			}
		}
		$chprocess();
	}
	
	//被杀时记录被杀时间
	function kill(&$pa, &$pd) 
	{	
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd);
		if($pd['hp'] <= 0 && \skillbase\skill_query(539,$pd)) {
			eval(import_module('sys'));
			\skillbase\skill_setvalue(539, 'lastdeath', $now, $pd);
		}
		return $ret;
	}
	
	//复活处理。
	function skill539_revive_player(&$pdata=NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pdata) {
			eval(import_module('player'));
			$pdata = &$sdata;
		}
		if(\skillbase\skill_query(539,$pdata) && skill539_revive_player_core($pdata)) {
			eval(import_module('sys','logger'));
			\skillbase\skill_setvalue(539, 'activated_num', (int)\skillbase\skill_getvalue(539,'activated_num', $pdata) + 1, $pdata);
			\skillbase\skill_setvalue(539, 'need_log', 1, $pdata);
			addnews($now,'revive539',$pdata['name']);
			$deathnum --; $alivenum ++;
			save_gameinfo();
		}
	}
	
	//复活处理核心函数。关键点在于单独update player表覆盖player_dead_flag字段（该字段不会被自动覆盖）
	//成功复活返回true，否则返回false
	function skill539_revive_player_core(&$pdata=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pdata) {
			eval(import_module('player'));
			$pdata = &$sdata;
		}
		if($pdata['hp']) return false;
		if($pdata['mhp'] <= 0) $pdata['mhp'] = 1;
		$pdata['hp'] = $pdata['mhp'];
		$pdata['state']=0;
		$pdata['inf']='';
		$pdata['player_dead_flag']=0;
		eval(import_module('sys'));
		$db->query("UPDATE {$tablepre}players SET player_dead_flag='0' WHERE pid='".$pdata['pid']."'");
		return true;
	}
	
	//判定本次指令是否符合复活条件
	function check_skill539_revive_available(&$pdata=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pdata) {
			eval(import_module('player'));
			$pdata = &$sdata;
		}
		if($pdata['hp'] <= 0 && \skillbase\skill_query(539,$pdata)) {
			eval(import_module('sys'));
			if($now > \skillbase\skill_getvalue(539,'lastdeath', $pdata) + get_skill539_revive_interval($pdata))
				return true;
		}
		return false;
	}
	
	//获得复活间隔。计算方式：初始30秒，每复活一次就翻倍
	function get_skill539_revive_interval(&$pdata=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$pdata) {
			eval(import_module('player'));
			$pdata = &$sdata;
		}
		if(!\skillbase\skill_query(539,$pdata)) return 999983;
		$ret = 30;
		$ret *= pow(2, (int)\skillbase\skill_getvalue(539,'activated_num', $pdata));
		return $ret;
	}
	
	//复活讯息
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'revive539') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}在烈火中重生了！</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}
?>