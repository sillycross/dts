<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
$configlist = array(
	'game' => './include/modules/core/sys/config/game.config.php',
	'map' => './include/modules/core/map/config/map.config.php',
	'gameflow_combo' => './include/modules/core/gameflow/gameflow_combo/config/gameflow_combo.config.php',
	'gameflow_antiafk' => './include/modules/core/gameflow/gameflow_antiafk/config/gameflow_antiafk.config.php',
	'corpse' => './include/modules/base/corpse/config/corpse.config.php',
	'rest' => './include/modules/base/rest/config/rest.config.php',
	'team' => './include/modules/base/team/config/team.config.php',
	'cooldown' => './include/modules/base/cooldown/config/cooldown.config.php'
);
foreach($configlist as $cval){
	include $cval;
}
if($command == 'edit') {
	$ednum = 0;
	$edfmt = Array(
		'game' => array(
			'validlimit'=>'int',
			'splimit'=>'int',
			'hplimit'=>'int',
			'hack_obbs'=>'int'
		),
		'map' => array(
			'areahour'=>'int',
			'areaadd'=>'int',
			'areawarntime'=>'int',
			'arealimit'=>'int',
			'areaesc'=>'b'
		),
		'gameflow_antiafk' => array(
			'antiAFKertime'=>'int'
		),
		'gameflow_combo' => array(
			'combolimit'=>'int',
			'deathlimit'=>'int'
		),
		'corpse' => array(
			'corpseprotect'=>'int'
		),
		'rest' => array(
			'rest_sleep_time'=>'int',
			'rest_heal_time'=>'int'
		),
		'cooldown' => array(
			'coldtimeon'=>'b',
			'movecoldtime'=>'int',
			'searchcoldtime'=>'int',
			'itemusecoldtime'=>'int',
			'showcoldtimer'=>'b'
		),
		'team' => array(
			'teamlimit'=>'int'
		)
	);
	$edlist = Array();
	$cmd_info = '';
	foreach($edfmt as $fkey => $fval){
		foreach($fval as $key => $val){
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
					if(isset($edlist[$fkey])) $edlist[$fkey][$key] = ${$key};
					else $edlist[$fkey] = array( $key => ${$key});
				}
			}
		}
	}
	
	
	$cmd_info .= "提交的修改请求数量： $ednum <br>";
	
	if($ednum){
		$adminlog = '';
		foreach($edlist as $fkey => $fval){
			
			$gamecfg_file = $configlist[$fkey];
			$file = file_get_contents($gamecfg_file);
			foreach($fval as $key => $val){
				//$cmd_info .= $key.' '.$val;
				if($edfmt[$fkey][$key] == 'int' || $edfmt[$fkey][$key] == 'b'){
					$file = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = $val;", $file);
				}else{
					$file = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '$val';", $file);
				}				
			}
			file_put_contents($gamecfg_file,$file);
		}
		adminlog('gamecfgmng',$gamecfg);
		$cmd_info .= '游戏数据修改完毕';
	}
}
include template('admin_gamecfg');
?>