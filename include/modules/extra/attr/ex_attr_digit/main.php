<?php

namespace ex_attr_digit
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^dg'] = '计数';//实际上这个是不会显示的
		$itemspkdesc['^dg']='当前计数为<:skn:>';
		$itemspkinfo['^rdsk'] = '探测器等级';
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if(in_array($cinfo[0], Array('^dg', '^rdsk'))) return false;
		}
		return $ret;
	}
}

?>