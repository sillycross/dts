<?php

namespace bufficons
{
	function init() {}
	
	//src为图片链接
	//一个完整的技能描述（$para变量）包含以下域：
	//style： 1 生效状态 2 冷却状态 3 冷却完成，可以激活 4 冷却完成，无法激活
	//totsec: 总生效/冷却时间 （取决于技能目前所处状态）
	//nowsec: 当前已经生效/冷却了的时间 
	//disappear: 生效结束后是消失还是进入冷却状态（1为消失）
	//clickable: 如不考虑冷却时间，本技能目前是否已满足主动激活条件
	//hint： 技能的描述文字
	//activate_hint： 激活技能的提示文字（或不能激活技能时的说明文字），如果本技能不是主动技能，与hint一样即可
	//onclick： 点击时的js操作（clickable时有效）
	//corner-text: （可选）在右下角显示的内容
	function bufficon_show($src, $para)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($para['style']==1)
		{
			$wh=round($para['nowsec']/$para['totsec']*32).'px';
			$para['lsec']=$para['totsec']-$para['nowsec'];
			include template('MOD_BUFFICONS_ICON_STYLE_1');
		}
		if ($para['style']==2)
		{
			$wh=round($para['nowsec']/$para['totsec']*32).'px';
			$para['lsec']=$para['totsec']-$para['nowsec'];
			include template('MOD_BUFFICONS_ICON_STYLE_2');
		}
		if ($para['style']==3)
		{
			include template('MOD_BUFFICONS_ICON_STYLE_3');
		}
	}
	
	//需要显示buff图标只需接管这个函数调用bufficon_show即可
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
}

?>
