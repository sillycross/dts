<?php

namespace skill504
{
	function init() 
	{
		define('MOD_SKILL504_INFO','card;unique;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[504] = 'pop子';
	}
	
	function acquire504(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(504,'done','0',$pa);
	}
	
	function lost504(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked504(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill504_get_popko_html()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if($sdata['hp']<=0) return;
		if (!\skillbase\skill_query(504)) return;
		$val = (int)\skillbase\skill_getvalue(504,'done');
		if ($val == 1) return;
		include template(MOD_SKILL504_ANIME);
	}
	
	function skill504_popko_anime_complete()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(504)) return;
		\skillbase\skill_setvalue(504,'done','1');
		eval(import_module('logger'));
		ob_clean();
		include template('MOD_SKILL504_OKOTTA');
		$log .= ob_get_contents();
		ob_clean();
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','input'));
		if($mode == 'special_skill504') {
			if($command == 'skill504_complete') {
				skill504_popko_anime_complete();
				return;
			}
		}
		$chprocess();
	}
}

?>
