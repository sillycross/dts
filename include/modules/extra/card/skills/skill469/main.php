<?php

namespace skill469
{
	function init() 
	{
		define('MOD_SKILL469_INFO','card;unique;');
		eval(import_module('clubbase'));
		$clubskillname[469] = '自爆';
	}
	
	function acquire469(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost469(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked469(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function apply_damage(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(469,$pd) && ceil($pd['mhp']*0.9)<=$pa['dmg_dealt'] && $pa['dmg_dealt']<$pd['mhp'])
		{
			eval(import_module('logger'));
			//$pa['dmg_dealt']=$pd['mhp'];
			$pd['hp']=0; $pd['deathmark']=40;
			$suicidedmg = round($pd['mhp']*1.1);
			$pa['hp']-=$suicidedmg;
			if ($active)
				$log .= "<span class=\"yellow\">你暴风骤雨般的攻击将对方打得毫无还手之力，<br>
					正当你欺身向前，准备了结对方的性命时，对方忽然振臂高呼：</span><br>
					<span class=\"clan\">“安拉胡阿克巴！”</span><br>
					<span class=\"yellow\">你这才发现对方腰间绑着一排明晃晃的炸药，但他已猛地扑了上来。</span><br>
					猛烈的爆炸对你造成了<span class=\"red\">$suicidedmg</span>点伤害！<br>";
			else	$log .= "<span class=\"yellow\">你被对方暴风骤雨般的攻击打得毫无还手之力。<br>
					对方欺身向前，准备了结你的性命。 你不甘就这么死去，振臂高呼到：</span><br>
					<span class=\"clan\">“安拉胡阿克巴！”</span><br>
					<span class=\"yellow\">对方这才发现你腰间绑着一排明晃晃的炸药。你用尽最后的力气拉响了引线，猛地扑了上去。</span><br>
					猛烈的爆炸对<span class=\"yellow\">".$pa['name']."</span>造成了<span class=\"red\">$suicidedmg</span>点伤害！<br>";
			if ($pa['hp']<0) $pa['hp']=0;
			if ($pa['hp']<=0)
			{
				$pa['deathmark']=41;
				//\attack\player_kill_enemy($pd, $pa, 1-$active);
				
			}
		}
		return $chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'death40') {	
			$dname = $typeinfo[$b].' '.$a;
			if(!$e)
				$e0="<span class=\"yellow\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else  $e0="<span class=\"yellow\">【{$dname}：“{$e}”】</span><br>\n";
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>受到<span class=\"yellow\">$c</span>的袭击，自爆身亡了！{$e0}</li>";
		}
		if($news == 'death41') {
			$dname = $typeinfo[$b].' '.$a;
			if(!$e)
				$e0="<span class=\"yellow\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else  $e0="<span class=\"yellow\">【{$dname}：“{$e}”】</span><br>\n";
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">$c</span>的自爆炸死了！{$e0}</li>";
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
