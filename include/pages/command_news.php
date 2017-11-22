<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

eval(import_module('sys','player'));

$newsfile = GAME_ROOT.'./gamedata/tmp/news/newsinfo_'.$room_prefix.'.php';
$newshtm = GAME_ROOT.'./gamedata/tmp/news/newsinfo_'.$room_prefix.'.htm';
$lnewshtm = GAME_ROOT.'./gamedata/tmp/news/lastnews_'.$room_prefix.'.htm';

if(filemtime($newsfile) > filemtime($lnewshtm)) {
	$lnewsinfo = \sys\load_news(0, $newslimit);
	$lnewsinfo = '<ul>'.implode('',$lnewsinfo).'</ul>';
	writeover($lnewshtm,$lnewsinfo);
}
if(!isset($newsmode)) $newsmode = '';
if (isset($sendmode) && $sendmode == 'news' && isset($lastnid)) {//游戏页面查看进行状况的调用
	$lastnid = (int)$lastnid;
	$newsinfo = \sys\getnews($lastnid);
	ob_clean();
	$jgamedata = gencode($newsinfo);
	echo $jgamedata;
	ob_end_flush();
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
		$newsinfo = '<ul>'.implode('',$newsinfo).'</ul>';
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

/* End of file command_news.php */
/* Location: /include/pages/command_news.php */