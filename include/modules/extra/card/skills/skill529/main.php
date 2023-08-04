<?php

namespace skill529
{
	function init() 
	{
		define('MOD_SKILL529_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[529] = '幻林';
	}
	
	function acquire529(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//获得时自动获取场上NPC的一件道具
		skill529_get_item($pa);
		//\skillbase\skill_lost(529, $pa);
	}
	
	function skill529_get_item(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$ipool = skill529_get_itempool();
		if(!empty($ipool)){
			shuffle($ipool);
			$getitem = $ipool[0];
			$pos = 0;
			for($i=1;$i<=6;$i++){
				if(empty($pa['itms'.$i])){
					$pos = $i;
					break;
				}
			}
			$pa['itm'.$i] = $getitem['itm'];
			$pa['itmk'.$i] = $getitem['itmk'];
			$pa['itme'.$i] = $getitem['itme'];
			$pa['itms'.$i] = $getitem['itms'];
			$pa['itmsk'.$i] = $getitem['itmsk'];
		}
	}
	
	//获取全部存活NPC的道具数据
	function skill529_get_itempool(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$query = "SELECT * FROM {$tablepre}players WHERE type > 0 AND hp > 0";
		$result = $db->query($query);
		$ipool = Array();
		while($r = $db->fetch_array($result)){
			foreach(Array('wep','arb','arh','ara','arf','art') as $p){
				if(!empty($r[$p]) && !empty($r[$p.'s'])){
					$ipool[] = Array(
						'itm' => $r[$p],
						'itmk' => $r[$p.'k'],
						'itme' => $r[$p.'e'],
						'itms' => $r[$p.'s'],
						'itmsk' => $r[$p.'sk'],
					);
				}
			}
			for($i=0;$i<=6;$i++){
				if(!empty($r['itm'.$i]) && !empty($r['itms'.$i])){
					$ipool[] = Array(
						'itm' => $r['itm'.$i],
						'itmk' => $r['itmk'.$i],
						'itme' => $r['itme'.$i],
						'itms' => $r['itms'.$i],
						'itmsk' => $r['itmsk'.$i],
					);
				}
			}
		}
		return $ipool;
	}
	
	function lost529(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked529(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
}

?>