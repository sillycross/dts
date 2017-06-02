<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 2){
	exit($_ERROR['no_power']);
}



$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type=0");
$validnum = $db->num_rows($result);

$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp>0 AND type=0");
$alivenum = $db->num_rows($result);

$result = $db->query("SELECT pid FROM {$tablepre}players WHERE hp<=0 OR state>=10");
$deathnum = $db->num_rows($result);

//\map\movehtm();

save_gameinfo();

adminlog('infomng');
echo "状态更新：激活人数 {$validnum},生存人数 {$alivenum},死亡人数 {$deathnum}<br>";
echo "已重置移动地点缓存数据";
?>