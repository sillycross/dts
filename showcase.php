<?php

define('CURSCRIPT', 'logistics');

require './include/common.inc.php';

$_REQUEST = gstrfilter($_REQUEST);

if (empty($_REQUEST["playerID"])) {
	$udata = udata_check();
	
	$n=$cuser;
	extract($udata);
	$curuser=true;
} else {
	$uname=urldecode($_REQUEST["playerID"]);
	$udata = fetch_udata_by_username($uname);
	if(empty($udata)) { gexit($_ERROR['user_not_exists'],__file__,__line__); }
	extract($udata);
	$curuser=false;
	if ($uname==$cuser) $curuser=true;
	$n=$uname;
}

eval(import_module('logistics','cardbase'));
$showcase_cardlist = \logistics\get_showcase_cardlist_from_udata($udata);
$showcase_gameitemlist = \logistics\get_showcase_gameitemlist_from_udata($udata);
$showcase_logitemlist = \logistics\get_showcase_logitemlist_from_udata($udata);
list($cardlist, $cardenergy, $card_data) = \cardbase\get_cardlist_energy_from_udata($udata);

include template('showcase');

/* End of file logistics.php */
/* Location: /logistics.php */