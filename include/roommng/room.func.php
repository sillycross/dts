<?php

//根据$room_id生成$tablepre，单纯是统一用
//若$without_num=true则返回的是只带前缀的（也无下划线）
function room_get_tablepre($room_id=0, $without_num=false){
	global $gtablepre;
	if(!$room_id) global $room_id;
	$room_id = (int)$room_id;
	if(!$room_id) return $gtablepre;
	else return $without_num ? $gtablepre.'s' : $gtablepre.'s'.$room_id.'_';
}

function room_check_subroom($room_prefix=''){//检查是标准局还是房间
	//if(!$room_prefix) global $room_prefix;
	return ($room_prefix && strpos($room_prefix,'s')===0);
}

function room_prefix2id($room_prefix=''){
	//if(!$room_prefix) global $room_prefix;
	if (room_check_subroom($room_prefix)) return (int)substr($room_prefix,1);
	else return 0;
}

function room_id2prefix($id){
	if(!$id) return '';
	elseif(strpos($room_prefix,'s')===0) return 's'.$id;
}

/* End of file room.func.php */
/* Location: /include/roommng/room.func.php */