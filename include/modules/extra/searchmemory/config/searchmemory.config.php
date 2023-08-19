<?php

namespace searchmemory
{
	$searchmemory_disabled_gtype = array(1);//不启用探索记忆的游戏模式：除错模式
	$searchmemory_max_slotnum = 5;//可显示的最大记忆数（即视野）
	$searchmemory_max_recordnum = 30;//需记录的最大记忆数
	$searchmemory_battle_active_debuff = -40;//迎战记忆中的敌人时，玩家先制率的减成值
	
	$weather_memory_loss = array(//各天气对记忆（视野）的影响。视野建议最少是2格，否则再探时如果同时发现了道具，会立刻把再探目标挤掉
		8 => -2,//起雾
		9 => -3,//浓雾
		12 => -3//暴风雪
	);
	
	//从尸体上剥物品的冷却时间，单位毫秒
	$searchmemorycoldtime = 3000;
}

?>