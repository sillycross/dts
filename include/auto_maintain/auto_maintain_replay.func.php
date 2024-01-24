<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

//记录每次维护的进度
function amr_maintain_progress_save()
{
	global $amr_progress_no, $amr_progress_sno, $amr_failure_no, $amr_failure_sno;
	$mfile = GAME_ROOT.'./gamedata/cache/auto_maintain_replay.config.php';
	writeover($mfile, "<?php\r\n\$amr_progress_no = {$amr_progress_no};\r\n\$amr_progress_sno = {$amr_progress_sno};\r\n\$amr_failure_no = ".var_export($amr_failure_no,1).";\r\n\$amr_failure_sno = ".var_export($amr_failure_sno,1).";");
}

//读取每次维护的进度
function amr_maintain_progress_get()
{
	$amr_progress_no = $amr_progress_sno = 0;
	$amr_failure_no = $amr_failure_sno = array();
	$mfile = GAME_ROOT.'./gamedata/cache/auto_maintain_replay.config.php';
	if(file_exists($mfile)) {
		include $mfile;
	}
	return array($amr_progress_no, $amr_progress_sno, $amr_failure_no, $amr_failure_sno);
}

//创建需要传递的录像列表
function amr_list_create($file){
	global $amr_progress_no, $amr_progress_sno, $amr_failure_no, $amr_failure_sno;
	list($amr_progress_no, $amr_progress_sno, $amr_failure_no, $amr_failure_sno) = amr_maintain_progress_get();
	$list = array();
	$source = GAME_ROOT.'./gamedata/replays';
	if ($handle=opendir($source)) 
	{
		while (($entry=readdir($handle))!==false)
		{  
			if( $entry!="." && $entry!=".." && !is_dir($source."/".$entry) && substr($entry, strlen($entry)-4)=='.dat')
			{
				if(!amr_check_replay_sent($entry)) $list[] = $entry;
			}
		}
	}
	sort($list);
	file_put_contents($file, json_encode($list));
	return $list;
}

//判定一个文件名的录像是否顺利发送过
function amr_check_replay_sent($filename){
	global $amr_progress_no, $amr_progress_sno, $amr_failure_no, $amr_failure_sno;
	$ret = 1;
	if(substr($filename,0,1) != 's') {
		$no = (int)substr($filename,0,-4);
		if($no > $amr_progress_no || in_array($no, $amr_failure_no)) $ret = 0;
	}else{
		$no = (int)substr($filename,2,-4);
		if($no > $amr_progress_sno || in_array($no, $amr_failure_sno)) $ret = 0;
	}
	return $ret;
}

//获取需要传递的录像列表，如果不存在则新建
function amr_list_get($file) {
	if(!file_exists($file)){
		$list = amr_list_create($file);
	}else{
		$list = json_decode(file_get_contents($file),1);
	}
	return $list;
}

//判定curl_post给远端replay_receive.php的返回值
//0为正常传递，1为远端已经存在，2以后为各种错误
function amr_check_send_success($response){
	$ret = 99;
	if(strpos($response, 'Successfully')!==false) $ret = 0;
	elseif(strpos($response, 'already')!==false) $ret = 1;
	else $ret = 2;
	return $ret;
}

//传递录像
function amr_curl_post($filename){
	global $replay_remote_storage, $replay_remote_storage_sign, $replay_remote_storage_key, $gameurl;
	if(!$replay_remote_storage) return;
	$gameurl0 = '/' == substr($gameurl,strlen($gameurl)-1) ? substr($gameurl,0,-1) : $gameurl;
	$context = array(
		'sign'=>$replay_remote_storage_sign, 
		'pass'=>$replay_remote_storage_key, 
		'cmd'=>'storage_req', 
		'filename'=>$filename,
		'callurl'=>$gameurl0.'/replay_receive.php',
		'no-overlap'=>1,
	);
	
	//如果不成功，同编号最多尝试3次
	for($i=0;$i<3;$i++) {
		$ret = curl_post($replay_remote_storage, $context);
		$retno = amr_check_send_success($ret);
		if($retno <= 1) break;
	}
	return array($retno, $ret);
}

function amr_main(){
	global $replay_remote_storage, $amr_progress_no, $amr_progress_sno, $amr_failure_no, $amr_failure_sno, $amr_logfile;
	$file = GAME_ROOT.'./gamedata/cache/tmp_amrlist.dat';
	$amr_logfile = GAME_ROOT.'./gamedata/tmp/log/auto_maintain_replay.txt';
	amr_log(date("Y-m-d H:i:s")."\r\n".'Replay maintain started.');
	if(!$replay_remote_storage) {
		amr_log('No remote storage setting.');
		return;
	}
	$list = amr_list_get($file);
	//清空失败列表
	$amr_failure_no = $amr_failure_sno = array();
	foreach($list as $li => $filename){
		$mark = substr($filename,0,1) == 's' ? 's' : '';
		$no = $mark == 's' ? substr($filename, 2, -4) : substr($filename, 0, -4);
		list($retno, $ret) = amr_curl_post($filename);
		if($mark != 's') {
			if($retno > 1) $amr_failure_no[] = $no;
			$amr_progress_no = $no;
		}else {
			if($retno > 1) $amr_failure_sno[] = $no;
			$amr_progress_sno = $no;
		}
		amr_log("File $filename sent. ".($retno <= 1 ? 'Successful. ' : 'Failure: ')."$ret ");
		if(!$retno) sleep(5);//如果成功传输，等待5秒，避免流量轰炸
	}
	amr_maintain_progress_save();
	amr_log("Maintaining over.\r\n--------------------------------\r\n");
	unlink($file);
}

function amr_log($log){
	global $amr_logfile;
	writeover($amr_logfile, $log."\r\n", 'ab+');
}

/* End of file auto_maintain_replay.func.php */
/* Location: /include/auto_maintain/auto_maintain_replay.func.php */