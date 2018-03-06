<?php

error_reporting(0);
ignore_user_abort(1);
define('CURSCRIPT', 'roomcmd');
define('IN_GAME', true);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
require GAME_ROOT.'./include/global.func.php';

$extra_context = array();
foreach($_GET as $pkey => $pval){
	$extra_context[$pkey] = $pval;
}
if(isset($_GET['command'])) $extra_context['not_ajax'] = 1;//蛋疼


echo render_page('command_roomcmd', $extra_context);

/* End of file roomcmd.php */
/* Location: /roomcmd.php */