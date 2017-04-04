安装指南 版本1：
1. 首先把游戏文件属性全部改成777，然后如果之前没有建过mysql结构，进mysql里执行：
	create database acdts;
	use acdts;
	source gamedata/sql/all.sql
	insert into acbra2_users (uid) values (0);
	insert into acbra2_winners (gid) values (0);
	insert into acbra2_game (gamenum) values (0);
2. 将./include/modules/core/sys/config/server.config.sample.php ****复制一份**** 然后重命名为server.config.php，并填写相应参数。****切记要复制文件，不要直接重命名****，不然push一下github那边就没有sample文件了……
3. 将./include/modulemng/modulemng.config.sample.php ****复制一份**** 然后重命名为modulemng.config.php，暂时不要动里面的东西。****切记要复制文件，不要直接重命名****，不然push一下github那边就没有sample文件了……
3.5 此时注意检查modulemng.config.php中$___MOD_CODE_ADV1、2、3以及$___MOD_SRV这四个参数，如果不是0则全部改成0，否则在游戏adv文件不完整的情况下可能根本开不起来
4. 访问游戏首页（这时应该没有任何错误了），用gamefounder帐号登录（这一步是为了获取有权限的cookie）。
5. 想要改modulemng.config.php可以开始改了。不想改的话可以直接跳到第8步。
6. 如果在第5步中开了daemon模式，进shell，****切到游戏根目录下****，执行“nohup ./acdts-daemonctl.sh &”，关掉shell，然后访问daemonmng.php，如果脚本状况显示“正在运行”就没问题。（暂不支持windows，谁去写个等效的bat脚本？）
7. 请访问modulemng.php，进入编辑模式=>保存=>应用修改。
8. 进游戏，看看能不能动，能动就完工了。



安装指南 版本2：（在你有install_NEW.php和install文件夹的情况下）
1. 首先把游戏文件属性全部改成777，去gamedata文件夹删掉install.lock文件
2. 运行install_NEW.php，一路照设定走，该输的密码要输。安装程序会自动复制server.config.php和modulemng.config.php，并自动修改___MOD_CODE_ADV一系列变量
3. 访问首页，如果没有错误则用gamefounder帐号登录（这一步是为了获取有权限的cookie）。
4. 如果打算开启adv模式则进modulemng.config.php手动设置，之后访问modulemng.php，进入编辑模式=>保存=>应用修改。
5. 如果开启了daemon模式：
	5.1 Linux下进shell，****切到游戏根目录下****，执行“nohup ./acdts-daemonctl.sh &”，关掉shell
	5.2 WIN下右键acdts-daemonctl.ps1，选执行，如果powershell窗口要求提升权限就输Y，之后必须维持powershell窗口的运行。需要设置php.exe的环境变量，或者直接修改ps1文件把地址写进去。
	5.3 然后访问daemonmng.php，如果脚本状况显示“正在运行”、服务器就没问题
备注：装完记得把install_NEW.php删掉。