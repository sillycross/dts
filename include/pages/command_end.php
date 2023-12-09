<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

eval(import_module('sys','player','map','input'));
if(!$cuser||!$cpass) { 
	gexit($_ERROR['no_login'],__file__,__line__);
	return;
} 
$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$cuser' AND type = 0");
if(!$db->num_rows($result)) { 
	echo 'redirect:index.php';
	return;
}

$pdata = $db->fetch_array($result);

$udata = udata_check();
if($pdata['pass'] != $udata['password'])
	$db->query("UPDATE {$tablepre}players SET pass='{$udata['password']}' WHERE name='$cuser'");

\player\load_playerdata($pdata);
\player\init_playerdata();
extract($pdata);

if($hp<=0 || $state>=10) {
	$dtime = date("Y年m月d日H时i分s秒",$endtime);
	if($bid) {
		$result = $db->query("SELECT name FROM {$tablepre}players WHERE pid='$bid'");
		if($db->num_rows($result)) { $kname = $db->result($result,0); }
	}
}

$noticelog = '';

if(isset($ecommand) && 'nextgamevars' == $ecommand){
	if(!defined('MOD_SET_GAMETYPE')){
		$noticelog = '缺少必要模块！<br>';
	}elseif($groomid){
		$noticelog = '只有标准房才能修改下一局模式！<br>';
	}elseif(!in_array($winmode, array(2,3,5,7)) || $cuser != $winner || !$winner_flag){
		$noticelog = '你不是获胜者，不能修改下一局模式！<br>';
	}elseif(!\set_gametype\check_gametype_set_valid($ngametype)){
		$noticelog = '不允许把下一局游戏设为该模式！<br>';
	}else{
		$ngamevars = array('next_gametype' => (int)$ngametype);
		$notice = \sys\user_set_gamevars($ngamevars)['notice'];
		foreach($notice as $ns){
			$noticelog .= $ns.'<br>';
		}
	}
	$next_gamevars_display = \sys\user_display_gamevars_setting();
	$gamedata = array('innerHTML' => array('nextgamevars' => $next_gamevars_display));
	$jgamedata=gencode($gamedata);
	ob_clean();
	echo $jgamedata;
}else{
	$next_gamevars_display = \sys\user_display_gamevars_setting();
	include template('end');
}

/* End of file command_end.php */
/* Location: /include/pages/command_end.php */