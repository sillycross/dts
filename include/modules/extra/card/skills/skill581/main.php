<?php

namespace skill581
{
	function init() 
	{
		define('MOD_SKILL581_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[581] = '永灵';
	}
	
	function acquire581(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost581(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked581(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if (\skillbase\skill_query(581,$sdata) && check_unlocked581($sdata))
		{
			if ((strpos($wepk,'WK') === 0) && (strpos($itmk0,'H') === 0) && ($itms0 !== $nosta))
			{
				eval(import_module('logger'));
				$itms0 = ceil($itms0 * 2);
				$log .= "<span class=\"yellow b\">在你精妙的刀工下，食材如同活了过来一般！</span><br>你制作的补给品数量增加了。<br>";
			}
		}
		$chprocess();
	}
	
}

?>