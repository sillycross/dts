<?php

namespace skill76
{
	$skill76_cd = 600;
	
	function init() 
	{
		define('MOD_SKILL76_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[76] = '充能';
	}
	
	function acquire76(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill76'));
		\skillbase\skill_setvalue(76,'lastuse',0,$pa);
		\skillbase\skill_setvalue(76,'rmt',2,$pa);
	}
	
	function lost76(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked76(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=11;
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill76_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(76, $pa) || !check_unlocked76($pa)) return 0;
		eval(import_module('sys','player','skill76'));
		$l=\skillbase\skill_getvalue(76,'lastuse',$pa);
		$r=(int)\skillbase\skill_getvalue(76,'rmt',$pa);
		if ($r==0 && ($now-$l)<=$skill76_cd) return 2;
		return 3;
	}
	
	function activate76()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill76','player','logger','sys'));
		\player\update_sdata();
		if (!\skillbase\skill_query(76) || !check_unlocked76($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill76_state($sdata);
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
		\skillbase\skill_setvalue(76,'lastuse',$now);
		$r=(int)\skillbase\skill_getvalue(76,'rmt',$pa);
		if ($r>0)
		{
			$r--; \skillbase\skill_setvalue(76,'rmt',$r,$pa);
		}
		$rage = \player\get_max_rage();
		addnews ( 0, 'bskill76', $name );
		$log.='<span class="lime b">技能「充能」发动成功。</span><br>';
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(76,$sdata))&&check_unlocked76($sdata))
		{
			eval(import_module('skill76'));
			$skill76_lst = (int)\skillbase\skill_getvalue(76,'lastuse'); 
			$skill76_time = $now-$skill76_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「充能」',
				'activate_hint' => '点击发动技能「充能」',
				'onclick' => "$('mode').value='special';$('command').value='skill76_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			$r=(int)\skillbase\skill_getvalue(76,'rmt',$pa);
			if ($r>0) $z['corner-text'] = $r;
			if ($r==0 && $skill76_time<$skill76_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill76_cd;
				$z['nowsec']=$skill76_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill76.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill76') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「充能」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
