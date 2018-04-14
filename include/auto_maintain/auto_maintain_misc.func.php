<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function am_log($mlog)
{
	global $am_logfile;
	writeover($am_logfile, $mlog."\r\n", 'ab+');
}

/* End of file auto_maintain_misc.func.php */
/* Location: /include/auto_maintain/auto_maintain_misc.func.php */