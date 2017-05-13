<?php

define('CURSCRIPT', 'chat');
define('LOAD_CORE_ONLY', TRUE);

require './include/common.inc.php';

if(!$cuser || !defined('IN_GAME')) {
	exit('Not in game.');
}

if(($sendmode == 'send')&&$chatmsg) {
	if(strpos($chatmsg,'/') === 0) {
		$result = $db->query("SELECT groupid FROM {$gtablepre}users WHERE username='$cuser'");
		$groupid = $db->result($result,0);
		if($groupid > 1) {
			if(strpos($chatmsg,'/post') === 0) {
				$chatmsg = substr($chatmsg,6);
				if($chatmsg){
					\sys\addchat(4, $chatmsg, $cuser);
					//$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('4','$now','$cuser','$chatmsg')");
				}
			} else {
				$chatdata = array('lastcid' => $lastcid, 'msg' => Array('<span class="red">指令错误。<br></span>'));
			}
		} else {
			$chatdata = array('lastcid' => $lastcid, 'msg' => Array('<span class="red">聊天信息不能用 / 开头。<br></span>'));
		}
	} else { 
		if($chattype == 0) {
			\sys\addchat(0, $chatmsg, $cuser);
			//$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('0','$now','$cuser','$chatmsg')");
		} elseif($chattype == 1) {
			\sys\addchat(1, $chatmsg, $cuser, $teamID);
			//$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('1','$now','$cuser','$teamID','$chatmsg')");
		}
	}
}
if(!$chatdata) {
	if($chatpid) $chatdata = getchat($lastcid,$teamID,$chatpid);
	else $chatdata = getchat($lastcid,$teamID);
}
ob_clean();
//$json = new Services_JSON();
//$jgamedata = $json->encode($chatdata);
$jgamedata = json_encode($chatdata);
echo $jgamedata;
ob_end_flush();


?>