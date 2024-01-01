<?php

define('CURSCRIPT', 'updatelist');
require './include/common.inc.php';

if(empty($y)) include template('updatelist_container');
elseif($y) {
	$gamedata = array();
	if(in_array($y, array('2023', '2018', '2017', '2016'))) {
		ob_start();
		include template('updatelist_'.$y);
		$gamedata['innerHTML']['cont'.$y] = ob_get_contents();
		ob_clean();
		ob_end_flush();
		if(isset($error)) $gamedata['innerHTML']['error'] = $error;
		$jgamedata = gencode($gamedata);
		echo $jgamedata;
	}
}

?>