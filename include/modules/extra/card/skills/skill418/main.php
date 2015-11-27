<?php

namespace skill418
{

	function init() 
	{
		define('MOD_SKILL418_INFO','club;hidden;');
	}
	
	function acquire418(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost418(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
	
	function calc_qiegao_drop(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(418))&&($pd['type']==90)) return ceil($chprocess($pa,$pd,$active)*1.5);
		return $chprocess($pa,$pd,$active);
	}

}

?>
