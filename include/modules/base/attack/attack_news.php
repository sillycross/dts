<?php

namespace attack
{
	
	function post_damage_news(&$pa, &$pd, $active, $dmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
//
//		$d=$dmg; $p1=$pa['name']; $p2=$pd['name'];
//		
//		eval(import_module('sys'));
//		if (($d >= 100) && ($d < 150)) {
//			$words = "{$p1}对{$p2}做出了{$d}点的攻击，一定是有练过。";
//		} elseif (($d >= 150) && ($d < 200)) {
//			$words = "{$p1}拿了什么神兵？{$p2}被打了{$d}滴血。";
//		} elseif (($d >= 200) && ($d < 250)) {
//			$words = "{$p1}简直不是人！{$p2}瞬间被打了{$d}点伤害。";
//		} elseif (($d >= 250) && ($d < 300)) {
//			$words = "{$p1}发出会心一击！{$p2}损失了{$d}点生命！";
//		} elseif (($d >= 300) && ($d < 400)) {
//			$words = "{$p1}使出浑身解数奋力一击！{$d}点伤害！{$p2}还安好吗？";
//		} elseif (($d >= 400) && ($d < 500)) {
//			$words = "{$p1}使出武器中内藏的力量！可怜的{$p2}受到了{$d}点的伤害！";
//		} elseif (($d >= 500) && ($d < 600)) {
//			$words = "{$p1}眼色一变使出绝招！{$p2}招架不住，生命减少{$d}点！";
//		} elseif (($d >= 600) && ($d < 750)) {
//			$words = "{$p1}手中的武器闪耀出七彩光芒！{$p2}招架不住，生命减少{$d}点！";
//		} elseif (($d >= 750) && ($d < 1000)) {
//			$words = "{$p1}受到天神的加护，打出惊天动地的一击——{$p2}被打掉{$d}点生命值！";
//		} elseif (($d >= 1000) && ($d < 5000)) {
//			$words = "{$p1}燃烧自己的生命得到了不可思议的力量！【{$d}】点的伤害值，没天理啊……{$p2}的HP足够么？";
//		} elseif (($d >= 5000) && ($d < 10000)) {
//			$words = "{$p1}超越自己的极限爆发出了震天动地的力量！在【{$d}】点的伤害后，{$p2}化作了一颗流星！";
//		} elseif (($d >= 10000) && ($d < 50000)) {
//			$words = "{$p1}运转百万匹周天，吐气扬声，一道霸气的光束过后，在【{$d}】点的伤害下，{$p2}还活着么？";
//		} elseif (($d >= 50000) && ($d < 200000)) {
//			$words = "{$p1}已然和手中的武器成为一体！随着一声令大地崩塌的长啸，{$p2}吃下了【{$d}】点的伤害！";
//		}	elseif (($d >= 200000) && ($d < 500000)) {
//			$words = "天空一道惊雷划过，{$p1}站在战场上，而{$p2}因为受到了【{$d}】点的伤害现在已经不见踪影！";
//		} elseif ( $d >= 500000) {
//			$words = "将{$p2}击飞出【{$d}】的{$p1}业已经天下无敌！";
//		} else {
//			$words = '';
//		}
//		if ($words) {
//			addnews ( 0, 'damage', $words );
//		}
		if($dmg >= 100) addnews ( 0, 'damage_signal', $pa['name'], $pd['name'], '', $dmg);
		return;
	}
	
	//处理damage_news，$a是攻击方，$b是挨打方，$d是伤害，注意$c是空着的
	function parse_damage_news($a, $b, $c, $d, $e, $exarr = array()){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$p1 = $a; $p2 = $b; $d = (int)$d;
		if($d > 500000) $words = "将{$p2}击飞出【{$d}】的{$p1}业已经天下无敌！";
		elseif($d >= 200000) $words = "天空一道惊雷划过，{$p1}站在战场上，而{$p2}因为受到了【{$d}】点的伤害现在已经不见踪影！";
		elseif($d >= 50000) $words = "{$p1}已然和手中的武器成为一体！随着一声令大地崩塌的长啸，{$p2}吃下了【{$d}】点的伤害！";
		elseif($d >= 10000) $words = "{$p1}运转百万匹周天，吐气扬声，一道霸气的光束过后，在【{$d}】点的伤害下，{$p2}还活着么？";
		elseif($d >= 5000) $words = "{$p1}超越自己的极限爆发出了震天动地的力量！在【{$d}】点的伤害后，{$p2}化作了一颗流星！";
		elseif($d >= 1000) $words = "{$p1}燃烧自己的生命得到了不可思议的力量！【{$d}】点的伤害值，没天理啊……{$p2}的HP足够么？";
		elseif($d >= 750) $words = "{$p1}受到天神的加护，打出惊天动地的一击——{$p2}被打掉{$d}点生命值！";
		elseif($d >= 600) $words = "{$p1}手中的武器闪耀出七彩光芒！{$p2}招架不住，生命减少{$d}点！";
		elseif($d >= 500) $words = "{$p1}眼色一变使出绝招！{$p2}招架不住，生命减少{$d}点！";
		elseif($d >= 400) $words = "{$p1}使出武器中内藏的力量！可怜的{$p2}受到了{$d}点的伤害！";
		elseif($d >= 300) $words = "{$p1}使出浑身解数奋力一击！{$d}点伤害！{$p2}还安好吗？";
		elseif($d >= 250) $words = "{$p1}发出会心一击！{$p2}损失了{$d}点生命！";
		elseif($d >= 200) $words = "{$p1}简直不是人！{$p2}瞬间被打了{$d}点伤害。";
		elseif($d >= 150) $words = "{$p1}拿了什么神兵？{$p2}被打了{$d}滴血。";
		elseif($d >= 100) $words = "{$p1}对{$p2}做出了{$d}点的攻击，一定是有练过。";
		else $words = '';
		return $words;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(isset($exarr['dword'])) $e0 = $exarr['dword'];

		if($news == 'death20') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>使用<span class=\"red b\">$nowep</span>击飞$e0</li>";
		} elseif($news == 'death21') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>使用<span class=\"red b\">$d</span>殴打致死$e0</li>";
		} elseif($news == 'death22') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>使用<span class=\"red b\">$d</span>斩杀$e0</li>";
		} elseif($news == 'death23') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>使用<span class=\"red b\">$d</span>射杀$e0</li>";
		} elseif($news == 'death24') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>投掷<span class=\"red b\">$d</span>致死$e0</li>";
		} elseif($news == 'death25') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>埋设<span class=\"red b\">$d</span>伏击炸死$e0</li>";
		} elseif($news == 'death29') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>发动<span class=\"red b\">$d</span>以灵力杀死$e0</li>";
//		} elseif($news == 'death43') {//弓系放到弓的模块里了
//			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">$c</span>使用<span class=\"red b\">$d</span>投射致死$e0</li>";
		} elseif($news == 'damage') {//兼容老代码
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">$a</span></li>";
		} elseif($news == 'damage_signal') {
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">".parse_damage_news($a, $b, $c, $d, $e, $exarr)."</span></li>";
		} else return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>