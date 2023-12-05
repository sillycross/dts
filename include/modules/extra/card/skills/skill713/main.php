<?php

namespace skill713
{
	function init() 
	{
		define('MOD_SKILL713_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[713] = '极目';
	}
	
	function acquire713(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(713,'lvl','0',$pa);
	}
	
	function lost713(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked713(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function discover_extra_item($mipool)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$itemnum = count($mipool);
		if (($itemnum > 1) && \skillbase\skill_query(713) && check_unlocked713())
		{
			$sk713_lvl = \skillbase\skill_getvalue(713,'lvl');
			if (!empty($sk713_lvl))
			{
				array_shift($mipool);
				$mipool = array_values($mipool);
				eval(import_module('logger'));
				\skill1006\add_beacon_from_itempool($mipool, $sk713_lvl);
				$log .= "你额外发现了一些道具。<br>";
			}
		}
		$chprocess($mipool);		
	}
	
}

?>