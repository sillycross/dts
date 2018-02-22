<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
//if($mygroup < 6){
//	exit($_ERROR['no_power']);
//}
$dir = GAME_ROOT.'./gamedata/';

if($command == 'write'){
	$nmlimit = astrfilter($postnmlmt);
	$iplimit = astrfilter($postiplmt);
	writeover("{$dir}banlist.list","<?php\n\n\$nmlimit = '$nmlimit';\n\$iplimit = '$iplimit';\n\n?>");
	//write_list($dir,$postnmlmt,$postiplmt);
	$cmd_info = '新的屏蔽列表已经写入。';
	adminlog('editbanlist',gencode($nmlimit),gencode($iplimit));
}else{
	include_once $dir.'banlist.list';
}

include template('admin_banlistmng');
?>