<?php
header('Content-Type: text/HTML; charset=utf-8');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('CURSCRIPT', 'card_reissue');
define('IN_GAME', TRUE);
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require './include/common.inc.php';
require_once './include/messages.func.php';

check_authority();

$result = $db->query("SELECT * FROM {$tablepre}users WHERE lastgame >= 43600");

while($ud = $db->fetch_array($result)){
	$a = \achievement_base\decode_achievements($ud);
	$c = explode('_',$ud['cardlist']);
	if(!empty($a['352']) && $a['352'] <= 2 && !in_array(95,$c)) create_reissue_message($ud['username'], 95, 400);//echo create_lack_log($ud['username'], 95);
	if(!empty($a['352']) && $a['352'] <= 1 && !in_array(96,$c)) create_reissue_message($ud['username'], 96, 600);//echo create_lack_log($ud['username'], 96);
	if($a['300'] >= 999983 && !in_array(86,$c)) create_reissue_message($ud['username'], 86, 0);//echo create_lack_log($ud['username'], 86);
}
echo '以下空白';

function create_lack_log($un, $ci){
	eval(import_module('cardbase'));
	return '用户 '.$un.' 缺失卡片 '.$cards[$ci]['name'].'. 已补偿.<br>';
}

function create_reissue_message($un, $ci=0, $qiegao=0){
	echo create_lack_log($un, $ci);
	$e = '';
	if($qiegao) $e .= 'getqiegao_'.$qiegao.';';
	if($ci) $e .= 'getcard_'.$ci.';';
	message_create($un, '奖励补偿', '由于GM的天然呆，您之前完成了成就却未获得奖励，现补偿如下：', $e);
}