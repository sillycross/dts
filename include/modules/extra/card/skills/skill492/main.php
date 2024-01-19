<?php

namespace skill492
{	
	function init() 
	{
		define('MOD_SKILL492_INFO','unique;');
		eval(import_module('clubbase'));
		$clubskillname[492] = '冲刺';
	}
	
	function acquire492(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost492(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked492(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_skill403_procrate(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd,$active);
		if($ret && \skillbase\skill_query(492, $pa) && check_unlocked492($pa)){
			$an = \map\get_area_wavenum();
			$ret += $an*20;//每禁区1次，追击率+20%
			if($ret > 95) $ret = 95;//追击率不会超过95%
		}
		return $ret;
	}
}

?>