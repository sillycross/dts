<?php

namespace skill593
{
	$skill593_cd = 60;
	
	function init() 
	{
		define('MOD_SKILL593_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[593] = '秘药';
	}
	
	function acquire593(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill593'));
		\skillbase\skill_setvalue(593,'lastuse',-120,$pa);
	}
	
	function lost593(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked593(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate593()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(593) || !check_unlocked593($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill593_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		$lastuse = \skillbase\skill_getvalue(593,'lastuse');
		\skillbase\skill_setvalue(593,'lastuse',$now);
		get_skill593_item($lastuse);
	}
	
	function get_skill593_item($lastuse)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain'));
		$file = __DIR__.'/config/skill593.config.php';
		$itemlist = openfile($file);
		$in = sizeof($itemlist);
		
		$i = rand(0, $in-1);				
		if (rand(0,1) > 0)
		{
			$sk593_time = min($now - $lastuse, 300);
			$i2 = rand((int)($sk593_time * 0.3), $in-1);
			$i = max($i, $i2);
		}
		
		list($iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
		$itm0=$iname;
		$itmk0=$ikind;
		$itme0=$ieff;
		$itms0=$ista;
		$itmsk0=\attrbase\config_process_encode_comp_itmsk($iskind);
		addnews(0, 'bskill593', $name, $iname);
		\itemmain\itemget();
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill593_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(593, $pa) || !check_unlocked593($pa)) return 0;
		eval(import_module('sys','player','skill593'));
		$l=\skillbase\skill_getvalue(593,'lastuse',$pa);
		if (($now-$l)<=$skill593_cd) return 2;
		return 3;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(593,$sdata))&&check_unlocked593($sdata))
		{
			eval(import_module('skill593'));
			$skill593_lst = (int)\skillbase\skill_getvalue(593,'lastuse'); 
			$skill593_time = $now-$skill593_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「秘药」',
				'activate_hint' => '点击发动技能「秘药」',
				'onclick' => "$('mode').value='special';$('command').value='skill593_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill593_time<$skill593_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill593_cd;
				$z['nowsec']=$skill593_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill593.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill593') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「秘药」</span>，获得了<span class=\"yellow b\">{$b}</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if ($n == '「蝴蝶梦丸」'){
			$ret .= '服用后会精神舒畅';
		}elseif ($n == '「蝴蝶梦丸噩梦」') {
			$ret .= '服用后会噩梦缠身';
		}elseif ($n == '「国士无双之药」') {
			$ret .= '服用后身体会变得强壮，但使用第四次后会发生意外';
		}elseif ($n == '「蓬莱之药」') {
			$ret .= '服用后会受到永生的诅咒';
		}
		return $ret;
	}
	
}

?>
