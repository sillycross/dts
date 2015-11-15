<?php

namespace skill232
{
	$shieldgain = Array(110,140,170,200,230,260);
	$upgradecost = Array(4,4,5,5,5,-1);
	$skill232_cd = 300;
	
	function init() 
	{
		define('MOD_SKILL232_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[232] = '力场';
	}
	
	function acquire232(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(232,'lvl','0',$pa);
		\skillbase\skill_setvalue(232,'lastuse',-3000,$pa);
	}
	
	function lost232(&$pa)
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
	
	function check_unlocked232(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade232()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill232','player','logger'));
		if (!\skillbase\skill_query(232))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$clv = \skillbase\skill_getvalue(232,'lvl');
		$ucost = $upgradecost[$clv];
		if ($clv == -1)
		{
			$log.='你已经升级完成了，不能继续升级！<br>';
			return;
		}
		if ($skillpoint<$ucost) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		$skillpoint-=$ucost; \skillbase\skill_setvalue(232,'lvl',$clv+1);
		$log.='升级成功。<br>';
	}
	
	function activate232()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill232','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(232) || !check_unlocked232($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill232_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==2){
			$log.='技能冷却中！<br>';
			return;
		}
		\skillbase\skill_setvalue(232,'lastuse',$now);
		$clv=\skillbase\skill_getvalue(232,'lvl');
		$sc = $shieldgain[$clv];
		if ($hp<($mhp+$sc)) $hp=$mhp+$sc;
		addnews ( 0, 'bskill232', $name );
		$log.='<span class="lime">技能「力场」发动成功。</span><br>';
	}
	
	function check_skill232_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(232, $pa) || !check_unlocked232($pa)) return 0;
		eval(import_module('sys','player','skill232'));
		$l=\skillbase\skill_getvalue(232,'lastuse',$pa);
		if (($now-$l)<=$skill232_cd) return 2;
		return 3;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(232,$sdata))&&check_unlocked232($sdata))
		{
			eval(import_module('skill232'));
			$skill232_lst = (int)\skillbase\skill_getvalue(232,'lastuse'); 
			$skill232_time = $now-$skill232_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「力场」',
				'activate_hint' => '点击发动技能「力场」',
				'onclick' => "$('mode').value='special';$('command').value='skill232_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill232_time<$skill232_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill232_cd;
				$z['nowsec']=$skill232_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill232.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill232') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"clan\">{$a}发动了技能<span class=\"yellow\">「力场」</span></span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
	
}

?>
