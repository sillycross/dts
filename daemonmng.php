<?php
header('Content-Type: text/HTML; charset=utf-8');
define('IN_GAME', TRUE);
define('GAME_ROOT', dirname(__FILE__).'/');
error_reporting(0);
$magic_quotes_gpc = get_magic_quotes_gpc();
require GAME_ROOT.'./include/global.func.php';
require GAME_ROOT.'./include/modulemng/modulemng.config.php';
require GAME_ROOT.'./include/socket.func.php';

//游戏内也有调用需求
if(empty($_GET['in_game_pass']) || $_GET['in_game_pass'] != substr(base64_encode($___MOD_CONN_PASSWD),0,6)){
	check_authority();
}
$___TEMP_runmode = 'Admin';
$___TEMP_CONN_PORT = -1;

if (isset($_GET['action']) && $_GET['action']=='restart')
{
	__STOP_ALL_SERVER__();
	touch(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
	__SOCKET_LOG__("已请求脚本启动一台新的服务器。");
	header('Location: daemonmng.php');
}

if (isset($_GET['action']) && $_GET['action']=='stopall')
{
	__STOP_ALL_SERVER__();
	header('Location: daemonmng.php');
}

if (isset($_GET['action']) && strpos($_GET['action'],'stop')===0)
{
	$sid = substr($_GET['action'],4);
	if(is_numeric($sid)){
		__STOP_SINGLE_SERVER__($sid);
	}
	header('Location: daemonmng.php');
}

echo '<br><span><font size=5>Daemon管理系统</font></span><br><br>';

if (!$___MOD_SRV)
	echo '<font color="red">目前不处于Daemon模式下。</font><br><br>';

echo '管理脚本状况： ';

$t1=(int)file_get_contents(GAME_ROOT.'./gamedata/tmp/server/scriptalive.txt');
$t2=(int)filemtime(GAME_ROOT.'./gamedata/tmp/server/scriptalive.txt');
$t=max($t1,$t2);
$ff=1;
if (time()-$t<=10)
{
	echo '<font color="green">正在运行</font><br>';
}
else 
{
	echo '<font color="red">不在运行</font><br><br>';
	echo '<font color="blue">请从服务器shell中启动./acdts-daemonctl.sh（Linux）或者启动./acdts-daemonctl.bat（WIN）</font><br>';
	$ff = 0;
}
echo '<br>';

if ($handle=opendir(GAME_ROOT.'./gamedata/tmp/server')) 
{
	$flag=0; $srvlist=Array(); $chosen=-1;
	while (($sid=readdir($handle))!==false) 
	{
		if ($sid=='.' || $sid=='..') continue;
		$sid=(int)$sid; 
		if ($sid<$___MOD_CONN_PORT_LOW || $sid>$___MOD_CONN_PORT_HIGH) continue;
		if (is_dir(GAME_ROOT.'./gamedata/tmp/server/'.(string)$sid))
			array_push($srvlist,$sid);
	}
	echo '有'.count($srvlist).'个进程正在运行。<br><br>';
	
	if ($ff && isset($_GET['action']) && $_GET['action']=='start' && count($srvlist)==0)
	{
		touch(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
		__SOCKET_LOG__("已请求脚本启动一台新的服务器。");
		header('Location: daemonmng.php');
	}
	
	if ($ff && count($srvlist)==0 && $___MOD_SRV)
	{
		echo '点击启动一个新的根进程。<br>
		（有几秒的延迟，请等待几秒然后刷新页面）<br><a href="daemonmng.php?action=start" style="text-decoration: none"><span>
		<font color="green">[启动]</font></span></a>';
		die();
	}
	
	$i=0;
	foreach ($srvlist as $key)
	{
		$i++;
		
		$r=__SEND_TOUCH_CMD__($key);
		if ($r == 'ok' || $r=='ok_root')
		{
			if ($r=='ok')
				echo "&nbsp;&nbsp;　&nbsp;&nbsp;进程 {$i}: 端口 <font color=\"blue\">{$key}</font> 状态 ";
			else  echo "&nbsp;[根]&nbsp;进程 {$i}: 端口 <font color=\"blue\">{$key}</font> 状态 ";
			echo '<font color="green">正常</font>';
		}
		else
		{
			echo "&nbsp;&nbsp;　&nbsp;&nbsp;进程 {$i}: 端口 <font color=\"blue\">{$key}</font> 状态 ";
			echo '<font color="red">异常</font> 错误信息：'.$r;
		}
		echo '&nbsp;&nbsp;<a href="daemonmng.php?action=stop'.$key.'"><font color="red">[关闭]</font></a>';
		echo '<br>';
	}
	
	if ($ff)
	{
		echo '<br>点击下面的按钮可以杀死所有进程，<br>如果处于Daemon模式（或于未来重新开启Daemon模式时），<br>一个新的根进程将被自动重新启动。<br>
		（有几秒的延迟，请等待几秒然后刷新页面）<br>
		<a href="daemonmng.php?action=restart" style="text-decoration: none"><span><font color="red">[重启所有进程]</font></span></a><br>';
	}
	
	echo '<br>点击下面的按钮可以杀死所有进程。<br>
	<a href="daemonmng.php?action=stopall" style="text-decoration: none"><span><font color="red">[杀死所有进程]</font></span></a><br>';
}
else  echo '无法打开gamedata/tmp/server目录。';

?>