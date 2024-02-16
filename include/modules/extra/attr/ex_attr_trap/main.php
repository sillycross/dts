<?php

namespace ex_attr_trap
{
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['M'] = '探雷';
		$itemspkdesc['M']='遭遇陷阱时回避率+35%';
		$itemspkremark['M']='……';
		$itemspkinfo['m'] = '防雷';
		$itemspkdesc['m']='遭遇陷阱时有40%概率免疫伤害';
		$itemspkremark['m']='……';
	}
	
	//陷阱探测回避加成
	function calculate_trapdetect_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		/*
		if($club == 7){//电脑社使用探雷器效率增加
			$escrate += 45;
		*/
		return 35;
	}
	
	function get_trap_escape_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\attrbase\check_itmsk('M'))
			return calculate_trapdetect_rate()+$chprocess();
		else  return $chprocess();
	}
	
	//陷阱迎击触发率
	function calculate_trapdef_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 40;
	}
	
	//判定迎击触发
	function check_trapdef_proc()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','trap','logger'));
		$proc_rate = calculate_trapdef_proc_rate();
		$dice = rand(0,99);
		if ($dice < $proc_rate)
		{
			//迎击触发
			if ($playerflag) 
			{
				addnews($now,'trapdef',$name,$trname,$itm0);
				if(!$selflag)
				{
					$w_log = "<span class=\"yellow b\">{$name}触发了你设置的陷阱{$itm0}，但是没有受到任何伤害！</span><br>";
					\logger\logsave ( $itmsk0, $now, $w_log ,'b');
				}	
			}	
			$log .= "糟糕，你触发了{$trprefix}陷阱<span class=\"yellow b\">$itm0</span>！<br>不过，身上装备着的防雷护盾启动了！<span class=\"yellow b\">在防雷功能的保护下你毫发无伤。</span><br>";
			return 1;
		}
		return 0;
	}
	
	function trap_deal_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','trap','logger'));
		
		if (\attrbase\check_itmsk('m'))
			if (check_trapdef_proc())
				return 0;
	
		return $chprocess();
	}
	
	function trap_miss_broken()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if (\attrbase\check_itmsk('M')) $log.='在探雷装备的帮助下，';
		$chprocess();
	}
	
	function trap_miss_reused()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if (\attrbase\check_itmsk('M')) $log.='在探雷装备的帮助下，';
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'trapdef') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}依靠防雷装备抵御了{$b}设置的陷阱{$c}的伤害</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
