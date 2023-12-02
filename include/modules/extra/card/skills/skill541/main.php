<?php

namespace skill541
{
	function init() 
	{
		define('MOD_SKILL541_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[541] = '寨决';
	}
	
	function acquire541(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost541(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked541(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//判定$pa用的卡是不是東埔寨卡包
	function check_touhodia541(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = false;
		if(!empty($pa['card']) && !empty($pa['cardname'])) {
			$cardid = \cardbase\check_realcard($pa['card'], $pa['cardname']);
			eval(import_module('cardbase'));
			if($cards[$cardid]['pack'] == '東埔寨Protoject') {
				$ret = true;
			}
		}
		return $ret;
	}
	
	function apply_total_damage_modifier_seckill(&$pa,&$pd,$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(541,$pa) && check_unlocked541($pa)) {			
			if ( $pa['dmg_dealt'] > $pd['hp']*0.8 && $pa['dmg_dealt'] < $pd['hp'] && check_touhodia541($pd)){
				$pa['dmg_dealt']=$pd['hp'];
				eval(import_module('logger'));
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class='red b'>现在宣布<:pa_name:>对<:pd_name:>下达的最终裁决……死刑！</span><br>");
				$pa['seckill'] = 1;
			}
		}
		$chprocess($pa,$pd,$active);
	}
}

?>