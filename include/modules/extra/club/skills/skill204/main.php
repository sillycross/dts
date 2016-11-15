<?php

namespace skill204
{
	function init() 
	{
		define('MOD_SKILL204_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[204] = '掠夺';
	}
	
	function acquire204(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost204(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked204(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=8;
	}
	
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(204,$pa))&&(check_unlocked204($pa)))
		{
			eval(import_module('logger'));
			$gold_r = ($pa['club']==3?2.5:1.5);
			$var_204=round($pa['lvl']*$gold_r);
			if ($active)
				$log.='<span class="yellow">「掠夺」技能使你立即获得了'.$var_204.'元！<br></span>';
			else  $log.='<span class="yellow">「掠夺」技能使敌人立即获得了'.$var_204.'元！<br></span>';
			
			$pa['money']+=$var_204;
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
