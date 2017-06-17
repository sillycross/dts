<?php

namespace skill418
{

	function init() 
	{
		define('MOD_SKILL418_INFO','card;hidden;');
	}
	
	function acquire418(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost418(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function calc_qiegao_drop(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(418))&&($pd['type']==90)) return ceil($chprocess($pa,$pd,$active)*1.5);
		return $chprocess($pa,$pd,$active);
	}

}

?>
