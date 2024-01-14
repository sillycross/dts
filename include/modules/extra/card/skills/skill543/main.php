<?php

namespace skill543
{
	function init() 
	{
		define('MOD_SKILL543_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[543] = '红薯';
	}
	
	function acquire543(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost543(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}

	//读写视野的时候需要确定skill1006已载入，所以需要后置到入场之后
	function post_load_profile_event(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(543)){
			eval(import_module('sys'));
			if(!empty($command)){
				add_skill543_searchmemory();
				\skillbase\skill_lost(543);
			}
		}
		$chprocess();
	}
	
	function add_skill543_searchmemory()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$itm = '红薯';
		$itmk = 'HB';
		$itme = 250;
		$itms = 1;
		$itmsk = '';
		$crimson_i = rand(0,4);//红暮的位置
		//添加4个红薯，其中随机一个位置是红暮
		for($i=0;$i<=4;$i++)
		{
			if($i != $crimson_i) {
				//先把道具数据插入地图
				$dropid = \itemmain\itemdrop_query($itm, $itmk, $itme, $itms, $itmsk, $pls);

				//把该道具追加到自己的临时视野
				$barr = array('iid' => $dropid, 'itm' => $itm, 'pls' => $pls, 'unseen' => 0, 'itmsk' => $itmsk);
				\skill1006\add_beacon($barr);
			}else{
				//添加红薯
				$barr = array('pid' => 1, 'Pname' => '红暮', 'pls' => 0, 'smtype' => 'enemy', 'unseen' => 0);
				\skill1006\add_beacon($barr);
			}
		}
	}
}

?>