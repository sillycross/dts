<?php
ignore_user_abort(1);//这一代码基本上是以异步调用的方式执行的

define('CURSCRIPT', 'a');
define('IN_GAME', true);

//啥也不载入，只判断密钥是否匹配
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');

require './include/common.inc.php';

$userdb_forced_local = 1;

$ldata = \activity_ranking\load_aranking('aprillfool2018', 10);

$userdb_forced_local = 0;

$adata = \activity_ranking\load_aranking('aprillfool2018', 10);
$al = array();
foreach($adata as $lv){
	$al[] = $lv['username'];
}

$i = 0;
foreach($ldata as $lv){
	if(!in_array($lv['username'], $al) || $lv['score1'] > $adata[array_search($lv['username'], $al)]['score1']){
		\activity_ranking\save_ulist_aranking('aprillfool2018', $lv);
		echo $lv['username'].' done.';
		if($i > 2) break;
	} 
	$i ++;
}