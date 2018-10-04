<?php

namespace battle
{
	function init() {}
	
	//输入：类似"<:pa_name:>攻击了<:pd_name:>"这样的字符串，并自动替换"你"或者对方的玩家名
	function battlelog_parser(&$pa, &$pd, $active, $battlelogstr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($active){
			$pa_name = '你'; $pd_name = $pd['name'];
		}else{
			$pa_name = $pa['name']; $pd_name = '你';
		}
		return str_replace('<:pa_name:>', $pa_name, str_replace('<:pd_name:>', $pd_name, $battlelogstr));
	}
}

?>
