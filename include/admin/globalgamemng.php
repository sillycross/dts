<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

$adminmsg = file_get_contents('./gamedata/adminmsg.htm') ;
$systemmsg = file_get_contents('./gamedata/systemmsg.htm') ;
include GAME_ROOT.'./include/roommng/roommng.config.php';//由于room不是模块，参数会被input覆盖，必须再载入一次

if($command == 'edit') {
	$ednum = 0;
	$edfmt = Array(
		'adminmsg'=>'html',
		'systemmsg' => 'html',
		'startmode'=>'int',
		'starthour'=>'int',
		'startmin'=>'int',
		'readymin'=>'int',
		'iplimit'=>'int',
		'newslimit'=>'int',
		'alivelimit'=>'int',
		'winlimit'=>'int',
		'chatlimit'=>'int',
		'chatrefresh'=>'int',
		'chatinnews'=>'int',
		'max_room_num'=>'int',
		'disable_access'=>'int',
		'disable_event'=>'int',
		'disable_newgame'=>'int',
		'disable_newroom'=>'int'
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
			}elseif($val == 'html'){
				${$key} = html_entity_decode(astrfilter($_POST[$key]),ENT_COMPAT);
			}else{
				${$key} = $_POST[$key];
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
		if(isset($edlist['adminmsg'])){
			file_put_contents('./gamedata/adminmsg.htm',$adminmsg);
		}
		if(isset($edlist['systemmsg'])){
			file_put_contents('./gamedata/systemmsg.htm',$systemmsg);
		}
		$sf = GAME_ROOT.'./include/modules/core/sys/config/game.config.php';
		//$sf = dirname(dirname(__FILE__)).'/modules/core/sys/config/system.config.php';
		$system_cont = file_get_contents($sf);
		foreach($edlist as $key => $val){
			if($key != 'adminmsg' && $key != 'systemmsg' && $key != 'max_room_num'){
				if($edfmt[$key] == 'int' || $edfmt[$key] == 'b'){
					$system_cont = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $system_cont);
				}else{
					$system_cont = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '${$key}';", $system_cont);
				}
			}
		}
		file_put_contents($sf,$system_cont);
		if(in_array('max_room_num',array_keys($edlist))){
			$key = 'max_room_num';
			$rf = GAME_ROOT.'./include/roommng/roommng.config.php';
			$roommng_cont = file_get_contents($rf);
			$roommng_cont = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $roommng_cont);
			file_put_contents($rf,$roommng_cont);
		}
		//打开ADV1以上时需要同时修改run文件夹下的内容
		$sf_run = GAME_ROOT.'./gamedata/run/core/sys/config/game.config.adv.php';
		if($___MOD_CODE_ADV1 && file_exists($sf_run)){
			file_put_contents($sf_run,$system_cont);
			$daemonmng_url = 'http://'.$_SERVER['HTTP_HOST'].substr($_SERVER['PHP_SELF'],0,-9).'daemonmng.php';
			$get_var = 'action=restart&in_game_pass='.substr(base64_encode($___MOD_CONN_PASSWD),0,6);
			file_get_contents($daemonmng_url.'?'.$get_var);
			$cmd_info .= '监测到ADV模式已打开，对应运行时文件已修改。<br>';
		}
		//putadminlog($adminlog);
		adminlog('globalgamemng',gencode($edlist));
		$cmd_info .= '系统环境修改完毕';
	}
}
$startmode_input = '';
for($i=0;$i<=3;$i++){
	if($i==$startmode){
		$startmode_input .= "<input type=\"radio\" name=\"startmode\" value=\"$i\" checked>".$lang['startmode_'.$i].'<br>';
	} else {
		$startmode_input .= "<input type=\"radio\" name=\"startmode\" value=\"$i\">".$lang['startmode_'.$i].'<br>';
	}
}
include template('admin_globalgamemng');
?>