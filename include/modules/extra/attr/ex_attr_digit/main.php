<?php

namespace ex_attr_digit
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^dg'] = '计数';//在继承了本模块并重载了check_comp_itmsk_visible()之后，可以避免显示这个属性名
		$itemspkdesc['^dg']='当前计数为<:skn:>';
		//探测器等级、下毒者编号写在这里是因为radar和poison模块比较基础，不宜改变继承顺序。新加模块应该继承本模块，不建议直接写在这里。
		$itemspkinfo['^rdsk'] = '探测器等级';
		$itemspkinfo['^psr'] = '下毒者编号';
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if(in_array($cinfo[0], Array('^dg', '^rdsk', '^psr'))) return false;
		}
		return $ret;
	}
}

?>