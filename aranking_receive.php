<?php
ignore_user_abort(1);//这一代码基本上是以异步调用的方式执行的

define('CURSCRIPT', 'aranking_receive');
define('IN_GAME', true);

//啥也不载入，只判断密钥是否匹配
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require './include/common.inc.php';

//跟userdb共用一套用户名密码
$valid = false;
if(isset($sign) && isset($pass)) {
	foreach($userdb_receive_list as $rs => $rp){
		if($rs === $sign && compare_ts_pass($pass, $rp['pass']) && (empty($rp['ip']) || $rp['ip'] == real_ip())){
			$valid = true;
			break;
		}
	} 
}
if(!$valid) {//所有请求都必须判定密码
	exit( 'Error: Invalid sign');
}

if(empty($cmd)) {
	exit( 'Error: Invalid command');
}else{
	$userdb_forced_local = 1;
	
	$para1 = !empty($para1) ? $para1 : NULL;
	$para2 = !empty($para2) ? $para2 : NULL;
	
	if('load_aranking' == $cmd) {
		$ret = \activity_ranking\load_aranking($para1, $para2);
	}elseif('save_ulist_aranking' == $cmd){
		$para2 = gdecode($para2, 1);
		\activity_ranking\save_ulist_aranking($para1, $para2);
		$ret = 'Successed.';
	}else{
		exit( 'Error: Invalid command 2');
	}
	echo gencode($ret);
	
}

/* End of file aranking_receive.php */
/* Location: /aranking_receive.php */