<?php

namespace skill210
{
	$skill210_cd = 900;
	$skill210_act_time = 120;
	
	function init() 
	{
		define('MOD_SKILL210_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[210] = '歼灭';
	}
	
	function acquire210(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill210'));
		\skillbase\skill_setvalue(210,'lastuse',-3000,$pa);
	}
	
	function lost210(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked210(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=21;
	}
	
	function activate210()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill210','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(210) || !check_unlocked210($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill210_state($sdata);
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
		\skillbase\skill_setvalue(210,'lastuse',$now);
		addnews ( 0, 'bskill210', $name );
		$log.='<span class="lime">技能「歼灭」发动成功。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill210_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(210, $pa) || !check_unlocked210($pa)) return 0;
		eval(import_module('sys','player','skill210'));
		$l=\skillbase\skill_getvalue(210,'lastuse',$pa);
		if (($now-$l)<=$skill210_act_time) return 1;
		if (($now-$l)<=$skill210_act_time+$skill210_cd) return 2;
		return 3;
	}
	
	/*function get_hitrate_multiplier(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(210,$pd) || !(check_skill210_state($pd)==1) || $pd['club']!=2 || $pd['wepk']!='WK') return $chprocess($pa, $pd, $active);
		return $chprocess($pa, $pd, $active)*0.75;
	}*/
	
	function get_basic_ex_dmg(&$pa,&$pd,$active,$key)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(210,$pa) || !(check_skill210_state($pa)==1) || \weapon\get_skillkind($pa,$pd,$active) != 'wk') return $chprocess($pa, $pd, $active,$key);
		eval(import_module('ex_dmg_att'));
		$var_210=$pa['att']/$ex_wep_dmg[$key];
		if ($pa['club']!=2) $var_210=$var_210/2;
		return $var_210+$chprocess($pa,$pd,$active,$key);
	}
	
	function get_physical_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		eval(import_module('logger','skill210'));
		$var_210=20;
		if ($pa['card']==5) $var_210=40;
		if ((\skillbase\skill_query(210,$pa))&&(check_skill210_state($pa)==1)&&(rand(0,99)<$var_210)&&(\weapon\get_skillkind($pa,$pd,$active) == 'wk')&&($pa['club']!=2)) 
		{
			$z=2;
			if ($active)
				$log.='<span class="red">暴击！</span><span class="lime">「歼灭」使你造成了'.$z.'倍物理伤害！</span><br>';
			else  $log.='<span class="red">暴击！</span><span class="lime">「歼灭」使敌人造成了'.$z.'倍物理伤害！</span><br>';
			$r=Array($z);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r=Array();
		eval(import_module('logger','skill210'));
		$var_210=20;
		if ($pa['card']==5) $var_210=40;
		if ((\skillbase\skill_query(210,$pa))&&(check_skill210_state($pa)==1)&&(rand(0,99)<$var_210)&&(\weapon\get_skillkind($pa,$pd,$active) == 'wk')&&($pa['club']==2)) 
		{
			$z=2;
			if ($active)
				$log.='<span class="red">暴击！</span><span class="lime">「歼灭」使你造成了'.$z.'倍最终伤害！</span><br>';
			else  $log.='<span class="red">暴击！</span><span class="lime">「歼灭」使敌人造成了'.$z.'倍最终伤害！</span><br>';
			$r=Array($z);
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(210,$sdata))&&check_unlocked210($sdata))
		{
			eval(import_module('skill210'));
			$skill210_lst = (int)\skillbase\skill_getvalue(210,'lastuse'); 
			$skill210_time = $now-$skill210_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「歼灭」',
				'activate_hint' => '点击发动技能「歼灭」',
				'onclick' => "$('mode').value='special';$('command').value='skill210_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill210_time<$skill210_act_time)
			{
				$z['style']=1;
				$z['totsec']=$skill210_act_time;
				$z['nowsec']=$skill210_time;
			}
			else  if ($skill210_time<$skill210_act_time+$skill210_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill210_cd;
				$z['nowsec']=$skill210_time-$skill210_act_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill210.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill210') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}发动了技能<span class=\"red\">「歼灭」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
