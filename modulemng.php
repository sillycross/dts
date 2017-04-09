<?php
header('Content-Type: text/HTML; charset=utf-8');
header( 'Content-Encoding: none; ' );

define('IN_MODULEMNG', TRUE);

define('IN_GAME', TRUE);
define('GAME_ROOT', dirname(__FILE__).'/');
error_reporting(0);
$magic_quotes_gpc = get_magic_quotes_gpc();

require GAME_ROOT.'./include/global.func.php';
check_authority();
	
require GAME_ROOT.'./include/modulemng/modulemng.config.php';
require GAME_ROOT.'./include/modulemng/modulemng.func.php';
require GAME_ROOT.'./include/modules/modules.func.php';

register_shutdown_function('shutDownFunction');

function shutDownFunction() { 
	$error = error_get_last();

	global $faillog;
	if ($faillog!='')
	{
		echo $faillog;
		echo '<br><a href="modulemng.php?mode=edit" style="text-decoration: none"><span><font color="blue">[返回编辑模式]</font></span></a><br>';   
		die();
	}
}

if (file_exists(GAME_ROOT.'./gamedata/modules.list.pass.php'))
{
	unlink(GAME_ROOT.'./gamedata/modules.list.pass.php');
}

if (!file_exists(GAME_ROOT.'./gamedata/modules.list.temp.php'))
{
	copy(GAME_ROOT.'./gamedata/modules.list.php',GAME_ROOT.'./gamedata/modules.list.temp.php');
}

