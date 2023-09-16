<?php

namespace skill447
{
	function init() 
	{
		define('MOD_SKILL447_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[447] = '热寂';
	}
	
	function acquire447(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost447(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked447(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(447,$pa) && check_unlocked447($pa)) {
			if ( $pa['dmg_dealt'] > $pd['hp']*0.9 && $pa['dmg_dealt'] < $pd['hp'] ){
				$pa['dmg_dealt']=$pd['hp'];
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"cyan b\">敌人的生命之火直接熄灭了，就像本来就没有存在过一样。</span><br>";
				else $log .= "<span class=\"cyan b\">你的生命之火直接熄灭了，就像本来就没有存在过一样。</span><br>";
				$pa['seckill'] = 1;
			}
		}
		$chprocess($pa,$pd,$active);
	}
	
}

?>
