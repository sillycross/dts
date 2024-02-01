<?php

define('CURSCRIPT', 'logistics');

require './include/common.inc.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }
$udata = udata_check();

extract($udata);
$cg = $udata['gold'];

if (!$type || ($type == 1))
{
	eval(import_module('cardbase'));
	$card_data = \cardbase\get_cardlist_energy_from_udata($udata)[2];
	$cardshop_list = \logistics\get_cardshop_list($udata);
	
	if (!empty($cardchoice))
	{
		$res = \logistics\logistics_buy($cardchoice, 1, $udata);
		header("Refresh:0");
	}
	
	include template('log_cardshop');
}
elseif ($type == 2)
{
	eval(import_module('logistics'));
	$logitemlist = \logistics\logistics_get_itemlist_from_udata($udata);
	
	if (!empty($itemchoice))
	{
		$res = \logistics\logistics_buy($itemchoice, 2, $udata);
		header("Refresh:0");
	}
	
	include template('log_itemshop');
}
else
{
	eval(import_module('logistics','cardbase'));
	$logitemlist = \logistics\logistics_get_itemlist_from_udata($udata);
	$cardlist = \cardbase\get_cardlist_energy_from_udata($udata)[0];
	
	if (!empty($itemchoice))
	{
		$res = \logistics\logistics_itemuse($itemchoice, $itempara, $udata);
	}
	
	include template('log_inventory');
}

/* End of file logistics.php */
/* Location: /logistics.php */