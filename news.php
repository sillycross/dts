<?php

define('CURSCRIPT', 'news');

require './include/common.inc.php';
//$t_s=getmicrotime();

$newsfile = GAME_ROOT.'./gamedata/tmp/news/newsinfo_'.$room_prefix.'.php';
$newshtm = GAME_ROOT.'./gamedata/tmp/news/newsinfo_'.$room_prefix.'.htm';
$lnewshtm = GAME_ROOT.'./gamedata/tmp/news/lastnews_'.$room_prefix.'.htm';

if(filemtime($newsfile) > filemtime($lnewshtm)) {
	$lnewsinfo = \sys\load_news(0, $newslimit);
	writeover($lnewshtm,$lnewsinfo);
}
if(!isset($newsmode)){$newsmode = '';}
if ($newsmode == 'game' && isset($lastnid)) {//来自游戏页面查看即时进行状况的调用
	$lastnid = (int)$lastnid;
	if($lastnid) {
		$gnewsinfo = \sys\load_news($lastnid);
	}
} elseif($newsmode == 'last') {//来自news.php查看进行状况的调用，由于可能长时间没有行动，统一查看页面缓存	
	//echo file_get_contents($lnewshtm);
	$newsdata['innerHTML']['newsinfo'] = file_get_contents($lnewshtm);
	if(isset($error)){$newsdata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = gencode($newsdata);
	echo $jgamedata;
	ob_end_flush();
} elseif($newsmode == 'all') {
	if(filemtime($newsfile) > filemtime($newshtm)) {
		$newsinfo = \sys\load_news();
		writeover($newshtm,$newsinfo);
	}
	//echo file_get_contents($newshtm);
	$newsdata['innerHTML']['newsinfo'] = file_get_contents($newshtm);
	if(isset($error)){$newsdata['innerHTML']['error'] = $error;}
	ob_clean();
	$jgamedata = gencode($newsdata);
	echo $jgamedata;
	ob_end_flush();	

} else {
	include template('news');
}
//$t_e=getmicrotime();
//putmicrotime($t_s,$t_e,'news_time');

?>