<?php

namespace skill446
{
	$skill446_cd = 36000;
	$skill446_act_time = 30;
	
	function init() 
	{
		define('MOD_SKILL446_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[446] = '死线';
	}
	
	function acquire446(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill446'));
		\skillbase\skill_setvalue(446,'lastuse',0,$pa);
	}
	
	function lost446(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked446(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate446()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill446','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(446) || !check_unlocked446($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill446_state($sdata);
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
		\skillbase\skill_setvalue(446,'lastuse',$now);
		addnews ( 0, 'bskill446', $name );
		$log.='<span class="lime b">技能「死线」发动成功。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill446_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(446, $pa) || !check_unlocked446($pa)) return 0;
		eval(import_module('sys','player','skill446'));
		$l=\skillbase\skill_getvalue(446,'lastuse',$pa);
		if (($now-$l)<=$skill446_act_time) return 1;
		if (($now-$l)<=$skill446_act_time+$skill446_cd) return 2;
		return 3;
	}
	
	function apply_total_damage_modifier_invincible(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(446,$pd) || !check_unlocked446($pd)) return $chprocess($pa,$pd,$active);
		eval(import_module('logger','skill446'));
		$var_446=check_skill446_state($pd);
		if ($var_446==1){
			$pa['dmg_dealt']=0;
			if ($active) $log .= "<span class=\"yellow b\">敌人的技能「死线」使你的攻击没有造成任何伤害！</span><br>";
			else $log .= "<span class=\"yellow b\">你的技能「死线」使敌人的攻击没有造成任何伤害！</span><br>";
		}
		$chprocess($pa,$pd,$active);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(446,$sdata))&&check_unlocked446($sdata))
		{
			eval(import_module('skill446'));
			$skill446_lst = (int)\skillbase\skill_getvalue(446,'lastuse'); 
			$skill446_time = $now-$skill446_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「死线」',
				'activate_hint' => '点击发动技能「死线」',
				'onclick' => "$('mode').value='special';$('command').value='skill446_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill446_time<$skill446_act_time)
			{
				$z['style']=1;
				$z['totsec']=$skill446_act_time;
				$z['nowsec']=$skill446_time;
			}
			else  if ($skill446_time<$skill446_act_time+$skill446_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill446_cd;
				$z['nowsec']=$skill446_time-$skill446_act_time;
				\skillbase\skill_lost(446);	//仅限一次，进入CD即自动失去技能
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill446.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill446') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「死线」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
