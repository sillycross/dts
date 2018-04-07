<?php
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

eval(import_module('sys','player'));

//跟userdb共用一套用户名密码
$valid = false;
if(isset($asign) && isset($apass)) {
	foreach($userdb_receive_list as $rs => $rp){
		if($rs === $asign && compare_ts_pass($apass, $rp['pass']) && (empty($rp['ip']) || $rp['ip'] == $arealip)){
			$valid = true;
			break;
		}
	} 
}
if(!$valid) {//所有请求都必须判定密码
	echo 'Error: Invalid sign';
}else{
	if(empty($acmd)) {
		echo 'Error: Invalid command';
	}else{
		$userdb_forced_local = 1;
		
		$apara1 = !empty($apara1) ? $apara1 : NULL;
		$apara2 = !empty($apara2) ? $apara2 : NULL;
		
		if('load_aranking' == $acmd) {
			$ret = \activity_ranking\load_aranking($apara1, $apara2);
		}elseif('save_ulist_aranking' == $acmd){
			$apara2 = gdecode($apara2, 1);
			\activity_ranking\save_ulist_aranking($apara1, $apara2);
			$ret = 'Successed.';
		}
		if(empty($ret)){
			echo 'Error: Invalid command 2';
		}else{
			echo gencode($ret);
		}
	}
}

/* End of file command_aranking.php */
/* Location: /include/pages/command_aranking.php */