<?php

define('CURSCRIPT', 'messages');

require './include/common.inc.php';
require './include/user.func.php';
require './include/messages.func.php';
$udata = udata_check();
$username = $udata['username'];

if(defined('MOD_CARDBASE')) {
	eval(import_module('cardbase'));
//	$result = $db->query("SELECT cards FROM {$gtablepre}users WHERE username='$username'");
//	$udata['cards'] = $db->fetch_array($result)['cards'];
}

if(!isset($mode)){
	$mode = 'show';
}

$messages = message_load();

$editflag = 0;
$info = array();

if($mode == 'del') {//删除
	$dellist = array();
	foreach(array_keys($messages) as $mi){
		if(!empty(${'sl'.$mi})) $dellist[] = $mi;
	}
	if(!empty($dellist)) $editflag = 1;
}elseif($mode == 'check') {//查看并收取附件
	$checklist = array();
	foreach(array_keys($messages) as $mi){
		if(!empty(${'sl'.$mi}) && !$messages[$mi]['checked'] && !empty($messages[$mi]['enclosure'])) $checklist[] = $mi;
	}
	if(!empty($checklist)) $editflag = 1;
}

if($editflag) {
	if(!empty($checklist)){
		message_check($checklist, $messages);
		$checkc = implode(',',$checklist);
		if(count($checklist) > 1)	$info[] = '已查收'.count($checklist).'条消息！';
		else $info[] = '消息已查收！';
		$db->query("UPDATE {$gtablepre}messages SET checked='1' WHERE mid IN ($checkc) AND receiver='$username'");
	}
	if(!empty($dellist)){
		$delc = implode(',',$dellist);
		$db->query("UPDATE {$gtablepre}messages SET deleted='1' WHERE mid IN ($delc) AND receiver='$username'");
		$dnum = $db->affected_rows();
		$info[] = '已删除'.$dnum.'条消息！';
	}
	
	//重载一次信息
	$messages = message_load();
}else{
	$info[] = '没有做任何更改';
}
//全部设为已读
foreach($messages as $mv){
	if(!$mv['read']) {
		$db->query("UPDATE {$gtablepre}messages SET `read`='1' WHERE receiver='$username' AND `read`='0'");
		break;
	}
}


$messages = message_disp($messages);
if('show'==$mode){//生成整个页面，不用ajax
	include template('message_page');
}else{//有指令，需要ajax
	$gamedata=Array();
	$gamedata['innerHTML']['info'] = implode('<br>', $info);
	if(isset($error)) $gamedata['innerHTML']['error'] = $error;
	include template('messages');
	$gamedata['innerHTML']['messages'] = ob_get_contents();
	$jgamedata = gencode($gamedata);
	ob_clean();
	echo $jgamedata;
}

/* End of file messages.php */
/* Location: /messages.php */