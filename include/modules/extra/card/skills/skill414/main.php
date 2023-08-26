<?php

namespace skill414
{
	$skill414_cd = 36000;
	
	function init() 
	{
		define('MOD_SKILL414_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[414] = '鹰眼';
	}
	
	function acquire414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill414'));
		\skillbase\skill_setvalue(414,'lastuse',-3000,$pa);
		\skillbase\skill_setvalue(414,'dur',0,$pa);
	}
	
	function lost414(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
		\skillbase\skill_setvalue(414,'dur',600+$wc*4);
		\skillbase\skill_setvalue(414,'lastuse',$now);
		addnews ( 0, 'bskill414', $name );
		$log.='<span class="lime b">技能「鹰眼」发动成功。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill414_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(414, $pa) || !check_unlocked414($pa)) return 0;
		eval(import_module('sys','player','skill414'));
		$l=\skillbase\skill_getvalue(414,'lastuse',$pa);
		$d=\skillbase\skill_getvalue(414,'dur',$pa);
		if (($now-$l)<=$d) return 1;
		if (($now-$l)<=$d+$skill414_cd) return 2;
		return 3;
	}
	
	function get_hitrate_change(&$pa,&$pd,$active,$hitrate)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(414,$pa) || !(check_skill414_state($pa)==1) || $pa['wep_kind']=='D' || $pa['wepk']=='WJ') return $chprocess($pa, $pd, $active,$hitrate);
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
			$skill414_dur = (int)\skillbase\skill_getvalue(414,'dur'); 
			$skill414_time = $now-$skill414_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「鹰眼」',
				'activate_hint' => '点击发动技能「鹰眼」',
				'onclick' => "$('mode').value='special';$('command').value='skill414_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill414_time<$skill414_dur)
			{
				$z['style']=1;
				$z['totsec']=$skill414_dur;
				$z['nowsec']=$skill414_time;
			}
			else  if ($skill414_time<$skill414_dur+$skill414_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill414_cd;
				$z['nowsec']=$skill414_time-$skill414_dur;
				\skillbase\skill_lost(414);
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill414.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill414') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「鹰眼」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>