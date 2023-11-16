<?php

namespace skill574
{
	$skill574_itmlist = array('「增殖的G」','夜雀歌谱','牛肉汤');
	
	function init() 
	{
		define('MOD_SKILL574_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[574] = '好味';
	}
	
	function acquire574(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost574(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked574(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skill574'));
		if (\skillbase\skill_query(574, $sdata) && check_unlocked574($sdata) && in_array($theitem['itm'], $skill574_itmlist))
		{
			$temp_itmk = $theitem['itmk'];
			$temp_itme = $theitem['itme'];
			$theitem['itmk'] = 'HB';
			$theitem['itme'] = '200';		
			$chprocess($theitem);
			if (!empty($theitem['itms']))
			{
				$theitem['itmk'] = $temp_itmk;
				$theitem['itme'] = $temp_itme;
			}
		}
		else $chprocess($theitem);
	}
	
}

?>
