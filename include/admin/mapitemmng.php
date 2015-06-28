<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if($mygroup < 9){
	exit($_ERROR['no_power']);
}

$sqldir = GAME_ROOT.'./gamedata/sql/';
foreach(Array('log','chat','mapitem','newsinfo') as $v){
	
	$d = file_get_contents("{$sqldir}{$v}.sql");
	
	
	$a = getmicrotime();
	if($v=='mapitem'){
		for($i=0;$i<30;$i++){
			$d2 = str_replace("\r", "\n", str_replace(' bra_', ' test_'.$i, $d));
			runquery($d2);
		}
	}else{
		$d = str_replace("\r", "\n", str_replace(' bra_', ' test_', $d));
		runquery($d);
	}
	
	$b = getmicrotime();
	$time = ($b-$a)*1000 ;
	echo "{$v}.sql 执行时间：$time 毫秒 <br>";
}






//var_dump($db->query("DROP TABLE test_log"));


?> 