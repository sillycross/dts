<?php

//房间的一些杂项函数

//根据$room_id生成$tablepre，单纯是统一用
//若$without_num=true则返回的是只带前缀的（也无下划线）
function room_get_tablepre($room_prefix='', $without_num=false){
	global $gtablepre;
	if(!$room_prefix) global $room_prefix;
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

/* End of file room.func.php */
/* Location: /include/roommng/room.func.php */