<?php

namespace skill94
{
	$skill94_song_words = array(
		1 => array(1=>'消耗100歌魂', 2=>'消耗一半歌魂', 3=>'消耗全部歌魂'),
		2 => array(1=>'造成伤害增加', 2=>'先制率增加', 3=>'获得升血属性'),
		3 => array(1=>'获得激奏3属性', 2=>'获得音爆属性', 3=>'受到伤害降低')
	);
	
	function init()
	{
		define('MOD_SKILL94_INFO','club;active;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[94] = '天籁';
		if (defined('MOD_NOISE'))
		{
			eval(import_module('noise'));
			$noiseinfo['skill94']='奇妙的歌声';
		}
	}
	
	function acquire94(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(94,'schoice','111',$pa);
	}
	
	function lost94(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(94,'schoice',$pa);
	}
	
	function check_unlocked94(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=17;
	}
	
	function upgrade94()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(94, $sdata) || !check_unlocked94($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val1 = (int)get_var_input('skillpara1');
		$val2 = (int)get_var_input('skillpara2');
		$val3 = (int)get_var_input('skillpara3');
		if (!in_array($val1, array(1,2,3)) || !in_array($val2, array(1,2,3)) || !in_array($val3, array(1,2,3)))
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(94,'schoice',$val1.$val2.$val3,$sdata);
		$log .= '切换成功。<br>';
	}
	
	function cast_skill94()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(94, $sdata) || !check_unlocked94($sdata)) 
		{
			$log .= '你没有这个技能。';
			return;
		}
		$skill94_lyric = get_var_input('skill94_lyric');
		if (!empty($skill94_lyric))
		{
			skill94_sing($skill94_lyric);
			$mode = 'command';
			return;
		}
		include template(MOD_SKILL94_CASTSK94);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($mode == 'special' && $command == 'skill94_special' && get_var_input('subcmd')=='castsk94') 
		{
			cast_skill94();
			return;
		}
		$chprocess();
	}
	
	function skill94_sing($lyric)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','map'));
		//为夺目技能记录歌魂消耗
		$ss_temp = $ss;
		//唱歌参数
		$schoice = \skillbase\skill_getvalue(94,'schoice',$sdata);
		$schoice1 = (int)$schoice[0];
		$schoice2 = (int)$schoice[1];
		$schoice3 = (int)$schoice[2];
		if (!in_array($schoice1, array(1,2,3)) || !in_array($schoice2, array(1,2,3)) || !in_array($schoice3, array(1,2,3)))
		{
			$log .= '参数不合法。<br>';
			return;
		}
		//消耗歌魂
		if ($schoice1 == 1) $r = 100;
		elseif ($schoice1 == 2) $r = floor($ss/2);
		else $r = $ss;
		if ($ss >= $r){
			$ss -= $r;
			$log .= "消耗<span class=\"yellow b\">{$r}</span>点歌魂，歌唱了<span class=\"yellow b\">「天籁」</span>。<br><br>";
		}else{
			$log .= "需要至少<span class=\"yellow b\">{$r}</span>点歌魂才能唱这首歌！<br>";
			return;
		}
		addnews($now,'song',$name,$plsinfo[$pls],'「天籁」');
		
		//显示歌词
		$lyric = '♪ '.$lyric.' ♪';
		$log .= '<span style="font-size:16px;line-height:28px">'.$lyric.'</span><br><br>';
		
		//添加聊天记录
		\sys\addchat(0, $lyric, $name);
		
		//添加声音
		if (defined('MOD_NOISE')) \noise\addnoise($pls,'skill94',$pid);
		
		//核心效果处理
		skill94_sing_process($schoice2, $schoice3, $r, $lyric);
		
		//夺目技能处理
		if (\skillbase\skill_query(91, $sdata) && \skill91\check_unlocked91($sdata) && ($ss < $ss_temp))
		{
			$ssuse = $ss_temp - $ss;
			$skill91_pls = (int)\skillbase\skill_getvalue(91,'pls',$sdata);
			if ($pls == $skill91_pls)
			{
				$skill91_uss = (int)\skillbase\skill_getvalue(91,'uss',$sdata);
				$skill91_uss += $ssuse;
			}
			else
			{
				\skillbase\skill_setvalue(91,'pls',$pls,$sdata);
				$skill91_uss = $ssuse;
			}
			if ($skill91_uss >= 240)
			{
				\skillbase\skill_setvalue(91,'uss','0',$sdata);
				\skill91\skill91_process();
			}
			else
			{
				\skillbase\skill_setvalue(91,'uss',$skill91_uss,$sdata);
			}
		}
	}
	
	function skill94_sing_process($effect1, $effect2, $r, $lyric)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$buff_e = floor((1+$r/1000)*100);
		$buff_time = ceil($r * $buff_e / 200 * \song\ss_factor($sdata));
		//处理自己
		skill94_data_process_single($sdata, $effect1, $effect2, $buff_time, $buff_e);
		//获取所有影响到的玩家
		$pdlist = \song\ss_get_affected_players($pls);
		if(empty($pdlist)) return;
		//依次处理玩家
		foreach($pdlist as $pdata)
		{
			$skill94_log = skill94_data_process_single($pdata, $effect1, $effect2, $buff_time, $buff_e);
			\logger\logsave($pdata['pid'], $now, $skill94_log ,'o');
			\player\player_save($pdata);
		}
	}
	
	function skill94_data_process_single(&$pa, $effect1, $effect2, $buff_time, $buff_e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		\skillbase\skill_acquire(96, $pdata);
		\skillbase\skill_setvalue(96, 'bufftime', $buff_time, $pdata);
		\skillbase\skill_setvalue(96, 'expire', $now + $buff_time, $pdata);
		\skillbase\skill_setvalue(96, 'type', $effect1.$effect2, $pdata);
		\skillbase\skill_setvalue(96, 'effect', $buff_e, $pdata);
		$skill94_log = "获得了状态<span class=\"cyan b\">「魂音」</span>，持续时间<span class=\"yellow b\">$buff_time</span>秒！<br>";
		return $skill94_log;
	}
	
}

?>