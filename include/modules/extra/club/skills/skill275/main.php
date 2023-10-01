<?php

namespace skill275
{
	function init() 
	{
		define('MOD_SKILL275_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[275] = '全知';
	}
	
	function acquire275(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost275(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked275(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//可以选择所有能选择的称号
	function get_club_choice_array()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (\skillbase\skill_query(275)) {
			eval(import_module('clubbase'));
			$res = array(0);
			foreach($clublist as $key=>$val){
				if ($val['probability']) $res[] = $key;
			}
			return $res;
		}
		return $chprocess();
	}
}

?>