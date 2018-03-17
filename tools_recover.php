<?php
error_reporting(E_ALL);
define('IN_MAINTAIN',true);
require './include/common.inc.php';

check_authority();
$db = init_dbstuff();

$file = 'recover.dat';
if(!file_exists($file)) exit("Cannot find file ".$file);
$cont = openfile($file);
foreach($cont as $cv) {
	$cv = json_decode($cv,1);
	combine_db_single($cv);
}
echo 'done';
function combine_db_single($cv){
	global $db,$gtablepre;
	if(empty($cv['username'])) return;
	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='{$cv['username']}'");
	$dv = $db->fetch_array($result);
	$flag = 0;
	if($dv['password'] == $cv['password']) {
		$flag = 1;
	}elseif(pass_compare($cv['username'], $cv['password'], $dv['password'])){
		$flag = 2;
	}
	if(1==$flag || 2==$flag){
		writeover('r.txt', $dv['username'].' '.$flag."\r\n",'ab+');
		foreach(array('credits','totalcredits','credits2','gold','gold2','validgames','wingames') as $val) {
			$dv[$val] += $cv[$val];
		}
		foreach(array('lastwin','lastgame','lastroomgame') as $val){
			$dv[$val] = max($dv[$val], $cv[$val]);
		}
		$cv['cardlist'] = explode('_',$cv['cardlist']);
		$dv['cardlist'] = explode('_',$dv['cardlist']);
		
		foreach($cv['cardlist'] as $ccv){
			if(!in_array($ccv, $dv['cardlist'])) $dv['cardlist'][] = $ccv;
		}
		$dv['cardlist'] = implode('_',$dv['cardlist']);
		$cv['u_achievements'] = \achievement_base\decode_achievements($cv);
		$dv['u_achievements'] = \achievement_base\decode_achievements($dv);
		writeover('a.txt', var_export($dv['u_achievements'], 1)."\r\n", 'ab+');
		foreach($cv['u_achievements'] as $cai =>$cav){
			if(326==$cai) {
				foreach($cav as $cav_v) {
					ach_combine($dv, $cav_v, $cai);
				}
			}else{
				ach_combine($dv, $cav, $cai);
			}
		}
		//$dv['u_achievements'] = \achievement_base\encode_achievements($dv['u_achievements']);
		
		writeover('b.txt', var_export($dv['u_achievements'], 1)."\r\n", 'ab+');
	}else{
		if($dv['username'] == $cv['username']) {
			$cv['username'].='_dianbo';
			writeover('r.txt', $cv['username']." 3\r\n",'ab+');
			//////
		}else{
			/////
			writeover('r.txt', $cv['username']." 4\r\n",'ab+');
		}
	}
}

function ach_combine(&$udata, $x, $key){
	if (\achievement_base\check_ach_valid($key) && !\skillbase\check_skill_info($key, 'global'))//技能存在而且有效
	{
		if(326==$key) {
			if(!in_array($x, $udata['u_achievements'][$key]))
				$udata['u_achievements'][$key][] = $x;
		}elseif(!\skillbase\check_skill_info($key, 'daily')){
			if(in_array($key, array(313, 351))){
				$udata['u_achievements'][$key] = max($udata['u_achievements'][$key], $x);
			}elseif(in_array($key, array(308, 309, 322, 323, 359, 352))){
				if($x > 0 && (!$udata['u_achievements'][$key] || $x < $udata['u_achievements'][$key]))
					$udata['u_achievements'][$key] = $x;
			}else{
				$udata['u_achievements'][$key] += $x;
			}
		}
	}
}