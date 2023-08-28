<?php

define('CURSCRIPT', 'valid');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';
require GAME_ROOT.'./include/user.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';

$_POST['valid_ip'] = real_ip();

echo render_page('command_valid');

/* End of file valid.php */
/* Location: /valid.php */