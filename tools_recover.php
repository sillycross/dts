<?php
error_reporting(E_ALL);
set_time_limit(0);
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
	global $db,$gtablepre,$gudata;
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
		//writeover('r.txt', $dv['username'].' '.$flag."\r\n",'ab+');
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
		foreach($cv['u_achievements'] as $cai =>$cav){
			if(326==$cai) {
				foreach($cav as $cav_v) {
					ach_combine($dv, $cav_v, $cai);
				}
			}else{
				ach_combine($dv, $cav, $cai);
			}
		}
		$dv['u_achievements'] = \achievement_base\encode_achievements($dv['u_achievements']);
		
		$db->array_update("{$gtablepre}users", $dv, "username='{$dv['username']}'");
	}else{
		if($dv['username'] == $cv['username']) {
			$cv['username'].='_dianbo';
		}
		$db->array_insert("{$gtablepre}users", $cv);
	}
}

function ach_combine(&$udata, $x, $key){
	if(in_array($key, array(363, 364))){
		if(!$udata['u_achievements'][$key]) $udata['u_achievements'][$key] = array();
		if(!$x) $x = array();
		$udata['u_achievements'][$key] = array_merge($udata['u_achievements'][$key], $x);
	}elseif (\achievement_base\check_ach_valid($key) && !\skillbase\check_skill_info($key, 'global'))//技能存在而且有效
	{
		$pdata = \player\create_dummy_playerdata();
		\skillbase\skill_acquire($key,$pdata);
		//\skillbase\skill_setvalue($key,'cnt',$x,$pdata);
		if(326==$key) {
			global $winner; $winner=$pdata['name'] = $udata['username']; $pdata['card'] = $x;
			$udata['u_achievements'][$key]=\achievement_base\ach_finalize($pdata, $udata, $udata['u_achievements'][$key], $key, 1);
		}elseif(!\skillbase\check_skill_info($key, 'daily')){
			if(in_array($key, array(313, 351, 327, 328, 329, 330, 331))){
				//最大值类型的成就，直接取较大者。不需要计算是否获得成就。过期活动也类似
				$udata['u_achievements'][$key] = max($udata['u_achievements'][$key], $x);
			}elseif(in_array($key, array(308, 309, 322, 323, 359, 352))){
				//最小值类型的成就，直接取较小者，不需要计算是否获得成就。
				if($x > 0 && (!$udata['u_achievements'][$key] || $x < $udata['u_achievements'][$key]))
					$udata['u_achievements'][$key] = $x;
			}else{
				//累积性的成就，以较大的值为基础，加上较小的值。需要计算是否获得成就
				$gudata['cardlist'] = $udata['cardlist'];
				$gudata['gold'] = $udata['gold'];
				if($udata['u_achievements'][$key] < $x) list($udata['u_achievements'][$key], $x) = array($x, $udata['u_achievements'][$key]);
				\skillbase\skill_setvalue($key,'cnt',$x,$pdata);
				
				
				$func='\\skill'.$key.'\\finalize'.$key;
				if(function_exists($func)) $ret=$func($pdata,$udata['u_achievements'][$key]);//兼容性代码，如果存在旧式的结算函数，就按旧式结算函数算
				else $ret = \achievement_base\ach_finalize($pdata, $udata, $udata['u_achievements'][$key], $key);

				$udata['u_achievements'][$key]=$ret;
				
				
				$udata['cardlist'] = $gudata['cardlist'];
				$udata['gold'] = $gudata['gold'];
			}
		}
	}
}