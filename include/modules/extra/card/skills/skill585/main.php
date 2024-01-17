<?php

namespace skill585
{
	$skill585_flag = -1;

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
			eval(import_module('sys','skill585'));
			if($skill585_flag < 0) {//战斗中第一次取属性的时候才执行，其他时候直接用查询的结果
				$skill585_flag = 0;
				$apls = $pa['pls'];
				$apid = $pa['pid'];
				
				$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='$apls' AND hp>0 AND pid != '$apid'");
				if($db->num_rows($result))
				{
					while($r = $db->fetch_array($result))
					{
						$pdata = \player\fetch_playerdata_by_pid($r['pid']);
						if ((strpos($pdata['name'], '复读机') !== false) || (!$pdata['type'] && '复读机' == $pdata['cardname']))
						{
							$skill585_flag = 1;
							break;
						}
					}
				}
			}
			
			if ($skill585_flag) array_push($ret,'w');
		}
		return $ret;
	}

}

?>