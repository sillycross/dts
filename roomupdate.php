<?php

error_reporting(0);

define('GEXIT_RETURN_JSON',TRUE);
define('NO_MOD_LOAD', TRUE);	
define('NO_SYS_UPDATE', TRUE);
require './include/common.inc.php';
require GAME_ROOT.'./include/socket.func.php';
require GAME_ROOT.'./include/roommng/roommng.func.php';
require GAME_ROOT.'./include/user.func.php';

include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';


$_COOKIE=gstrfilter($_COOKIE);
$cuser=$_COOKIE[$gtablepre.'user'];
$cpass=$_COOKIE[$gtablepre.'pass'];

$db = init_dbstuff();
$udata = udata_check();
//$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
//if(!$db->num_rows($result)) gexit('Cookie无效。请重新登录。');
//$udata = $db->fetch_array($result);
//if($udata['password'] != $cpass) gexit('Cookie无效。请重新登录。');
$room_prefix = room_id2prefix($udata['roomid']);
$room_flag = 1;
if (!room_check_subroom($room_prefix)) {
//	$db->query("UPDATE {$gtablepre}users SET roomid='0' WHERE username='$cuser'");
//	gexit('你不在一个房间内。');
	$room_flag = 0;
}else{
	$room_id_r = $udata['roomid'];//substr($udata['roomid'],1);

	ignore_user_abort(1);
	
	$_POST=gstrfilter($_POST);
	if (!file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$room_id_r.'.txt')) {
		$room_flag = 0;
//		$db->query("UPDATE {$gtablepre}users SET roomid='0' WHERE username='$cuser'");
//		gexit('房间文件缓存不存在。');
	}else{
		$result = $db->query("SELECT groomid,groomstatus,groomtype,roomvars FROM {$gtablepre}game WHERE groomid='$room_id_r'");
		if (!$db->num_rows($result)) {
			$room_flag = 0;
//			$db->query("UPDATE {$gtablepre}users SET roomid='0' WHERE username='$cuser'");
//			gexit('房间数据记录不存在。');
		}
		$rarr=$db->fetch_array($result);
		if ($rarr['groomstatus']==0) {
			$room_flag = 0;
//			$db->query("UPDATE {$gtablepre}users SET roomid='0' WHERE username='$cuser'");
//			gexit('房间已关闭。');	
		}
	}
}
if(!$room_flag){
	$db->query("UPDATE {$gtablepre}users SET roomid='0' WHERE username='$cuser'");
	ob_clean();
	$gamedata['url']='index.php';
	echo gencode($gamedata);
	die();
}

if ($rarr['groomstatus'] >= 40) 
{
	ob_clean();
	$gamedata['url']='game.php';
	echo gencode($gamedata);
	die();
}
//elseif($rarr['groomstatus']>=10 && $rarr['groomstatus']<40 && ($disable_newgame || $disable_newroom))
//{//不知道这句有没有用
//	$db->query("UPDATE {$gtablepre}users SET roomid='0' WHERE username='$cuser'");
//	gexit('系统维护中，暂时不能加入房间。');
//}

//$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$room_id_r.'.txt'),1);
$roomdata = gdecode($rarr['roomvars'] ,1);
$room_id = $rarr['groomid'];
//载入气泡框模块和发光按钮模块
require GAME_ROOT.'./include/modules/extra/misc/bubblebox/module.inc.php';
require GAME_ROOT.'./include/modules/extra/misc/glowbutton/module.inc.php';

$timestamp = (int)($_POST['timestamp']);

if ($timestamp<$roomdata['timestamp'])
{
	room_showdata($roomdata,$cuser);
	die();
}

//创建socket
$___TEMP_socket=socket_create(AF_INET,SOCK_STREAM,getprotobyname("tcp"));  
if ($___TEMP_socket===false) { ob_clean(); die(); }
if (socket_set_option($___TEMP_socket,SOL_SOCKET,SO_REUSEADDR,1)===false) { ob_clean(); die(); }
while (1)
{
	$___TEMP_CONN_PORT_TRY=rand($room_poll_port_low,$room_poll_port_high);
	if (socket_bind($___TEMP_socket,'127.0.0.1',$___TEMP_CONN_PORT_TRY) !== false) 
	{
		$___TEMP_CONN_PORT = $___TEMP_CONN_PORT_TRY;
		break;
	}
}
if (socket_listen($___TEMP_socket)===false) { ob_clean(); die(); }

$uid = uniqid('',true);

$db->query("INSERT INTO {$gtablepre}roomlisteners (port,timestamp,roomid,uniqid) VALUES ('$___TEMP_CONN_PORT','$timestamp','$room_id_r','$uid')");

//等待30秒，没有接收到任何信息则直接退出
if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'a', 30, 0)) 
{
	$db->query("DELETE FROM {$gtablepre}roomlisteners WHERE (port,timestamp,roomid,uniqid) IN (('$___TEMP_CONN_PORT','$timestamp','$room_id_r','$uid'))");
	ob_clean();
	die();
}

if (!file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$room_id_r.'.txt')) { ob_clean(); die(); }

$result = $db->query("SELECT groomid,groomstatus,groomtype,roomvars FROM {$gtablepre}game WHERE groomid='$room_id_r'");
if (!$db->num_rows($result)) { ob_clean(); die(); }
$rarr=$db->fetch_array($result);
if ($rarr['groomstatus']==0) { ob_clean(); die(); }
if ($rarr['groomstatus']>=40) 
{
	ob_clean();
	$gamedata['url']='game.php';
	echo gencode($gamedata);
	die();
}

//$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$room_id_r.'.txt'),1);
$roomdata = gdecode($rarr['roomvars'] ,1);
room_showdata($roomdata,$cuser);
die();

?>