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

$url = '127.0.0.1/dts1/dts/userdb_receive.php';
$context = array(
	'sign' => 'local',
	'pass' => '142857',
	'command' => 'get_ip',
	'para1' => gencode(Array(array('username'=>'b','groupid'=>'3'),array('username'=>'c','groupid'=>'4'))),
);
//echo curl_post($url, $context);
var_dump(gdecode(curl_post($url, $context),1));
/* End of file userdb_receive.php */
/* Location: /userdb_receive.php */