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

$result = $db->query("SELECT * FROM {$tablepre}users");

eval(import_module('cardbase'));
foreach (array_keys($list) as $k){
	eval(import_module('skill'.$k));
}

while($ud = $db->fetch_array($result)){
	
	$a = \achievement_base\decode_achievements($ud);
	
	if($a[302] >= 5 && $a[302] < 30) {
		echo '账户 '.$ud['username'].' 已达到302第三阶段，应发奖励<br>';ob_end_flush(); flush();
		reissue($ud['username'], 66, 302);
	}
	
	if($a[304] >= 5 && $a[304] < 30) {
		echo '账户 '.$ud['username'].' 已达到304第三阶段，应发奖励<br>';ob_end_flush(); flush();
		reissue($ud['username'], 78, 304);
	}
}
echo 'done.';

function reissue($un, $q, $ai=0){
	eval(import_module('skill'.$ai));
	$n = reset(${'ach'.$ai.'_name'});
	$pt = '因为「'.$n.'」系列成就获得条件修改，您已完成该成就，现补发奖励。';
	include_once './include/messages.func.php';
	message_create(
		$un,
		'补发奖励',
		$pt,
		'getcard_'.$q.';'
	);	
}