<?php
ignore_user_abort(1);//这一代码基本上是以异步调用的方式执行的

define('CURSCRIPT', 'aranking_receive');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';
require GAME_ROOT.'./include/user.func.php';

$_POST['arealip'] = real_ip();

echo render_page('command_aranking');

/* End of file aranking_receive.php */
/* Location: /aranking_receive.php */