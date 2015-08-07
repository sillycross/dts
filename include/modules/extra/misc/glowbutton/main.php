<?php

namespace glowbutton
{
	$glowbutton_style = Array();
	
	function init() {}
	
	function glowbutton_delete_trailing_spaces($s)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$head=0; 
		while ($head<strlen($s) && ctype_space($s[$head])) $head++;
		$tail=strlen($s)-1;
		while ($head<=$tail && ctype_space($s[$tail])) $tail--;
		if ($head>$tail) return '';
		return substr($s,$head,$tail-$head+1);
	}
	
	function glowbutton_set_mousedown_event($str)
	{	
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('glowbutton'));
		$glowbutton_style['mousedown-event']=$str;
	}
	
	function glowbutton_set_default()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('glowbutton'));
		$glowbutton_style = Array(
			//下面是必须主动设置的选项
			'id' => '',				//按钮id，页面html中同时存在的不同气泡框id必须不同
			'color' => 'ffffff',		//按钮颜色 
			'line-height' => 15,		//按钮文字行高
			'line-num' => 1,			//按钮文字行数，实际文字行数必须是这个值
			'max-text-width' => 80,		//按钮文字最大宽度，超出部分将被隐藏
			'clickable' => 0,			//是否可以点击，如可以则必须提供mousedown-event
			'glow-on-hover' => 1,		//是否在鼠标悬停时边缘产生发光效果，如果clickable为1，本选项永远视为1
			'mousedown-event' => '',	//点击触发js代码，这个选项应该通过glowbutton_set_mousedown_event设置。注意这段代码是内联在""里的，注意字符转义
			//下面是可选选项的默认配置
			'margin-x' => 10,			//横向文字区边缘留白宽度
			'margin-y' => 8,			//纵向文字区边缘留白宽度
			'background-opacity' => 0.2,	//高亮显示时背景的透明度
		);
	}
	
	function glowbutton_set_style($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('glowbutton'));
		glowbutton_set_default();
		$str.=';'; $i=0; $s1=''; $s2=''; $sflag=0;
		while ($i<strlen($str))
		{
			if ($str[$i]==';')
			{
				if ($sflag) 
				{
					$s1=glowbutton_delete_trailing_spaces($s1);
					$s2=glowbutton_delete_trailing_spaces($s2);
					if ($s1!='' && isset($glowbutton_style[$s1])) $glowbutton_style[$s1]=$s2;
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
	
	function glowbutton_get_style($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('glowbutton'));
		if ($str=='id') return 'glowbutton-'.$glowbutton_style[$str];
		return $glowbutton_style[$str];
	}			
}

?>
