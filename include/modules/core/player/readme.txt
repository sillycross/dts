玩家相关模块

几个关于玩家的函数：

fetch_playerdata_by_pid获取任意玩家信息，player_save保存任意玩家信息
如果非NPC也可以用fetch_playerdata(名字)获取玩家信息
load_playerdata载入玩家信息

所有对别的玩家的操作必须通过这些函数完成！！！不可以自己写数据库指令！因为很多要求初始化的模块会绑定这些函数完成自己的初始化！




