<?php

define('CURSCRIPT', 'kujilist');

require './include/common.inc.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
$udata = udata_check();

extract($udata);
$cg=$udata['gold'];

eval(import_module('kujibase'));
	
$kreq=$kujicost;

include template('kujilist');

/* End of file kujilist.php */
/* Location: /kujilist.php */