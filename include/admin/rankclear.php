<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

//$db->query("ALTER TABLE {$tablepre}users ADD totalcredits int(10) unsigned not null default '0' after credits");
$db->query("UPDATE {$tablepre}users SET totalcredits=totalcredits+floor(credits/2000)");
$db->query("UPDATE {$tablepre}users SET credits='0'");

include template('admin_menu');

?>