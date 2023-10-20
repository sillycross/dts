<?php

namespace skill585
{
	function init() 
	{
		define('MOD_SKILL585_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[585] = '回声';
	}
	
	function acquire585(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost585(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked585(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_ex_attack_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(585,$pa) && check_unlocked585($pa))
		{
			$flag = 0;
			eval(import_module('sys','player'));
			$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls={$pa['pls']} AND hp>0 AND pid != {$pa['pid']}");
			if($db->num_rows($result))
			{
				while($r = $db->fetch_array($result))
				{
					$pdata = \player\fetch_playerdata_by_pid($r['pid']);
					if ((strpos($pdata['name'], '复读机') !== false) || (($pdata['type'] == 0) && ($pdata['card'] == 345)))
					{
						$flag = 1;
						break;
					}
				}
			}
			if ($flag == 1) array_push($ret,'w');
		}
		return $ret;
	}

}

?>