<?php

namespace item_uv
{
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['V'] = '技能书籍';
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','weapon','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'V' ) === 0) 
		{
			$log .= "你阅读了<span class=\"red\">$itm</span>。<br>";
			//特殊的技能书类型VS，效果是获得技能编号为itmsk的技能
			if (strpos ( substr($itmk,1), 'S' ) !== false)	//技能书
			{
				eval(import_module('clubbase'));
				$useflag = 0;
				$sk_kind = (int)$itmsk;
				if ($sk_kind<1) $sk_kind = 1;
				if (defined('MOD_SKILL'.$sk_kind) && $clubskillname[$sk_kind]!='')
				{
					if (\skillbase\skill_query($sk_kind))
					{
						$log.="你发现这本书就是昨天刚刚看过的那本，不打算继续看下去了。<br>";
					}
					else
					{
						$log.="你感觉受益匪浅。你获得了技能「<span class=\"yellow\">".$clubskillname[$sk_kind]."</span>」，请前往技能界面查看。<br>";
						\skillbase\skill_acquire($sk_kind);
						$useflag = 1;
						//\itemmain\itms_reduce($theitem);
					}
				}
				else
				{
					$log.="技能书参数错误，这应该是一个BUG，请联系管理员。<br>";
					return;
				}
			}
			
			//特殊的技能书类型VO，效果是获得编号为itmsk的卡片
			//可以有特判
			if (strpos ( substr($itmk,1), 'O' ) !== false)	//技能书
			{
				if ($itm == '博丽神社的参拜券')
				{
					eval(import_module('sys'));
					if ($now - $starttime >= 1200)
					{
						$log.='<span class="yellow">博丽神社今天已经关门啦，下次请早点来吧。（这个道具必须在开局20分钟内使用）<br></span>';
						return;
					}
				}
				eval(import_module('cardbase'));
				$sk_kind = (int)$itmsk;
				\cardbase\get_card($sk_kind);
				$log.='<span class="yellow">你获得了卡片「'.$cards[$sk_kind]['name'].'」！请前往“帐号资料”→“查看我的卡册”查看。</span><br>';
				$useflag = 1;
			}
			
			//下面是普通的技能书处理（效果是加某个系的熟练）
			$skill_minimum = 100;
			$skill_limit = 300;
			
			$dice = rand ( - 5, 5 );
			$vefct = NULL;
			if (strpos ( substr($itmk,1), 'V' ) !== false) {//全系技能书
				$skcnt = 0; $ws_sum = 0;
				foreach (array_unique(array_values($skillinfo)) as $key)
				{
					$skcnt++;
					$ws_sum += $$key;
				}
				if ($ws_sum < $skill_minimum * $skcnt) {
					$vefct = $itme;
				} elseif ($ws_sum < $skill_limit * $skcnt) {
					$vefct = round ( $itme * (1 - ($ws_sum - $skill_minimum * $skcnt) / ($skill_limit * $skcnt - $skill_minimum * $skcnt)) );
				} else {
					$vefct = 0;
				}
				if ($vefct < 5) {
					if ($vefct < $dice) {
						$vefct = - $dice;
					}
				}
				foreach (array_unique(array_values($skillinfo)) as $key)
				{
					$$key+=$vefct;
				}
				$wsname = "全系熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'P' ) !== false) {
				if ($wp < $skill_minimum) {
					$vefct = $itme;
				} elseif ($wp < $skill_limit) {
					$vefct = round ( $itme * (1 - ($wp - $skill_minimum) / ($skill_limit - $skill_minimum)) );
				} else {
					$vefct = 0;
				}
				if ($vefct < 5) {
					if ($vefct < $dice) {
						$vefct = - $dice;
					}
				}
				$wp += $vefct; //$itme;
				$wsname = "斗殴熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'K' ) !== false) {
				if ($wk < $skill_minimum) {
					$vefct = $itme;
				} elseif ($wk < $skill_limit) {
					$vefct = round ( $itme * (1 - ($wk - $skill_minimum) / ($skill_limit - $skill_minimum)) );
				} else {
					$vefct = 0;
				}
				if ($vefct < 5) {
					if ($vefct < $dice) {
						$vefct = - $dice;
					}
				}
				$wk += $vefct;
				$wsname = "斩刺熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'G' ) !== false) {
				if ($wg < $skill_minimum) {
					$vefct = $itme;
				} elseif ($wg < $skill_limit) {
					$vefct = round ( $itme * (1 - ($wg - $skill_minimum) / ($skill_limit - $skill_minimum)) );
				} else {
					$vefct = 0;
				}
				if ($vefct < 5) {
					if ($vefct < $dice) {
						$vefct = - $dice;
					}
				}
				$wg += $vefct;
				$wsname = "射击熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'C' ) !== false) {
				if ($wc < $skill_minimum) {
					$vefct = $itme;
				} elseif ($wc < $skill_limit) {
					$vefct = round ( $itme * (1 - ($wc - $skill_minimum) / ($skill_limit - $skill_minimum)) );
				} else {
					$vefct = 0;
				}
				if ($vefct < 5) {
					if ($vefct < $dice) {
						$vefct = - $dice;
					}
				}
				$wc += $vefct;
				$wsname = "投掷熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'D' ) !== false) {
				if ($wd < $skill_minimum) {
					$vefct = $itme;
				} elseif ($wd < $skill_limit) {
					$vefct = round ( $itme * (1 - ($wd - $skill_minimum) / ($skill_limit - $skill_minimum)) );
				} else {
					$vefct = 0;
				}
				if ($vefct < 5) {
					if ($vefct < $dice) {
						$vefct = - $dice;
					}
				}
				$wd += $vefct;
				$wsname = "引爆熟练度";
				$useflag = 1;
			} elseif (strpos ( substr($itmk,1), 'F' ) !== false) {
				if ($wf < $skill_minimum) {
					$vefct = $itme;
				} elseif ($wf < $skill_limit) {
					$vefct = round ( $itme * (1 - ($wf - $skill_minimum) / ($skill_limit - $skill_minimum)) );
				} else {
					$vefct = 0;
				}
				if ($vefct < 5) {
					if ($vefct < $dice) {
						$vefct = - $dice;
					}
				}
				$wf += $vefct;
				$wsname = "灵击熟练度";
				$useflag = 1;
			}
			if(NULL!==$vefct) {
				if ($vefct > 0) {
					$log .= "嗯，有所收获。<br>你的{$wsname}提高了<span class=\"yellow\">$vefct</span>点！<br>";
				} elseif ($vefct == 0) {
					$log .= "对你来说书里的内容过于简单了。<br>你的熟练度没有任何提升。<br>";
				} else {
					$vefct = - $vefct;
					$log .= "对你来说书里的内容过于简单了。<br>而且由于盲目相信书上的知识，你反而被编写者的纰漏所误导了！<br>你的{$wsname}下降了<span class=\"red\">$vefct</span>点！<br>";
				}
			}
			if($useflag) \itemmain\itms_reduce($theitem);
			return;
		}
		$chprocess($theitem);
	}
}

?>
