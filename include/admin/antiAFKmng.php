<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

$antiAFKintv = 3;
if($command == 'kill'){
	kill_all_AFKer($timelimit);
}

function kill_all_AFKer($timelimit=1){
	global $now,$db,$tablepre,$antiAFKertime,$alivenum,$deathnum,$antiAFKintv,$cmd_info;
	$cmd_info = '';
	if (!is_numeric($timelimit)){
		$cmd_info .= '时间间隔错误！<br>';
		return;
	} elseif($timelimit < $antiAFKintv) {
		$cmd_info .= '时间间隔太短，可能波及正常玩家。';
		return;
	}
	$cmd_info .= '将杀死： '.$timelimit.' 分钟内没有任何行动的玩家。<br>';
	$timelimit *= 60;
	
	$deadline=$now-$timelimit;
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE type=0 AND endtime < '$deadline' AND hp>'0' AND state<'10'");
	while($al = $db->fetch_array($result)) {
		$afkerlist[$al['pid']]=Array('name' => $al['name'] ,'pls' => $al['pls']);
	}

	if(!$afkerlist){$cmd_info .= '没有符合条件的角色。';return;}
	foreach($afkerlist as $kid => $kcontent){
		$db->query("UPDATE {$tablepre}players SET hp='0',state='32' WHERE pid='$kid' AND type='0' AND hp>'0' AND state<'10'");
		if($db->affected_rows()){
			adminlog('killafker',$kid);
			$cmd_info .= '角色 '.$kcontent['name'].' 被杀死。<br>';
			addnews($now,'death32',$kcontent['name'],'',$kcontent['pls']);
			$alivenum--;
			$deathnum++;
			
		} else {
			$cmd_info .= '无法杀死角色 '.$kcontent['name'].' 。<br>';
		}
	}
	save_gameinfo();
	return;
}

//kill_all_AFKer(10);
echo <<<EOT
<form method="post" name="antiAFKmng" onsubmit="admin.php">
<input type="hidden" name="mode" value="antiAFKmng">
<input type="hidden" name="command" value="kill">
杀死<input type="text" name="timelimit" value="$antiAFKintv" size="4" maxlength="4">分钟内没有行动的玩家。<br>
<input type="submit" value="挂机党都去死吧！"><br>
</form>
EOT;

?>