<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function message_create($to, $title='', $content='', $enclosure='', $from='sys', $t=0)
{
	global $now,$db,$gtablepre;
	if(!$t) $t = $now;
	if(!$to) return;
	$ins_arr = array(
		'timestamp' => $t,
		'sender' => $from,
		'receiver' => $to,
		'title' => $title,
		'content' => $content,
		'enclosure' => $enclosure
	);
	$db->array_insert("{$gtablepre}messages", $ins_arr);
}

function message_load($mid_only=0)
{
	global $udata,$db,$gtablepre;
	$username = $udata['username'];
	if($mid_only) $result = $db->query("SELECT mid FROM {$gtablepre}messages WHERE receiver='$username' AND deleted=0 ORDER BY timestamp DESC");
	else $result = $db->query("SELECT * FROM {$gtablepre}messages WHERE receiver='$username' AND deleted=0 ORDER BY timestamp DESC");
	$messages = array();
	while($r = $db->fetch_array($result)){
		$messages[$r['mid']] = $r;
	}
	return $messages;
}

function message_disp($messages)
{
	global $udata;
	if(defined('MOD_CARDBASE')) eval(import_module('cardbase'));
	$user_cards = explode('_',$udata['cardlist']);
	//显示卡片的基本参数
	$showpack=1;
	foreach($messages as &$mv){
		$mv['hint'] = '<span class="L5">未读!</span>';
		if($mv['read']) $mv['hint'] = '<span class="grey">已读</span>';
		if(!empty($mv['enclosure'])) {
			if($mv['checked']) $mv['hint'] .= ' <span class="grey">附件已收</span>';
			else $mv['hint'] .= ' <span class="L5">附件未收!</span>';
		}
		$mv['time_disp'] = date("Y年m月d日 H:i:s", $mv['timestamp']);
		$mv['encl_disp'] = '';
		if(!empty($mv['enclosure']) && defined('MOD_CARDBASE')){
			$mv['encl_disp'] .= '<br>附件：';
			preg_match('/getcard_(\d+)/s', $mv['enclosure'], $matches);
			if(is_numeric($matches[1])) {
				$getcard = $matches[1];
				$nowcard = $cards[$getcard];
				$nownew = !in_array($getcard, $user_cards);
				ob_start();
				include template(MOD_CARDBASE_CARD_FRAME);
				$mv['encl_disp'] .= ob_get_contents();
				ob_end_clean();
			}
		}
	}
	return $messages;
}

function message_check($checklist, $messages)
{
	global $udata,$db,$gtablepre,$info;
	if(defined('MOD_CARDBASE')) eval(import_module('cardbase'));
	if(!defined('MOD_CARDBASE')) return;
	$user_cards = explode('_',$udata['cardlist']);
	foreach($checklist as $cid){
		if($messages[$cid]['checked']) continue;
		if(!empty($messages[$cid]['enclosure'])){
			preg_match('/getcard_(\d+)/s', $messages[$cid]['enclosure'], $matches);
			if(is_numeric($matches[1])) {
				$getcard = $matches[1];
				$getname = $cards[$getcard]['name'];
				$getrare = $cards[$getcard]['rare'];
				if(!in_array($getcard, $user_cards)) $info[] = '获得了卡片“<span class="'.$card_rarecolor[$getrare].'">'.$getname.'</span>”！';
				else $info[] = '已有卡片“<span class="'.$card_rarecolor[$getrare].'">'.$getname.'</span>”，转化为了'.$card_price[$getrare].'切糕！';
				\cardbase\get_card($getcard, $udata);
			}
		}
	}
}

/* End of file messages.func.php */
/* Location: include/messages.func.php */