<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}


$db->query("UPDATE {$gtablepre}users SET totalcredits=totalcredits+floor(credits/2000)");
$db->query("UPDATE {$gtablepre}users SET credits='0'");
$db->query("UPDATE {$gtablepre}users SET elo_playedtimes='0'");
$db->query("UPDATE {$gtablepre}users SET elo_rating='1500'");
$db->query("UPDATE {$gtablepre}users SET elo_volatility='400'");
$db->query("UPDATE {$gtablepre}users SET elo_history=''");

include template('admin_menu');

?>