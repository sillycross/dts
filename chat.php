<?php

define('CURSCRIPT', 'chat');
define('LOAD_CORE_ONLY', TRUE);

require './include/common.inc.php';

if($sendmode != 'newspage' && (!$cuser || !defined('IN_GAME'))) {
	exit('Not in game.');
}

$ctablecorrect = 1;
//强制读本地用户数据（如果有的话）
$userdb_forced_local = 1;
//如果拉取的房间号同账号不对应则进行游戏局数判定
if((isset($cgamenum) && $gamenum != $cgamenum) || (isset($croomid) && $groomid != $croomid)){
	if(room_check_gamenum($croomid, $cgamenum)) {
		//如果房间存在且局数有效，则修改当前聊天房间号
		$ctablepre = room_get_tablepre(room_id2prefix($croomid));
	}else{
		$ctablecorrect = 0;//否则标记房间号错误
	}
}

if($ctablecorrect && $sendmode == 'send' && $chatmsg ) {//发送聊天
	if(strpos($chatmsg,'/') === 0) {
		$groupid = $cudata['groupid'];
		if($groupid > 1) {
			if(strpos($chatmsg,'/post') === 0) {
				$chatmsg = substr($chatmsg,6);
				if($chatmsg){
					\sys\addchat(4, $chatmsg, $cuser);
					//$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('4','$now','$cuser','$chatmsg')");
				}
			} else {
				$showdata = array('lastcid' => $lastcid, 'msg' => Array('<span class="red b">指令错误。<br></span>'));
			}
		} else {
			$showdata = array('lastcid' => $lastcid, 'msg' => Array('<span class="red b">聊天信息不能用 / 开头。<br></span>'));
		}
	} else { 
		if($chattype == 0) {
			\sys\addchat(0, $chatmsg, $cuser, '', 0, $cpls);
		} elseif($chattype == 1) {
			\sys\addchat(1, $chatmsg, $cuser, $teamID, 0, $cpls);
		}
	}
}elseif($ctablecorrect && $sendmode == 'newspage'){//来自news.php的调用
	$showdata['innerHTML']['newsinfo'] = '';
	$chats = getchat(0,'',0,$chatinnews);
	$chatmsg = $chats['msg'];
	foreach($chatmsg as $val){
		$showdata['innerHTML']['newsinfo'] .= $val;
	}	
}
//房间号错误，显示错误信息并停止轮询js运行
if(!$ctablecorrect && $lastcid >= 0 && $gamestate > 0) {
	$lastcid = -1;
	$showdata = array(
		'lastcid' => $lastcid,
		'msg' => Array('<span class="red b">房间号错误，可能是新一局游戏已开始。<br></span>'),
		'cmd' => 'chat-ref-stop'
	);
}
//$sendmode=='ref'时没有特殊判断，直接作下列处理
if($ctablecorrect && !$showdata) {
	if($chatpid) $showdata = getchat($lastcid,$teamID,$chatpid);
	else $showdata = getchat($lastcid,$teamID);
}
if(isset($error)){$showdata['innerHTML']['error'] = $error;}
ob_clean();
$jgamedata = gencode($showdata);
echo $jgamedata;
ob_end_flush();

/* End of file chat.php */
/* Location: /chat.php */