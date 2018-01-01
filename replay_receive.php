<?php


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
if(!$valid && $_POST['cmd']=='storage') {//只有储存请求需要判定密码
	echo 'Invalid Sign';
}else{
	if($_POST['cmd'] == 'storage') {
		//显示比POST快得多得多，直接反向请求
		$objdir = './gamedata/remote_replays';
		if(!is_dir($objdir)) mymkdir($objdir);
		$objdir2 = $_POST['sign'];
		if(!is_dir($objdir.'/'. $objdir2)) mymkdir($objdir.'/'. $objdir2);
		$objdir3 = str_replace('.dat','',$_POST['filename']);
		if(!is_dir($objdir.'/'. $objdir2.'/'.$objdir3)) mymkdir($objdir.'/'. $objdir2.'/'.$objdir3);
		$objfile = $objdir.'/'. $objdir2.'/'.$objdir3.'/'.$_POST['filename'];
		//反向请求
		$callbackurl = $_POST['callurl'];
		$context = array(
			'cmd'=>'storagecallback', 
			'filename'=>$_POST['filename'],
			'datalibname'=>$_POST['datalibname']
		);
		$ret = send_post($callbackurl, $context); 
		//$ret = html_entity_decode($ret,ENT_COMPAT);
		list($content,$datalibcont) = gdecode($ret,1);
		//$content = $_POST['content'];
		$datalibname = $_POST['datalibname'];
		//$datalibcont = gdecode($_POST['datalibcont']);
		$objdir_datalib = $objdir.'/'. $objdir2.'/datalib';
		if(!is_dir($objdir_datalib)) mymkdir($objdir_datalib);
		$objfile_datalib = $objdir_datalib.'/'.$datalibname;
		
		if(empty($content)) echo 'Some error occurred.';
		else {
			file_put_contents($objfile, $content);
			//记录datalib的编号
			file_put_contents(str_replace('.dat','.datalib.txt',$objfile), $datalibname);
			//记录datalib
			if(!file_exists($objfile_datalib)) file_put_contents($objfile_datalib, $datalibcont);
			echo 'Successfully Received.';
		}
		
	//	if ($_FILES['file']['type'] == 'dat'){//只允许接受dat
	//		if ($_FILES['file']['error'] > 0){
	//			echo 'Error Code: ' . $_FILES["file"]["error"] . '<br />';
	//		}else{
	//			echo 'Upload: ' . $_FILES['file']['name'] . '<br />';
	//	    echo 'Type: ' . $_FILES['file']['type'] . '<br />';
	//	    echo 'Size: ' . ($_FILES['file']['size'] / 1024) . ' Kb<br />';
	//	    echo 'Temp file: ' . $_FILES['file']['tmp_name'] . '<br />';
	//	    
	//	    $objdir = './gamedata/remote_replays';
	//	    if(!is_dir($objdir)) mymkdir($objdir);
	//	    $objdir2 = $_POST['sign'];
	//	    if(!is_dir($objdir.'/'. $objdir2)) mymkdir($objdir.'/'. $objdir2);
	//	    $objfile = $objdir.'/'. $objdir2.'/'.$_FILES['file']['name'];
	//	    
	//	    if (file_exists($objfile))
	//	    {
	//	      echo $objfile . ' already exists. ';
	//	    }
	//	    else
	//	    {
	//	      move_uploaded_file($_FILES['file']['tmp_name'], $objfile);
	//	      echo 'Stored in: ' .$objfile;
	//	    }
	//		}
	//	}
	}elseif($_POST['cmd'] == 'loadrep') {
		$objdir = './gamedata/remote_replays/'.$_POST['sign'].'/'.str_replace('.dat','',$_POST['filename']);
		$objfile = $objdir.'/'.$_POST['filename'];
		if (file_exists($objfile))
		{
		  $rdata = file_get_contents($objfile);
		  $datalib_name = file_get_contents(str_replace('.dat','.datalib.txt',$objfile));
		  echo gencode(array('rdata' => $rdata, 'datalib_name' => $datalib_name));
		}else{
			echo $objfile . 'does not exist. ';
		}
	}elseif($_POST['cmd'] == 'loaddatalib') {
		$objfile = './gamedata/remote_replays/'.$_POST['sign'].'/datalib/'.$_POST['filename'];
		if (file_exists($objfile))
		{
		  $rdata = file_get_contents($objfile);
		  echo $rdata;
		}else{
			echo $objfile . 'does not exist. ';
		}
	}elseif($_POST['cmd'] == 'storagecallback') {
		$objfile = './gamedata/replays/'.$_POST['filename'];
		$datalibfile = './gamedata/javascript/'.$_POST['datalibname'];
		echo gencode(array(file_get_contents($objfile), file_get_contents($datalibfile)));
	}else{
		echo 'Bad command';
	}
}

/* End of file replay_receive.php */
/* Location: /replay_receive.php */