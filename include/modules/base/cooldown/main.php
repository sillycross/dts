<?php

namespace cooldown
{
	function init() 
	{
		global $cmdcdtime, $rmcdtime;
		$cmdcdtime=0; $rmcdtime=0;
	}
	
	function get_move_coldtime(&$dest){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cooldown'));
		return $movecoldtime;
	}
	
	function get_search_coldtime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cooldown'));
		return $searchcoldtime;
	}
	
	function get_itemuse_coldtime(&$item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cooldown'));
		return $itemusecoldtime;
	}
	
	//设置冷却时间的通用函数，单位毫秒
	//如果别的动作已有了冷却时间，会自动选较大的那个
	function set_coldtime($cd, $forced=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cooldown'));
		if($coldtimeon || $forced) {
			if($cmdcdtime < $cd) $cmdcdtime = $cd;
		}
		return $cmdcdtime;
	}
	
	//检查是否在冷却中
	function check_in_coldtime()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','cooldown','logger'));
		if($coldtimeon)
		{
			$cdover = $cdsec*1000 + $cdmsec;
			$nowmtime = floor(getmicrotime()*1000);
			$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
			if($rmcdtime > 0) {
				$log .= '<span class="yellow b">冷却时间尚未结束！</span><br>';
				$mode = 'command';
				return true;
			}
		}
		return false;
	}
	
	function move($moveto = 99) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','cooldown'));

		if(check_in_coldtime()) return;
		
		if($coldtimeon) set_coldtime(get_move_coldtime($moveto));
		
		$chprocess($moveto);
		
	}
	
	function search() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','cooldown'));
		
		if(check_in_coldtime()) return;
	
		if($coldtimeon) set_coldtime(get_search_coldtime());
		
		$chprocess();
	
	}
	
	function itemuse_wrapper($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','cooldown'));

		if(check_in_coldtime()) return;
		
		if($coldtimeon) set_coldtime(get_itemuse_coldtime($item));
		
		$chprocess($item);
	}
	
	function get_pstime()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		global $___MOD_SRV;
		if ($___MOD_SRV)	//daemon模式下使用client的开始执行时的时间作为页面开始时间，由于以三个下划线开头，是安全的
		{
			return get_var_in_module('___PAGE_STARTTIME_VALUE', 'input');
		}
		else			//非daemon模式下使用正常的command.php的开始时间作为页面开始时间
		{
			global $pagestartime;
			return $pagestartime;
		}
	}
	
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','cooldown'));
		
		$pstime = get_pstime();
	
		//指令执行完毕，更新冷却时间
		if($coldtimeon)
			if ($cmdcdtime!=0)	//执行了有冷却时间的命令，更新为该命令的冷却时间
			{
				//用页面开始时间去计算冷却时间
				$nowmtime = floor($pstime*1000)+$cmdcdtime;
				$cdsec = floor($nowmtime/1000);
				$cdmsec = fmod($nowmtime , 1000);
				$cdover = $cdsec*1000 + $cdmsec;
				//然后用当前时间来计算CD
				$nowmtime = floor(getmicrotime()*1000);
				$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
			}
			else				//没有执行有冷却时间的命令，冷却时间不变
			{
				$cdover = $cdsec*1000 + $cdmsec;
				$nowmtime = floor(getmicrotime()*1000);
				$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
			}

		$chprocess();
	}
	
	function check_cooltime_on()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cooldown'));
		return $coldtimeon;
	}
	
	function prepare_response_content()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		eval(import_module('sys','logger','player','cooldown'));
		if(check_cooltime_on())
		{
			$cdover = $cdsec*1000 + $cdmsec;
			$nowmtime = floor(getmicrotime()*1000);
			$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
			if($showcoldtimer && $rmcdtime){
				$gamedata['timer'] = $rmcdtime;
			}
			if($hp > 0 && $showcoldtimer && $rmcdtime){
				$x1=$rmcdtime/1000; $x1=(int)$x1; $y1=($rmcdtime%1000)/100; $y1=(int)$y1;
				$log .= "行动冷却时间：<span id=\"timer\" class=\"yellow b\">{$x1}.{$y1}</span>秒<script type=\"text/javascript\">demiSecTimerStarter($rmcdtime);</script><br>";
			}
		}
	}
	
	function prepare_initial_response_content()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		eval(import_module('sys','logger','player','cooldown'));
		if(check_cooltime_on())
		{
			$cdover = $cdsec*1000 + $cdmsec;
			$nowmtime = floor(getmicrotime()*1000);
			$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
			if($hp > 0 && $showcoldtimer && $rmcdtime)
			{
				$x1=$rmcdtime/1000; $x1=(int)$x1; $y1=($rmcdtime%1000)/100; $y1=(int)$y1;
				$log .= "行动冷却时间：<span id=\"timer\" class=\"yellow b\">{$x1}.{$y1}</span>秒<script type=\"text/javascript\">demiSecTimerStarter($rmcdtime);</script><br>";
			}
		}
	}
	
}

?>
