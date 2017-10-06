<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

@ob_end_clean();
header('Content-Type: text/HTML; charset=utf-8'); // 以事件流的形式告知浏览器进行显示
header( 'Content-Encoding: none; ' );
header('Cache-Control: no-cache');         // 告知浏览器不进行缓存
header('X-Accel-Buffering: no');           // 关闭加速缓冲
@ini_set('implicit_flush',1);
ob_implicit_flush(1);
set_time_limit(0);
@ini_set('zlib.output_compression',0);

if(!isset($_GET['iknow'])) {
	$thisfile = pathinfo($_SERVER['PHP_SELF'], PATHINFO_BASENAME);
	echo '警告：如果你不是刻意运行此代码，很可能导致游戏录像数据丢失！<br>';
	echo '<a href="'.$thisfile.'?iknow=1">我知道自己在干什么</a>';
	die();
}
//die('okey dokey lokey');

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
output_t('Auth checked.');
$dirpath = GAME_ROOT.'./gamedata/replays';
$filelist = array();
//第一步，记录每一局对应的文件
if ($handle=opendir($dirpath)) 
{
	while (($entry=readdir($handle))!==false)
	{   
		if($entry != '.' && $entry != '..'){
			$exname = pathinfo($entry, PATHINFO_EXTENSION);
			if($exname != 'dat') {
				output_t($entry.' read.');
				list($name1, $name2) = explode('.', $entry);
				if('s' == $name1) $gid = 's.'.$name2;
				elseif(is_numeric($name1)) $gid = $name1;
				if(isset($gid)){
					if(!isset($filelist[$gid])) $filelist[$gid] = array();
					$filelist[$gid][] = $dirpath.'/'.$entry;
				}
			}
		}
	}
}
output_t('Step 1 over. now folding. ');
//第二步，每一局各自打包文件
foreach($filelist as $gid => $flist){
	output_t('Folding '.$gid.'...');
	fold($dirpath.'/'.$gid.'.dat', $flist);
	foreach($flist as $fv) unlink($fv);
}

output_t('Done. ');

function output_t($str){
	echo $str.'<br>';
	ob_flush();
	flush();
}