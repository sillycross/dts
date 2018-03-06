<?php

define('CURSCRIPT', 'help');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
require GAME_ROOT.'./include/global.func.php';

echo render_page('command_help');

/* End of file help.php */
/* Location: /help.php */