<?php

namespace skill261
{
	$skill261_cd = 60000;
	
	function init() 
	{
		define('MOD_SKILL261_INFO','club;upgrade;locked;');
		eval(import_module('clubbase'));
		$clubskillname[261] = '决战';
	}
	
	function acquire261(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill261'));
		\skillbase\skill_setvalue(261,'lastuse',-3000,$pa);
	}
	
	function lost261(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$t=\skillbase\skill_getvalue(261,'lastuse',$pa);
		if ($t>0) $pa['wp']=floor($pa['wp']/2);
	}
	
	function check_unlocked261(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=19;
	}
	
	function activate261()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill261','player','logger','sys','itemmain'));
		\player\update_sdata();
		if (!\skillbase\skill_query(261) || !check_unlocked261($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		$st = check_skill261_state($sdata);
		if ($st==0){
			$log.='你不能使用这个技能！<br>';
			return;
		}
		if ($st==2){
			$log.='你已经使用过这个技能了！<br>';
			return;
		}
		\skillbase\skill_setvalue(261,'lastuse',$now);
		$wp=$wp+$wp;
		addnews ( 0, 'bskill261', $name );
		$log.='<span class="red b">技能「决战」发动成功。</span><br>';
	}
	
	//return 1:技能生效中 2:技能冷却中 3:技能冷却完毕 其他:不能使用这个技能
	function check_skill261_state(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(261, $pa) || !check_unlocked261($pa)) return 0;
		eval(import_module('sys','player','skill261'));
		$l=\skillbase\skill_getvalue(261,'lastuse',$pa);
		if (($now-$l)<=$skill261_cd) return 2;
		return 3;
	}
	//效果改到discover()处理后
	function discover($schmode){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($schmode);
		eval(import_module('sys','player','skill261'));
		if (\skillbase\skill_query(261) && check_skill261_state($sdata)==2)
		{
			$wp-=5;
			if ($wp<50) $wp=50;
		}
		return $ret;
	}
	
//	function search_area()	
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','skill261'));
//		if (\skillbase\skill_query(261) && check_skill261_state($sdata)==2)
//		{
//			$wp-=5;
//			if ($wp<50) $wp=50;
//		}
//		$chprocess();
//	}
	
	function move_to_area($moveto)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill261'));
		if (\skillbase\skill_query(261) && check_skill261_state($sdata)==2)
		{
			$wp-=5;
			if ($wp<50) $wp=50;
		}
		return $chprocess($moveto);
	}
	
	function get_skill263_chance(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(261,$pd) && check_unlocked261($pd))
		{
			$t=(int)\skillbase\skill_getvalue(261,'lastuse',$pd);
			if ($t>0) $ret += 15;
		}
		return $ret;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if ((\skillbase\skill_query(261,$sdata))&&check_unlocked261($sdata))
		{
			eval(import_module('skill261'));
			$skill261_lst = (int)\skillbase\skill_getvalue(261,'lastuse'); 
			$skill261_time = $now-$skill261_lst; 
			$z=Array(
				'disappear' => 0,
				'clickable' => 1,
				'hint' => '技能「决战」',
				'activate_hint' => '点击发动技能「决战」',
				'onclick' => "$('mode').value='special';$('command').value='skill261_special';$('subcmd').value='activate';postCmd('gamecmd','command.php');this.disabled=true;",
			);
			if ($skill261_time<$skill261_cd)
			{
				$z['style']=2;
				$z['totsec']=$skill261_cd;
				$z['nowsec']=$skill261_time;
			}
			else 
			{
				$z['style']=3;
			}
			\bufficons\bufficon_show('img/skill261.gif',$z);
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill261') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"red b\">「决战」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
