<?php

namespace skill537
{
	function init() 
	{
		define('MOD_SKILL537_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[537] = '荒原';
	}
	
	function acquire537(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(537,'activated','0',$pa);
	}
	
	function lost537(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(537,'activated',$pa);
	}
	
	function check_unlocked537(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		if(\skillbase\skill_query(537, $pa) && $pa['lvl'] < 30) {
			eval(import_module('lvlctl'));
			$lvupskpt=0;
		}		
	}
}
?>