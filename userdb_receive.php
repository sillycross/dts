<?php
ignore_user_abort(1);//这一代码基本上是以异步调用的方式执行的

define('CURSCRIPT', 'userdb_receive');
define('IN_GAME', true);

//啥也不载入，只判断密钥是否匹配
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';
require GAME_ROOT.'./include/user.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
include GAME_ROOT.'./include/modules/core/sys/config/system.config.php';

$db = init_dbstuff();

$valid = false;
if(isset($_POST['sign']) && isset($_POST['pass'])) {
	foreach($userdb_receive_list as $rs => $rp){
		if($rs === $_POST['sign'] && $rp['pass'] === $_POST['pass'] && (empty($rp['ip']) || $rp['ip'] == real_ip())){
			$valid = true;
			break;
		}
	} 
}
if(!$valid) {//所有请求都必须判定密码
	exit( 'Error: Invalid sign');
}
if(empty($_POST['command'])) {
	exit( 'Error: Invalid command');
}else{
	$command = $_POST['command'];
	$para1 = !empty($_POST['para1']) ? $_POST['para1'] : NULL;
	$para2 = !empty($_POST['para2']) ? $_POST['para2'] : NULL;
	$para3 = !empty($_POST['para3']) ? $_POST['para3'] : NULL;
	$para4 = !empty($_POST['para4']) ? $_POST['para4'] : NULL;
	$para5 = !empty($_POST['para5']) ? $_POST['para5'] : NULL;
	
	$userdb_foreced_local = 1;
	$userdb_foreced_key = !empty($_POST['key']) ? $_POST['key'] : NULL;
	
	if('get_ip' == $command){
		$ret = real_ip();
	}elseif('fetch_udata' == $command) {
		//查询1次不可超过500条返回结果
		if(userdb_receive_count($para2, $para3) > 500) exit('Error: Too many results');
		$ret = fetch_udata($para1, $para2, $para3, $para4, $para5);
		userdb_receive_save_pool($userdb_foreced_key);
	}elseif('insert_udata' == $command){
		$para1 = gdecode($para1, 1);
		//插入1次不可超过10条数据
		if(!isset($para1['username']) && sizeof($para1) > 10) exit('Error: Too many inserts');
		$ret = insert_udata($para1, $para2, $para3, $para4, $para5);
	}elseif('update_udata' == $command){
		$para1 = gdecode($para1, 1);
		//gwrite_var('a.txt',$para1);
		//更改1次不可超过500条涉及对象
		if(userdb_receive_count($para2) > 500) exit('Error: Too many updates');
		$ret = update_udata($para1, $para2, $para3, $para4, $para5);
	}elseif('update_udata_multilist' == $command){
		$para1 = gdecode($para1, 1);
		//更改1次不可超过500条涉及对象
		if(sizeof($para1) > 500) exit('Error: Too many updates');
		$ret = update_udata_multilist($para1, $para2, $para3, $para4, $para5);
	}elseif('release_user_lock_from_pool' == $command){
		userdb_receive_load_pool($userdb_foreced_key);
		release_user_lock_from_pool($userdb_foreced_key);
		userdb_receive_clean_pool($userdb_foreced_key);
		$ret = 'Info: Release success';
	}else{
		exit( 'Error: Invalid command 2');
	}
	echo gencode($ret);
}

function userdb_receive_count($where, $sort=''){
	$tmp = fetch_udata('COUNT(*)', $where, $sort);
	return array_shift($tmp[0]);
}

function userdb_receive_save_pool($key){
	global $udata_lock_pool;
	writeover('./gamedata/tmp/userlock/'.$key.'.pool', gencode($udata_lock_pool));
}

function userdb_receive_load_pool($key){
	global $udata_lock_pool;
	$file = './gamedata/tmp/userlock/'.$key.'.pool';
	if(file_exists($file)) {
		$udata_lock_pool = gdecode(trim(file_get_contents($file)),1);
	}
}

function userdb_receive_clean_pool($key){
	$file = './gamedata/tmp/userlock/'.$key.'.pool';
	if(file_exists($file)) {
		unlink($file);
	}
}
/* End of file userdb_receive.php */
/* Location: /userdb_receive.php */