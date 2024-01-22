<?php

namespace skill726
{
	function init()
	{
		define('MOD_SKILL726_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[726] = '狱剑';
	}

	function acquire726(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	function lost726(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function skill103_sendin($itmn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(726, $sdata) && (strpos(${'itmk'.$itmn}, 'K')===false))
		{
			eval(import_module('logger'));
			$log .= "<span class=\"red b\">你只能嵌入斩系武器。<br>";
		}
		else $chprocess($itmn);
	}
	
}

?>
