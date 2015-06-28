attack_wrapper.php:

	实现了攻击外围流程：
 
		攻击准备 => 打击进行attack() => 攻击结束
	
	攻击准备：发攻击通告，攻击方加载攻击参数
	
	攻击结束：发伤害结算通告，发死亡通告
	
	dmg_dealt域是用来保存总伤害的。
	
attack.php:
	
	实现了打击流程：
	
		打击准备 => 打击进行strike() => 打击结束
		
	打击准备：初始化dmg_dealt
	打击结束：扣血
	
也就是说真正的核心攻击函数是strike();

作为大逃杀最绕的的部分之一：
	
	$active && $pa['is_counter'] 当前玩家的反击
	$active && !$pa['is_counter'] 当前玩家的主动攻击
	!$active && $pa['is_counter'] 敌方的反击
	!$active && !$pa['is_counter'] 敌方的先制攻击
	
