<?php

namespace skill804
{
	function init() 
	{
		define('MOD_SKILL804_INFO','card;feature;');
		eval(import_module('clubbase'));
		$clubskillname[804] = '速递';
	}
	
	function acquire804(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost804(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked804(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//被击破时生成随机奖励道具
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $pd, $active);
		if (\skillbase\skill_query(804, $pd) && ($pd['hp'] <= 0))
		{
			$clv = (int)\skillbase\skill_getvalue(804,'lvl',$pd);
			//生成奖励道具列表
			$file = __DIR__.'/config/skill804_'.$clv.'.config.php';
			$itemlist = openfile($file);
			$in = sizeof($itemlist);
			$prizeid = array_randompick(range(0, $in-1), rand(2,4));
			$i = 1;
			foreach ($prizeid as $v)
			{
				$itmstr = $itemlist[$v];
				if(!empty($itmstr) && strpos($itmstr,',')!==false)
				{
					list($pitm,$pitmk,$pitme,$pitms,$pitmsk) = explode(',',$itmstr);
					$pd['itm'.$i] = $pitm;
					$pd['itmk'.$i] = $pitmk;
					$pd['itme'.$i] = $pitme;
					$pd['itms'.$i] = $pitms;
					$pd['itmsk'.$i] = $pitmsk;
					$i += 1;
				}
			}
		}
	}

}

?>