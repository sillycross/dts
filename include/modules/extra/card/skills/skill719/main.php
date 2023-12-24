<?php

namespace skill719
{
	function init() 
	{
		define('MOD_SKILL719_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[719] = '炼狱';
	}
	
	function acquire719(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(719,'acount',0,$pa);
		\skillbase\skill_setvalue(719,'lastkillnum',0,$pa);
		\skillbase\skill_setvalue(719,'release',0,$pa);
		\skillbase\skill_setvalue(719,'warned',0,$pa);
	}
	
	function lost719(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(719,'acount',$pa);
		\skillbase\skill_delvalue(719,'lastkillnum',$pa);
		\skillbase\skill_delvalue(719,'release',$pa);
		\skillbase\skill_delvalue(719,'warned',$pa);
	}

	function check_unlocked719(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade719()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(719) || !check_unlocked719($sdata))
		{
			$log.='你没有这个技能！<br>';
			return;
		}	
		$skill719_release = \skillbase\skill_getvalue(719,'release');
		if ($skill719_release)
		{
			$log.='你无法发动此技能。<br>';
			return;
		}
		$skill719_count = (int)\skillbase\skill_getvalue(719,'acount');
		if ($skill719_count >= 19)
		{
			$skill719_warned = \skillbase\skill_getvalue(719,'warned');
			if (empty($skill719_warned))
			{
				$log .= '<span class="red b">你身上的负荷已经很沉重了。你真的还要这样做吗？</span><br>';
				\skillbase\skill_setvalue(719,'warned',1);	
				return;
			}
			else
			{
				$log .= '<span class="red b">你的身体和灵魂被巨大的负荷压垮了。</span><br>';
				$state = 50;
				$sdata['sourceless'] = 1; 
				\player\kill($sdata,$sdata);
				return;
			}
		}
		\skillbase\skill_setvalue(719,'acount',$skill719_count+1);
		\skillbase\skill_setvalue(719,'lastkillnum',$killnum + $npckillnum);
		$log .= '发动成功。<br>';
	}
	
	//先制率
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(719, $ldata))
		{
			$skill719_count_a = (int)\skillbase\skill_getvalue(719, 'acount', $ldata);
			if ($skill719_count_a)
			{
				if (\skillbase\skill_getvalue(719, 'release', $ldata)) $r *= 1 + 0.015 * $skill719_count_a;
				else $r *= 1 - 0.015 * $skill719_count_a;
			}
		}
		if (\skillbase\skill_query(719, $edata))
		{
			$skill719_count_d = (int)\skillbase\skill_getvalue(719, 'acount', $edata);
			if ($skill719_count_d)
			{
				if (\skillbase\skill_getvalue(719, 'release', $edata)) $r *= 1 - 0.015 * $skill719_count_d;
				else $r *= 1 + 0.015 * $skill719_count_d;
			}
		}
		return $chprocess($ldata,$edata)*$r;
	}
	
	//命中率/闪避率
	function get_hitrate_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(719, $pa))
		{
			$skill719_count_a = (int)\skillbase\skill_getvalue(719, 'acount', $pa);
			if ($skill719_count_a)
			{
				if (\skillbase\skill_getvalue(719, 'release', $pa)) $r *= 1 + 0.05 * $skill719_count_a;
				else $r *= 1 - 0.05 * $skill719_count_a;
			}
		}
		if (\skillbase\skill_query(719, $pd))
		{
			$skill719_count_d = (int)\skillbase\skill_getvalue(719, 'acount', $pd);
			if ($skill719_count_d)
			{
				if (\skillbase\skill_getvalue(719, 'release', $pd)) $r *= 1 - 0.05 * $skill719_count_d;
				else $r *= 1 + 0.05 * $skill719_count_d;
			}
		}
		return $chprocess($pa, $pd, $active)*$r;
	}
	
	//物品发现率
	function calculate_itemfind_obbs_multiplier()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(719))
		{
			$skill719_count = (int)\skillbase\skill_getvalue(719, 'acount');
			if ($skill719_count)
			{
				if (\skillbase\skill_getvalue(719, 'release')) $r *= 1 + 0.05 * $skill719_count;
				else $r *= 1 - 0.05 * $skill719_count;
			}
		}
		return $chprocess()*$r;
	}
	
	//角色发现率
	function calculate_meetman_rate($schmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = 1;
		if (\skillbase\skill_query(719))
		{
			$skill719_count = (int)\skillbase\skill_getvalue(719, 'acount');
			if ($skill719_count)
			{
				if (\skillbase\skill_getvalue(719, 'release')) $r *= 1 + 0.05 * $skill719_count;
				else $r *= 1 - 0.05 * $skill719_count;
			}
		}
		return $chprocess($schmode)*$r;
	}
	
	//最终伤害
	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(719, $pa))
		{
			$skill719_count_a = (int)\skillbase\skill_getvalue(719, 'acount',$pa);
			if ($skill719_count_a)
			{
				$dmggain = 5 * $skill719_count_a;
				eval(import_module('logger'));
				if (\skillbase\skill_getvalue(719, 'release', $pa))
				{
					if ($active) $log .= "<span class=\"red b\">「炼狱」使你造成的最终伤害增加了{$dmggain}%！</span><br>";
					else $log .= "<span class=\"red b\">「炼狱」使{$pa['name']}造成的最终伤害增加了{$dmggain}%！</span><br>";
				}
				else
				{
					if ($active) $log .= "<span class=\"red b\">「炼狱」使你造成的最终伤害降低了{$dmggain}%！</span><br>";
					else $log .= "<span class=\"red b\">「炼狱」使{$pa['name']}造成的最终伤害降低了{$dmggain}%！</span><br>";
					$dmggain = -$dmggain;
				}
				$r = array(1 + $dmggain/100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa,$pd,$active);
		if (\skillbase\skill_query(719, $pa) && empty(\skillbase\skill_getvalue(719, 'release', $pa)) && \skillbase\skill_getvalue(719, 'acount', $pa))
		{
			if ($pa['killnum'] + $pa['npckillnum'] - (int)\skillbase\skill_getvalue(719, 'lastkillnum', $pa) >= 15)
			{
				\skillbase\skill_setvalue(719, 'release', 1, $pa);
				if ($active)
				{
					eval(import_module('logger'));
					$log .= "<span class=\"red b\">你已经克服了身上的重压！</span><br>";
				}
			}
		}
	}
	
}

?>