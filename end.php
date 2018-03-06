<?php

define('CURSCRIPT', 'end');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';

echo render_page('command_end');

/* End of file end.php */
/* Location: /end.php */