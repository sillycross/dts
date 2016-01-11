<?php

namespace skill433
{
	
	function init() 
	{
		define('MOD_SKILL433_INFO','club;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[433] = '断肠';
	}
	
	function acquire433(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost433(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function check_unlocked433(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if ($pa['type']==0 && \skillbase\skill_query(433,$pd))	//被玩家击杀才有效
		{
			eval(import_module('sys','logger'));
			$log.='<span class="yellow">敌人的技能「断肠」使你失去了所有称号技能！</span>';
			$arr=\skillbase\get_acquired_skill_array($pa);
			foreach ($arr as $key)
				if (defined('MOD_SKILL'.$key.'_INFO') && strpos(constant('MOD_SKILL'.$key.'_INFO'),'club;')!==false && strpos(constant('MOD_SKILL'.$key.'_INFO'),'hidden;')===false)
					\skillbase\skill_lost($key,$pa);
			\skillbase\skill_acquire(433,$pa);
		}
		$chprocess($pa,$pd);	
	}
}

?>
