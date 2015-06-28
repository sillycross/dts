if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','cooldown'));
		if($coldtimeon){
			$cdover = $cdsec*1000 + $cdmsec;
			$nowmtime = floor(getmicrotime()*1000);
			$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
		}

		if ($coldtimeon && $rmcdtime > 0)
		{
			$log .= '<span class="yellow">冷却时间尚未结束！</span><br>';
			$mode = 'command';
			return;
		}
		
		$chprocess();
		
		if($coldtimeon) $cmdcdtime=$searchcoldtime;
	}
	
	function itemuse($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','cooldown'));
		if($coldtimeon){
			$cdover = $cdsec*1000 + $cdmsec;
			$nowmtime = floor(getmicrotime()*1000);
			$rmcdtime = $nowmtime >= $cdover ? 0 : $cdover - $nowmtime;
		}

		if ($coldtimeon && $rmcdtime > 0)
		{
			$log .= '<span class="yellow">冷却时间尚未结束！</span><br>';
			$mode = 'command';
			return;
		}
		
		$chprocess($item);
		
		if($coldtimeon) $cmdcdtime=$itemusecoldtime;
	}
	
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','logger','player','cooldown'));
		//指令执行完毕，更新冷却时间
		if($coldtimeon)
			if ($cmdcdtime!=0)	//执行了有冷却时间的命令，更新为该命令的冷却时间
			{
				//设置CD结束时间为页面开始执行时间+冷却时间
				$nowmtime = floor($pagestart_time*1000)+$cmdcdtime;
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
	
	function prepare_response_content()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		eval(import_module('sys','logger','player','cooldown'));
		if($coldtimeon && $showcoldtimer && $rmcdtime){
			$gamedata['timer'] = $rmcdtime;
		}
		if($hp > 0 && $coldtimeon && $showcoldtimer && $rmcdtime){
			$x1=$rmcdtime/1000; $x1=(int)$x1; $y1=($rmcdtime%1000)/100; $y1=(int)$y1;
			$log .= "行动冷却时间：<span id=\"timer\" class=\"yellow\">{$x1}.{$y1}</span>秒<br>";
		}
	}
	
	function prepare_initial_response_content()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess();
		
		eval(import_module('sys','logger','player','cooldown'));
		if($hp > 0 && $coldtimeon && $showcoldtimer && $rmcdtime)
		{
			$x1=$rmcdtime/1000; $x1=(int)$x1; $y1=($rmcdtime%1000)/100; $y1=(int)$y1;
			$log .= "行动冷却时间：<span id=\"timer\" class=\"yellow\">{$x1}.{$y1}</span>秒<script type=\"text/javascript\">demiSecTimerStarter($rmcdtime);</script><br>";
		}
	}
	
}

?>
