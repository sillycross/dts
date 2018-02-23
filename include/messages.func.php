<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function init_messages($mode){
	if('showdel' == $mode || 'recover' == $mode) {
		return deleted_message_load();
	}else{
		return message_load();
	}
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

//直接放到sys模块里了
//function message_check_new($username)
//{
//	global $db,$gtablepre;
//	$result = $db->query("SELECT mid FROM {$gtablepre}messages WHERE receiver='$username' AND rd=0");
//	if($db->num_rows($result)) return true;
//	return false;
//}

function message_load($mid_only=0)
{
	global $udata,$db,$gtablepre;
	$username = $udata['username'];
	if($mid_only) $result = $db->query("SELECT mid FROM {$gtablepre}messages WHERE receiver='$username' ORDER BY timestamp DESC, mid DESC");
	else $result = $db->query("SELECT * FROM {$gtablepre}messages WHERE receiver='$username' ORDER BY timestamp DESC, mid DESC");
	$messages = array();
	while($r = $db->fetch_array($result)){
		$messages[$r['mid']] = $r;
	}
	return $messages;
}

function deleted_message_load()
{
	global $udata,$db,$gtablepre;
	$username = $udata['username'];
	$result = $db->query("SELECT * FROM {$gtablepre}del_messages WHERE receiver='$username' ORDER BY timestamp DESC, mid DESC");
	$d_messages = array();
	while($r = $db->fetch_array($result)){
		$d_messages[$r['mid']] = $r;
	}
	return $d_messages;
}

function message_get_encl_num($encl, $tp)
{
	preg_match('/'.$tp.'_(\d+)/s', $encl, $matches);
	if($matches && is_numeric($matches[1])) return $matches[1];
	else return 0;
}

function message_disp($messages)
{
	global $udata;
	if(defined('MOD_CARDBASE')) eval(import_module('cardbase'));
	$user_cards = explode('_',$udata['cardlist']);
	//显示卡片的基本参数
	$showpack=1;
	foreach($messages as $mi => &$mv){
		$mv['hint'] = '<span class="L5">NEW!</span>';
		if($mv['rd']) $mv['hint'] = '';
		
		$mv['time_disp'] = date("Y年m月d日 H:i:s", $mv['timestamp']);
		$mv['encl_disp'] = '';
		if(!empty($mv['enclosure']) && defined('MOD_CARDBASE')){
			
			if($mv['checked']) $mv['encl_hint'] = '<span class="grey">附件已收</span>';
			else $mv['encl_hint'] = "<a class='L5' onclick=\"$('extracmd').name='sl$mi';$('extracmd').value='1';$('mode').value='check';postCmd('message_cmd', 'messages.php');$('extracmd').name='extracmd';$('extracmd').value='';\">附件<br>点此查收</a>";

			//切糕判定
			$getqiegao = message_get_encl_num($mv['enclosure'], 'getqiegao');
			if($getqiegao) {
				$mv['encl_disp'] .= '<div class="gold b">'.$getqiegao.'切糕</div>';
			}
			//卡片判定
			$getcard = message_get_encl_num($mv['enclosure'], 'getcard');
			if($getcard) {
				$nowcard = $cards[$getcard];
				$nownew = !in_array($getcard, $user_cards);
				ob_start();
				include template(MOD_CARDBASE_CARD_FRAME);
				$tmp_cardpage = ob_get_contents();
				ob_end_clean();
				$mv['encl_disp'] .= '<div>卡片：<span class="'.$card_rarecolor[$nowcard['rare']].'" title="'.str_replace('"',"'",$tmp_cardpage).'">'.$nowcard['name'].($nownew ? ' <span class="L5">NEW!</span>' : '').'</span></div>';
			}
			//因果判定
			$getkarma = message_get_encl_num($mv['enclosure'], 'getkarma');
			if($getkarma) {
				$mv['encl_disp'] .= '<div class="clan">'.$getkarma.'因果</div>';
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
	if(!is_array($udata['cardlist'])) {
		$cl_changed = 1;
		$udata['cardlist'] = explode('_',$udata['cardlist']);
	}
	$getqiegaosum = $getcardflag = $getkarmasum = 0;
	
	foreach($checklist as $cid){
		if($messages[$cid]['checked']) continue;
		if(!empty($messages[$cid]['enclosure'])){
			//获得切糕
			$getqiegao = message_get_encl_num($messages[$cid]['enclosure'], 'getqiegao');
			if($getqiegao) {
				$info[] = '获得了<span class="gold b">'.$getqiegao.'切糕</span>';
				$getqiegaosum += $getqiegao;
			}
			//获得卡片
			$getcard = message_get_encl_num($messages[$cid]['enclosure'], 'getcard');
			if($getcard) {
				$getname = $cards[$getcard]['name'];
				$getrare = $cards[$getcard]['rare'];
				if(!in_array($getcard, $udata['cardlist'])) $info[] = '获得了卡片“<span class="'.$card_rarecolor[$getrare].'">'.$getname.'</span>”！';
				else $info[] = '已有卡片“<span class="'.$card_rarecolor[$getrare].'">'.$getname.'</span>”，转化为了'.$card_price[$getrare].'切糕！';
				//\cardbase\get_card($getcard, $udata);
				//不直接写数据库，最后统一写
				\cardbase\get_card_process($getcard, $udata);
				$getcardflag = 1;
			}
			//获得因果
			$getkarma = message_get_encl_num($messages[$cid]['enclosure'], 'getkarma');
			if($getkarma) {
				$info[] = '获得了<span class="clan">'.$getkarma.'因果</span>';
				$getkarmasum += $getkarma;
			}
		}
	}
	if(!empty($cl_changed)) $udata['cardlist'] = implode('_',$udata['cardlist']);
	if($getqiegaosum || $getcardflag || $getkarmasum) {
		$n = $udata['username'];
		$gold = $udata['gold']+$getqiegaosum;
		$gold2 = $udata['gold2']+$getkarmasum;
		$cardlist = $udata['cardlist'];
		$db->query("UPDATE {$gtablepre}users SET gold='$gold',gold2='$gold2',cardlist='$cardlist' WHERE username='$n'");
	}
}

/* End of file messages.func.php */
/* Location: include/messages.func.php */