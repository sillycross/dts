<?php

define('CURSCRIPT', 'news');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
require GAME_ROOT.'./include/global.func.php';

echo render_page('command_news');

/* End of file news.php */
/* Location: /news.php */