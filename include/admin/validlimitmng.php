<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 6){
	exit($_ERROR['no_power']);
}
$dir = GAME_ROOT.'./gamedata/';

if($write){
	write_valid_limit($dir,$postnmlmt,$postiplmt);
	echo '新的屏蔽列表已经写入。<br>';
}

include_once $dir.'banlist.list';

//foreach(Array('nm','ip') as $ar_nm){
//	${$ar_nm.'lmtlist0'} = ${$ar_nm.'lmtlist'} = '';
//	foreach(${$ar_nm.'limit'} as $value){
//		${$ar_nm.'lmtlist'} .= $value.'|';
//	}
//	if(${$ar_nm.'lmtlist0'} != ${$ar_nm.'lmtlist'}){
//		${$ar_nm.'lmtlist'} = substr(${$ar_nm.'lmtlist'},0,-1);
//	}
//}

function write_valid_limit($dir,$nmlmtstr,$iplmtstr){
	foreach(Array('nm','ip') as $ar_nm){
		${$ar_nm.'lmtarray'} = explode('|',${$ar_nm.'lmtstr'});
		${$ar_nm.'lmtlist0'} = ${$ar_nm.'lmtlist'} = '';
		foreach(${$ar_nm.'lmtarray'} as $value){
			${$ar_nm.'lmtlist'} .= "'$value',";
		}
		if(${$ar_nm.'lmtlist0'} != ${$ar_nm.'lmtlist'}){
			${$ar_nm.'lmtlist'} = 'Array('.substr(${$ar_nm.'lmtlist'},0,-1).')';
		}else{
			${$ar_nm.'lmtlist'} = 'Array()';
		}
	}
	$vldata = "<?php\n\n\$nmlimit = {$nmlmtlist};\n\$iplimit = {$iplmtlist};\n\n?>";
	if($fp = fopen("{$dir}banlist.list", 'w')) {
		if(flock($fp,LOCK_EX)) {
			fwrite($fp, $vldata);
		} else {
			exit("Couldn't save the game's info !");
		}
		fclose($fp);
	} else {
		gexit('Can not write to cache files, please check directory ./gamedata/ .', __file__, __line__);
	}
	return;
}
echo <<<EOT
<form method="post" name="banlist" onsubmit="admin.php">
<input type="hidden" name="mode" value="banlistmng">
<input type="hidden" name="command" value="banlistmng">
<input type="hidden" name="write" value="1">
<div>输入要屏蔽的用户名和IP段，用|隔开。</div>
<div>用户名屏蔽：<br><textarea name="postnmlmt" style="width:450;height:150">$nmlist</textarea></div><br>
<div>IP段屏蔽：<br><textarea name="postiplmt" style="width:450;height:150">$iplist</textarea></div>
<input type="submit" value="提交">
</form>
EOT;
?>