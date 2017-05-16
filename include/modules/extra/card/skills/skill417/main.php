<?php

namespace skill417
{

	function init() 
	{
		define('MOD_SKILL417_INFO','card;hidden;');
	}
	
	function acquire417(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost417(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function get_move_coldtime(&$dest){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(417)) return $chprocess($dest)*1.7;
		return $chprocess($dest);
	}
	
	function get_search_coldtime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(417)) return $chprocess()*1.7;
		return $chprocess();
	}
	
	function get_itemuse_coldtime(&$item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(417)) return $chprocess($item)*2;
		return $chprocess($item);
	}

}

?>
