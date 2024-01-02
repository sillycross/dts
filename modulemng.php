<?php
header('Content-Type: text/HTML; charset=utf-8');

define('IN_MODULEMNG', TRUE);

define('IN_GAME', TRUE);
define('GAME_ROOT', dirname(__FILE__).'/');
error_reporting(0);
$magic_quotes_gpc = false;//get_magic_quotes_gpc();

require GAME_ROOT.'./include/global.func.php';
check_authority();
	
include GAME_ROOT.'./include/modulemng/modulemng.config.php';
require GAME_ROOT.'./include/modulemng/modulemng.func.php';
require GAME_ROOT.'./include/modules/modules.func.php';

register_shutdown_function('shutDownFunction');

function shutDownFunction() { 
	$error = error_get_last();

	global $faillog;
	if ($faillog!='')
	{
		echo $faillog;
		echo '<br>或者因为如下原因：';
		var_dump($error);
		echo '<br><a href="modulemng.php?mode=edit" style="text-decoration: none"><span><font color="blue">[返回编辑模式]</font></span></a><br>';   
		die();
	}
}

if (file_exists(GAME_ROOT.'./gamedata/modules.list.pass.php'))
{
	unlink(GAME_ROOT.'./gamedata/modules.list.pass.php');
}

if (!file_exists(GAME_ROOT.'./gamedata/modules.list.temp.php') || time() - filemtime(GAME_ROOT.'./gamedata/modules.list.temp.php') > 86400)//创建时间超过1天的temp文件会废弃
{
	copy(GAME_ROOT.'./gamedata/modules.list.php',GAME_ROOT.'./gamedata/modules.list.temp.php');
}
$page = 'index';
if($_GET['mode']=='advmng')
{
	//$edfmt = Array('___MOD_CODE_ADV1'=>'int','___MOD_CODE_ADV2'=>'int','___MOD_CODE_ADV3'=>'int','___MOD_SRV'=>'int');
	if($_GET['action'] == 'turn_on') $edvar = 1;
	elseif($_GET['action'] == 'turn_off') $edvar = 0;
	if($_GET['type'] == 10) {
		if ($edvar) $edcfg = array('___MOD_CODE_ADV1' => 1);
		else $edcfg = array('___MOD_CODE_ADV1' => 0, '___MOD_CODE_ADV2' => 0, '___MOD_CODE_ADV3' => 0, '___MOD_SRV' => 0);
	} elseif($_GET['type'] == 20) {
		if ($edvar) $edcfg = array('___MOD_CODE_ADV1' => 1, '___MOD_CODE_ADV2' => 1);
		else $edcfg = array('___MOD_CODE_ADV2' => 0, '___MOD_CODE_ADV3' => 0, '___MOD_SRV' => 0);
	} elseif($_GET['type'] == 25) {
		if ($edvar) $edcfg = array('___MOD_CODE_ADV1' => 1, '___MOD_CODE_ADV2' => 1, '___MOD_CODE_COMBINE' => 1);
		else $edcfg = array('___MOD_CODE_COMBINE' => 0);
	} elseif($_GET['type'] == 30) {
		if ($edvar) $edcfg = array('___MOD_CODE_ADV1' => 1, '___MOD_CODE_ADV2' => 1, '___MOD_CODE_ADV3' => 1);
		else $edcfg = array('___MOD_CODE_ADV3' => 0);
	} elseif($_GET['type'] == 40) {
		if ($edvar) $edcfg = array('___MOD_CODE_ADV1' => 1, '___MOD_CODE_ADV2' => 1, '___MOD_SRV' => 1);
		else $edcfg = array('___MOD_SRV' => 0);
	}
	
	if(isset($edcfg) && isset($edvar)){
		$mf=GAME_ROOT.'./include/modulemng/modulemng.config.php';
		$config_cont = file_get_contents($mf);
		foreach($edcfg as $ek => $ev){
			$config_cont = preg_replace("/[$]{$ek}\s*\=\s*-?[0-9]+;/is", "\${$ek} = {$ev};", $config_cont);
		}
		file_put_contents($mf,$config_cont);
	}
	include GAME_ROOT.'./include/modulemng/modulemng.config.php';
}
if ($_GET['mode']=='edit' || $_GET['action']=='reset')
{
	$page = 'edit';
	if ($_GET['action']=='reset') 
	{
		copy(GAME_ROOT.'./gamedata/modules.list.php',GAME_ROOT.'./gamedata/modules.list.temp.php');
		$res = "<span><font color=\"green\">成功重置到原先状态。</font></span><br><br>";
	}
}
elseif ($_GET['mode']=='autoscan') //自动搜索新增模块功能
{
	//首先维护一个已有模块名的列表
	$file=GAME_ROOT.'./gamedata/modules.list.php';
	$content=openfile($file);
	$list = $add_list = Array();
	foreach($content as $v) {
		$list[] = explode(',',$v)[0];
	}
	//遍历modules文件夹下的所有文件，依次判定是否有更新时间较新且不在已有列表里的文件
	$listmtime = filemtime($file);
	$rootdir = GAME_ROOT.'./include/modules';
	get_all_filenames($rootdir, $all_filenames);
	$fi = 0;
	foreach($all_filenames as $v) {
		$filename = array_pop(explode('/',$v));
		if('module.inc.php' == $filename) {
			$fi ++;
			if(filemtime($v) > $listmtime) {
				$tmp_cont = file_get_contents($v);
				preg_match("/namespace\s+?(\S+)\s*?\{/s", $tmp_cont, $matches);
				if(!empty($matches[1])) {
					$modulename = $matches[1];
					if(!in_array($modulename, $list)) {
						$fj ++;
						$add_list[] = $modulename;
						$in=sizeof($content);
						$content[$in] = $modulename.','.substr($v, strlen($rootdir)+1, strlen($v)-strlen($rootdir)-strlen($filename)-1).',0';
					}
				}
			}
		}
	}
	//如果新增了模块，重写模块列表
	if(!empty($add_list)) {
		writeover_array($file,$content);
		copy(GAME_ROOT.'./gamedata/modules.list.php',GAME_ROOT.'./gamedata/modules.list.temp.php');
	}
	$res = '遍历了'.$fi.'个模块，检测到'.sizeof($add_list).'个新增模块。';
	if(!empty($add_list)) $res .='<br>新增模块有：'.implode(' ', $add_list).'。<br>新增模块默认关闭，请自行开启。<br><br>';
	$page = 'edit';
}
elseif ($_GET['action']=='enable')
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
	$page = 'edit';
	$res = '<br>点击保存后修改才能生效！<br><br>';
}
elseif ($_GET['action']=='disable')
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
	$page = 'edit';
	$res = '<br>点击保存后修改才能生效！<br><br>';
}
elseif ($_GET['action']=='save')
{
	$res=module_validity_check(GAME_ROOT.'./gamedata/modules.list.temp.php');
	echo '<br><span><font size=5>模块管理系统</font></span><br><br>';
	$page = 'checking';
	if ($res === 1)
	{
		echo "<span>没有发现致命错误，请阅读以下日志，如没有问题，请点击“<font color=\"red\">应用更改</font>”按钮令更改生效。</span><br><br>";
		echo "<span><font color=\"green\">没有发现问题。</font></span><br><br>";
		echo "<span>应用更改可能会花费几秒钟，请耐心等待。</span><br><br>";
		if(isset($_GET['mode']) && 'quick'==$_GET['mode']) $href = 'modulemng_activate.php?mode=quick';
		else $href = 'modulemng_activate.php';
		echo '<a href="'.$href.'" style="text-decoration: none"><span><font color="red">[应用更改]</font></span></a>&nbsp;&nbsp;<a href="modulemng.php?mode=edit" style="text-decoration: none"><span><font color="blue">[返回编辑模式]</font></span></a><br>';   
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
elseif ($_GET['moduleaction']=='activate')
{
	$page = 'activating';
	$res=module_change_apply();
}
elseif ($_GET['action']=='remove')
{
	$page = 'edit';
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
}
elseif ($_POST['action']=='add')
{
	$file=GAME_ROOT.'./gamedata/modules.list.php';
	$content=openfile($file);
	if ($_POST['modpath'][strlen($_POST['modpath'])-1]!='/') $_POST['modpath'].='/';
	$s=$_POST['modname'].','.$_POST['modpath'].',0';
	$in=sizeof($content); $content[$in]=$s;
	writeover_array($file,$content);
	copy(GAME_ROOT.'./gamedata/modules.list.php',GAME_ROOT.'./gamedata/modules.list.temp.php');
	$page = 'edit';
}
if($page == 'index') {
	echo '<br><span><font size=5>模块管理系统</font></span><br><br>';
	echo show_adv_state().'<br>';
	echo '<a href="modulemng.php?mode=autoscan" style="text-decoration: none"><span><font color="red">[自动检测新增模块]</font></span></a> 会自动检测新增的模块。<br>';
	echo '<a href="modulemng.php?mode=edit" style="text-decoration: none"><span><font color="red">[进入编辑模式]</font></span></a> 添加或修改模块可用性。<br>';
	echo '<a href="modulemng.php?action=save" style="text-decoration: none"><span><font color="green">[重设代码缓存]</font></span></a> 整体重设模块结构和adv模式代码。<br>';
	echo '<a href="modulemng.php?action=save&mode=quick" style="text-decoration: none"><span><font color="green">[重设代码缓存（快速）]</font></span></a> 只重设有改动的代码函数。新增模块、函数，或模块依赖顺序有调整时切勿使用，建议只在微调config文件时使用。<br><br>';  
	//printmodtable(GAME_ROOT.'./gamedata/modules.list.php',1);
	
}elseif($page == 'edit'){
	echo '<br><span><font size=5>模块管理系统<font size=4 color="red"> 编辑模式</font></font></span><br><br>';
	if(isset($res)) echo $res;
	echo '<a href="modulemng.php?action=reset" style="text-decoration: none"><span><font color="red">[重置]</font></span></a>&nbsp;&nbsp;
	<a href="modulemng.php?action=save" style="text-decoration: none"><span><font color="green">[保存]</font></span></a>&nbsp;&nbsp;
	<a href="modulemng.php" style="text-decoration: none"><span><font color="blue">[返回]</font></span></a><br><br>';
	echo '<font color="green">添加模块:</font><br>
	<form method="post" name="addmodule" action="modulemng.php">
	<input name="action" value="add" type="hidden">
	模块名<input name="modname" size="20" maxlength="100" value="" type="text">
	路径<input name="modpath" size="20" maxlength="100" value="" type="text">
	<input name="enter" value="添加" type="submit">
	</form>模块名务必不要打错，这个系统是不检查的。<br>路径基准位置是./include/modules<br>
	例: 添加位于./include/modules/core/sys的sys模块，应填写路径“core/sys”和模块名“sys”<br><br>';
	printmodtable(GAME_ROOT.'./gamedata/modules.list.temp.php');
}


/* End of file modulemng.php */
/* Location: /modulemng.php */