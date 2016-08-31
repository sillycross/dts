<?php

namespace skill475
{
	function init() 
	{
		define('MOD_SKILL475_INFO','club;locked;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[475] = '';
	}
	
	/*
	 * 游戏模式介绍：
	 * 死后会在15秒后复活（少数死法除外），限时2禁，目标是获得尽可能多的胜利点数
	 * 
	 * 胜利点数结算方式：
	 * 自身赏金初始为100，每当通过击杀玩家或NPC收入金钱时，自身赏金增加同样数目
	 * 击杀其他玩家时，设对方赏金为x，则你的胜利点数增加0.15x点，自身赏金增加0.3x点，而对方的赏金降低45%
	 * 被NPC击杀或因意外事件而死时，会损失10%携带的金钱
	 * 击杀NPC时获得的胜利点数：小兵20点，幻象250点，真职人/电波幽灵750点，杏仁豆腐1200点，猴子2500点
	 * 
	 * 战斗击杀其他玩家时可以选择的选项：
	 * 1. 销毁对方30%的金钱（自己不获得）
	 * 2. 获得对方30%的金钱，并放弃同等数值的胜利点数
	 * 3. 降低15%对方武器效果值
	 * 4. 降低对方一件防具的20%效果值
	 * 5. 销毁对方装备的饰品或一件背包物品
	 *
	 * 其他方式击杀玩家依然获得胜利点数但没有上述选项可以选择
	 *
	 * 其他特性：
	 * 1. 没有boss npc刷新（红蓝，scp，英灵），英灵没有特效
	 * 2. 地雷价格下降，妖精的羽翼价格上升
	 * 3. 蛋服卡片禁用
	 */
	 
	function acquire475(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(475,'wpt',0,$pa);		//胜利点数
		\skillbase\skill_setvalue(475,'bounty',0,$pa);		//自身被击杀时对方获得的胜利点数
	}
	
	function lost475(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(475,'wpt',$pa);
		\skillbase\skill_delvalue(475,'bounty',$pa);
	}
	
	function check_unlocked475(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
		
	
}

?>