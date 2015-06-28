gamestate的基本控制。
gamestate=0: 处于上局游戏结束状态
gamestate=10: 本局游戏已经处于准备状态
gamestate=20: 本局游戏已经开始
gamestate>=40: 开始判定游戏结束（一旦生存者人数降至1即结束）

提供了 0 => 10 => 20 的逻辑 和 >=40 判定结束的逻辑
