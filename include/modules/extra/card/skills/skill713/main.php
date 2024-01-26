<?php

namespace skill713
{
	function init() 
	{
		define('MOD_SKILL713_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[713] = '一览';
	}
	
	function acquire713(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$sk713_lvl = \skillbase\skill_getvalue(713,'lvl',$pa);
		if (empty($sk713_lvl)) \skillbase\skill_setvalue(713,'lvl','3',$pa);//最大一次能增加的发现数
		else \skillbase\skill_setvalue(713,'lvl',strval($sk713_lvl+3),$pa);
	}
	
	function lost713(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked713(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	function discover_extra_item($mipool)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itemnum = count($mipool);
		if ($itemnum > 0 && \skillbase\skill_query(713) && check_unlocked713())
		{
			$sk713_lvl = min(\skillbase\skill_getvalue(713,'lvl'), $itemnum);
			for($sk713_t=0;$sk713_t<$sk713_lvl;$sk713_t++) {//先判定实际发现几次
				if(!\itemmain\check_discover_item_found(-20 * (1 + $sk713_t))) {//只要有任何一次没有发现道具就跳出循环；每次发现都会让发现率-20%
					break;
				}
			}
			if (!empty($sk713_t))
			{
				if(\searchmemory\get_seen_num() + $sk713_t > \searchmemory\calc_memory_slotnum()) $unseenflag = 1;
				$findtxt = '<br>你努力搜寻，额外发现了';
				//遍历$mipool的前$sk713_t个
				for($i=0;$i<$sk713_t;$i++) {
					if($i > 0) $findtxt .= ($i == $sk713_t - 1) ? '和' : '、';
					$findtxt .= '<span class="yellow b">'.$mipool[$i]['itm'].'</span>';
					
					//依次添加到正常视野
					$marr = array('iid' => $mipool[$i]['iid'], 'itm' => $mipool[$i]['itm'], 'pls' => $mipool[$i]['pls'], 'unseen' => 0);
					\searchmemory\add_memory($marr, 0);
				}
				
				$findtxt .= '。';				
				if(!empty($unseenflag)) $findtxt .= '其他部分道具移出了你的视野。<br>';
				
				eval(import_module('itemmain'));
				$itemfind_extra_log .= $findtxt;
				
				//\skill1006\add_beacon_from_itempool($mipool, $sk713_t);
			}
		}
		$chprocess($mipool);
	}
	
}

?>