<?php

namespace skill5
{
	function init() 
	{
		eval(import_module('wound'));
		//受伤状态简称（用于profile显示）
		$infinfo['p'] = '<span class="purple">毒</span>';
		//受伤状态名称动词
		$infname['p'] = '<span class="purple">中毒</span>';
		//受伤状态对应的特效技能编号
		$infskillinfo['p'] = 5;
	}
	
	function acquire5(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost5(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (strpos($pa['inf'],'p')!==false) \skillbase\skill_acquire(5,$pa);
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		if (\skillbase\skill_query(5,$pa)) \skillbase\skill_lost(5,$pa);
		$chprocess($pa);
	}
	
	function deal_poison_move_damage($damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$hp -= $damage;
		$log .= "<span class=\"purple\">毒发</span>减少了<span class=\"red\">$damage</span>点生命！<br>";
		if($hp <= 0 ){
			$state = 12;
			\player\update_sdata(); $sdata['sourceless'] = 1;
			\player\kill($sdata,$sdata);
			\player\player_save($sdata);
			\player\load_playerdata($sdata);
		}
	}
	
	function search_area()	//毒发探索掉血
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (\skillbase\skill_query(5))
		{
			$damage = round($mhp * 0.03125) + rand(0,10);
			deal_poison_move_damage($damage);
		}
		if ($hp>0) $chprocess();
	}
	
	function move_to_area($moveto)	//毒发移动掉血
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if (\skillbase\skill_query(5))
		{
			$damage = round($mhp * 0.0625) + rand(0,10);
			deal_poison_move_damage($damage);
		}
		if ($hp>0) $chprocess($moveto);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	//毒发死亡新闻
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'death12') 
		{
			$dname = $typeinfo[$b].' '.$a;
			if(!$e)
				$e0="<span class=\"yellow\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else  $e0="<span class=\"yellow\">【{$dname}：“{$e}”】</span><br>\n";
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">毒发</span>死亡{$e0}</li>";
		} 
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