if ($_GET['mode']=='edit' || $_GET['action']=='reset')
{
	if ($_GET['action']=='reset') 
	{
		copy(GAME_ROOT.'./gamedata/modules.list.php',GAME_ROOT.'./gamedata/modules.list.temp.php');
		$flag=1;
	}
	echo '<br><span><font size=5>模块管理系统<font size=4 color="red"> 编辑模式</font></font></span><br><br>';
	if ($flag==1) 
	{
		echo "<span><font color=\"green\">成功重置到原先状态。</font></span><br><br>";
	}
	echo '<a href="modulemng.php?action=reset" style="text-decoration: none"><span><font color="red">[重置]</font></span></a>&nbsp;&nbsp;
	<a href="modulemng.php?action=save" style="text-decoration: none"><span><font color="green">[保存]</font></span></a>&nbsp;&nbsp;
	<a href="modulemng.php" style="text-decoration: none"><span><font color="blue">[返回]</font></span></a><br><br>';   
	printmodtable(GAME_ROOT.'./gamedata/modules.list.temp.php');
	die();
}
else  if ($_GET['action']=='enable')
{
	$sid=(int)$_GET['sid'];
	$file=GAME_ROOT.'./gamedata/modules.list.temp.php';
	$content=openfile($file);
	$in=sizeof($content); $n=0;
	if (0<=$sid && $sid<$in)
	{
		$a = explode(',',$content[$sid]);
		$a[2]=1;
		$content[$sid]=implode(',',$a);
		writeover_array($file,$content);
	}
	echo '<br><span><font size=5>模块管理系统<font size=4 color="red"> 编辑模式</font></font></span><br><br>';
	echo '<a href="modulemng.php?action=reset" style="text-decoration: none"><span><font color="red">[重置]</font></span></a>&nbsp;&nbsp;
	<a href="modulemng.php?action=save" style="text-decoration: none"><span><font color="green">[保存]</font></span></a>&nbsp;&nbsp;
	<a href="modulemng.php" style="text-decoration: none"><span><font color="blue">[返回]</font></span></a><br><br>';   
	printmodtable(GAME_ROOT.'./gamedata/modules.list.temp.php');
	echo '<br>点击保存后修改才能生效！<br><br>';
	die();
}
else  if ($_GET['action']=='disable')
{
	$sid=(int)$_GET['sid'];
	$file=GAME_ROOT.'./gamedata/modules.list.temp.php';
	$content=openfile($file);
	$in=sizeof($content); $n=0;
	if (0<=$sid && $sid<$in)
	{
		$a = explode(',',$content[$sid]);
		$a[2]=0;
		$content[$sid]=implode(',',$a);
		writeover_array($file,$content);
	}
	echo '<br><span><font size=5>模块管理系统<font size=4 color="red"> 编辑模式</font></font></span><br><br>';
	echo '<a href="modulemng.php?action=reset" style="text-decoration: none"><span><font color="red">[重置]</font></span></a>&nbsp;&nbsp;
	<a href="modulemng.php?action=save" style="text-decoration: none"><span><font color="green">[保存]</font></span></a>&nbsp;&nbsp;
	<a href="modulemng.php" style="text-decoration: none"><span><font color="blue">[返回]</font></span></a><br><br>';   
	printmodtable(GAME_ROOT.'./gamedata/modules.list.temp.php');
	echo '<br>点击保存后修改才能生效！<br><br>';
	die();
}
else  if ($_GET['action']=='save')
{
	$res=module_validity_check(GAME_ROOT.'./gamedata/modules.list.temp.php');
	echo '<br><span><font size=5>模块管理系统</font></span><br><br>';
	if ($res === 1)
	{
		echo "<span>没有发现致命错误，请阅读以下日志，如没有问题，请点击“<font color=\"red\">应用更改</font>”按钮令更改生效。</span><br><br>";
		echo "<span><font color=\"green\">没有发现问题。</font></span><br><br>";
		echo "<span>应用更改可能会花费几秒钟，请耐心等待。</span><br><br>";
		echo '<a href="modulemng_activate.php" style="text-decoration: none"><span><font color="red">[应用更改]</font></span></a>&nbsp;&nbsp;<a href="modulemng.php?mode=edit" style="text-decoration: none"><span><font color="blue">[返回编辑模式]</font></span></a><br>';   
		copy(GAME_ROOT.'./gamedata/modules.list.php',GAME_ROOT.'./gamedata/modules.list.temp.php');
		die();
	}
	else
	{
		echo $res;
		echo '<br><a href="modulemng.php?mode=edit" style="text-decoration: none"><span><font color="blue">[返回编辑模式]</font></span></a><br>';   
            die();
      }
}
else  if ($_GET['moduleaction']=='activate')
{
	$res=module_change_apply();
}
else  if ($_GET['action']=='remove')
{
	$sid=(int)$_GET['sid'];
	$file=GAME_ROOT.'./gamedata/modules.list.php';
	$content=openfile($file);
	$in=sizeof($content); $n=0;
	if (0<=$sid && $sid<$in)
	{
		$a = explode(',',$content[$sid]);
		if ($a[2]==0)
		{
			$content2=Array();
			for ($i=0; $i<$in; $i++) if ($i!=$sid) array_push($content2,$content[$i]);
			$content=$content2;
			writeover_array($file,$content);
			copy(GAME_ROOT.'./gamedata/modules.list.php',GAME_ROOT.'./gamedata/modules.list.temp.php');
		}
	}
	header('Location: modulemng.php');
	die();
}
else  if ($_POST['action']=='add')
{
	$file=GAME_ROOT.'./gamedata/modules.list.php';
	$content=openfile($file);
	if ($_POST['modpath'][strlen($_POST['modpath'])-1]!='/') $_POST['modpath'].='/';
	$s=$_POST['modname'].','.$_POST['modpath'].',0';
	$in=sizeof($content); $content[$in]=$s;
	writeover_array($file,$content);
	copy(GAME_ROOT.'./gamedata/modules.list.php',GAME_ROOT.'./gamedata/modules.list.temp.php');
	header('Location: modulemng.php');
}
else
{
	echo '<br><span><font size=5>模块管理系统</font></span><br><br>';
	echo '<a href="modulemng.php?mode=edit" style="text-decoration: none"><span><font color="red">[进入编辑模式]</font></span></a><br><br>';   
	printmodtable(GAME_ROOT.'./gamedata/modules.list.php',1);
	echo '<br><br><font color="green">添加模块:</font><br>
	<form method="post" name="addmodule" action="modulemng.php">
	<input name="action" value="add" type="hidden">
	模块名<input name="modname" size="20" maxlength="100" value="" type="text">
	路径<input name="modpath" size="20" maxlength="100" value="" type="text">
	<input name="enter" value="添加" type="submit">
	</form>模块名务必不要打错，这个系统是不检查的。<br>路径基准位置是./include/modules<br>
	例: 添加位于./include/modules/core/sys的sys模块，应填写路径“core/sys”和模块名“sys”<br>';

	die();
}


?>