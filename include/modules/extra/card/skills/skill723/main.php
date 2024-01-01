<?php

namespace skill723
{
	function init()
	{
		define('MOD_SKILL723_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[723] = '上膛';
	}
	
	function acquire723(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost723(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked723(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//弹夹数翻倍
	function check_ammukind($cwepk, $cwepsk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($retk, $retn) = $chprocess($cwepk, $cwepsk);
		if (\skillbase\skill_query(723)) $retn *= 2;
		return array($retk, $retn);
	}
	
	function battle_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if ((\skillbase\skill_query(723, $pa) && $active) || (\skillbase\skill_query(723, $pd) && !$active))
		{
			eval(import_module('player','weapon'));
			if (((strpos($wepk, 'WG') === 0) || (strpos($wepk, 'WJ') === 0)) && ($weps == $nosta) && !\itemmain\check_in_itmsk('o', $wepsk))
			{
				//在背包中找合适的子弹
				list($bulletkind, $bulletnum) = \ammunition\check_ammukind($wepk, $wepsk);
				$bulletitmn = 0;
				for ($i=1;$i<=6;$i++)
				{
					if (strpos(${'itmk'.$i}, $bulletkind) === 0)
					{
						$bulletitmn = $i;
						break;
					}
				}
				//装填
				if ($bulletitmn > 0)
				{
					$theitem = array();
					$theitem['itm'] = &${'itm'.$bulletitmn};
					$theitem['itmk'] = &${'itmk'.$bulletitmn};
					$theitem['itme'] = &${'itme'.$bulletitmn};
					$theitem['itms'] = &${'itms'.$bulletitmn}; 
					$theitem['itmsk'] = &${'itmsk'.$bulletitmn};
					eval(import_module('logger'));
					$log .= "<br><span class=\"lime b\">你发动了旋转引导扇区，从手卡特殊召唤了两个弹仓！你为武器装填了更多的弹药。</span><br>";
					\ammunition\itemuse_ugb($theitem);
				}
			}
		}
	}
	
}

?>