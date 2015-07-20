<?php

namespace bubblebox
{
	$bubblebox_style = Array();
	
	function init() {}
	
	function bubblebox_delete_trailing_spaces($s)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$head=0; 
		while ($head<strlen($s) && ctype_space($s[$head])) $head++;
		$tail=strlen($s)-1;
		while ($head<=$tail && ctype_space($s[$tail])) $tail--;
		if ($head>$tail) return '';
		return substr($s,$head,$tail-$head+1);
	}
	
	function bubblebox_set_default()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('bubblebox'));
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
			'scroll-bar' => 1,	//是否显示滚动条
			//下面是各种杂项设置，一般不需要改动，直接用默认配置就行
			'border-width-x' => 5,	//横向半透明边缘宽度
			'border-width-y' => 5,	//纵向半透明边缘宽度
			'margin-top' => 8,	//上侧文字区边缘留白宽度
			'margin-bottom' => 8,	//下侧文字区边缘留白宽度
			'margin-left' => 8,	//左侧文字区边缘留白宽度
			'margin-right' => 4,	//右侧文字区边缘留白宽度（注意滚动条会出现在右侧，而且宽度是算在文字区内的，如果气泡框内容固定，可以根据是否会出现滚动条进行适当调整）
		);
	}
	
	function bubblebox_set_style($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('bubblebox'));
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
	
	function bubblebox_get_style($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('bubblebox'));
		return $bubblebox_style[$str];
	}			
}

?>
