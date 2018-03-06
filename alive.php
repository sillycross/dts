<?php

define('CURSCRIPT', 'alive');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
require GAME_ROOT.'./include/global.func.php';

echo render_page('command_alive');

/* End of file alive.php */
/* Location: /alive.php */