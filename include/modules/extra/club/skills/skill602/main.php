<?php

namespace skill602
{

	function init() 
	{
		define('MOD_SKILL602_INFO','hidden;debuff;');
		eval(import_module('clubbase'));
		$clubskillname[602] = '晕眩';
	}
	
	function acquire602(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost602(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_skill602_state(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(602,$pa)) return 0;
		$e=\skillbase\skill_getvalue(602,'end',$pa);
		$ct = floor(getmicrotime()*1000);
		if ($ct<$e) return 1;
		return 0;
	}
	
	function set_stun_period($t, &$pa)	//单位毫秒
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($gamestate>=50) $t=round($t/3);	//死斗眩晕时间变为1/3
		$flag=1;
		$ct = floor(getmicrotime()*1000);
		$e = 0;
		if (\skillbase\skill_query(602,$pa))
		{
			$e = floor(\skillbase\skill_getvalue(602,'end',$pa)); 
			if ($ct>=$e) $flag=0;
		}
		else  $flag=0;
		if ($flag==0)
		{
			\skillbase\skill_acquire(602,$pa);
			\skillbase\skill_setvalue(602,'start',$ct,$pa);
		}
		if ($ct+$t>$e) $e=$ct+$t;
		\skillbase\skill_setvalue(602,'end',$e,$pa);
		$pa['new_stun_flag']=1;
	}
	
	function assault_finish(&$pa, &$pd, $active)		//发送战斗被动方晕眩提示
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($active)
		{
			if (isset($pd['new_stun_flag']) && $pd['new_stun_flag']==1)
			{
				$pd['battlelog'] .= '敌人的攻击导致你<span class="cyan b">晕眩</span>了！<br>';
				unset($pd['new_stun_flag']);
			}
		}
		else  
		{
			if (isset($pa['new_stun_flag']) && $pa['new_stun_flag']==1)
			{
				$pa['battlelog'] .= '敌人的攻击导致你<span class="cyan b">晕眩</span>了！<br>';
				unset($pa['new_stun_flag']);
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	function send_stun_battle_news($aname, $bname)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		addnews ( 0, 'bstun1', $aname, $bname );
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bstun1') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}的攻击致使{$b}<span class=\"cyan b\">晕眩</span>了</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(602,$sdata))
		{
			eval(import_module('skill602','skillbase'));
			$skill602_start = floor(\skillbase\skill_getvalue(602,'start')); 
			$skill602_end = floor(\skillbase\skill_getvalue(602,'end')); 
			$z=Array(
				'disappear' => 1,
				'clickable' => 0,
				'hint' => '你处于晕眩状态！<br>无法进行任何行动或战斗，受到的伤害增加',
			);
			$ct = floor(getmicrotime()*1000);
			if ($ct<$skill602_end)
			{
				$z['style']=1;
				$z['totsec']=round(($skill602_end-$skill602_start)/1000);
				$z['nowsec']=round(($ct-$skill602_start)/1000);
				\bufficons\bufficon_show('img/skill602.gif',$z);
			}
			else 
			{
				\skillbase\skill_lost(602);
			}
		}
		$chprocess();
	}
	
	function check_cooltime_on()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (check_skill602_state()) return 0;	//不显示冷却时间提示
		return $chprocess();
	}
	
	function calculate_active_obbs_change(&$ldata,&$edata,$active_r)	//不会先手敌人
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (check_skill602_state($ldata)) $change_to = 0;
		if (check_skill602_state($edata)) $change_to = 100;
		if(isset($change_to)){
			$ldata['active_words'] .= '→'.$change_to;
			return $change_to;
		}
		return $chprocess($ldata,$edata,$active_r);
	}
	
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function check_can_counter(&$pa, &$pd, $active)			//不会反击敌人
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//注意判定的是$pa能否反击$pd
		if (check_skill602_state($pa)) return 0; 
		return $chprocess($pa, $pd, $active);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (check_skill602_state($pd)) $pd['stun_flag']=1;
		$chprocess($pa, $pd, $active);
	}
	
	/*function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		if (isset($pd['stun_flag']) && $pd['stun_flag'])	
		{
			eval(import_module('logger'));
			if ($active)
				$log.='<span class="yellow b">敌人处于眩晕状态，受到的伤害增加！</span><br>';
			else  $log.='<span class="yellow b">你处于眩晕状态，受到的伤害增加！</span><br>';
			$r=Array(1.2);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}*/
	
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (\skillbase\skill_query(602,$sdata))
		{
			$ct = floor(getmicrotime()*1000);
			$e = floor(\skillbase\skill_getvalue(602,'end')); 
			$rmt = $e - $ct;
			if ($ct<$e)
			{
				$log .= '<span class="yellow b">你现在处于晕眩状态，什么都做不了！<br>晕眩状态持续时间还剩<span id="timer">'.floor($rmt/1000).'.'.(floor($rmt/100)%10).'</span>秒</span><br><img style="display:none;" type="hidden" src="img/blank.png" onload="demiSecTimerStarter('.$rmt.');">';
				$mode = 'command'; $command = 'menu';
			}
		}
		$chprocess();
	}
	
			
}

?>
