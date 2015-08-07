<?php

define('CURSCRIPT', 'alive');

require './include/common.inc.php';
//extract(gkillquotes($_POST));
//unset($_GET);

if(!isset($alivemode) || $alivemode == 'last'){
	$query = $db->query("SELECT name,gd,sNo,icon,lvl,exp,killnum,teamID FROM {$tablepre}players WHERE type=0 AND hp>0 order by killnum desc, lvl desc limit $alivelimit");
}elseif($alivemode == 'all'){
	$query = $db->query("SELECT name,gd,sNo,icon,lvl,exp,killnum,teamID FROM {$tablepre}players WHERE type=0 AND hp>0 order by killnum desc, lvl desc");
}else{
	echo 'error';
	exit();
}
//if($alivemode == 'all') {
//	$query = $db->query("SELECT name,gd,sNo,icon,lvl,exp,killnum,teamID FROM {$tablepre}players WHERE type=0 AND hp>0 order by killnum desc, lvl desc");
//} else {
//	$query = $db->query("SELECT name,gd,sNo,icon,lvl,exp,killnum,teamID FROM {$tablepre}players WHERE type=0 AND hp>0 order by killnum desc, lvl desc limit $alivelimit");
//}
while($playerdata = $db->fetch_array($query)) {
	$playerdata['iconImg'] = "{$playerdata['gd']}_{$playerdata['icon']}.gif";
	$result = $db->query("SELECT motto FROM {$gtablepre}users WHERE username = '".$playerdata['name']."'");
	$playerdata['motto'] = $db->result($result, 0);
	$alivedata[] = $playerdata;
}
if(!isset($alivemode)){
	include template('alive');
}else{
	include template('alivelist');
	$alivedata['innerHTML']['alivelist'] = ob_get_contents();
	if(isset($error)){$alivedata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = compatible_json_encode($alivedata);
	echo $jgamedata;
	ob_end_flush();
}

//include template('alive');

?>