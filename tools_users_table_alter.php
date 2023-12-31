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

$result = $db->query("DESCRIBE {$tablepre}users");
$ret = $db->fetch_all($result);

$validgames_need_alter = $wingames_need_alter = $lastgame_need_alter = 0;
$lastvisit_need_add = 1;

foreach($ret as $v) {
	if($v['Field'] == 'lastvisit') $lastvisit_need_add = 0;
	if($v['Field'] == 'validgames' && substr($v['Type'], 0, 9)!=='mediumint') $validgames_need_alter = 1;
	if($v['Field'] == 'wingames' && substr($v['Type'], 0, 9)!=='mediumint') $wingames_need_alter = 1;
	if($v['Field'] == 'lastgame' && substr($v['Type'], 0, 4)!=='char') $lastgame_need_alter = 1;
}

//var_dump($lastvisit_need_add, $validgames_need_alter, $wingames_need_alter);

if($lastvisit_need_add) {
	echo 'Column "lastvisit" has not been added. Now adding...<br>';
	$result = $db->query("ALTER TABLE {$tablepre}users ADD COLUMN lastvisit int(10) unsigned NOT NULL DEFAULT '0' AFTER lastword");
	echo 'Done.<br><br>';
}
if($validgames_need_alter) {
	echo 'Column "validgames" needs to be altered. Now altering...<br>';
	$result = $db->query("ALTER TABLE {$tablepre}users MODIFY COLUMN validgames mediumint(8) unsigned NOT NULL DEFAULT '0'");
	echo 'Done.<br><br>';
}
if($wingames_need_alter) {
	echo 'Column "wingames" needs to be altered. Now altering...<br>';
	$result = $db->query("ALTER TABLE {$tablepre}users MODIFY COLUMN wingames mediumint(8) unsigned NOT NULL DEFAULT '0'");
	echo 'Done.<br><br>';
}
if($lastgame_need_alter) {
	echo 'Column "lastgame" needs to be altered. Now altering...<br>';
	$result = $db->query("ALTER TABLE {$tablepre}users MODIFY COLUMN lastgame char(10) NOT NULL DEFAULT ''");
	echo 'Done.<br><br>';
}

echo 'All done.';