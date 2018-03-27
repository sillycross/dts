<?php
error_reporting(E_ALL);
@ob_end_clean();
header('Content-Type: text/HTML; charset=utf-8'); // 以事件流的形式告知浏览器进行显示
header('Cache-Control: no-cache');         // 告知浏览器不进行缓存
header('X-Accel-Buffering: no');           // 关闭加速缓冲
@ini_set('implicit_flush',1);
ob_implicit_flush(1);
set_time_limit(0);
@ini_set('zlib.output_compression',0);
define('IN_MAINTAIN',true);
echo str_repeat(" ",1024);
echo '<script language="javascript"> 
$z=setInterval(function() { window.scroll(0,document.body.scrollHeight); },100); 
function stop() { window.scroll(0,document.body.scrollHeight); clearInterval($z); }</script>
<body onload=stop(); ></body>'; 
require './include/common.inc.php';
$servermark = '';
//$servermark = 'S';

check_authority();

$namecase=array('Amarillo_NMC', 'nemoma', 'digichart', 'Saphil', '2Ag', '完美而潇洒的变态', '箱子npc');

$file = 'recover.dat';
if(!file_exists($file)) exit("Cannot find file ".$file);
$insert_only = 1;
if($insert_only) $db->query("TRUNCATE TABLE {$gtablepre}users");

$cont = openfile($file);
foreach($cont as $cv) {
	$cv = json_decode($cv,1);
	combine_db_single($cv);
}
echo 'done';
function combine_db_single($cv){
	global $db,$gtablepre,$gudata,$namecase,$servermark;
	if(empty($cv['username'])) return;
	if(empty($insert_only)) {
		$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='{$cv['username']}'");
		$dv = $db->fetch_array($result);
		$flag = 0;
		if($dv['password'] == $cv['password']) {
			$flag = 1;
		}elseif(pass_compare($cv['username'], $cv['password'], $dv['password'])){
			$flag = 2;
		}elseif(!empty($dv) && in_array($cv['username'], $namecase)){
			$flag = 4;
		}elseif(!empty($dv) && substr($dv['ip'],0,7) == substr($cv['ip'],0,7)){
			//$flag = 5;
		}
	}else{
		$cv = array('username' => '');
	}
	
	if(!empty($cv['elo_history']))
	{
		$eha = '';
		for($i=0; $i<strlen($cv['elo_history']); $i+=10){
			$eha.= $servermark.substr($cv['elo_history'], $i, 10);
		}
		$cv['elo_history'] = $eha;
	}
	if(1==$flag || 2==$flag || 4==$flag || 5 == $flag){
		
		foreach(array('credits','totalcredits','credits2','gold','gold2','validgames','wingames') as $val) {
			$dv[$val] += $cv[$val];
		}
		foreach(array('lastwin','lastgame','lastroomgame') as $val){
			$dv[$val] = max($dv[$val], $cv[$val]);
		}
		if($cv['elo_playedtimes'] > $dv['elo_playedtimes']) {
			foreach(array('elo_rating', 'elo_volatility', 'elo_playedtimes', 'elo_history') as $val){
				$dv[$val] = $cv[$val];
			}
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
		if(strtolower($dv['username']) == strtolower($cv['username'])) {
			$cv['username'].='_'.$servermark;
			$cv['password']=md5($cv['username'].md5($cv['username']));
			$flag = 3;
		}
		unset($cv['uid']);
		$db->array_insert("{$gtablepre}users", $cv);
	}
	combine_log($cv['username'], $flag);
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

function combine_log($un, $flag) {
	global $no;
	if(!$no) $no = 1;
	else $no ++;
	$file = 'combine.log';
	file_put_contents('', $file);
	$log = 'No.'.$no.'  '.$un;
	if(0==$flag) $log .= ' 无账号 插入';
	elseif(1==$flag) $log .= ' 同名同密码 合并';
	elseif(2==$flag) $log .= ' 同名新旧格式密码 合并';
	elseif(3==$flag) $log .= ' 同名不同密码 插入';
	elseif(4==$flag) $log .= ' 特例 合并';
	elseif(5==$flag) $log .= ' 同名不同密码但IP相同 合并';
	echo $log.'<br>';
	ob_end_flush(); flush();
	if(3==$flag) writeover($file, $log."\r\n", 'ab+');
}