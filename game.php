<?php

define('CURSCRIPT', 'game');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';
include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';

if(isset($_POST['mode']) && $_POST['mode'] == 'quit') {
	gsetcookie('user','');
	gsetcookie('pass','');
	header("Location: index.php");
	exit();
}

echo render_page('command_game');

/* End of file game.php */
/* Location: /game.php */