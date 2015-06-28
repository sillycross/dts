<?php

define('CURSCRIPT', 'news');

require './include/common.inc.php';
//$t_s=getmicrotime();
//require_once GAME_ROOT.'./include/JSON.php';

$newsfile = GAME_ROOT.'./gamedata/newsinfo.php';
$newshtm = GAME_ROOT.TPLDIR.'/newsinfo.htm';
$lnewshtm = GAME_ROOT.TPLDIR.'/lastnews.htm';

if(filemtime($newsfile) > filemtime($lnewshtm)) {
	$lnewsinfo = \sys\nparse_news(0,$newslimit);
	writeover($lnewshtm,$lnewsinfo);
}
if(!isset($newsmode)){$newsmode = '';}

if($newsmode == 'last') {
	
	include template('lastnews');
	$newsdata['innerHTML']['newsinfo'] = ob_get_contents();
	if(isset($error)){$newsdata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = compatible_json_encode($newsdata);
//	$json = new Services_JSON();
//	$jgamedata = $json->encode($newsdata);
	echo $jgamedata;
	ob_end_flush();
} elseif($newsmode == 'all') {
	
	if(filemtime($newsfile) > filemtime($newshtm)) {
		$newsinfo = \sys\nparse_news(0,65535);
		writeover($newshtm,$newsinfo);
	}
	include template('newsinfo');
	$newsdata['innerHTML']['newsinfo'] = ob_get_contents();
	if(isset($error)){$newsdata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = compatible_json_encode($newsdata);
	//$json = new Services_JSON();
	//$jgamedata = $json->encode($newsdata);
	echo $jgamedata;
	ob_end_flush();	

} elseif($newsmode == 'chat') {
	$newsdata['innerHTML']['newsinfo'] = '';
	$chats = getchat(0,'',$chatinnews);
	$chatmsg = $chats['msg'];
	foreach($chatmsg as $val){
		$newsdata['innerHTML']['newsinfo'] .= $val;
	}	
	if(isset($error)){$newsdata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = compatible_json_encode($newsdata);
//	$json = new Services_JSON();
//	$jgamedata = $json->encode($newsdata);
	echo $jgamedata;
	ob_end_flush();
} else {
	include template('news');
}
//$t_e=getmicrotime();
//putmicrotime($t_s,$t_e,'news_time');

?>	
