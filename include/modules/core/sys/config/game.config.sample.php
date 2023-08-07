<?php

namespace sys
{
	//是否禁止曜日活动
	$disable_event = 1;
	//是否禁止沙盒模式
	//$disable_sandbox_mode = 0;
	//是否禁止新游戏（主游戏下一局时间会变为未定，无法新建房间）用于更新
	$disable_newgame = 0;
	//是否禁止创建房间
	$disable_newroom = 0;
	
	//游戏开始方式 0=后台手动开始，1=每天固定时间开始，2=上局结束后，间隔固定小时后的整点开始，3=上局结束后，间隔固定分钟开始
	$startmode = 0;
	//游戏开始的小时，如果，如果$startmode=1,表示开始时间0~23，如果$startmode=2，表示间隔小时，>0，如果$startmode=3则无视
	$starthour = 0;
	//游戏开始的分钟数，范围1~59，$startmode=3时表示间隔分钟
	$startmin = 3;
	//游戏提前准备的分钟数，建议小于$startmin，小于1时游戏会自动认为是1
	$readymin = 1;

	//同ip限制激活人数。0为不限制
	$iplimit = 0;
	//头像数量（男女相同）
	$iconlimit = 20;
	//游戏进行状况显示条数
	$newslimit = 50;
	//生存者显示条数
	$alivelimit = 30;
	//玩家排行榜显示条数
	$ranklimit = 20;
	//历史优胜者显示条数
	$winlimit = 50;
	//胜率榜最小参赛次数
	$winratemingames = 30;
	
	//游戏内聊天信息显示条数
	$chatlimit = 50;
	//聊天信息更新时间(单位:毫秒)
	$chatrefresh = 3000;
	//游戏外聊天信息显示条数。0为不显示，数字为显示条数
	$chatinnews = 50;

	//本局游戏人数限制
	$validlimit = 300;
	
	// 初始耐力最大值 
	$splimit = 400;
	// 初始生命最大值 
	$hplimit = 400;

	//hack基础成功率
	$hack_obbs = 40;

}

?>