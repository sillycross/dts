<?php

namespace user_settings
{
	$decoded_u_settings = Array();//暂存u_settings

	function init() {}
	
	//获得u_settings，如果本次进程已经解码过，会使用$decoded_u_settings的值
	function get_u_settings() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','user_settings'));
		$ret = Array();
		if(!empty($decoded_u_settings)) {
			$ret = $decoded_u_settings;
		}
		elseif(!empty($cudata['u_settings'])) {
			$ret = gdecode($cudata['u_settings'], 1);
			if(!is_array($ret)) $ret = Array();
		}
		return $ret;
	}

	//跳过开局剧情
	function opening_by_shootings_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		$u_settings = get_u_settings();
		if(!empty($u_settings['skip_opening'])) {
			$ret = false;
		}
		return $ret;
	}

	//只要能合并的道具就自动合并。
	function itemget_mergable_type2_process($sameitem){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$u_settings = get_u_settings();
		if(!empty($u_settings['item_auto_merge'])){
			eval(import_module('logger'));
			$log .= '<span class="yellow b">因为游戏设置，自动合并了道具。</span><br>';
			$i = $sameitem[0];
			\itemmain\itemmerge(0,$i);
			return;
		}
		return $chprocess($sameitem);
	}
}

?>