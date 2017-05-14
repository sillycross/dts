<?php

define('CURSCRIPT', 'chat');
define('LOAD_CORE_ONLY', TRUE);

require './include/common.inc.php';

if($sendmode != 'newspage' && (!$cuser || !defined('IN_GAME'))) {
	exit('Not in game.');
}

if(($sendmode == 'send')&&$chatmsg) {//发送聊天
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
		} elseif($chattype == 1) {
			\sys\addchat(1, $chatmsg, $cuser, $teamID);
		}
	}
}elseif ($sendmode == 'news' && isset($lastnid)) {//来自游戏页面查看即时进行状况的调用
	$lastnid = (int)$lastnid;
	$showdata = \sys\getnews($lastnid);
}elseif($sendmode == 'newspage'){//来自news.php的调用
	$showdata['innerHTML']['newsinfo'] = '';
	$chats = getchat(0,'',0,$chatinnews);
	$chatmsg = $chats['msg'];
	foreach($chatmsg as $val){
		$showdata['innerHTML']['newsinfo'] .= $val;
	}	
}
//$sendmode=='ref'时没有特殊判断，直接作下列处理
if(!$showdata) {
	if($chatpid) $showdata = getchat($lastcid,$teamID,$chatpid);
	else $showdata = getchat($lastcid,$teamID);
}
if(isset($error)){$showdata['innerHTML']['error'] = $error;}
ob_clean();
$jgamedata = gencode($showdata);
echo $jgamedata;
ob_end_flush();
?>