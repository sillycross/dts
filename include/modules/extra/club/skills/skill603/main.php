<?php

namespace skill603
{

	function init() 
	{
		define('MOD_SKILL603_INFO','hidden;debuff;');
		eval(import_module('clubbase'));
		$clubskillname[603] = '静止';
	}
	
	function acquire603(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost603(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_skill603_state(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(603,$pa)) return 0;
		$e=\skillbase\skill_getvalue(603,'end',$pa);
		$ct = floor(getmicrotime()*1000);
		if ($ct<$e) return 1;
		return 0;
	}
	
	function set_stun_period603($t, &$pa)	//单位毫秒
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//if ($gamestate>=50) $t=round($t/3);	//死斗眩晕时间变为1/3
		$flag=1;
		$ct = floor(getmicrotime()*1000);
		$e = 0;
		if (\skillbase\skill_query(603,$pa))
		{
			$e = floor(\skillbase\skill_getvalue(603,'end',$pa)); 
			if ($ct>=$e) $flag=0;
		}
		else  $flag=0;
		if ($flag==0)
		{
			\skillbase\skill_acquire(603,$pa);
			\skillbase\skill_setvalue(603,'start',$ct,$pa);
		}
		if ($ct+$t>$e) $e=$ct+$t;
		\skillbase\skill_setvalue(603,'end',$e,$pa);
		$pa['new_stun_flag']=1;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(603,$sdata))
		{
			eval(import_module('skill603','skillbase'));
			$skill603_start = floor(\skillbase\skill_getvalue(603,'start')); 
			$skill603_end = floor(\skillbase\skill_getvalue(603,'end')); 
			$z=Array(
				'disappear' => 1,
				'clickable' => 0,
				'hint' => '时间被静止了，无法进行任何行动！',
			);
			$ct = floor(getmicrotime()*1000);
			if ($ct<$skill603_end)
			{
				$z['style']=1;
				$z['totsec']=round(($skill603_end-$skill603_start)/1000);
				$z['nowsec']=round(($ct-$skill603_start)/1000);
				\bufficons\bufficon_show('img/skill603.gif',$z);
			}
			else 
			{
				\skillbase\skill_lost(603);
			}
		}
		$chprocess();
	}
	
	function check_cooltime_on()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (check_skill603_state()) return 0;	//不显示冷却时间提示
		return $chprocess();
	}
	
	function calculate_active_obbs_change(&$ldata,&$edata,$active_r)	//不会先手敌人
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (check_skill603_state($ldata)) return 0;
		if (check_skill603_state($edata)) return 100;
		return $chprocess($ldata,$edata,$active_r);
	}
	
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function check_can_counter(&$pa, &$pd, $active)			//不会反击敌人
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//注意判定的是$pa能否反击$pd
		if (check_skill603_state($pa)) return 0; 
		return $chprocess($pa, $pd, $active);
	}
	
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (\skillbase\skill_query(603,$sdata))
		{
			$ct = floor(getmicrotime()*1000);
			$e = floor(\skillbase\skill_getvalue(603,'end')); 
			$rmt = $e - $ct;
			if ($ct<$e)
			{
				$log .= '<span class="yellow">时间被静止了，无法动弹！<br>持续时间还剩<span id="timer">'.floor($rmt/1000).'.'.(floor($rmt/100)%10).'</span>秒</span><br><img style="display:none;" type="hidden" src="img/blank.png" onload="demiSecTimerStarter('.$rmt.');">';
				$mode = 'command'; $command = 'menu';
			}
		}
		$chprocess();
	}
	
}

?>