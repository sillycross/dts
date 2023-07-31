<?php

namespace skill247
{
	$skill247_cd = 60;
	
	function init() 
	{
		define('MOD_SKILL247_INFO','card;club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[247] = '挖坑';
	}
	
	function acquire247(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill247'));
		\skillbase\skill_setvalue(247,'lastuse',-3000,$pa);
	}
	
	function lost247(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked247(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill247_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(247, $pa) || !check_unlocked247($pa)) return 0;
		eval(import_module('sys','player','skill247'));
		$l=\skillbase\skill_getvalue(247,'lastuse',$pa);
		if (($now-$l)<=$skill247_cd) return 2;
		return 3;
	}
	
	function get_skill247_trap_eff()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$skill247_lst = (int)\skillbase\skill_getvalue(247,'lastuse'); 
		$skill247_time = $now-$skill247_lst; 
		return round(min($skill247_time/60,3)*$att);
	}
	
	function activate247()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill247','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(247) || !check_unlocked247($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill247_state($sdata);
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
		$itm_name_arr = Array('毒性【ACFUN大逃杀3.0】', '毒性【ACFUN大逃杀革新企划书】', '毒性【大逃杀新界面】', '毒性【新电波大逃杀剧情模式】','有毒的腿？');
		$itme = get_skill247_trap_eff();
		for($i=0;$i<2;$i++){
			shuffle($itm_name_arr);
			$trapitem=Array(
				'itm' => $itm_name_arr[0],
				'itmk' => 'TO',
				'itme' => $itme,
				'itms' => 1,
				'itmsk' => ''
			);
			\trap\trap_use($trapitem); 
		}
		addnews ( 0, 'bskill247', $name );
		$log.='你满意地看着你刚挖的大坑，它们一定能给玩家们带来笑容。<br><span class="lime b">技能「挖坑」发动成功。</span><br>';
		\skillbase\skill_setvalue(247,'lastuse',$now);
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(247,$sdata))&&check_unlocked247($sdata))
		{
			eval(import_module('skill247'));
			$skill247_lst = (int)\skillbase\skill_getvalue(247,'lastuse'); 
			$skill247_time = $now-$skill247_lst; 
			$sk247_eff = get_skill247_trap_eff();
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「挖坑」',
				'activate_hint' => '点击发动技能「挖坑」<br>在当前地图布设2个'.$sk247_eff.'效果的毒性陷阱',
				'onclick' => "$('mode').value='special';$('command').value='skill247_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill247_time<$skill247_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill247_cd;
				$z['nowsec']=$skill247_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill247.gif',$z);
		}
		$chprocess();
	}
	
	function get_traplist() 	//不会踩自己的陷阱
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(247)) return $chprocess();
		eval(import_module('sys','player'));
		return $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls' AND itmsk <> '$pid' ORDER BY itmk DESC");
	}
	
	//不能获得肌肉兄贵称号
	function club_choice_probability_process($clublist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(247)) return $chprocess($clublist);
		if(isset($clublist[14])) $clublist[14]['probability'] = 0;
		return $clublist;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill247') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「挖坑」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
