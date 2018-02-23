<?php
//ignore_user_abort(1);//这一代码基本上是以异步调用的方式执行的
header('Content-Type: text/HTML; charset=utf-8'); // 以事件流的形式告知浏览器进行显示
header('Cache-Control: no-cache');         // 告知浏览器不进行缓存
header('X-Accel-Buffering: no');           // 关闭加速缓冲
@ini_set('implicit_flush',1);
ob_implicit_flush(1);
define('CURSCRIPT', 'replay_receive');
define('IN_GAME', true);
set_time_limit(0);

//啥也不载入，只判断密钥是否匹配
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
ini_set('post_max_size', '20M');

require GAME_ROOT.'./include/global.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
include GAME_ROOT.'./include/modules/core/sys/config/system.config.php';
check_authority();
//先做一个列表
if(!file_exists('tmp_replist.dat')){
	$list = array();
	$source = './gamedata/replays';
	if ($handle=opendir($source)) 
	{
		while (($entry=readdir($handle))!==false)
		{  
			if( $entry!="." && $entry!=".." && !is_dir($source."/".$entry) && substr($entry, strlen($entry)-4)=='.dat')
			{
				$list[] = $entry;
			}
		}
	}
	sort($list);
	file_put_contents('tmp_replist.dat', json_encode($list));
	echo 'tmp_replist.dat created.';ob_end_flush(); flush();
}else{
	$list = json_decode(file_get_contents('tmp_replist.dat'),1);
	echo 'tmp_replist.dat loaded.';ob_end_flush(); flush();
}

if(empty($_GET['start'])){
	$start = 0;
}else{
	$start = $_GET['start'];
}
$limit = 10000;
if(!empty($_GET['limit'])) $limit = $_GET['limit'];
//$limit = 5;//每这么多次运行后结束，同时让客户界面重新发送一次请求
$sleep = 30;//每次请求之后等待这么久时间，避免造成数据轰炸

if(empty($replay_remote_storage)) exit('<br>Replay remote storage is closed!');

echo '<br>start from '.$start;
$i = $start;
foreach($list as $li => $lv){
	if($li<$start) continue;
	
	$rpurl = $replay_remote_storage;
	$context = array(
		'sign'=>$replay_remote_storage_sign, 
		'pass'=>$replay_remote_storage_key, 
		'cmd'=>'storage_req', 
		'filename'=>$lv,
		'callurl'=>$server_address.'/replay_receive.php',
		'no-overlap'=>1,
	);
	
	echo '<br>'.$lv.' sent...';
	echo ' ';ob_end_flush(); flush();
	$ret = curl_post($rpurl, $context);
	if(empty(trim($ret))) $ret = 'Unknown error occured';
	elseif(strpos($ret, 'already')!==false) $ret = '<font color="grey">'.$ret.'</font>';
	elseif(strpos($ret, 'Successfully')!==false) $ret = '<font color="lime">'.$ret.'</font>';
	echo $ret; ob_end_flush(); flush();
	
	$i++;
	if($i-$start >= $limit) {
		echo '<br>stopped at '.$i;
		break;
	}
	$w_list = array_slice($list, $li);
	file_put_contents('tmp_replist.dat', json_encode($w_list));
	if(strpos($ret, 'Successfully')!==false) sleep($sleep);
}

if($i-$start < $limit) unlink('tmp_replist.dat');