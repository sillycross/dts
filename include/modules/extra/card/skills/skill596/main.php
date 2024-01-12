<?php

namespace skill596
{
	$skill596_prefix = array('揭秘！','震惊！','爆料！','曝光！','深度解析！','必看！','再不看就迟了！','威胁！','神秘！','恐怖！','惊愕！','疑惑！','禁忌！','逆转！','当心！','复杂！','悲情！','欢乐！','失望！');
	$skill596_suffix = array(
		array('的背后故事！','也在用！','给出权威解答！','揭秘虚拟幻境！','的另一重身份！','打上门来了！','为什么要这么做？','是怎么做到的？','就是她吗？','面前，如何保护自己？','竟参与了这件事！','看了都沉默！','看了都流泪！','不为人知的奇闻轶事'),
		array('竟有如此危害？','内幕曝光令人震惊！','让你的生活更加美好！','令人反思！','你绝对不容错过！','竟然能合成神器！','让你一夜暴富！','正在成为新时尚！','竟让他们大打出手！','大家都在用！','今天半价！','竟成为关键证据！','你还在这样使用吗？','原理受多位贤者热议！','的问题终于彻底暴露！')
	);
	$skill596_itmk = array('WC','WC','ME','MA','X','X','X','X');
	
	function init() 
	{
		define('MOD_SKILL596_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[596] = '念写';
		$itemspkinfo['^eflag'] = '念写手机标记';//不显示
		$itemspkinfo['^wflag'] = '念写武器标记';//不显示
	}
	
	function acquire596(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost596(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked596(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if ($itm == '手机' && \skillbase\skill_query(596))
		{
			eval(import_module('player','logger','skill596'));
			if(false !== \itemmain\check_in_itmsk('^eflag', $itmsk)) {
				$log .= "<span class=\"yellow b\">$itm</span>没电了，没法快乐瞎编了。<br>";
				return;
			}
			$log .= "你打开了<span class=\"yellow b\">$itm</span>，开始搜索素材并编写新闻……<br>";
			if (rand(0,2) < 1)
			{
				if ($itms > 1)
				{
					$log .= "手机没电了，你换了一个新的手机。<br>";
					$itms -= 1;
				}
				else{
					$itmsk = '^eflag596';
					$log .= "手机没电了。<br>";
				}
			}
			$itm0 = generate_sk596_iname();
			if (mb_strlen($itm0, 'utf-8') > 30)
			{
				$log .= "你开始书写标题：<span class=\"yellow b\">{$itm0}……</span><br>尽管标题已经很长了，但你还是意犹未尽，继续添油加醋。<br>正当你写的起劲的时候，突然感到背后传来一阵浓重的杀气。<br><span class=\"red b\">\"总算让我找到了，最近假新闻的源头！\"</span><br>……<br>在一通不由分说的暴打之后，你遍体鳞伤地挣扎着爬了起来。<br><span class=\"red b\">真是大快人心啊！</span><br>";
				$itm0 = '';
				$hp = 1;
				foreach(array('h','b','a','f') as $value)
				{
					$inf = str_replace($value,'',$inf);
				}
				$inf .= 'hbaf';
			}
			else
			{
				$itme0=1; $itms0=1;
				$itmsk0 = '^wflag596';
				$itmk0 = array_randompick($skill596_itmk);
				addnews(0, 'bskill596', $name, $itm0);
				\itemmain\itemget();
			}
			return;
		}
		$chprocess($theitem);
	}
	
	function generate_sk596_iname()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill596'));
		$iname = array_randompick($skill596_prefix);
		$dice = rand(0,2);
		if ($dice == 0)
		{
			$result = $db->query("SELECT name FROM {$tablepre}players WHERE type=0 AND pid!='$pid'");
			$namelist = array();
			if ($db->num_rows($result))
			{
				while($r = $db->fetch_array($result)) $namelist[] = $r['name'];
			}
			eval(import_module('npc'));
			foreach($npcinfo as $key => $npcs)
			{
				if ($key != 90)
				{
					foreach($npcs['sub'] as $npc) $namelist[] = $npc['name'];
				}
			}
			$iname .= array_randompick($namelist).array_randompick($skill596_suffix[0]);
		}
		else
		{
			$iname .= \skill424\wdebug_getreq(array('mapitem','mixitem','syncitem','overlayitem','presentitem','ygoitem'), 0).array_randompick($skill596_suffix[1]);
		}
		return $iname;
	}
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (\attrbase\check_in_itmsk('^wflag', \attrbase\get_ex_attack_array($pa, $pd, $active)))
		{
			eval(import_module('logger'));
			$rageup = rand(30,100);
			$pd['rage'] = min($pd['rage'] + $rageup, \rage\get_max_rage($pd));
			if ($rageup > 75)
			{
				if ($active) $log .= "<span class=\"yellow b\">{$pd['name']}被你捏造的新闻气晕了！</span><br>";
				else $log .= "<span class=\"yellow b\">你被{$pa['name']}捏造的新闻气晕了！</span><br>";
				\skill602\set_stun_period(1000, $pd);
				\skill602\send_stun_battle_news($pa['name'], $pd['name']);
			}
			else
			{
				if ($active) $log .= "<span class=\"red b\">你捏造的新闻让{$pd['name']}怒火中烧！</span><br>";
				else $log .= "<span class=\"red b\">{$pa['name']}捏造的新闻让你怒火中烧！</span><br>";
			}
		}	
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'bskill596')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}发动了技能<span class=\"yellow b\">「念写」</span>，获得了<span class=\"yellow b\">{$b}</span></span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if('^eflag' == $cinfo[0]) return false;
			if('^wflag' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
}

?>
