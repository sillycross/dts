<?php

//房间设置

//最大房间数目
$max_room_num = 50;

//长轮询端口号范围
$room_poll_port_low = 25000;
$room_poll_port_high = 35000;

//房间类型
$roomtypelist = Array(
	0 => Array(
		'name' => '1v1模式',
		'pnum' => 2,	//最大参与人数
		'leader-position' => Array(	//各个编号位置的所属队伍队长位置
			0 => 0,
			1 => 1,
		),
		'color' => Array(		//队伍颜色，只需对队长设置即可
			0 => 'ff0022',
			1 => '5900ff',
		),
		'teamID' => Array(	//队伍名，只需对队长设置即可
			0 => '红队',
			1 => '蓝队',
		),
		'show-team-leader' => 0,	//是否显示“队长”标签（如队伍大于1人设为1）
	),
	1 => Array(
		'name' => '2v2模式',
		'pnum' => 4,
		'leader-position' => Array(
			0 => 0,
			1 => 0,
			2 => 2,
			3 => 2,
		),
		'color' => Array(
			0 => 'ff0022',
			2 => '5900ff',
		),
		'teamID' => Array(
			0 => '红队',
			2 => '蓝队',
		),
		'show-team-leader' => 1,
	),
	2 => Array(
		'name' => '3v3模式',
		'pnum' => 6,
		'leader-position' => Array(
			0 => 0,
			1 => 0,
			2 => 0,
			3 => 3,
			4 => 3,
			5 => 3,
		),
		'color' => Array(
			0 => 'ff0022',
			3 => '5900ff',
		),
		'teamID' => Array(
			0 => '红队',
			3 => '蓝队',
		),
		'show-team-leader' => 1,
	),
	3 => Array(
		'name' => '1v1v1模式',
		'pnum' => 3,
		'leader-position' => Array(
			0 => 0,
			1 => 1,
			2 => 2,
		),
		'color' => Array(
			0 => 'ff0022',
			1 => '5900ff',
			2 => '8cff00',
		),
		'teamID' => Array(
			0 => '红队',
			1 => '蓝队',
			2 => '绿队',
		),
		'show-team-leader' => 0,
	),
);
	
?>
