<?php

namespace skill412
{
	
	function init() 
	{
		define('MOD_SKILL412_INFO','club;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[412] = '反演';
	}
	
	function acquire412(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost412(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked412(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//住手啊，这根本就不是莫比乌斯！
	//如果伤害里包含平方数，或者伤害本身大于一千亿，返回0
	//否则如果伤害的（1和本身以外的）因数数目为偶数，返回1；如果为奇数则返回-1
	//比如，24=2*2*2*3，包含平方数4，所以返回0
	//再比如，14=2*7，因数个数为偶数，返回1
	//再比如，17是个质数，返回-1；66=2*3*11，因数个数为奇数，返回-1
	function calculate_mobius_function($val)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$val=(int)$val;
		if ($val>1e11) return 0;	//你tm怎么打出那么高伤害的……
		$x=2; $c=0;
		while ($x<$val/$x+2)
		{
			if ($val%$x==0) 
			{
				$c++; $val/=$x;
				if ($val%$x==0) return 0;
			}
			$x++;
		}
		if ($x!=1) $c++;
		if ($c%2==0) return 1; else return -1;
	}
	
	function apply_total_damage_change(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(412,$pd) && check_unlocked412($pd) && $pa['dmg_dealt']>0)
		{
			eval(import_module('logger'));
			$t=calculate_mobius_function($pa['dmg_dealt']);
			if ($t==0)
			{
				//无伤害
				$log.='<span class="lime">只见敌人周围突然出现了奇怪的呈U形的力场，你的伤害似乎被力场完全吸收了。</span><br>';
				$pa['dmg_dealt']=0;
			}
			else  if ($t==1)
			{
				//有效
				$log.='<span class="lime">只见敌人周围突然出现了奇怪的呈U形的力场，但是你的攻击势不可挡地击穿了它。</span><br>';
			}
			else  
			{
				//反弹233				
				$pa['mobiusflag'] = 1;//临时性的标记就不搞skill了				
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	//反演的反弹效果移到伤害效果的发生阶段，这样就在伤害制御之后了
	function apply_damage(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if(isset($pa['mobiusflag']) && $pa['mobiusflag']) {
			unset($pa['mobiusflag']);
			$log.='<span class="lime">只见敌人周围突然出现了奇怪的呈U形的力场，你造成的伤害竟然被反弹了回来！</span><br>';
			$log.="<span class=\"red\">你受到了{$pa['dmg_dealt']}点伤害！</span><br>";
			\attack\post_damage_news($pd, $pa, 1-$active, $pa['dmg_dealt']);
			$pa['hp']-=$pa['dmg_dealt'];
			if ($pa['hp']<0) $pa['hp']=0;
			$pa['dmg_dealt']=0;
			if ($pa['hp']<=0)
			{
				$pa['deathmark']=39;
				//\attack\player_kill_enemy($pd, $pa, 1-$active);
			}
		}
		return $chprocess($pa,$pd,$active);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'death39') {
			$dname = $typeinfo[$b].' '.$a;
			if(!$e)
				$e0="<span class=\"yellow\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else  $e0="<span class=\"yellow\">【{$dname}：“{$e}”】</span><br>\n";
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">$c</span>的反演力场反弹伤害而亡{$e0}";
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
