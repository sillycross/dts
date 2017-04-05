<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

if($command == 'edit') {
	
	$ednum = 0;
	$edfmt = Array('authkey'=>'','bbsurl'=>'','gameurl'=>'','homepage'=>'','moveut'=>'int','moveutmin'=>'int','tplrefresh'=>'b','errorinfo'=>'b');
	$edlist = Array();
	$cmd_info = '';
	foreach($edfmt as $key => $val){
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
		//$adminlog = '';
		$cf = dirname(dirname(__FILE__)).'./modules/core/sys/config/server.config.php';
		$configfile = file_get_contents($cf);
		foreach($edlist as $key => $val){
			if($edfmt[$key] == 'int' || $edfmt[$key] == 'b'){
				$configfile = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $configfile);
			}else{
				$configfile = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '${$key}';", $configfile);
			}
			
			//$adminlog .= setadminlog('configmng',$key,$val);
		}
		file_put_contents($cf,$configfile);
		//putadminlog($adminlog);
		adminlog('configmng');
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
