安装指南：
1. 首先把游戏文件属性全部改成777，然后如果之前没有建过mysql结构，进mysql里执行：
	create database acdts;
	use acdts;
	source gamedata/sql/all.sql
	insert into acbra2_users (uid) values (0);
	insert into acbra2_winners (gid) values (0);
	insert into acbra2_game (gamenum) values (0);
2. 将./include/modules/core/sys/config/server.config.sample.php ****复制一份**** 然后重命名为server.config.php，并填写相应参数。****切记要复制文件，不要直接重命名****，不然push一下github那边就没有sample文件了……
3. 将./include/modulemng.config.sample.php ****复制一份**** 然后重命名为modulemng.config.php，暂时不要动里面的东西。****切记要复制文件，不要直接重命名****，不然push一下github那边就没有sample文件了……
4. 访问游戏首页（这时应该没有任何错误了），用gamefounder帐号登录（这一步是为了获取有权限的cookie）。
5. 想要改modulemng.config.php可以开始改了。不想改的话可以直接跳到第8步。
6. 如果在第5步中开了daemon模式，进shell，****切到游戏根目录下****，执行“nohup ./acdts-daemonctl.sh &”，关掉shell，然后访问daemonmng.php，如果脚本状况显示“正在运行”就没问题。（暂不支持windows，谁去写个等效的bat脚本？）
7. 请访问modulemng.php，进入编辑模式=>保存=>应用修改。
8. 进游戏，看看能不能动，能动就完工了。

安装指南2：
1. 首先把游戏文件属性全部改成777
2. 将./include/modules/core/sys/config/server.config.sample.php ****复制一份**** 然后重命名为server.config.php，并填写相应参数。****切记要复制文件，不要直接重命名****，不然push一下github那边就没有sample文件了……
3. 将./include/modulemng.config.sample.php ****复制一份**** 然后重命名为modulemng.config.php，暂时不要动里面的东西。****切记要复制文件，不要直接重命名****，不然push一下github那边就没有sample文件了……
4. 然后去gamedata文件夹删掉install.lock文件
5. 运行install_NEW.php，一路照设定走
6. 目前没有实现游戏立刻开局，无所谓了