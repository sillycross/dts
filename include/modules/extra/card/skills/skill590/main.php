<?php

namespace skill590
{
	function init() 
	{
		define('MOD_SKILL590_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[590] = '囤积';	
	}
	
	function acquire590(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(590, 'dmgreduce', get_sk590_dmgreduce($pa), $pa);	
	}
	
	function lost590(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(590, 'dmgreduce', $pa);
	}
	
	function check_unlocked590(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function get_sk590_dmgreduce(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$udata = fetch_udata_by_username($pa['name']);
		$gold = $udata['gold'];
		$r = 0;
		if ($gold > 1000) $r = min(floor(150 * log10($gold) - 450) / 10, 20);
		return $r;
	}

	function get_final_dmg_multiplier(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = array();
		if (\skillbase\skill_query(590, $pd) && check_unlocked590($pd))
		{
			eval(import_module('logger'));
			$sk590_dmgreduce = \skillbase\skill_getvalue(590, 'dmgreduce', $pd);
			if ($sk590_dmgreduce > 0){
				if ($active) $log .= "<span class=\"yellow b\">「囤积」使敌人受到的最终伤害降低了{$sk590_dmgreduce}%！</span><br>";
				else $log .= "<span class=\"yellow b\">「囤积」使你受到的最终伤害降低了{$sk590_dmgreduce}%！</span><br>";
				$r = array(1 - $sk590_dmgreduce / 100);
			}
		}
		return array_merge($r,$chprocess($pa,$pd,$active));
	}
	
}

?>
