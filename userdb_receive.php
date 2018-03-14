<?php
ignore_user_abort(1);//这一代码基本上是以异步调用的方式执行的

define('CURSCRIPT', 'userdb_receive');
define('IN_GAME', true);

//啥也不载入，只判断密钥是否匹配
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
ini_set('post_max_size', '20M');

require GAME_ROOT.'./include/global.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
include GAME_ROOT.'./include/modules/core/sys/config/system.config.php';

$valid = false;
if(isset($_POST['sign']) && isset($_POST['pass'])) {
	foreach($userdb_receive_list as $rs => $rp){
		if($rs === $_POST['sign'] && $rp === $_POST['pass']){
			$valid = true;
			break;
		}
	} 
}
if(!$valid) {//所有请求都必须判定密码
	exit( 'Invalid Sign');
}
/* End of file userdb_receive.php */
/* Location: /userdb_receive.php */