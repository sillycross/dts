<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($command == 'edit') {
	$ednum = 0;
	$edfmt = Array(
		'areahour'=>'int',
		'areaadd'=>'int',
		'arealimit'=>'int',
		'areaesc'=>'int',
		'antiAFKertime'=>'int',
		'corpseprotect'=>'int',
		'coldtimeon'=>'b',
		'showcoldtimer'=>'b',
		'validlimit'=>'int',
		'combolimit'=>'int',
		'deathlimit'=>'int',
		'splimit'=>'int',
		'hplimit'=>'int',
		'sleep_time'=>'int',
		'heal_time'=>'int',
		'teamlimit'=>'int'
		
	);
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
		$adminlog = '';
		$gamecfg_file = config('gamecfg',$gamecfg);
		$file = file_get_contents($gamecfg_file);
		foreach($edlist as $key => $val){
			if($edfmt[$key] == 'int' || $edfmt[$key] == 'b'){
				$file = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $file);
			}else{
				$file = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '${$key}';", $file);
			}
			
		}
		file_put_contents($gamecfg_file,$file);
		adminlog('gamecfgmng',$gamecfg);
		$cmd_info .= '游戏数据修改完毕';
	}
}
include template('admin_gamecfg');
?>