<?php

namespace rest
{
	//体力恢复时间(秒):*秒1%恢复
	$rest_sleep_time = 3;
	//生命恢复时间(秒):*秒1%恢复
	$rest_heal_time = 6;
	//静养解除异常状态基础时间(秒)
	$rest_recover_time = 90;
	//休息状态名
	$restinfo = Array('通常','睡眠','治疗','静养');
	//可以静养的位置列表
	$rest_hospital_list = Array(11,19,32);
}

?>
