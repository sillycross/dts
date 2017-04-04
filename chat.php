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
					$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('4','$now','$cuser','$chatmsg')");
				}
			} else {
				$chatdata = array('lastcid' => $lastcid, 'msg' => Array('<span class="red">指令错误。</span><br>'));
			}
		} else {
			$chatdata = array('lastcid' => $lastcid, 'msg' => Array('<span class="red">聊天信息不能用 / 开头。</span><br>'));
		}
	} else { 
		if($chattype == 0) {
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('0','$now','$cuser','$chatmsg')");
		} elseif($chattype == 1) {
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('1','$now','$cuser','$teamID','$chatmsg')");
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