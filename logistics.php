<?php

define('CURSCRIPT', 'logistics');

require './include/common.inc.php';

eval(import_module('cardbase'));

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
$udata = udata_check();

extract($udata);
$cg = $udata['gold'];
$card_data = \cardbase\get_cardlist_energy_from_udata($udata)[2];

$cardshop_list = \logistics\get_cardshop_list($udata);

if ($cardchoice)
{
	$res=\logistics\logistics_buy($cardchoice, 1, $udata);
	header("Refresh:0");
}

include template('logistics');

/* End of file logistics.php */
/* Location: /logistics.php */