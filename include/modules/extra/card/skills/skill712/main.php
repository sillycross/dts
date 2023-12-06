<?php

namespace skill712
{
	$skill712_cd = 30;
	
	function init() 
	{
		define('MOD_SKILL712_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[712] = '宝槌';
	}
	
	function acquire712(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(712,'lastuse',0,$pa);
	}
	
	function lost712(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked712(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function activate712()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(712) || !check_unlocked712($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill712_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		$lastuse = \skillbase\skill_getvalue(712,'lastuse');
		\skillbase\skill_setvalue(712,'lastuse',$now);
		skill712_process();
	}
	
	function skill712_process()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE pls = '$pls'");
		$itemnum = $db->num_rows($result);
		if ($itemnum <= 0){
			$log .= '<span class="yellow b">周围没有任何物品，你换了个锤子！</span><br>';
			return;
		}
		$mipool = Array();
		while($r = $db->fetch_array($result)){
			if(\itemmain\discover_item_filter($r))
				$mipool[] = $r;
		}
		shuffle($mipool);
		//将全部视野置入记忆
		\searchmemory\change_memory_unseen('ALL');
		\skill1006\add_beacon_from_itempool($mipool, \searchmemory\calc_memory_slotnum());
		$log .= '<span class="yellow b">你挥动万宝槌，将全部视野刷新了！</span><br>';
	}

	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill712_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(712, $pa) || !check_unlocked712($pa)) return 0;
		eval(import_module('sys','player','skill712'));
		$l=\skillbase\skill_getvalue(712,'lastuse',$pa);
		if (($now-$l)<=$skill712_cd) return 2;
		return 3;
	}

	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(712,$sdata))&&check_unlocked712($sdata))
		{
			eval(import_module('skill712'));
			$skill712_lst = (int)\skillbase\skill_getvalue(712,'lastuse'); 
			$skill712_time = $now-$skill712_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「宝槌」',
				'activate_hint' => '点击发动技能「宝槌」',
				'onclick' => "$('mode').value='special';$('command').value='skill712_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill712_time<$skill712_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill712_cd;
				$z['nowsec']=$skill712_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill712.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill712') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「宝槌」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>