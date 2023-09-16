<?php

namespace skill452
{
	function init() 
	{
		define('MOD_SKILL452_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[452] = '转移';
	}
	
	function acquire452(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost452(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked452(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function battle_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(452,$pd) && check_unlocked452($pd) && $pa['dmg_dealt']>=150 && $pd['hp']>0 && $pd['tactic']==4)
		{
			eval(import_module('logger','map','sys'));
			$plsnum = sizeof($plsinfo) - 1;
			$pls452 = $arealist[rand($areanum+1,$plsnum)];	
			if ($areanum+1 < $plsnum){
				while ($pls452==34) {$pls452 = $arealist[rand($areanum+1,$plsnum)];}
			}
			if ($active)
				$log.="<span class=\"cyan b\">{$pd['name']}通过相位裂隙紧急转移到了{$plsinfo[$pls452]}！</span><br>";
			else  $log.="<span class=\"cyan b\">你通过相位裂隙紧急转移到了{$plsinfo[$pls452]}！</span><br>";
			$pd['pls']=$pls452;
		}
		$chprocess($pa,$pd,$active);
	}
}

?>
