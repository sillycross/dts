<?php

namespace skill451
{

	function init() 
	{
		define('MOD_SKILL451_INFO','card;hidden;');
	}
	
	function acquire451(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost451(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function get_move_coldtime(&$dest){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(451)) return $chprocess($dest)*0.9;
		return $chprocess($dest);
	}
	
	function get_search_coldtime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(451)) return $chprocess()*0.9;
		return $chprocess();
	}
	
	function get_itemuse_coldtime(&$item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(451)) return $chprocess($item)*0.9;
		return $chprocess($item);
	}

}

?>
