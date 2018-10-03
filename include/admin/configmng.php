<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

if($command == 'edit') {
	
	$ednum = 0;
	$edfmt = Array('authkey'=>'','bbsurl'=>'','gtitle'=>'','gameurl'=>'','server_address'=>'','homepage'=>'','moveut'=>'int','moveutmin'=>'int','tplrefresh'=>'b','errorinfo'=>'b');
	//system.config.php的也放这了
	$edfmt_system = Array('userdb_remote_storage'=>'','userdb_remote_storage_sign'=>'','userdb_remote_storage_pass'=>'','replay_remote_send'=>'b','replay_remote_storage'=>'','replay_remote_storage_sign'=>'','replay_remote_storage_key'=>'',);
	$edfmt_both = array_merge($edfmt,$edfmt_system);
	$edlist = Array();
	$cmd_info = '';
	foreach($edfmt_both as $key => $val){
		if(isset($_POST[$key])){
			${'o_'.$key} = ${$key};
			if($val == 'int'){
				${$key} = intval($_POST[$key]);
			}elseif($val == 'b'){
				intval($_POST[$key]) != 0 ? ${$key} = 1 : ${$key} = 0;
			}else{
				${$key} = astrfilter($_POST[$key]);
			}
			if(${$key} != ${'o_'.$key}){
				$ednum ++;
				if(${$key}===''){
					$cmd_info .= "$lang[$key] 已清空<br>";
				}else{
					$cmd_info .= "$lang[$key] 修改为 ${$key} <br>";
				}
				$edlist[$key] = ${$key};
			}
		}
	}
	
	$cmd_info .= "提交的修改请求数量： $ednum <br>";
	
	if($ednum){
		$runflagnotice = 0;
		foreach(array('server', 'system') as $sn){
			$cf = GAME_ROOT.'./include/modules/core/sys/config/'.$sn.'.config.php';
			$config_cont = file_get_contents($cf);
			$edfmt_system_key = array_keys($edfmt_system);
			
			foreach($edlist as $key => $val){
				if((!in_array($key, $edfmt_system_key) && 'server' == $sn) || (in_array($key, $edfmt_system_key) && 'system' == $sn)) {
					if($edfmt_both[$key] == 'int' || $edfmt_both[$key] == 'b'){
						$config_cont = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $config_cont);
					}else{
						$config_cont = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '${$key}';", $config_cont);
					}
				}
			}
			file_put_contents($cf,$config_cont);
			$cf_run = GAME_ROOT.'./gamedata/run/core/sys/config/'.$sn.'.config.adv.php';
			if($___MOD_CODE_ADV1 && file_exists($cf_run)){
				file_put_contents($cf_run,$config_cont);
				$runflagnotice = 1;
			}
		}
		
		if($runflagnotice) $cmd_info .= '监测到ADV模式已打开，对应运行时文件已修改。<br>';
		
		adminlog('configmng',gencode($edlist));
		$cmd_info .= '服务参数已修改';
	}
}
$sysnow = time();
list($nowsec,$nowmin,$nowhour,$nowday,$nowmonth,$nowyear,$nowwday,$nowyday,$nowisdst) = localtime($sysnow);
$nowmonth++;
$nowyear += 1900;
$orin_time = $nowyear.$lang['year'].$nowmonth.$lang['month'].$nowday.$lang['day'].$nowhour.$lang['hour'].$nowmin.$lang['min'];
list($setsec,$setmin,$sethour,$setday,$setmonth,$setyear,$setwday,$setyday,$setisdst) = localtime($sysnow + $moveut*3600 + $moveutmin*60);
$setmonth++;
$setyear += 1900;
$set_time = $setyear.$lang['year'].$setmonth.$lang['month'].$setday.$lang['day'].$sethour.$lang['hour'].$setmin.$lang['min'];

include template('admin_configmng');
?>
