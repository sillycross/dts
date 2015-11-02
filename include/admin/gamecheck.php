<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

if($gamestate >= 20){

	$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=0");
	$validnum = $db->num_rows($result);
	
	$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp>0 AND type=0");
	$alivenum = $db->num_rows($result);
	
	$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp<=0 OR state>=10");
	$deathnum = $db->num_rows($result);
	
	\map\movehtm();
	
	save_gameinfo();
	
	adminlog('infomng');
	
	$cmd_info = "状态更新：激活人数 {$validnum},生存人数 {$alivenum},死亡人数 {$deathnum}<br>";
	$cmd_info .= "已重置移动地点缓存数据";
}else{
	$cmd_info = "当前游戏未开始！";
}

/*$result=$db->query("SHOW FULL COLUMNS FROM {$tablepre}players");
while ($row=$db->fetch_array($result)){
	foreach ($row as $val)
		echo $val."<br>";
}*/
/*
$db->query("ALTER TABLE {$tablepre}swinners ADD cardname text not null AFTER gdlist");
$db->query("ALTER TABLE {$tablepre}winners ADD cardname text not null AFTER gdlist");
$db->query("ALTER TABLE {$tablepre}users ADD gold int(10) unsigned not null default '0' AFTER n_achievements");
$db->query("ALTER TABLE {$tablepre}users ADD cardlist text not null AFTER n_achievements");
$db->query("ALTER TABLE {$tablepre}users ADD card int(10) unsigned not null default '0' AFTER n_achievements");
$db->query("ALTER TABLE {$tablepre}users ADD cd_s int(10) unsigned not null default '0' AFTER n_achievements");
$db->query("ALTER TABLE {$tablepre}users ADD cd_a int(10) unsigned not null default '0' AFTER n_achievements");
$db->query("ALTER TABLE {$tablepre}users ADD cd_a1 int(10) unsigned not null default '0' AFTER n_achievements");
$db->query("ALTER TABLE {$tablepre}users ADD cd_a int(10) unsigned not null default '0' AFTER n_achievements");
$db->query("ALTER TABLE {$tablepre}users ADD cd_b int(10) unsigned not null default '0' AFTER n_achievements");
$db->query("ALTER TABLE {$tablepre}users ADD lastwin int(10) unsigned not null default '0' AFTER n_achievements");
$db->query("ALTER TABLE {$tablepre}players ADD card int(10) unsigned not null default '0' AFTER flare");
$db->query("ALTER TABLE {$tablepre}players ADD cardname text not null AFTER flare");*/

include template('admin_menu');

?>