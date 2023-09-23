<?php

namespace sys
{
	/*Game system settings*/

	//文件验证字符串
	$checkstr = "<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>\r\n";
	//是否开启密码兼容模式（登录时兼容1.25之前密码储存模式）
	$oldpswdcmp = 0;
	//是否缓存css文件。0=不缓存，1=缓存
	$allowcsscache = 1;
	//是否用数据库储存历史进行状况。0=/gamedata/bak/文件储存，1=数据库history表hnews字段储存。建议根据服务器配置灵活设置
	$hnewsstorage = 1;
	//游戏所用配置文件
	$gamecfg = 1;
	
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
	
	//用户数据库远程存放地址（是特定的一个接收php），留空为存本地
	//开启后，本地数据库相当于缓存，实际以远端数据库为准
	$userdb_remote_storage = '';
	//用户数据远程存放签名
	$userdb_remote_storage_sign = 'local';
	//用户数据远程存放密钥
	$userdb_remote_storage_pass = '142857';
	//接收来自以下地址的用户数据读写
	//键名为地址（其实只是个签名），键值为密钥和IP，应该与发送端上面那个密钥对应
	$userdb_receive_list = array(
		'local' => Array('pass' => '142857', 'ip' => '127.0.0.1'),
	);
	
	//录像远程存放地址（是特定的一个接收php），留空为存本地
	//开启后会先查询本地是否存在录像，再查询远程是否存在录像
	$replay_remote_storage = '';
	//是否在生成录像时就直接存到远端。就算启动也不会删除本地录像，请定期手动删除或者等待系统自动维护时删除
	$replay_remote_send = 0;
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