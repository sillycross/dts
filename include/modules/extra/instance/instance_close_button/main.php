<?php

namespace instance_close_button
{
	$gametype_allow_close_button = Array(18, 19);//允许游戏内关闭房间的游戏类型
	
	function init() {
	}
	
	//判定是否符合游戏内关闭房间的条件：玩家为房主（0号位置），游戏未连斗，且没有其他玩家存活
	//返回true为符合，返回false不符合
	function check_instance_close_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//必须是允许关闭的房间类型
		eval(import_module('sys','instance_close_button'));
		if(!in_array($gametype, $gametype_allow_close_button)) return false;
		//必须未连斗
		if(\gameflow_combo\is_gamestate_combo()) return false;
		//必须没有其他玩家存活
		if($alivenum > 0) return false;
		//指令发起者必须为房主
		if(!function_exists('room_gettype_from_gtype')) include_once GAME_ROOT.'./include/roommng/roommng.func.php';
		if(0!==room_upos_check($roomvars)) return false;
		
		return true;
	}
	
	//关闭房间指令
	function instance_close(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('logger'));
		if(!check_instance_close_available()) {
			$log .= '<span class="red b">当前不满足关闭房间的条件！</span>';
			return;
		}
		
		eval(import_module('sys','player'));
		addnews($now, 'instance_close', $cuser);	//发送进行状况
		
		$gamestate = 40;//立刻连斗来结束;
		save_gameinfo();
		
		$gamedata['url']='game.php';
		return;
	}
	
	//关闭房间指令，接管pre_act()
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(strpos($command,'instance_close')===0){
			instance_close();
			return;
		}
		$chprocess();
	}
	
	//相关的进行状况描述
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'instance_close') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">房主{$a}关闭了当前房间。</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}
?>