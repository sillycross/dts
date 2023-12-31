<?php

namespace skill907
{
	function init() 
	{
		define('MOD_SKILL907_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[907] = '干扰';
	}
	
	function acquire907(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost907(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked907(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		if (\skillbase\skill_query(907))
		{
			if (strpos($itmk, 'EE') === 0 || ((strpos($itmk, 'ER') === 0) && rand(0,99)))
			{
				eval(import_module('logger'));	
				$log .= "周围充斥的强电磁波使你使用{$itm}的尝试失败了。<br>";
				return;
			}
		}
		$chprocess($theitem);
	}

}

?>