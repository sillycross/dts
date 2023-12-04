<?php

namespace skill531
{
	$sk531_log = array
	(
		'剑柄中开始播放录音：<span class="linen b">“看在你没准还是新手的份上，就再给你一些提示吧。这把剑可以与商店中购买的光束刀合成为另一种形态。”</span><br>',
		'剑柄中开始播放录音：<span class="linen b">“接下来寻找一个全息幻象干掉，再拿它的手套和商店里购买的『祝福宝石』一起和这把剑合成吧。”</span><br>',
		'剑柄中开始播放录音：<span class="linen b">“然后是购买『军用火焰放射器』和☆十星认证☆，用来合成它的下一个形态。”</span><br>',
		'剑柄中开始播放录音：<span class="linen b">“它现在足够你用到最后了。当然，你也可以尝试再将它与最终战术『剑海』合成。之后祝你好运。”</span><br>',
	);
	
	$sk531_mixinfo = array
	(
		array('class' => 'card', 'stuff' => array('【红杀铁剑】','光束刀'),'result' => array('【红杀铁剑·流火】','WK',180,100,'uc')),
		array('class' => 'card', 'stuff' => array('【红杀铁剑·流火】','全息幻象的虚拟手套','『祝福宝石』'),'result' => array('【红杀铁剑·奔焰】','WK',440,160,'ufc')),
		array('class' => 'card', 'stuff' => array('【红杀铁剑·奔焰】','『军用火焰放射器』','☆十星认证☆'),'result' => array('【红杀铁剑·龙炎】','WKG',1200,'∞','ufdyc')),
		array('class' => 'card', 'stuff' => array('【红杀铁剑·龙炎】','最终战术『剑海』'),'result' => array('【红杀铁剑·焚烬】','WKG',88888,'∞','rufdycZ')),
	);
	
	$sk531_itm = array('【红杀铁剑】','【红杀铁剑·流火】','【红杀铁剑·奔焰】','【红杀铁剑·龙炎】');
	
	function init()
	{
		define('MOD_SKILL531_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[531] = '癫佬';
	}
	
	function acquire531(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost531(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked531(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//交战双方任一方有本技能则生成特殊的交战台词
	function post_damage_news(&$pa, &$pd, $active, $dmg)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($dmg >= 100 && (\skillbase\skill_query(531, $pa) || \skillbase\skill_query(531, $pd))) 
			addnews ( 0, 'damage_signal_531', $pa['name'], $pd['name'], '', $dmg);
		else $chprocess($pa, $pd, $active, $dmg);
		return;
	}
	
	//处理生成进行状况
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'damage_signal_531') 
		{
			//通过对parse_damage_news传入$exarr参数来提示用另一种描述
			$exarr['flag531'] = 1;
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"linen b\">『".parse_damage_news($a, $b, $c, $d, $e, $exarr)."』</span></li>";
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	//处理damage_news，$a是攻击方，$b是挨打方，$d是伤害，注意$c是空着的
	function parse_damage_news($a, $b, $c, $d, $e, $exarr = array()){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$p1 = $a; $p2 = $b; $d = (int)$d;
		if(!empty($exarr['flag531'])) {
			if($d > 10000000) $words = "{$p1}疯了！他竟把力量强谷至自毁境界——【{$d}】点的力量！拳未发出，力量的反噬已将他的背部整个爆开，但他还是笑着，将这拳以他生命的一切疯狂推动！<br>只要能把这拳向{$p2}轰出，世上便没有任何可以抵挡它的东西————绝对没有呀！！！";
			elseif($d >= 200000) $words = "最终力量、完全速度、完全状态！{$p1}轰发出他一生以来最强的一击！强横无比的最后一击，若以数值计算，就是仅次于自毁境界的【{$d}】点力量！<br>惊天动地，幻境被{$p1}全力疯狂破坏，而{$p2}怎可以抵挡了？！";//
			elseif($d >= 50000) $words = "{$p1}疯狂把对方轰炸，每一击都如反物质爆发般，制造一个核爆的威力！在这【{$d}】点的毁灭力量面前，{$p2}就连一个细胞都不能剩下！";//
			elseif($d >= 10000) $words = "{$p1}一击竟有核爆般威力，【{$d}】点的威力带有天崩地裂般的感觉！没有任何生物可以在这般力量之下生还……就连{$p2}也办不到呀！";//
			elseif($d >= 5000) $words = "{$p1}的精神与生命只集中于如何把对方屠杀！【{$d}】点的伤害贯穿了{$p2}的身躯，连天也将要分裂，连海也似要爆破了！";//
			elseif($d >= 1000) $words = "{$p1}的每一击都用尽生命去推动，用尽生命去——杀！！【{$d}】点的恐怖威力，{$p2}就连骨头和内脏也要粉碎呀！";//
			elseif($d >= 750) $words = "{$p1}竟把自己推谷至{$d}点的境界！被这绝强的力量轰中，{$p2}身上爆裂开来，令他痛苦、发狂！";//
			elseif($d >= 600) $words = "{$p1}有{$d}点的杀招结结实实地轰中{$p2}的身躯！随着身体的破碎，{$p2}惨被轰出云层之外！";//
			elseif($d >= 500) $words = "{$p1}强绝威力爆发，雷响震动！一轮疯狂轰炸，{$d}点伤害令{$p2}毫无还手之力！";//
			elseif($d >= 400) $words = "{$p1}把力量灌注于一击之内！这{$d}点的超级力量将{$p2}压迫、搅碎！";//
			elseif($d >= 300) $words = "{$p1}轰出{$d}点的威力如炮弹般强大，将{$p2}轰至穿墙射飞！";//
			elseif($d >= 250) $words = "{$p1}使出杀招！斗气险些将{$p2}头颅分割！{$p2}的身体现出伤痕，鲜血四溅，生命减少{$d}点！";//
			elseif($d >= 200) $words = "{$p1}的攻击如最强猛的机枪爆射！{$d}点的攻势把{$p2}的退路完全封锁！";//
			elseif($d >= 150) $words = "{$p1}刹那间发挥力量，{$d}点伤害！{$p2}竟被轰至飞天！";//
			elseif($d >= 100) $words = "{$p1}一击杀来！面对这{$d}点的可恶伤害，{$p2}有什么办法吗？";//
			else $words = '';
			return $words;
		}
		return $chprocess($a, $b, $c, $d, $e, $exarr);
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itm = &$theitem['itm'];
		if (\skillbase\skill_query(531) && check_unlocked531())
		{
			eval(import_module('skill531'));
			$i = array_search($itm, $sk531_itm);
			if (false !== $i)
			{
				eval(import_module('logger'));
				$log .= $sk531_log[$i];
			}
		}
		$chprocess($theitem);
	}
	
	//添加额外合成线
	function get_mixinfo()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		if (\skillbase\skill_query(531) && check_unlocked531())
		{
			eval(import_module('skill531'));
			$ret = array_merge($ret, $sk531_mixinfo);
		}
		return $ret;
	}
	
}

?>