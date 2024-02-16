<?php
header('Content-Type: text/HTML; charset=utf-8');
define('IN_GAME', TRUE);
define('GAME_ROOT', dirname(__FILE__).'/');
define('CURSCRIPT', 'daemonmng');
error_reporting(0);
$magic_quotes_gpc = false;//get_magic_quotes_gpc();
require GAME_ROOT.'./include/global.func.php';
require GAME_ROOT.'./include/modulemng/modulemng.config.php';
require GAME_ROOT.'./include/socket.func.php';

$action = !empty($_POST['action']) ? $_POST['action'] : $_GET['action'];

//游戏内也有调用需求
if(empty($_POST['in_game_pass']) || $_POST['in_game_pass'] != substr(base64_encode($___MOD_CONN_PASSWD),0,6)){
	check_authority();
}
$___TEMP_runmode = 'Admin';
$___TEMP_CONN_PORT = -1;

if (isset($action) && $action=='restart')
{
	__STOP_ALL_SERVER__();
	curl_new_server($___MOD_CONN_PASSWD,1);
	//touch(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
	__SOCKET_LOG__("已停止所有进程，并请求脚本启动新的驻留进程。");
	
	header('Location: daemonmng.php');
}

if (isset($action) && $action=='stopall')
{
	__STOP_ALL_SERVER__();
	header('Location: daemonmng.php');
}

if (isset($action) && strpos($action,'stop')===0)
{
	$sid = substr($action,4);
	if(is_numeric($sid)){
		__STOP_SINGLE_SERVER__($sid);
	}
	header('Location: daemonmng.php');
}

echo '<br><span><font size=5>Daemon管理系统</font></span><br><br>';

if (!$___MOD_SRV)
	echo '<font color="red">目前不处于Daemon模式下。</font><br><br>';

echo '自动启动驻留进程： ';
echo $___MOD_SRV_AUTO ? '<font color="green">是</font>' : '<font color="red">否</font>';
echo '<br>';

echo '外部触发脚本状况： ';

$t1=(int)file_get_contents(GAME_ROOT.'./gamedata/tmp/server/scriptalive.txt');
$t2=(int)filemtime(GAME_ROOT.'./gamedata/tmp/server/scriptalive.txt');
$t=max($t1,$t2);
$running = 1;
$ff=1;
if (time()-$t<=10)
{
	echo '<font color="green">正在运行</font><br>';
}
else 
{
	echo '<font color="red">不在运行</font><br>';
	$running = 0;
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
	
	if (count($srvlist)==0 && $___MOD_SRV)
	{
		echo '点击启动一个新的根进程。<br>
		（有几秒的延迟，请等待几秒然后刷新页面）<br><a href="daemonmng.php?action=start" style="text-decoration: none"><span>
		<font color="green">[启动]</font></span></a><br><br>';
	}
	
	if (isset($action) && $action=='start' && count($srvlist)==0)
	{
		curl_new_server($___MOD_CONN_PASSWD,1);
		//touch(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
		__SOCKET_LOG__("已请求脚本启动一台新的驻留进程。");
		header('Location: daemonmng.php');
	}
	
	if(!$running && (!$___MOD_SRV_AUTO || !count($srvlist))) {
		echo '<font color="blue">请手动启动根进程，或者从服务器shell中启动./acdts-daemonctl.sh（Linux）或者启动./acdts-daemonctl.bat（WIN）</font><br>';
		$ff = 0;
	}
	
	
	
	if ($ff && count($srvlist)==0 && $___MOD_SRV)
	{
		echo '点击启动一个新的根进程。<br>
		（有几秒的延迟，请等待几秒然后刷新页面）<br><a href="daemonmng.php?action=start" style="text-decoration: none"><span>
		<font color="green">[启动]</font></span></a>';
		die();
	}
	
	$now = time();
	$i=0;
	foreach ($srvlist as $key)
	{
		$i++;
		$keyadrs = GAME_ROOT.'./gamedata/tmp/server/'.$key;
		
		$r=__SEND_TOUCH_CMD__($key);
		if ($r == 'ok' || $r=='ok_root')
		{
			if ($r=='ok')
				echo "&nbsp;&nbsp;　&nbsp;&nbsp;进程 {$i}: 端口 <font color=\"blue\">{$key}</font> 状态 ";
			else echo "&nbsp;[根]&nbsp;进程 {$i}: 端口 <font color=\"blue\">{$key}</font> 状态 ";
			if(file_exists($keyadrs.'/busy')) echo '<font color="orange">正常&忙碌</font>';
			else echo '<font color="green">正常</font>';
			list($worknum, $memorysize) = explode(',', file_get_contents($keyadrs.'/worknum'));
			echo '&nbsp;分均请求数：'.ceil((int)$worknum / (max(1, $now - filemtime($keyadrs.'/start_time')) / 60) * 1000) / 1000;
			echo '&nbsp;内存占用：'.ceil((int)$memorysize/1024/1024).'MB';
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
		echo '<br>点击下面的按钮可以杀死所有进程，然后启动一个新的根进程。<br>
		（有几秒的延迟，请等待几秒然后刷新页面）<br>
		<a href="daemonmng.php?action=restart" style="text-decoration: none"><span><font color="red">[重启所有进程]</font></span></a><br>';
	}
	
	echo '<br>点击下面的按钮可以杀死所有进程。<br>
	<a href="daemonmng.php?action=stopall" style="text-decoration: none"><span><font color="red">[杀死所有进程]</font></span></a><br>';
}
else  echo '无法打开gamedata/tmp/server目录。';

?>