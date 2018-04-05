<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);
set_time_limit(0);
define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();

$ul = array(
'HWSH4',
'蓝凝',
'chokey',
'走失的猫',
'1',
'poi',
'李白',
'123456789',
'player188',
'df',
'943167',
'海棠',
'qweqweqwe',
'黑长直',
'XF天下',
'邪教徒',
'不是傻非酱！',
'QAQ',
'ctt',
'DM',
'doudou',
'rfvtgb300',
'test',
'超银河眼和尚',
'柒点复苏',
'大自然的力量',
'ACFUN大逃杀',
'w1054435476',
'mikatsuki',
'Lucifer',
'7777589',
'a7777589',
'b7777589',
'Adams',
'岸波白野',
'990049',
'向恬',
'啊11',
'widdw',
'OvO~',
'KKKK',
'KKKKK',
'llll',
'所等',
'KKKKKK',
'zimiki',
'狐狸',
'蕾姆',
'ay',
'lindaman',
'空条承太郎',
'BR',
'das',
'killer',
'游佐',
'芙兰朵露·斯卡雷特',
'2333',
'axushusd',
'瓜皮',
'rocky1',
'嘟嘟',
'上帝',
'啊啊啊啊',
'tk1360091',
'okay小白',
'Kflems2018429',
'1231',
'kimo',
'QwQ',
'111112',
'mopl',
'轩轩',
'NPC',
'胖子',
'RickLinEvil',
'岸边露伴',
'嘻嘻嘻',
'1234561',
'ttrtty5',
'中津静流',
'miao',
'syayyj',
'ty199167',
'18X的夏',
'a571732256',
'573',
'党员',
'岛风',
'ly',
'misaki',
'雪风',
'sora',
'小酱萝卜',
'小酱萝卜。'
);

echo 'started.<br>';
$gid_history = 3911;
$gid_shistory = 1660;
foreach(array('history', 'shistory') as $val){
	$tmp = ${'gid_'.$val};
	$result = $db->query("SELECT * FROM {$gtablepre}{$val} WHERE gid <= $tmp");
	while($r = $db->fetch_array($result)){
		$qr = array();
		if(in_array($r['winner'], $ul)) {
			echo $val.' game '.$r['gid'].'\'s winner is '.$r['winner'].' and parsed.<br>';
			$qr['winner'] = $r['winner'].'_0';
		}
		if(in_array($r['hdp'], $ul)) {
			echo $val.' game '.$r['gid'].'\'s hdp is '.$r['hdp'].' and parsed.<br>';
			$qr['hdp'] = $r['hdp'].'_0';
		}
		if(in_array($r['hkp'], $ul)) {
			echo $val.' game '.$r['gid'].'\'s hkp is '.$r['hkp'].' and parsed.<br>';
			$qr['hkp'] = $r['hkp'].'_0';
		}
		if(!empty($qr)) {
			//echo $db->array_update("{$gtablepre}{$val}", $qr, "gid = '{$r['gid']}'");
		}
	}
}
echo 'done';