<?php

namespace skill69
{
	function init() 
	{
		define('MOD_SKILL69_INFO','club;feature;');
		eval(import_module('clubbase'));
		$clubskillname[69] = '富家';
		$clubdesc_h[11] = $clubdesc_a[11] = '初始金钱为500元；购买物品时价格打七五折';
	}
	
	function acquire69(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$pa['money']+=480;
	}
	
	function lost69(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked69(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_shopiteminfo($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($item);
		if (\skillbase\skill_query(69)) $ret['price']=round($ret['price']*0.75);
		return $ret;
	}
	
	function prepare_shopitem($sn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=$chprocess($sn);
		if (\skillbase\skill_query(69)) 
		{
			for ($i=0; $i<count($ret); $i++)
				$ret[$i]['price']=round($ret[$i]['price']*0.75);
		}
		return $ret;
	}
}

?>
