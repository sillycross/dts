<?php

error_reporting(0);

define('GEXIT_RETURN_JSON',TRUE);
define('NO_MOD_LOAD', TRUE);	
define('NO_SYS_UPDATE', TRUE);
require './include/common.inc.php';
require GAME_ROOT.'./include/socket.func.php';
require GAME_ROOT.'./include/roommng/roommng.func.php';

require GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
$_COOKIE=gstrfilter($_COOKIE);
$cuser=$_COOKIE[$gtablepre.'user'];
$cpass=$_COOKIE[$gtablepre.'pass'];
require GAME_ROOT.'./include/db/db_'.$database.'.class.php';
$db = new dbstuff;
$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) gexit('Cookie无效。请重新登录。');
$udata = $db->fetch_array($result);
if($udata['password'] != $cpass) gexit('Cookie无效。请重新登录。');
if ($udata['roomid']=='' || $udata['roomid'][0]!='s') gexit('你不在一个房间内。');

$roomid = substr($udata['roomid'],1);

ignore_user_abort(1);

$_POST=gstrfilter($_POST);
if (!file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$roomid.'.txt')) gexit('房间不存在。');

$result = $db->query("SELECT * FROM {$gtablepre}rooms WHERE roomid='$roomid'");
if (!$db->num_rows($result)) gexit('房间不存在。');
$zz=$db->fetch_array($result);
if ($zz['status']==0) gexit('房间不存在。');
if ($zz['status']==2) 
{
	ob_clean();
	$gamedata['url']='game.php';
	echo base64_encode(gzencode(json_encode($gamedata)));
	die();
}

$roomdata = json_decode(mgzdecode(base64_decode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$roomid.'.txt'))),1);

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

$db->query("INSERT INTO {$gtablepre}roomlisteners (port,timestamp,roomid,uniqid) VALUES ('$___TEMP_CONN_PORT','$timestamp','$roomid','$uid')");

//等待30秒，没有接收到任何信息则直接退出
if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'a', 30, 0)) 
{
	$db->query("DELETE FROM {$gtablepre}roomlisteners WHERE (port,timestamp,roomid,uniqid) IN (('$___TEMP_CONN_PORT','$timestamp','$roomid','$uid'))");
	ob_clean();
	die();
}

if (!file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$roomid.'.txt')) { ob_clean(); die(); }

$result = $db->query("SELECT * FROM {$gtablepre}rooms WHERE roomid='$roomid'");
if (!$db->num_rows($result)) { ob_clean(); die(); }
$zz=$db->fetch_array($result);
if ($zz['status']==0) { ob_clean(); die(); }
if ($zz['status']==2) 
{
	ob_clean();
	$gamedata['url']='game.php';
	echo base64_encode(gzencode(json_encode($gamedata)));
	die();
}

$roomdata = json_decode(mgzdecode(base64_decode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$roomid.'.txt'))),1);

room_showdata($roomdata,$cuser);
die();

?>
