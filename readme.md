# 常磐大逃杀 N.E.W.版
一个基于php、以文本形式为主、多人即时混战类的webgame。

## 游戏特性
- 即开即玩，易于上手 + 战术复杂，难于精通
- 源于大逃杀原作的1 VS N混战精神
- 多样化的求胜路径和策略
- 令人眼花缭乱的攻击属性、合成链、技能树
- 在标准模式以外，还有SOLO、团战、PVE等多种模式
- 成就和集卡系统
- 大量引人会心一笑的ACG梗

## 技术特性
- 能将模块化源代码自动合并为高效执行代码的模块化引擎，兼顾代码组织、多人协作和运行效率优化
- 基于socket的驻留daemon，支持Linux和WIN

## 安装指南
1. 首先把游戏文件属性全部改成777，然后如果之前没有建过mysql结构，进mysql里执行：

```
	create database acdts;
	use acdts;
	source gamedata/sql/all.sql;
	insert into acbra2_users (uid) values (0);
	insert into acbra2_winners (gid) values (0);
	insert into acbra2_game (gamenum) values (0);
```

2. 将./include/modules/core/sys/config/server.config.sample.php ****复制一份**** 然后重命名为server.config.php，并填写相应参数。****切记要复制文件，不要直接重命名****，不然push一下github那边就没有sample文件了……
3. 将./include/modulemng/modulemng.config.sample.php ****复制一份**** 然后重命名为modulemng.config.php，暂时不要动里面的东西。****切记要复制文件，不要直接重命名****，不然push一下github那边就没有sample文件了……
4. 访问游戏首页（这时应该没有任何错误了），用gamefounder帐号登录（这一步是为了获取有权限的cookie）。
5. 想要改modulemng.config.php可以开始改了。不想改的话可以直接跳到第8步。
6. 如果在第5步中开了daemon模式，进shell，****切到游戏根目录下****，执行“nohup ./acdts-daemonctl.sh &”，关掉shell，然后访问daemonmng.php，如果脚本状况显示“正在运行”就没问题。（暂不支持windows，谁去写个等效的bat脚本？）
7. 请访问modulemng.php，进入编辑模式=>保存=>应用修改。
8. 进游戏，看看能不能动，能动就完工了。


（在你有install_NEW.php和install文件夹的情况下）
安装指南 普及无坑版：
1. 首先把游戏根目录下的gamedata、include和templates三个文件夹的属性全部改成777，win7以上系统另外需要为IUSR用户添加完全访问权限，可自行查找相关教程进行设置。另外，去gamedata文件夹删掉install.lock文件
2. 运行install_NEW.php，一路照提示走，其中网站地址、数据库名、数据库用户名和密码、管理员用户名和密码应根据实际情况进行改动。安装程序会自动复制server.config.php和modulemng.config.php，并自动修改___MOD_CODE_ADV一系列变量
3. 访问首页，如果没有错误则用刚才安装时输的管理员帐号登录（这一步是为了获取有权限的cookie）。
4. 如果打算开启adv模式，则访问modulemng.php，根据需求开启ADV1 ADV2 ADV3和SRV共4个选项，之后点击“重设代码缓存”-->“应用更改”，等系统自动处理完毕。
5. 如果开启了daemon模式：
	5.1 Linux下进shell，****切到游戏根目录下****，执行“nohup ./acdts-daemonctl.sh &”，关掉shell
	5.2 WIN下要运行daemon需要powershell 3.0以上支持。需要先把php.exe所在目录纳入环境变量，具体方法为复制php.exe所在目录的绝对路径，然后“控制面板”-->“系统”-->“高级系统设置”-->“高级”-->“环境变量”，在“系统变量”窗格里找到“Path”一栏点击“编辑”，之后在“变量值”的最后，先输入半角分号“;”，然后把绝对路径黏贴进去，一路确定。然后运行游戏目录下的acdts-daemonctl.bat，如果powershell窗口要求提升权限就输Y，之后必须维持bat窗口的运行。
	5.3 然后访问daemonmng.php，如果脚本状况显示“正在运行”，点击“启动一个新的服务器”，过几秒刷新，如果显示有进程在运行就没问题。
备注：如果服务器要开放给广域网，装完记得把install_NEW.php删掉。
