<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

////////////////////////////////////////////////////////////////////////////
//////////////////////////游戏前玩家信息检查///////////////////////////////////
////////////////////////////////////////////////////////////////////////////

if(!$cuser||!$cpass) 
{ 
	gexit($_ERROR['no_login'],__file__,__line__); 
	return;
} 

$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");

if(!$db->num_rows($result)) 
{ 
	$gamedata['url'] = 'valid.php';
	ob_clean();
	$jgamedata = gencode($gamedata);
	echo $jgamedata;
	return;
}

$pdata = $db->fetch_array($result);

//判断是否密码错误
$udata = udata_check();
if($pdata['pass'] != $udata['password'])
	$db->query("UPDATE {$tablepre}players SET pass='{$udata['password']}' WHERE name='$cuser'");
	
if($gamestate == 0) {
	$gamedata['url'] = 'end.php';
	ob_clean();
	$jgamedata = gencode($gamedata);
	echo $jgamedata;
	return;
}

////////////////////////////////////////////////////////////////////////////
////////////////////////////正式进入游戏//////////////////////////////////////
///////////从这里开始，不应该有任何错误信息返回，应该只返回$jgamedata///////////////
////////////////////////////////////////////////////////////////////////////
	
//初始化各变量
$log = $cmd = $main = '';

//$timecost = get_script_runtime($pagestartime);
//$timecostlis = (string)$timecost;
$pagestartimez=microtime(true); 

\player\load_playerdata(\player\fetch_playerdata($cuser));

$gamedata = array(
	'innerHTML' => array(),
	'value' => array(),
	'src' => array()
);
\player\init_playerdata();

\player\pre_act();
if ($hp > 0 && $state <= 3) \player\act();
\player\post_act();

if($endtime < 0) $endtime = 0;//$endtime若为负数则变为0，某些特殊功能会用到
else $endtime = $now;

if ($___MOD_SRV)
{
	$timecost = microtime(true) - $pagestartimez;
	$timecost = sprintf("%.4f",$timecost); 
	if ($timecost >= 0.05) __SOCKET_WARNLOG__("本次操作同步问题触发窗口达到了 $timecost 秒。游戏状态：".$gamestate."；操作信息：".(!empty($command) ? $command : '-')."；操作者：".$cuser);
}

//$timecost = get_script_runtime($pagestartime);
//$timecostlis .= '/'.$timecost;

//显示指令执行结果
\player\prepare_response_content();

\player\parse_interface_gameinfo();
\player\parse_interface_profile();

//如果是刷新页面，自动重生成一次右侧命令界面（为了录像），其余全部不再判定
if('enter' == $command) {
	$gamedata['innerHTML']['cmd_interface'] = dump_template('cmd_interface');
}

if($hp <= 0) {
	$dtime = date("Y年m月d日H时i分s秒",$endtime);
	$kname='';
	if($bid) {
		$result = $db->query("SELECT name FROM {$tablepre}players WHERE pid='$bid'");
		if($db->num_rows($result)) { $kname = $db->result($result,0); }
	}
	$gamedata['innerHTML']['cmd'] = dump_template('death');
	$mode = 'death';
} elseif($cmd){
	$gamedata['innerHTML']['cmd'] = $cmd;
} elseif($itms0){
	$gamedata['innerHTML']['cmd'] = dump_template(MOD_ITEMMAIN_ITEMFIND);
} elseif($state == 1 || $state == 2 || $state ==3) {
	$gamedata['innerHTML']['cmd'] = dump_template('rest');
} elseif(!$cmd) {
	if($mode != 'command' && $mode && (file_exists($mode.'.htm') || file_exists(GAME_ROOT.TPLDIR.'/'.$mode.'.htm'))) {
		$gamedata['innerHTML']['cmd'] = dump_template($mode);
	} elseif(defined('MOD_TUTORIAL') && $gametype == 17){
		$gamedata['innerHTML']['cmd'] = dump_template(MOD_TUTORIAL_TUTORIAL);
	}	else {
		//$mode = 'command';
		$gamedata['innerHTML']['cmd'] = dump_template('command');
		//给#log窗格加了最小高度，但又需要让不存在$log的页面正常显示，于是让js自动隐藏空的#log窗格，那么这里就得输出一个东西
		if(empty($uip['innerHTML']['log'])) $uip['innerHTML']['log'] = ' ';
	}
} else {
	$log .= '游戏流程故障，请联系管理员<br>';
}

if(isset($url)){$gamedata['url'] = $url;}
if(!empty($uip['timing'])) {$gamedata['timing'] = $uip['timing'];}
if(!empty($uip['display'])) {$gamedata['display'] = $uip['display'];}
if(!empty($uip['effect'])) {$gamedata['effect'] = $uip['effect'];}
if(!empty($uip['innerHTML'])) {$gamedata['innerHTML'] = array_merge($gamedata['innerHTML'], $uip['innerHTML']);}
if(!empty($uip['value'])) {$gamedata['value'] = array_merge($gamedata['value'], $uip['value']);}
if(!empty($uip['src'])) {$gamedata['src'] = array_merge($gamedata['src'], $uip['src']);}

//$gamedata['innerHTML']['pls'] = $plsinfo[$pls];
//if ($gametype!=2) $gamedata['innerHTML']['anum'] = $alivenum; else $gamedata['innerHTML']['anum'] = $validnum;
$gamedata['innerHTML']['main'] = dump_template($main ? $main : 'profile');

if(isset($error)){$gamedata['innerHTML']['error'] = $error;}


//测试
//函数调用计数
//$log.="<span class=\"grey b\">Fn call count: ".count($___TEMP_CALLS_COUNT)."</span><br>"; 
/*
$timecost = get_script_runtime($pagestartime);
if (isset($timecost2)) $log.="<span class=\"grey b\">模块加载时间: $timecost2 秒</span><br>"; 
if ($___MOD_SRV)
{
	$log.="<span class=\"grey b\">核心运行时间: $timecost 秒</span><br>"; 
	$log.="<span class=\"grey b\">页面运行时间: _____PAGE_RUNNING_TIME_____ 秒</span>"; //这个好像显示不了
}
else  $log.="<span class=\"grey b\">页面运行时间: $timecost 秒$ts</span>"; 
*/

//$timecost = get_script_runtime($pagestartime);
//$timecostlis .= '/'.$timecost;

//$jgamedata = str_replace('_____CORE_RUNNING_TIME_____',$timecostlis,$jgamedata);

$jgamedata=gencode($gamedata);
ob_clean();
echo $jgamedata;

\player\update_sdata();
\player\player_save($sdata);

/* End of file command_act.php */
/* Location: /include/pages/command_act.php */