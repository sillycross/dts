<?php
error_reporting(E_ALL);
@ob_end_clean();
header('Content-Type: text/HTML; charset=utf-8'); // 以事件流的形式告知浏览器进行显示
header('Cache-Control: no-cache');         // 告知浏览器不进行缓存
header('X-Accel-Buffering: no');           // 关闭加速缓冲
@ini_set('implicit_flush',1);
ob_implicit_flush(1);
set_time_limit(0);
@ini_set('zlib.output_compression',0);
define('IN_MAINTAIN',true);
echo str_repeat(" ",1024);
echo '<script language="javascript"> 
$z=setInterval(function() { window.scroll(0,document.body.scrollHeight); },100); 
function stop() { window.scroll(0,document.body.scrollHeight); clearInterval($z); }</script>
<body onload=stop(); ></body>'; 
define('CURSCRIPT', 'card_reissue');
define('IN_GAME', TRUE);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require './include/common.inc.php';

check_authority();

$result = $db->query("SELECT * FROM {$tablepre}users WHERE lastwin > 1430755201");

$list = array(
	300 => array(
		3 => 86,
	),
	313 => array(
		2 => 23,
		3 => 89,
		4 => 118,
		5 => 156,
	),
	350 => array(
		2 => 205,
	),
	354 => array(
		2 => 42,
	),
	358 => array(
		1 => 163,
		3 => 160,
	),
	360 => array(
		3 => 164,
	),
	361 => array(
		3 => 209,
	),
);
$list2 = array(
	359 => array(
		1 => 208,
	),
);
$list3 = array(
	302 => array(
		30 => 66,
	),
	304 => array(
		30 => 87,
	),
	306 => array(
		5 => 98,
	),
	307 => array(
		8 => 81,
	),
	312 => array(
		4 => 88,
	),
	325 => array(
		100 => 119,
	),
);
$list4 = array(
	327 => array(
		30 => 1,
		100 => 1,
	),
	328 => array(
		60 => 1,
	),
	329 => array(
		50 => 1,
	),
	331 => array(
		1 => 1,
	),
	362 => array(
		6400 => 1,
	),
);
eval(import_module('cardbase'));
foreach (array_keys($list) as $k){
	eval(import_module('skill'.$k));
}

while($ud = $db->fetch_array($result)){
	
	$a = \achievement_base\decode_achievements($ud);
	$c = explode('_',$ud['cardlist']);
	$count326 = count(explode($a[326]));
	if($count326 >= 25 && !in_array(81,$c)) {
		echo '账户 '.$ud['username'].' 缺失来自成就「全能骑士」的卡片「篝火挑战者」';ob_end_flush(); flush();
		reissue($ud['username'], 81);
	}
	foreach($list as $i => $l){
		foreach ($l as $n => $v) {
			if($a[$i] >= ${'ach'.$i.'_threshold'}[$n]) {
				if(!in_array($v,$c)) {
					if(isset(${'ach'.$i.'_name'})) {
						$an = reset(${'ach'.$i.'_name'});
						if(isset(${'ach'.$i.'_name'}[$n])) $an = ${'ach'.$i.'_name'}[$n];
						echo '账户 '.$ud['username'].' 缺失来自成就「'.$an.'」的卡片「'.$cards[$v]['name'].'」';ob_end_flush(); flush();
					}else{
						echo '账户 '.$ud['username'].' 缺失来自'.$i.'号成就的卡片「'.$cards[$v]['name'].'」';ob_end_flush(); flush();
					}
					reissue($ud['username'], $v);
				}
			}
		}
	}
	foreach($list2 as $i => $l){
		foreach ($l as $n => $v) {
			if($a[$i] > 0 && $a[$i] <= ${'ach'.$i.'_threshold'}[$n] && !in_array($v,$c)) {
				if(isset(${'ach'.$i.'_name'})) {
					$an = reset(${'ach'.$i.'_name'});
					if(isset(${'ach'.$i.'_name'}[$n])) $an = ${'ach'.$i.'_name'}[$n];
					echo '账户 '.$ud['username'].' 缺失来自成就「'.$an.'」的卡片「'.$cards[$v]['name'].'」';ob_end_flush(); flush();
				}else{
					echo '账户 '.$ud['username'].' 缺失来自'.$i.'号成就的卡片「'.$cards[$v]['name'].'」';ob_end_flush(); flush();
				}
				reissue($ud['username'], $v);
			}
		}
	}
	foreach($list3 as $i => $l){
		foreach ($l as $n => $v) {
			if($a[$i] >= $n && !in_array($v,$c)) {
				if(isset(${'ach'.$i.'_name'})) {
					$an = reset(${'ach'.$i.'_name'});
					echo '账户 '.$ud['username'].' 缺失来自成就「'.$an.'」的卡片「'.$cards[$v]['name'].'」';ob_end_flush(); flush();
				}else{
					echo '账户 '.$ud['username'].' 缺失来自'.$i.'号成就的卡片「'.$cards[$v]['name'].'」';ob_end_flush(); flush();
				}
				reissue($ud['username'], $v);
			}
		}
	}
	$count = 0;
	foreach($list4 as $i => $l){
		foreach ($l as $n => $v) {
			if($a[$i] >= $n) $count ++;
		}
	}
	$count_get = count(array_intersect(array(200, 201, 202, 203, 204),$c));
	if($count > $count_get && $count_get < 5) {
		echo '账户 '.$ud['username'].' 缺失卡集「Battle Rekindle」 '.($count - $count_get).' 张卡片';ob_end_flush(); flush();
		$arr = array_intersect(array(200, 201, 202, 203, 204),$c);
		shuffle($arr);
		reissue($ud['username'], $arr[0]);
	}
}
echo 'done.';

function reissue($un, $card, $ai=0){
	$pt = '账户合并时遗漏的成就卡片现在补发。';
	include_once './include/messages.func.php';
	message_create(
		$un,
		'补发奖励',
		$pt,
		'getcard_'.$card.';'
	);	
	eval(import_module('cardbase'));
	echo ' 已发放'.$cards[$card]['name'].'<br>';
	ob_end_flush(); flush();
}