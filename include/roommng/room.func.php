<?php

//房间的一些杂项函数

//根据$room_id生成$tablepre，单纯是统一用
//若$without_num=true则返回的是只带前缀的（也无下划线）
function room_get_tablepre($room_prefix=NULL, $without_num=false){
	global $gtablepre;
	if(NULL === $room_prefix) global $room_prefix;
	if (room_check_subroom($room_prefix))
		return $without_num ? $gtablepre.room_prefix_kind($room_prefix) : $gtablepre.$room_prefix.'_';
	else return $gtablepre;
}

//检查是标准局还是房间
function room_check_subroom($room_prefix=''){
	//if(!$room_prefix) global $room_prefix;
	return ($room_prefix && 's' == room_prefix_kind($room_prefix));
}

//检查对应房间的局数是否正确
function room_check_gamenum($room_id, $room_gamenum){
	global $db,$gtablepre;
	$ret = false;
	$roomresult = $db->query("SELECT groomid, gamenum FROM {$gtablepre}game WHERE groomid='$room_id'");
	if($db->num_rows($roomresult)) {
		$rs = $db->fetch_array($roomresult);
		if($rs['gamenum'] == $room_gamenum) $ret = true;
	}
	return $ret;
}

//去掉房间前缀中的s（或者n之类的）只返回房间号
function room_prefix2id($room_prefix=''){
	//if(!$room_prefix) global $room_prefix;
	if (room_check_subroom($room_prefix)) return (int)substr($room_prefix,1);
	else return 0;
}

//返回房间前缀类型
function room_prefix_kind($room_prefix=''){
	//if(!$room_prefix) global $room_prefix;
	if ($room_prefix) return (string)substr($room_prefix,0,1);
	else return '';
}

//房间号转前缀，现在只支持s
function room_id2prefix($id){
	if(!$id) return '';
	else return 's'.$id;
}

//设置当前roomid，根据IN_DAEMON的值来决定是否gsetcookie
function set_current_roomid($id){
	gsetcookie_comp('roomid', $id);
}

//试炼模式生成规则文字
function get_instance3_debuff_tips($alvl){
	$club_tips = array(
		0 => '称号固定为解离攻坚',
		1 => '可任意选择一个称号',
		2 => '称号固定为走路萌物',
	);
	$debuff_tips = array(
		1 => '等级上限-100',
		2 => '全息幻象受到伤害-15%',
		3 => '杏仁豆腐、黑幕、真职人、电波幽灵受到伤害-15%',
		4 => '初始体力上限-200',
		5 => '初始生命上限-50',
		6 => '陷阱遭遇率+20%',
		7 => '商店物品售价+25%',
		8 => '初始生命上限-50',
		9 => '使用命体回复道具效果-30%',
		10 => '受到最终伤害+10%',
		11 => '记忆上限-20',
		12 => '开局金钱-1000元',
		13 => '开局5分钟内攻击无法获得熟练度',
		14 => '开局获得一个诅咒道具',
		15 => '数据碎片造成伤害+25%',
		16 => '受伤或受到异常状态20秒内无法去除',
		17 => '视野上限-4',
		18 => '受伤或受到异常状态时-10生命上限',
		19 => '受到最终伤害+10%',
		20 => '开局进入连斗',
		21 => '受到最终伤害+15%',
		22 => '开局获得一个诅咒道具',
		23 => '无法使用探测设备',
		24 => '无法使用视野和记忆',
		25 => '剧情BOSS和英灵殿NPC造成的伤害+25%',
		26 => '无法装备身体防具',
		27 => '物品/角色发现率-20%',
		28 => '抹消基础失效率变为10%',
		29 => '控伤基础失效率变为15%',
		30 => '先制率-20%，命中率-20%',
	);
	$s = $club_tips[floor(max($alvl-1,0)/10)].'<br>';
	if ($alvl > 0)
	{
		for ($i=1;$i<=$alvl;$i++)
		{
			$s .= $debuff_tips[$i].'<br>';
		}
	}
	return $s;
}

/* End of file room.func.php */
/* Location: /include/roommng/room.func.php */