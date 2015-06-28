battle_wrapper.php:

	战斗外围流程battle_wrapper()：

		战斗外围准备battle_prepare() => 战斗进行battle() => 战斗外围结束battle_finish()

	battle_prepare()
		
		battle_prepare()应当只包含非战斗动作。
		无既定动作
		
	battle_finish()
	
		battle_finish()应当只负责把战斗结果真正应用（因为battle_prepare()和battle()都是在$pa和$pd里打的）。
		既定动作：保存玩家状态，更新游戏状态，处理尸体
		
	战斗流程battle()
			
		战斗准备assault_prepare() => 战斗进行assault() => 战斗结束assault_finish()
		
	战斗准备assault_prepare()
	
		既定动作：设置是否是玩家主动发起的攻击
	
	战斗结束assault_finish()
		
		无既定动作
		
battle.php:

	实现战斗流程assault()
	
		甲攻击乙attack_wrapper() => 判定乙是否反击check_can_counter() => 乙攻击甲attack_wrapper() =>  战斗结束。
		
	check_can_counter()
	
		既定动作：返回1
		
	attack_wrapper()
	
		攻击准备attack_prepare() => 攻击进行attack() => 攻击结束attack_finish()
		
	attack_prepare():
		
		既定动作：加载攻击方攻击参数
		
	attack():
		
		无既定动作
		
	attack_finish():
		
		扣血，判定并处理玩家死亡
