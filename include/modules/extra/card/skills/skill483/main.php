<?php

namespace skill483
{
	$skill483_cd = 0;
	
	function init() 
	{
		define('MOD_SKILL483_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[483] = '氪金';
	}
	
	function acquire483(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill483'));
		\skillbase\skill_setvalue(483,'lastuse',-3000,$pa);
		\skillbase\skill_setvalue(483,'dur',0,$pa);
		\skillbase\skill_setvalue(483,'cost',500,$pa);
	}
	
	function lost483(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked483(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate483()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill483','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(483) || !check_unlocked483($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill483_state($sdata);
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
		$c=(int)\skillbase\skill_getvalue(483,'cost',$pa);
		if ($money<$c)
		{
			$log.='<span class="yellow">金钱不足！用你的脑子想一想，不充钱你会变得更强吗？</span><br>';
			return;
		}
		$money-=$c;
		\skillbase\skill_setvalue(483,'dur',120);
		\skillbase\skill_setvalue(483,'lastuse',$now);
		\skillbase\skill_setvalue(483,'cost',$c*2);
		addnews ( 0, 'bskill483', $name );
		$log.='<span class="lime">技能「氪金」发动成功。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill483_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(483, $pa) || !check_unlocked483($pa)) return 0;
		eval(import_module('sys','player','skill483'));
		$l=\skillbase\skill_getvalue(483,'lastuse',$pa);
		$d=\skillbase\skill_getvalue(483,'dur',$pa);
		if (($now-$l)<=$d) return 1;
		if (($now-$l)<=$d+$skill483_cd) return 2;
		return 3;
	}
	
	
	//复活判定注册
	function set_revive_sequence(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd);
		if(check_skill483_state($pd)==1){
			$pd['revive_sequence'][100] = 'skill483';
		}
		return;
	}	
	
	//复活判定
	function revive_check(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $rkey);
		if('skill483' == $rkey && in_array($pd['state'],Array(20,21,22,23,24,25,29)) && $pa['type']==0){
			$ret = true;
		}
		return $ret;
	}
	
	//回满血，发复活状况
	function post_revive_events(&$pa, &$pd, $rkey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $rkey);
		if('skill483' == $rkey){
			$pd['hp']=$pd['mhp'];
			$pd['skill483_flag']=1;
			addnews ( 0, 'revival483', $pa['name'], $pd['name'] );
		}
		return;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($pa,$pd,$active);
		
		eval(import_module('sys','logger'));
		if (isset($pd['skill483_flag']) && $pd['skill483_flag'])
		{
			if ($active)
			{
				$log.='<span class="lime">但是氪金战士不可能死！敌人又站了起来！</span><br>';
				$pd['battlelog'].='<span class="lime">但是氪金战士不可能死！</span>你又站了起来，';
			}
			else
			{
				$log.='<span class="lime">但是氪金战士不可能死！你又站了起来！</span><br>';
				$pa['battlelog'].='<span class="lime">但是氪金战士不可能死！</span>敌人又站了起来，';
			}
		}
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(483,$sdata))&&check_unlocked483($sdata))
		{
			eval(import_module('skill483'));
			$skill483_lst = (int)\skillbase\skill_getvalue(483,'lastuse'); 
			$skill483_dur = (int)\skillbase\skill_getvalue(483,'dur'); 
			$skill483_cost = \skillbase\skill_getvalue(483,'cost'); 
			$skill483_time = $now-$skill483_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「氪金」',
				'activate_hint' => '点击发动技能「氪金」，消耗'.$skill483_cost.'元',
				'onclick' => "$('mode').value='special';$('command').value='skill483_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill483_time<$skill483_dur)
			{
				$z['style']=1;
				$z['totsec']=$skill483_dur;
				$z['nowsec']=$skill483_time;
			}
			else  if ($skill483_time<$skill483_dur+$skill483_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill483_cd;
				$z['nowsec']=$skill483_time-$skill483_dur;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill483.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill483') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}发动了技能<span class=\"yellow\">「氪金」</span></span></li>";
		
		if($news == 'revival483') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}击杀了{$b}，却不料{$b}是传说中的氪金战士！{$b}又站了起来！</span></li>";
		
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
