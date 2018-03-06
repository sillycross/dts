<?php

define('CURSCRIPT', 'valid');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';


echo render_page('command_valid');

/* End of file valid.php */
/* Location: /valid.php */