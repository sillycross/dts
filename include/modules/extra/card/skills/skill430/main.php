<?php

namespace skill430
{
	$skill430_cd = 60;
	
	function init() 
	{
		define('MOD_SKILL430_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[430] = '搬运';
	}
	
	function acquire430(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill430'));
		\skillbase\skill_setvalue(430,'lastuse',-3000,$pa);
	}
	
	function lost430(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked430(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function activate430()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill430','player','logger','sys','itemmain'));
		\player\update_sdata();
		if (!\skillbase\skill_query(430) || !check_unlocked430($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill430_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		\skillbase\skill_setvalue(430,'lastuse',$now);
		
		$file=GAME_ROOT."./include/modules/base/itemmain/config/mapitem.config.php";//真是丑陋！
		$itemlist = openfile($file);
		$in = sizeof($itemlist);
		do{
			$i=rand(4,$in-1);//妈了个臀
			list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
		} while (!is_numeric($iarea) || \itemmain\check_in_itmsk('x', $iskind));
		$itm0=$iname;$itme0=$ieff;$itms0=$ista;$itmsk0=$iskind;$itmk0=$ikind;
		addnews ( 0, 'bskill430', $name,$iname );
		\itemmain\itemget();
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill430_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(430, $pa) || !check_unlocked430($pa)) return 0;
		eval(import_module('sys','player','skill430'));
		$l=\skillbase\skill_getvalue(430,'lastuse',$pa);
		if (($now-$l)<=$skill430_cd) return 2;
		return 3;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(430,$sdata))&&check_unlocked430($sdata))
		{
			eval(import_module('skill430'));
			$skill430_lst = (int)\skillbase\skill_getvalue(430,'lastuse'); 
			$skill430_time = $now-$skill430_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「搬运」',
				'activate_hint' => '点击发动技能「搬运」',
				'onclick' => "$('mode').value='special';$('command').value='skill430_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill430_time<$skill430_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill430_cd;
				$z['nowsec']=$skill430_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill430.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill430') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「搬运」</span>，获得了<span class=\"yellow b\">{$b}</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
