<?php

namespace skill209
{
	function init() 
	{
		define('MOD_SKILL209_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[209] = '舞钢';
	}
	
	function acquire209(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost209(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked209(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
		
	function get_1st_dmg_factor_l(&$pa,&$pd,$active,$basefluc){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(209,$pa))&&(check_unlocked209($pa))&&($pa['wepk'][1]=="K")){
			eval(import_module('logger'));
			$log.="<span class=\"lime\">「舞钢」使你的斩击更加致命！</span><br>";
			return 0;
		}
		$chprocess($pa, $pd, $active);
	}
}

?>
