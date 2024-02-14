<?php

namespace map_display
{
	function init() {}
	
	//获得当前地图显示配置组，需要gamevars支持
	function get_map_display_config()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map_display','map'));
		$config = !empty($gamevars['map_display_group']) ? $gamevars['map_display_group'] : 0;
		if(!empty($map_display_group[$config])) $ret = $map_display_group[$config];
		else $ret = $map_display_group[0];
		//根据plsinfo生成每个地图的XY编号数组
		$ret['xyinfo'] = Array();
		foreach(\map\get_all_plsno() as $i) {
			if(!empty($xyinfo[$i])) $ret['xyinfo'][$xyinfo[$i]] = $i;
		}
		return $ret;
	}

	//把纵向格子数字变为大写英文字母，超过26个字母则用双字母表示
	function map_display_y2coor($y)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if($y > 26) {
			$ret = map_display_y2coor(floor($y / 26)) . map_display_y2coor($y % 26);
		}else{
			$ret = chr($y + 64);
		}
		return $ret;
	}
}

?>