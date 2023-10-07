<?php

namespace skill567
{
	function init() 
	{
		define('MOD_SKILL567_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[567] = '毒雾';
	}
	
	function acquire567(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost567(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked567(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function discover($schmode){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(567))
		{
			eval(import_module('sys','player'));
			$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE pls = '$pls' AND itmk LIKE 'H%'");
			$edibles = array();
			while($itmarr = $db->fetch_array($result))
			{
				$edibles[] = $itmarr;
			}
			shuffle($edibles);
			$count = rand(1,5);
			$i = 0;
			foreach($edibles as $itm)
			{
				$iid = $itm['iid'];
				$itmk = substr_replace($itm['itmk'],'P',0,1);
				
				$db->query("UPDATE {$tablepre}mapitem SET itmk = '$itmk', itmsk = '$pid' WHERE iid = '$iid'");
				$i += 1;
				if ($i >= $count) break;
			}
			//每次都显示的话有点吵……
			//eval(import_module('logger'));
			//$log .= "<span class=\"purple b\">你周身散发出的毒雾渗透了周围的物品！</span><br>";
		}
		return $chprocess($schmode);
	}
	
	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(567, $pa))
		{
			$flag = 0;
			for ($i=0; $i<=6; $i++)
			{
				if (0 === strpos($pd['itmk'.$i], 'H'))
				{
					if (rand(0,1) < 1)
					{
						$pd['itmk'.$i][0] = 'P';
						$pd['itmsk'.$i] = $pa['pid'];
					}
					$flag = 1;
				}
			}
			if (1 === $flag)
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"purple b\">你周身散发出的毒雾渗透了敌人包裹中的物品！</span><br>";
				else $log .= "<span class=\"purple b\">敌人周身散发出的毒雾渗透了你包裹中的物品！</span><br>";
			}
		}
		$chprocess($pa, $pd, $active);
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(567) && ($theitem['itmk'][0] === 'P') && ($pid === $theitem['itmsk']))
		{
			$theitem['itmk'][0] = 'H';
			$ret = $chprocess($theitem);
			if ($theitem['itmk'] != '') $theitem['itmk'][0] = 'P';
			return $ret;
		}
		else return $chprocess($theitem);
	}

}

?>