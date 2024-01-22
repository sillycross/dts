<?php

namespace skill725
{
	$skill725stateinfo = array(1 => '关闭', 2 => '开启');
	
	function init()
	{
		define('MOD_SKILL725_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[725] = '噬魂';
	}

	function acquire725(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(725, 'choice', 2, $pa);
	}

	function lost725(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(725, 'choice', $pa);
	}

	function check_unlocked725(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade725()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(725,$sdata) || !check_unlocked725($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val < 1 || $val > 2)
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(725,'choice',$val);
		if(1 == $val) $log .= '现在销毁尸体时不会提取灵魂碎片。<br>';
		else $log .= '现在销毁尸体时会提取灵魂碎片。<br>';
	}
	
	function getcorpse_action(&$edata, $item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(725, $sdata) && ($item == 'destroy') && (2 == \skillbase\skill_getvalue(725,'choice',$sdata)) && \corpse\check_can_destroy($edata))
		{
			eval(import_module('logger'));
			$itmk0 = 'HB';
			$itme0 = min(ceil(0.1*$edata['mhp'])+100, 10000);
			$itms0 = ceil($edata['msp']/16);
			$itmsk0 = 'z';
			if ($itme0 < 200) $itm0 = '灰暗的灵魂碎片';
			elseif ($itme0 < 600) $itm0 = '透明的灵魂碎片';
			elseif ($itme0 < 1500) $itm0 = '明亮的灵魂碎片';
			else $itm0 = '闪耀的灵魂碎片';
			$log .= "你从<span class=\"yellow b\">{$edata['name']}</span>的尸体提取出了<span class=\"yellow b\">{$itm0}</span>。<br>";
		}
		$chprocess($edata, $item);
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if ($itm == '打火机' && \skillbase\skill_query(725))
		{
			eval(import_module('player','logger'));
			if($itme <= 0) {
				$log .= "<span class=\"yellow b\">$itm</span>没油了。<br>";
				return;
			}
			$log .= "你使用了<span class=\"yellow b\">$itm</span>。<br>";
			$flag = 0;
			for($i=1;$i<=6;$i++)
			{
				if (${'itms'.$i} && (strpos(${'itm'.$i},'灵魂碎片')!==false) && (strpos(${'itm'.$i},'加工过的')===false))
				{
					${'itme'.$i} += ceil(rand(50,150) * ${'itme'.$i} / 100);
					if (${'itms'.$i} != '∞') ${'itms'.$i} += ceil(rand(50,150) * ${'itms'.$i} / 100);
					$log .= "<span class=\"yellow b\">${'itm'.$i}</span>变成了";
					${'itm'.$i} = '加工过的'.${'itm'.$i};
					$log .= "<span class=\"yellow b\">${'itm'.$i}</span>！<br>";
					$flag = 1;
					break;
				}
			}
			if ($flag)
			{
				$itme -= 1;
				if ($itme == 0)
				{
					if ($itms > 1)
					{
						$log .= "打火机没油了，你换了一个新的打火机。<br>";
						$itme = 1;
						$itms -= 1;
					}
					else $log .= "打火机没油了。<br>";
				}
			}
			else $log .= "包裹里没有要加工的灵魂碎片。<br>";
			return;
		}
		$chprocess($theitem);
	}
	
	function get_edible_hpup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(725) && (strpos($theitem['itm'],'灵魂碎片')!==false)) return round($chprocess($theitem)*0.3);
		return $chprocess($theitem);
	}
	
	function get_edible_spup(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(725) && (strpos($theitem['itm'],'灵魂碎片')!==false)) return round($chprocess($theitem)*0.3);
		return $chprocess($theitem);
	}

}

?>
