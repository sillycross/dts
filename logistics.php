<?php

define('CURSCRIPT', 'logistics');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require GAME_ROOT.'./include/global.func.php';

if(!empty($_GET['type'])) {
	$_POST['type'] = $_GET['type'];
}

echo render_page('command_logistics');

/* End of file logistics.php */
/* Location: /logistics.php */