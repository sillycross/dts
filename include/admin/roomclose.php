<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

$antiAFKintv = 3;
if($command == 'kill'){
	closeroom($roomtoclose);
}

function closeroom($rid=1){
	global $now,$db,$gtablepre,$cmd_info;
	$cmd_info = '';
	$result = $db->query("SELECT status FROM {$gtablepre}rooms WHERE roomid = '$rid'");
	if(!$db->num_rows($result)) 
	{
		$cmd_info .= '房间不存在！<br>';
		return;
	}
	$zz=$db->fetch_array($result);
	if ($zz['status']==0)
	{
		$cmd_info .= '房间不存在！<br>';
		return;
	}
	if ($zz['status']==2)
	{
		$cmd_info .= '不能关闭游戏中的房间！<br>';
		return;
	}
	if (!file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$rid.'.txt')) 
	{
		$cmd_info .= '房间不存在！<br>';
		return;
	}
	$db->query("UPDATE {$gtablepre}rooms SET status = 0 WHERE roomid='$rid'");
	unlink(GAME_ROOT.'./gamedata/tmp/rooms/'.$rid.'.txt');
	$cmd_info .= $rid.'号房间已关闭。<br>';
	return;
}

//kill_all_AFKer(10);
echo <<<EOT
<form method="post" name="roomclose" onsubmit="admin.php">
<input type="hidden" name="mode" value="roomclose">
<input type="hidden" name="command" value="kill">
关闭<input type="text" name="roomtoclose" value="1" size="4" maxlength="4">号房间。<br>
<input type="submit" value="提交"><br>
</form>
EOT;

?>