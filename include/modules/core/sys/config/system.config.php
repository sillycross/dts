<?php

namespace sys
{
	/*Game system settings*/

	//文件验证字符串
	$checkstr = "<? if(!defined('IN_GAME')) exit('Access Denied'); ?>\n";
	//是否允许游客进入插件。0=不允许，1=允许
	$isLogin = 1;
	//是否开启密码兼容模式（登录时兼容1.25之前密码储存模式）
	$oldpswdcmp = 0;
	//是否缓存css文件。0=不缓存，1=缓存
	$allowcsscache = 1;
	//是否用数据库储存历史进行状况。0=/gamedata/bak/文件储存，1=数据库history表hnews字段储存。建议根据服务器配置灵活设置
	$hnewsstorage = 0;
	//站长留言
	//$systemmsg = '';
	//游戏开始方式 0=后台手动开始，1=每天固定时间开始，2=上局结束后，间隔固定小时后的整点开始，3=上局结束后，间隔固定分钟开始
	$startmode = 0;
	//游戏开始的小时，如果，如果$startmode=1,表示开始时间0~23，如果$startmode=2，表示间隔小时，>0，如果$startmode=3则无视
	$starthour = 0;
	//游戏开始的分钟数，范围1~59，$startmode=3时表示间隔分钟
	$startmin = 3;
	//游戏提前准备的分钟数，建议小于$startmin，小于1时游戏会自动认为是1
	$readymin = 1;
	//游戏所用配置文件
	$gamecfg = 1;


	//同ip限制激活人数。0为不限制
	$iplimit = 3;
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
	//翻页列表直接跳转页码的个数
	//$pagelimit = 7;
	
	//失焦冻结时间，单位分钟
	//$lostfocusmin = 10;
	//这好像是某一段时间蛋服时间经常穿越而弄出来的设想，还没写完服务器就修好了，要不要来个人给它重新定义一下

	//游戏内聊天信息显示条数
	$chatlimit = 50;
	//聊天信息更新时间(单位:毫秒)
	$chatrefresh = 3000;
	//游戏进行中是否显示聊天。0为不显示，数字为显示条数
	$chatinnews = 50;
	
	//是否禁止曜日活动
	$disable_event = 0;
	//是否禁止沙盒模式
	//$disable_sandbox_mode = 0;
	//是否禁止新游戏（主游戏下一局时间会变为未定，无法新建房间）用于更新
	$disable_newgame = 0;
	//是否禁止创建房间
	$disable_newroom = 0;
	
	//房间游戏模式列表
	//之所以放在这里是避免global房间的参数，毕竟房间是模块外
	$room_mode = Array(10,11,12,13,14,15,16,17,18,19);
	//团队模式游戏类型列表
	$teamwin_mode = Array(11,12,13,14);
	//不可完成一般成就的游戏类型列表(这个是不是放到achievement_base里比较好？不过这么写可以省一个import)
	//已废弃，现在用的是achievement_base及各个成就文件里的$ach_allow_mode
	//$ach_ignore_mode = Array(1,2,3,10,11,12,13,14,15,16,17);
	//不可获得胜利切糕的游戏类型
	$qiegao_ignore_mode = Array(1,10,11,12,13,14,15,16,17);
	//计算天梯积分的游戏类型
	$elorated_mode=Array(10,11,12,13,14);
	//不允许PVE的游戏类型
	$pve_ignore_mode=Array(1,3,10,11,12,13,14);	
	
	//录像远程存放地址（是特定的一个接收php），留空为存本地
	//开启后会先查询本地是否存在录像，再查询远程是否存在录像
	$replay_remote_storage = 'http://127.0.0.1/dts1/dts/replay_receive.php';
	//是否在生成录像时就直接存到远端。就算启动也不会删除本地录像，请定期手动删除
	$replay_remote_send = 1;
	//录像远程存放签名
	$replay_remote_storage_sign = 'local';
	//录像远程存放密钥
	$replay_remote_storage_key = '142857';
	//接收来自以下地址的录像
	//键名为地址（其实只是个签名），键值为密钥，应该与发送端上面那个密钥对应
	$replay_receive_list = array(
		'local' => '142857',
	);
	//不记录录像的游戏类型
	//这个要在command里调用，倒是可以直接写在这个文件了
	$replay_ignore_mode=Array(17);
}

?>