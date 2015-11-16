<?php

namespace skill414
{
	$skill414_cd = 3600;
	$skill414_act_time = 480;
	
	function init() 
	{
		define('MOD_SKILL414_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[414] = '鹰眼';
	}
	
	function acquire414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill414'));
		\skillbase\skill_setvalue(414,'lastuse',-3000,$pa);
	}
	
	function lost414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function check_unlocked414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate414()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill414','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(414) || !check_unlocked414($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill414_state($sdata);
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
		\skillbase\skill_setvalue(414,'lastuse',$now);
		addnews ( 0, 'bskill414', $name );
		$log.='<span class="lime">技能「鹰眼」发动成功。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill414_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(414, $pa) || !check_unlocked414($pa)) return 0;
		eval(import_module('sys','player','skill414'));
		$l=\skillbase\skill_getvalue(414,'lastuse',$pa);
		if (($now-$l)<=$skill414_act_time) return 1;
		if (($now-$l)<=$skill414_act_time+$skill414_cd) return 2;
		return 3;
	}
	
	function get_hitrate(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(414,$pa) || !(check_skill414_state($pa)==1) || $pa['wep_kind']=='D' || $pa['wepk']=='WJ') return $chprocess($pa, $pd, $active);
		return 10000;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(414,$sdata))&&check_unlocked414($sdata))
		{
			eval(import_module('skill414'));
			$skill414_lst = (int)\skillbase\skill_getvalue(414,'lastuse'); 
			$skill414_time = $now-$skill414_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「鹰眼」',
				'activate_hint' => '点击发动技能「鹰眼」',
				'onclick' => "$('mode').value='special';$('command').value='skill414_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill414_time<$skill414_act_time)
			{
				$z['style']=1;
				$z['totsec']=$skill414_act_time;
				$z['nowsec']=$skill414_time;
			}
			else  if ($skill414_time<$skill414_act_time+$skill414_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill414_cd;
				$z['nowsec']=$skill414_time-$skill414_act_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill414.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill414') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}发动了技能<span class=\"yellow\">「鹰眼」</span></span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
