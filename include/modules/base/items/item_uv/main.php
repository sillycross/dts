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
			$skill_minimum = 100;
			$skill_limit = 300;
			$log .= "你阅读了<span class=\"red\">$itm</span>。<br>";
			$dice = rand ( - 5, 5 );
			if (strpos ( $itmk, 'VV' ) === 0) {
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
			} elseif (strpos ( $itmk, 'VP' ) === 0) {
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
			} elseif (strpos ( $itmk, 'VK' ) === 0) {
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
				$wk += $vefct; //$itme; 
				$wsname = "斩刺熟练度";
			} elseif (strpos ( $itmk, 'VG' ) === 0) {
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
				$wg += $vefct; //$itme; 
				$wsname = "射击熟练度";
			} elseif (strpos ( $itmk, 'VC' ) === 0) {
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
				$wc += $vefct; //$itme; 
				$wsname = "投掷熟练度";
			} elseif (strpos ( $itmk, 'VD' ) === 0) {
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
				$wd += $vefct; //$itme; 
				$wsname = "引爆熟练度";
			} elseif (strpos ( $itmk, 'VF' ) === 0) {
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
				$wf += $vefct; //$itme; 
				$wsname = "灵击熟练度";
			}
			if ($vefct > 0) {
				$log .= "嗯，有所收获。<br>你的{$wsname}提高了<span class=\"yellow\">$vefct</span>点！<br>";
			} elseif ($vefct == 0) {
				$log .= "对你来说书里的内容过于简单了。<br>你的熟练度没有任何提升。<br>";
			} else {
				$vefct = - $vefct;
				$log .= "对你来说书里的内容过于简单了。<br>而且由于盲目相信书上的知识，你反而被编写者的纰漏所误导了！<br>你的{$wsname}下降了<span class=\"red\">$vefct</span>点！<br>";
			}
			\itemmain\itms_reduce($theitem);
			return;
		}
		$chprocess($theitem);
	}
}

?>
