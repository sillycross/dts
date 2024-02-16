<?php

namespace skill246
{
	$skill246_cd = 36000;
	$skill246_act_time = 45;
	
	function init() 
	{
		define('MOD_SKILL246_INFO','club;upgrade;locked;unique;');
		eval(import_module('clubbase','wep_j','dualwep'));
		$clubskillname[246] = '隐身';
		$wj_allowed_bskill[] = 246;
		$dualwep_allowed_bskill[] = 246;
	}
	
	function acquire246(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill246'));
		\skillbase\skill_setvalue(246,'lastuse',0,$pa);
	}
	
	function lost246(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked246(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate246()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill246','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(246) || !check_unlocked246($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill246_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==1){
			$log.='你已经发动过这个技能了！<br>';
			return;
		}
		if ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		\skillbase\skill_setvalue(246,'lastuse',$now);
		$log.='<span class="lime b">技能「隐身」发动成功。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill246_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(246, $pa) || !check_unlocked246($pa)) return 0;
		eval(import_module('sys','player','skill246'));
		$l=\skillbase\skill_getvalue(246,'lastuse',$pa);
		if (($now-$l)<=$skill246_act_time) return 1;
		if (($now-$l)<=$skill246_act_time+$skill246_cd) return 2;
		return 3;
	}
	
	//隐身状态下攻击不会显示战斗技
	function check_battle_skill_available(&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(246,$sdata) && check_skill246_state($sdata)==1) return false;
		else return $chprocess($edata,$skillno);
	}
	
	//隐身状态下攻击不允许使用其他战斗技能，但自动触发破隐效果
	//隐身刚结束那一下无法使用任何技能
	function load_user_battleskill_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(246,$pdata) && check_skill246_state($pdata)==1)
			$pdata['bskill']=246; 
		elseif (\skillbase\skill_query(246,$pdata) && check_skill246_state($pdata)==2)
			$pdata['bskill']=0; 
		else $chprocess($pdata);
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill']!=246) {
			$chprocess($pa, $pd, $active);
			return;
		}
		if (!\skillbase\skill_query(246,$pa) || check_skill246_state($pa)!=1)
		{
			eval(import_module('logger'));
			$log .= '你没有这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			eval(import_module('logger'));
			$log .= '<span class="yellow b">敌人完全没有预料到你的存在，你对着措手不及的敌人发起了致命一击！</span><br>';
			\skillbase\skill_lost(246, $pa);
			addnews ( 0, 'bskill246', $pa['name'], $pd['name'] );
		}
		$chprocess($pa, $pd, $active);
	}	
	
	//命中率增加
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if ($pa['bskill']!=246) return $ret;
		return $ret*1.3;
	}
	
	//不会被发现
	function check_alive_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(246,$edata) && check_skill246_state($edata)==1)
			return 0;
		else  return $chprocess($edata);
	}
	
	//不会被先手
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(246,$ldata) && check_skill246_state($ldata)==1)
			return 1;
		else  return $chprocess($ldata,$edata);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(246,$sdata))&&check_unlocked246($sdata))
		{
			eval(import_module('skill246'));
			$skill246_lst = (int)\skillbase\skill_getvalue(246,'lastuse'); 
			$skill246_time = $now-$skill246_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「隐身」',
				'activate_hint' => '点击发动技能「隐身」',
				'onclick' => "$('mode').value='special';$('command').value='skill246_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill246_time<$skill246_act_time)
			{
				$z['style']=1;
				$z['totsec']=$skill246_act_time;
				$z['nowsec']=$skill246_time;
			}
			elseif ($skill246_time<$skill246_act_time+$skill246_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill246_cd;
				$z['nowsec']=$skill246_time-$skill246_act_time;
				\skillbase\skill_lost(246);	//仅限一次，进入CD即自动失去技能
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill246.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill246') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「破隐一击」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
