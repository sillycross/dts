<?php

namespace skill729
{
	function init()
	{
		define('MOD_SKILL729_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[729] = '天命';
	}
	
	function acquire729(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost729(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked729(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) 
		{
			if (\skillbase\skill_query(729,$sdata) && check_unlocked729($sdata) && ($itm == '二十面骰'))
			{
				eval(import_module('clubbase'));
				$log .= "你使用了<span class=\"yellow b\">{$itm}</span>。<br>";
				
				$acquired_skills = \skillbase\get_acquired_skill_array($sdata);
				$count = 0;
				//计算技能数，并失去所有非hidden技能
				if (!empty($acquired_skills))
				{
					foreach ($acquired_skills as $skillid)
					{
						if (isset($clubskillname[$skillid]) && (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;') === false))
						{
							\skillbase\skill_lost($skillid, $sdata);
							$count += 1;
						}
					}
				}
				//得到新的技能
				if ($count > 0)
				{
					$clubskillcount = rand(floor($count/3),$count);
					\item_randskills\get_rand_clubskill($sdata, $clubskillcount);
					$cardskillcount = $count - $clubskillcount;
					if ($cardskillcount > 0)
					{
						eval(import_module('item_randskills'));
						$ls_skills = array_merge(...array_values($rs_cardskills));
						$rs_skills = array_randompick($ls_skills, $cardskillcount);
						if (!is_array($rs_skills)) $rs_skills = [$rs_skills];
						foreach ($rs_skills as $skillid)
						{
							\skillbase\skill_acquire($skillid, $sdata);
						}
					}
					$log .= "随着骰子的转动，你感到自己的身体变得焕然一新！<br>";
				}
				else
				{
					$log .= "但是，这个骰子对你好像没什么用的样子……<br>";
				}
				\itemmain\itms_reduce($theitem);
				return;
			}	
		}
		$chprocess($theitem);
	}
	
}

?>