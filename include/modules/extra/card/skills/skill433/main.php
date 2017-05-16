<?php

namespace skill433
{
	
	function init() 
	{
		define('MOD_SKILL433_INFO','card;unique;locked;');
		eval(import_module('clubbase'));
		$clubskillname[433] = '白板';
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
	
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((!\skillbase\skill_query(433,$pa) || !check_unlocked433($pa))&&(!\skillbase\skill_query(433,$pd) || !check_unlocked433($pd)))
		{
			return $chprocess($pa, $pd, $active);
		}
		else
		{
			if (($pa['type']==0)&&($pd['type']==0)){
				$pa['skill433_flag']=1;
				$pd['skill433_flag']=1;
			}
		}
		$chprocess($pa, $pd, $active);
	}	
	
	function skill_query($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillid=(int)$skillid;
		if ($pa!=NULL && isset($pa['skill433_flag']) && $pa['skill433_flag'])
		{
			//所有称号技能失效
			if (defined('MOD_SKILL'.$skillid.'_INFO') && strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'card;')!==false && strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;')===false)
				return 0;
		}
		return $chprocess($skillid,$pa);
	}
	
	/*function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if ($pa['type']==0 && \skillbase\skill_query(433,$pd) && $pa['user_commanded']==1)	//被玩家击杀才有效
		{
			eval(import_module('sys','logger'));
			$log.='<span class="yellow">敌人的技能「断肠」使你失去了所有称号技能！</span>';
			$arr=\skillbase\get_acquired_skill_array($pa);
			foreach ($arr as $key)
				if (defined('MOD_SKILL'.$key.'_INFO') && \skillbase\check_skill_info($key, 'club') && !\skillbase\check_skill_info($key, 'hidden'))
					\skillbase\skill_lost($key,$pa);
			\skillbase\skill_acquire(433,$pa);
		}
		$chprocess($pa,$pd);	
	}*/
	
	
}

?>
