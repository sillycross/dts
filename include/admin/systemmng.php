<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

$adminmsg = file_get_contents('./gamedata/adminmsg.htm') ;
$systemmsg = file_get_contents('./gamedata/systemmsg.htm') ;

if($command == 'edit') {
	$ednum = 0;
	$edfmt = Array(
		'adminmsg'=>'html',
		'systemmsg' => 'html',
		'startmode'=>'int',
		'starthour'=>'int',
		'startmin'=>'int',
		'iplimit'=>'int',
		'newslimit'=>'int',
		'alivelimit'=>'int',
		'winlimit'=>'int',
		'chatlimit'=>'int',
		'chatrefresh'=>'int',
		'chatinnews'=>'int',
		'disableevent'=>'int'
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
		if(in_array('adminmsg',array_keys($edlist))){
			file_put_contents('./gamedata/adminmsg.htm',$adminmsg);
		}
		if(in_array('systemmsg',array_keys($edlist))){
			file_put_contents('./gamedata/systemmsg.htm',$systemmsg);
		}
		$sf = GAME_ROOT.'./include/modules/core/sys/config/system.config.php';
		//$sf = dirname(dirname(__FILE__)).'/modules/core/sys/config/system.config.php';
		$system_cont = file_get_contents($sf);
		foreach($edlist as $key => $val){
			if($key != 'adminmsg' && $key != 'systemmsg'){
				if($edfmt[$key] == 'int' || $edfmt[$key] == 'b'){
					$system_cont = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $system_cont);
				}else{
					$system_cont = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '${$key}';", $system_cont);
				}
			}
		}
		file_put_contents($sf,$system_cont);
		//打开ADV1以上时需要同时修改run文件夹下的内容
		$sf_run = GAME_ROOT.'./gamedata/run/core/sys/config/system.config.adv.php';
		if($___MOD_CODE_ADV1 && file_exists($sf_run)){
			file_put_contents($sf_run,$system_cont);
			$cmd_info .= '监测到ADV模式已打开，对应运行时文件已修改。<br>';
		}
		//putadminlog($adminlog);
		adminlog('systemmng');
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
include template('admin_systemmng');
?>