<?php

namespace skill500
{
	$skill500_cd = 30;
	$skill500_act_time = 3;
	
	$skill500_rage = 30;
	
	function init() 
	{
		define('MOD_SKILL500_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[500] = '时停';
	}
	
	function acquire500(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(500,'lastuse',0,$pa);
		save_gameinfo();
	}
	
	function lost500(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked500(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate500()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill500','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(500) || !check_unlocked500($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		elseif($rage < $skill500_rage){
			$log.='怒气不足，需要<span class="yellow b">'.$skill500_rage.'点怒气</span>！<br>';
			return;
		}
		$st = check_skill500_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		elseif ($st==1){
			$log.='这一技能正在发动中！<br>';
			return;
		}
		elseif ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		\skillbase\skill_setvalue(500,'lastuse',$now);
		addnews ( 0, 'bskill500', $name );
		$gamevars['timestopped'] = 1;//设一个全局变量
		save_gameinfo();
		$rage -= $skill500_rage;
		//所有同地点玩家获得2秒的眩晕
		$pids = array();
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=0 AND pls='$pls' AND pid!='$pid'");
		if($db->num_rows($result)){
			while($r = $db->fetch_array($result)){
				$pids[] = $r['pid'];
			}
		}
		foreach($pids as $piv){
			$edata = \player\fetch_playerdata_by_pid($piv);
			\skill603\set_stun_period603($skill500_act_time*1000,$edata);
			//就不挨个发送提示了
			\skillbase\skill_setvalue(603,'timestop',1,$edata);
			\player\player_save($edata);
		}
		
		$log.='<span class="lime b">技能「时停」发动成功，你让时间暂时停止了！</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill500_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(500, $pa) || !check_unlocked500($pa)) return 0;
		eval(import_module('sys','player','skill500'));
		$l=\skillbase\skill_getvalue(500,'lastuse',$pa);
		if (($now-$l)<=$skill500_act_time) return 1;
		if (($now-$l)<=$skill500_act_time+$skill500_cd) {
			return 2;
		}
		return 3;
	}
	
	//如果是时间停止导致的眩晕，在眩晕结束时自动判定一下是不是需要发送时间停止结束的讯息
	function lost603(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('sys'));
		if(!empty($gamevars['timestopped'])) {
			$gamevars['timestopped'] = 0;
			save_gameinfo();
			addnews ( 0, 'bskill500_end');
		}

	}
	
	//命中率变为原来的150%
	function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($pa, $pd, $active);
		if (1 == check_skill500_state($pa)) $ret *= 1.5;
		return $ret;
	}
	
	//不会被发现
	function check_alive_discover(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill500_state($edata))
			return 0;
		else  return $chprocess($edata);
	}
	
	//不会被先手
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (1 == check_skill500_state($ldata))
			return 1;
		else  return $chprocess($ldata,$edata);
	}
	
	//不会踩到陷阱
	function calculate_real_trap_obbs_change($var)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($var);
		eval(import_module('player'));
		if(1 == check_skill500_state($sdata)) $ret = 0;
		return $ret;
	}
	
	//不会受到敌人反击
	//若要接管此函数，请阅读base\battle\battle.php里的注释，并加以判断
	function check_can_counter(&$pa, &$pd, $active)			
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//注意判定的是$pa能否反击$pd
		if (1 == check_skill500_state($pd)) return 0; 
		return $chprocess($pa, $pd, $active);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ( \skillbase\skill_query(500,$sdata) && check_unlocked500($sdata))
		{
			eval(import_module('skill500'));
			$skill500_lst = (int)\skillbase\skill_getvalue(500,'lastuse'); 
			$skill500_time = $now-$skill500_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「时停」',
				'activate_hint' => '点击发动技能「时停」',
				'onclick' => "$('mode').value='special';$('command').value='skill500_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill500_time < $skill500_act_time)
			{
				$z['style']=1;
				$z['totsec']=$skill500_act_time;
				$z['nowsec']=$skill500_time;
			}
			elseif ($skill500_time < $skill500_act_time+$skill500_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill500_cd;
				$z['nowsec']=$skill500_time-$skill500_act_time;
				//第一次进入CD时侦测一下全局变量
				if(!empty($gamevars['timestopped'])) {
					$gamevars['timestopped'] = 0;
					save_gameinfo();
					addnews ( 0, 'bskill500_end');
				}
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill500.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill500') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能「时停」，让时间暂时停止了流动！</span></li>";
		elseif($news == 'bskill500_end') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">时间重新开始流动了！</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>