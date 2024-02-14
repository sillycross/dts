<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

eval(import_module('sys'));

if(!$cuser||!$cpass) {
	gexit($_ERROR['no_login'],__file__,__line__);
	return;
}
$udata = udata_check();

extract($udata);

$type = get_var_input('type');
if(empty($type)) $type = 0;

$template_id = '';

if (!$type || ($type == 1))
{
	eval(import_module('cardbase'));
	//$o = \cardbase\get_cardlist_energy_from_udata($udata)[2];
	$cardchoice = get_var_input('cardchoice');
	if (!empty($cardchoice))
	{
		$res = \logistics\logistics_buy($cardchoice, 1, $udata);
		if($res > 0) {
			$notice = '购买成功。';
		}else{
			$notice = '购买失败。';
		}
	}

	$card_data = \cardbase\get_cardlist_energy_from_udata($udata)[2];
	//echo var_export($o, 1).'<br>'.var_export($card_data, 1).'<br>';
	$cardshop_list = \logistics\get_cardshop_list($udata);
	
	$template_id = 'log_cardshop';
}
elseif ($type == 2)
{
	eval(import_module('logistics'));
	$itemchoice = get_var_input('itemchoice');
	if (!empty($itemchoice))
	{
		$res = \logistics\logistics_buy($itemchoice, 2, $udata);
		if($res > 0) {
			$notice = '购买成功。';
		}else{
			$notice = '购买失败。';
		}
	}
	$logitemlist = \logistics\logistics_get_itemlist_from_udata($udata);
	
	$template_id = 'log_itemshop';
}
elseif ($type == 4)
{
	eval(import_module('logistics','cardbase'));
	$cardchoice = get_var_input('cardchoice');
	$cardpos = get_var_input('cardpos');
	if (NULL !== $cardchoice)
	{
		$res = \logistics\set_showcase_card($cardchoice, $cardpos, $udata);
		if($res > 0) {
			$notice = '设置成功。';
		}else{
			$notice = '设置失败。';
		}
	}

	$showcase_cardlist = \logistics\get_showcase_cardlist_from_udata($udata);
	list($cardlist, $cardenergy, $card_data) = \cardbase\get_cardlist_energy_from_udata($udata);
	$showcase_gameitemlist = \logistics\get_showcase_gameitemlist_from_udata($udata);
	$showcase_logitemlist = \logistics\get_showcase_logitemlist_from_udata($udata);
	
	$template_id = 'log_showcase';
}
else
{
	eval(import_module('logistics','cardbase'));
	$itemchoice = get_var_input('itemchoice');
	$itempara = get_var_input('itempara');
	if (!empty($itemchoice))
	{
		$res = \logistics\logistics_itemuse($itemchoice, $itempara, $udata);
		if (!empty($res))
		{
			$notice = '使用成功。'.$res;
		}else{
			$notice = '使用失败。';
		}
	}

	$logitemlist = \logistics\logistics_get_itemlist_from_udata($udata);
	$cardlist = \cardbase\get_cardlist_energy_from_udata($udata)[0];
	$showcase_logitemlist = \logistics\get_showcase_logitemlist_from_udata($udata);
	
	$template_id = 'log_inventory';
}

$cg = $udata['gold'];

if(!empty($template_id)) {
	ob_start();
	include template($template_id);
	ob_end_flush();
}

/* End of file command_logistics.php */
/* Location: /include/pages/command_logistics.php */