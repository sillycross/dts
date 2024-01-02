<?php

namespace sys
{
	/*Game system settings for gamemode*/

	//跟gametype有关的一系列配置。
	//之所以放在这里是避免global房间的参数，毕竟房间是模块外
	$room_mode = Array(10,11,12,13,14,15,16,17,18,19);
	//团队模式游戏类型列表
	$teamwin_mode = Array(14);
	//不可完成一般成就的游戏类型列表(这个是不是放到achievement_base里比较好？不过这么写可以省一个import)
	//已废弃，现在用的是achievement_base及各个成就文件里的$ach_allow_mode
	//$ach_ignore_mode = Array(1,2,3,10,11,12,13,14,15,16,17);
	//不可获得胜利切糕的游戏类型
	$qiegao_ignore_mode = Array(1,10,14,15,16,17);
	//计算天梯积分的游戏类型
	$elorated_mode=Array(10,14);
	//不允许PVE的游戏类型
	$pve_ignore_mode=Array(1,3,10,14);	
	
	//不记录录像的游戏类型
	//这个要在command里调用，倒是可以直接写在这个文件了
	$replay_ignore_mode=Array(17);
}

?>