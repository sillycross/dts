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
		'noiselimit'=>'int',
		'chatlimit'=>'int',
		'chatrefresh'=>'int',
		'chatinnews'=>'int'		
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
		//$adminlog = '';
		//$gamecfg_file = config('gamecfg',$gamecfg);
		$systemfile = file_get_contents('./gamedata/system.php');
		foreach($edlist as $key => $val){
			if($key != 'adminmsg' && $key != 'systemmsg'){
				if($edfmt[$key] == 'int' || $edfmt[$key] == 'b'){
					$systemfile = preg_replace("/[$]{$key}\s*\=\s*-?[0-9]+;/is", "\${$key} = ${$key};", $systemfile);
				}else{
					$systemfile = preg_replace("/[$]{$key}\s*\=\s*[\"'].*?[\"'];/is", "\${$key} = '${$key}';", $systemfile);
				}
			}
			//$adminlog .= setadminlog('systemcfgmng',$key,$val);
		}
		file_put_contents('./gamedata/system.php',$systemfile);
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