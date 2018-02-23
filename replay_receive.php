<?php
ignore_user_abort(1);//这一代码基本上是以异步调用的方式执行的

define('CURSCRIPT', 'replay_receive');
define('IN_GAME', true);

//啥也不载入，只判断密钥是否匹配
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
ini_set('post_max_size', '20M');

require GAME_ROOT.'./include/global.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
include GAME_ROOT.'./include/modules/core/sys/config/system.config.php';

$valid = false;
if(isset($_POST['sign']) && isset($_POST['pass'])) {
	foreach($replay_receive_list as $rs => $rp){
		if($rs === $_POST['sign'] && $rp === $_POST['pass']){
			$valid = true;
			break;
		}
	} 
}
if(!$valid && $_POST['cmd']=='storage_req') {//只有储存请求需要判定密码
	exit( 'Invalid Sign');
}

//由于录像文件可能高达几十M，不能让进程等待全部传输完毕，需要立刻返回值，然后本地重启一个反向请求
if($_POST['cmd'] == 'storage_req') {
	//显示比POST快得多得多，直接反向请求
	$objdir = './gamedata/remote_replays';
	if(!is_dir($objdir)) mymkdir($objdir);
	$objdir2 = $_POST['sign'];
	if(!is_dir($objdir.'/'. $objdir2)) mymkdir($objdir.'/'. $objdir2);
	$objdir3 = str_replace('.dat','',$_POST['filename']);
	if(!is_dir($objdir.'/'. $objdir2.'/'.$objdir3)) mymkdir($objdir.'/'. $objdir2.'/'.$objdir3);
	$objfile = $objdir.'/'. $objdir2.'/'.$objdir3.'/'.$_POST['filename'];
	
	if(!empty($_POST['no-overlap']) && file_exists($objfile)) exit($_POST['filename'].' has already existed.');
	//反向请求1，请求载入录像
	$callbackurl = $_POST['callurl'];
	$context = array(
		'cmd'=>'storage_callback', 
		'filename'=>$_POST['filename'],
	);
	$content = curl_post($callbackurl, $context);
	if(empty($content)) {
		exit ('Some error occurred.');
	} else {
		file_put_contents($objfile, $content);
	}
	
	//反向请求2，请求载入datalib
	$datalibcont = '';
	$datalibname = !empty($_POST['datalibname']) ? $_POST['datalibname'] : '';
	if($datalibname) {
		$context = array(
			'cmd'=>'storage_callback_datalib', 
			'datalibname'=>$datalibname,
		);
		$datalibcont = curl_post($callbackurl, $context);
		
		$objdir_datalib = $objdir.'/'. $objdir2.'/datalib';
		if(!is_dir($objdir_datalib)) mymkdir($objdir_datalib);
		$objfile_datalib = $objdir_datalib.'/'.$datalibname;
		//记录datalib的编号
		file_put_contents(str_replace('.dat','.datalib.txt',$objfile), $datalibname);
		//记录datalib
		if(!file_exists($objfile_datalib)) file_put_contents($objfile_datalib, $datalibcont);
	}

	echo 'Successfully Received.';

}elseif($_POST['cmd'] == 'loadrep' || $_POST['cmd'] == 'checkdatalib' || $_POST['cmd'] == 'loaddatalib') {
	$objdir = './gamedata/remote_replays/'.$_POST['sign'].'/'.str_replace('.dat','',$_POST['filename']);
	$objfile = $objdir.'/'.$_POST['filename'];
	if($_POST['cmd'] == 'checkdatalib') $objfile = str_replace('.dat','.datalib.txt',$objfile);
	elseif($_POST['cmd'] == 'loaddatalib') $objfile = './gamedata/remote_replays/'.$_POST['sign'].'/datalib/'.$_POST['filename'];
	if (file_exists($objfile))
	{
		ob_clean();
	  echo file_get_contents($objfile);
	}else{
		echo $objfile . ' does not exist. ';
	}
}elseif(strpos($_POST['cmd'],'storage_callback')===0) {
	if($_POST['cmd'] == 'storage_callback') {
		$objfile = './gamedata/replays/'.$_POST['filename'];
	}elseif($_POST['cmd'] == 'storage_callback_datalib'){
		$objfile = './gamedata/javascript/'.$_POST['datalibname'];
	}
	if (file_exists($objfile))
	{
		ob_clean();
	  echo file_get_contents($objfile);
	}else{
		echo $objfile . ' does not exist. ';
	}
}else{
	echo 'Bad command';
}

/* End of file replay_receive.php */
/* Location: /replay_receive.php */