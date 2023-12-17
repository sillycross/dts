<?php

namespace skill708
{
	function init() 
	{
		define('MOD_SKILL708_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[708] = '得了';
	}
	
	function acquire708(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost708(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked708(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa))
		{
			eval(import_module('player'));
			$pa = $sdata;
		}
		return $pa['lvl'] >= 18;
	}
	
	function itemmix_recipe_check($mixitem){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(708) && check_unlocked708())
		{
			eval(import_module('sys','player'));
			$res = array();
			if(count($mixitem) >= 2)
			{
				$recipe = \itemmix\get_mixinfo();
				$mi_names = array();
				foreach($mixitem as $i) $mi_names[] = \itemmix\itemmix_name_proc($i['itm']);
				foreach($recipe as $minfo)
				{
					$ms = $minfo['stuff'];
					//对破则禁用此效果
					if ($minfo['result'][0] == '概念武装『破则』')
					{
						sort($mi_names);
						sort($ms);
						if(count($mi_names)==count($ms) && $mi_names == $ms) {
							$minfo['type'] = 'normal';
							$res[] = $minfo;
						}
					}
					elseif (count($mi_names)==count($ms) && skill708_compare($mi_names, $ms))
					{
						$minfo['type'] = 'normal';
						$res[] = $minfo;
					}
				}
			}
			return $res;
		}
		else return $chprocess($mixitem);
	}
		
	function skill708_compare($list1, $list2)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$count1 = array_count_values($list1);
		$count2 = array_count_values($list2);
		$diff = 0;
		$strs = [];
		foreach ($count1 as $key => $count)
		{
			if (!isset($count2[$key]))
			{
				$diff += $count;
				$strs[] = $key;
			}
			elseif ($count2[$key] < $count)
			{
				$diff += $count - $count2[$key];
				$strs[] = $key;
			}
		}
		foreach ($count2 as $key => $count)
		{
			if (!isset($count1[$key]) || ($count1[$key] !== $count)) $strs[] = $key;
		}
		//完全一致可以合成
		if (!$diff) return true;
		if ($diff > 1) return false;
		$strs = array_values(array_unique($strs));
		$str1 = $strs[0];
		$str2 = $strs[1];
		if (mb_strlen($str1, 'UTF-8') != mb_strlen($str2, 'UTF-8')) return false;
		$flag = 0;
		for ($i = 0; $i < mb_strlen($str1, 'UTF-8'); $i++)
		{
			$char1 = mb_substr($str1, $i, 1, 'UTF-8');
			$char2 = mb_substr($str2, $i, 1, 'UTF-8');
			if ($char1 !== $char2)
			{
				//差一个字可以合成，多了不行
				if (!$flag) $flag = 1;
				else return false;
			}
		}
		return true;
	}

}

?>