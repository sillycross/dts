<?php

namespace skill24
{
	function init() 
	{
		define('MOD_SKILL24_INFO','club;hidden;');
		eval(import_module('clubbase'));
		//eval(import_module('player'));
		
		$clubdesc_h[20] .= '<br>每次升级额外获得1-2点生命';
		$clubdesc_h[24] .= '<br>每次升级额外获得1-2点生命';
		//不显示了吧……会和技能说明里的升级部分冲突
//		if(!empty($sdata) && \skillbase\skill_query(24)){
//			$clubdesc_a[20] .= '<br>每次升级额外获得1-2点生命';
//			$clubdesc_a[24] .= '<br>每次升级额外获得1-2点生命';
//		}
	}
	
	function acquire24(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost24(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lvlup(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('lvlctl'));
		if (rand(0,2)==0) $lvuphp += 2; else $lvuphp += 1;
		$chprocess($pa);
	}
}

?>
