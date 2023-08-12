<?php

define('CURSCRIPT', 'messages');

require './include/common.inc.php';
require_once './include/messages.func.php';
$udata = udata_check();
$username = $udata['username'];

$message_rec_cost = 100;//恢复邮件价格

if(defined('MOD_CARDBASE')) {
	eval(import_module('cardbase'));
}

if(!isset($mode)){
	$mode = 'show';
}
$messages = init_messages($mode);

$editflag = 0;
$info = array();

if($mode == 'del' || $mode == 'del2') {//删除
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
}elseif($mode == 'recover') {//恢复删除邮件
	$reclist = array();
	foreach(array_keys($messages) as $mi){
		if(!empty(${'sl'.$mi})) $reclist[] = $mi;
	}
	if(!empty($reclist)) {
		$cost = $message_rec_cost * sizeof($reclist);
		if($udata['gold'] < $cost) {
			$info[] = '切糕不足，无法恢复邮件！';
		}else {
			\cardbase\get_qiegao(-$cost, $udata);
			$info[] = '支付了'.$cost.'切糕';
			$editflag = 1;
		}
	}
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
		$ins_arr = array();
		if('del'==$mode) {//正常删除会添加到垃圾箱
			foreach($dellist as $di){
				$tmp = $messages[$di];
				$tmp['dtimestamp'] = $now;
				unset($tmp['mid']);
				$ins_arr[] = $tmp;
			}
			if(!empty($ins_arr)) $db->array_insert("{$gtablepre}del_messages", $ins_arr);
		}
		$delc = implode(',',$dellist);
		if('del' == $mode){
			$db->query("DELETE FROM {$gtablepre}messages WHERE mid IN ($delc) AND receiver='$username'");
			$dnum = $db->affected_rows();
			$info[] = '已删除'.$dnum.'条消息！';
		}elseif('del2' == $mode){
			$db->query("DELETE FROM {$gtablepre}del_messages WHERE mid IN ($delc) AND receiver='$username'");
			$dnum = $db->affected_rows();
			$info[] = '已彻底删除'.$dnum.'条消息！';
		}
		
	}
	if(!empty($reclist)){
		$ins_arr = array();
		foreach($reclist as $ri){
			$tmp = $messages[$ri];
			unset($tmp['mid'],$tmp['dtimestamp']);
			$ins_arr[] = $tmp;
		}
		if(!empty($ins_arr)) $db->array_insert("{$gtablepre}messages", $ins_arr);
		$recc = implode(',',$reclist);
		$db->query("DELETE FROM {$gtablepre}del_messages WHERE mid IN ($recc) AND receiver='$username'");
		$rnum = $db->affected_rows();
		$info[] = '已恢复'.$rnum.'条消息！';
	}
	if('recover' == $mode || 'del2' == $mode){//删除或者恢复命令，显示的是垃圾桶页面
		$mode = 'showdel';
	}
	//重载一次信息
	$messages = init_messages($mode);
}elseif(strpos($mode,'show') !== 0 && empty($info)){
	$info[] = '没有做任何更改';
}
//全部设为已读
if('show' == $mode){
	foreach($messages as $mv){
		if(!$mv['rd']) {
			$db->query("UPDATE {$gtablepre}messages SET rd='1' WHERE receiver='$username' AND rd='0'");
			break;
		}
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