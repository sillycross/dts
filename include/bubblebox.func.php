<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

//根据给定的数据生成悬浮框代码用于htm显示
//从bubblebox模块移植出来。需要占用global $bubblebox_style变量
//global.func.php会require_once本文件

//去除字符串尾部的空格
function bubblebox_delete_trailing_spaces($str)
{
	$head=0; 
	while ($head<strlen($str) && ctype_space($str[$head])) $head++;
	$tail=strlen($str)-1;
	while ($head<=$tail && ctype_space($str[$tail])) $tail--;
	if ($head>$tail) return '';
	return substr($str,$head,$tail-$head+1);
}

//设置bubblebox的默认选项。需要占用global $bubblebox_style变量
function bubblebox_set_default()
{
	global $bubblebox_style;
	$bubblebox_style = Array(
		//下面是必须主动设置的选项
		'id' => '',			//气泡框id，页面html中同时存在的不同气泡框id必须不同
		'height' => 100,		//气泡框文字区的高度（由于边缘装饰，气泡框实际占用高度/宽度会比这个值略高一些）
		'width' => 100,		//气泡框文字区的宽度
		//下面是可选选项的默认配置
		'z-index-base' => 10,	//气泡框的基准z-index，应当是10的倍数。这个功能目前暂时没有实际意义，未来允许同时显示多个气泡框时可能有用。
		'offset-x' => 0,		//气泡框中心点相对于屏幕中心点的横向偏移
		'offset-y' => 0,		//气泡框中心点相对于屏幕中心点的纵向偏移
		'opacity' => 0.95,	//气泡框本体不透明度
		'cancellable' => 0,	//是否允许通过点击气泡框外面任意位置来消除气泡框	
		'change-to' => '', //消除气泡框后立刻显示的气泡框，相当于跳转，虽然没有用上
		'scroll-bar' => 0,	//是否显示滚动条，这是基于jscrollpane控件生成的滚动条，目前已弃用，改为css滚动条
		//下面是各种杂项设置，一般不需要改动，直接用默认配置就行
		'border-width-x' => 5,	//横向半透明边缘宽度
		'border-width-y' => 5,	//纵向半透明边缘宽度
		'margin-top' => 8,	//上侧文字区边缘留白宽度
		'margin-bottom' => 8,	//下侧文字区边缘留白宽度
		'margin-left' => 8,	//左侧文字区边缘留白宽度
		'margin-right' => 4,	//右侧文字区边缘留白宽度（注意滚动条会出现在右侧，而且宽度是算在文字区内的，如果气泡框内容固定，可以根据是否会出现滚动条进行适当调整）
	);
	return $bubblebox_style;
}

//设置bubblebox的样式
//传参为样式字符串，用半角分号分隔
function bubblebox_set_style($str)
{
	global $bubblebox_style;
	bubblebox_set_default();
	$str.=';'; $i=0; $s1=''; $s2=''; $sflag=0;
	while ($i<strlen($str))
	{
		if ($str[$i]==';')
		{
			if ($sflag) 
			{
				$s1=bubblebox_delete_trailing_spaces($s1);
				$s2=bubblebox_delete_trailing_spaces($s2);
				if ($s1!='' && isset($bubblebox_style[$s1])) $bubblebox_style[$s1]=$s2;
				$sflag=0; $s1=''; $s2='';
			}
		}
		else  if ($str[$i]==':')
		{
			if ($sflag==0) $sflag++;
		}
		else
		{
			if ($sflag==0) $s1.=$str[$i]; else $s2.=$str[$i];
		}
		$i++;
	}
}

//读取bubblebox的样式
//传参为单个样式名
function bubblebox_get_style($str)
{
	global $bubblebox_style;
	$str = trim($str);
	if(substr($str,strlen($str)-1,1)==';') $str=substr($str,0,strlen($str)-1);
	return $bubblebox_style[$str];
}

//生成bubblebox的开始标签。
//传参为样式字符串，用半角分号分隔
function bubblebox_start($str)
{
	global $bubblebox_style;
	bubblebox_set_style($str);
	//获取单个样式数值
	$attr_arr = Array('id', 'width', 'height', 'z-index-base', 'offset-x', 'offset-y', 'opacity', 'cancellable', 'change-to', 'scroll-bar', 'border-width-x', 'border-width-y', 'margin-top', 'margin-bottom', 'margin-left', 'margin-right');
	foreach($attr_arr as $v) {
		${'bbox_'.str_replace('-','_',$v)} = bubblebox_get_style($v);
	}
	//用于显示的样式数值修改
	// $bbox_z_index_base_tie1 = $bbox_z_index_base + 3;
	// $bbox_z_index_base_tie2 = $bbox_z_index_base + 4;
	// $bbox_z_index_base_tie3 = $bbox_z_index_base + 5;
	// $bbox_z_index_base_tie4 = $bbox_z_index_base + 6;
	$bbox_positioner_miner_margin_top = $bbox_offset_y-ceil(($bbox_height+$bbox_border_width_y*2+$bbox_margin_top+$bbox_margin_bottom)/2);
	$bbox_positioner_miner_margin_left = $bbox_offset_x-ceil(($bbox_width+$bbox_border_width_x*2+$bbox_margin_left+$bbox_margin_right)/2);
	$bbox_positioner_miner_min_width = $bbox_width+$bbox_border_width_x*2+$bbox_margin_left+$bbox_margin_right;
	$bbox_positioner_miner_max_width = $bbox_width+$bbox_border_width_x*2+$bbox_margin_left+$bbox_margin_right;
	$bbox_positioner_miner_min_height = $bbox_height+$bbox_border_width_y*2+$bbox_margin_top+$bbox_margin_bottom;
	$bbox_positioner_miner_max_height = $bbox_height+$bbox_border_width_y*2+$bbox_margin_top+$bbox_margin_bottom;
	$bbox_screen_fader_miner_margin_top = $bbox_offset_y-ceil(($bbox_height+$bbox_margin_top+$bbox_margin_bottom)/2);
	$bbox_screen_fader_miner_margin_left = $bbox_offset_x-ceil(($bbox_width+$bbox_margin_left+$bbox_margin_right)/2);
	$bbox_screen_fader_miner_min_width = $bbox_width+$bbox_margin_left+$bbox_margin_right;
	$bbox_screen_fader_miner_max_width = $bbox_width+$bbox_margin_left+$bbox_margin_right;
	$bbox_screen_fader_miner_min_height = $bbox_height+$bbox_margin_top+$bbox_margin_bottom;
	$bbox_screen_fader_miner_max_height = $bbox_height+$bbox_margin_top+$bbox_margin_bottom;
	$bubblebox_container_min_height = $bbox_height+$bbox_margin_top+$bbox_margin_bottom;
	$bubblebox_container_max_height = $bbox_height+$bbox_margin_top+$bbox_margin_bottom;

	include template('bubblebox_start');
}

//生成bubblebox的结束标签。
function bubblebox_end()
{
	include template('bubblebox_end');
}

/* End of file bubblebox.func.php */
/* Location: include/bubblebox.func.php */