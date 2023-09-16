<?php

namespace skill551
{
	$skill551_plslist = array(7,29);//可放生的地点，清水和风祭
	
	//可放生的道具和奖励经验值
	$skill551_itemlist = array(
		'矿泉水' => 1,//这下为醋包饺子了
		'面包' => 1,
		'手机' => 1,
		'鸡肉' => 1,
		'野生的雪貂' => 6,
		'走失的猫咪' => 6,
		'幽灵' => 6,
		'怨灵' => 6,
		'凸眼鱼' => 8,
		'招潮蟹' => 12,
		'南京挂花鸭' => 20,
		'黄鸡方块' => 30,//我觉得这是活物，会咕咕叫的
		'安康鱼' => 40,
		'河豚鱼' => 40,
		'油炖萌物「金鲤」' => 60,
		'油炖萌物「石斑」' => 60,
		'油炖油炖萌萌金鲤鲤' => 60,
		'油炖油炖萌萌石斑斑' => 60
	);
	
	function init() 
	{
		define('MOD_SKILL551_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[551] = '放生';
	}
	
	function acquire551(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost551(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked551(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemdrop($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','skill551','searchmemory'));			
		if($item == 'wep'){
			$itm = & $wep;
			$itmk = & $wepk;
			$itme = & $wepe;
			$itms = & $weps;
			$itmsk = & $wepsk;
		} elseif(strpos($item,'ar') === 0) {
			$itmn = substr($item,2,1);
			$itm = & ${'ar'.$itmn};
			$itmk = & ${'ar'.$itmn.'k'};
			$itme = & ${'ar'.$itmn.'e'};
			$itms = & ${'ar'.$itmn.'s'};
			$itmsk = & ${'ar'.$itmn.'sk'};

		} elseif(strpos($item,'itm') === 0) {
			$itmn = substr($item,3,1);
			$itm = & ${'itm'.$itmn};
			$itmk = & ${'itmk'.$itmn};
			$itme = & ${'itme'.$itmn};
			$itms = & ${'itms'.$itmn};
			$itmsk = & ${'itmsk'.$itmn};
		}
		$flag_free = 0;
		if (\skillbase\skill_query(551,$sdata) && in_array($pls, $skill551_plslist))
		{
			//检查丢弃道具能不能放生
			if (isset($skill551_itemlist[$itm])) {
				eval(import_module('logger'));
				$log .= "你放生了<span class=\"red b\">$itm</span>。<br>你的功德增加了$skill551_itemlist[$itm]点！<br>";
				\lvlctl\getexp($skill551_itemlist[$itm],$sdata);
				if ('黄鸡方块' === $itm) $log .= "<span class=\"red b\">$itm</span>越飞越高，你目送着它消失在远方的天空中。<br>";
				else $log .= "<span class=\"red b\">$itm</span>久久不肯离开，直到你把它丢远才不舍地离去。<br>";
				$flag_free = 1;
				$temp_log = $log;
			}
		}
		$chprocess($item);
		if ($flag_free == 1) {
			//移除视野中的该道具
			\searchmemory\remove_memory_core(-1);
			$log = $temp_log;
		}
	}
}

?>