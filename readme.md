# 常磐大逃杀 N.E.W.版

一个基于php、以文本形式为主、多人即时混战类、带有roguelike特征的webgame，可理解为Multi-player online roguelite battle-royale webgame。

## 游戏目标

- “大”——积累资源，壮大力量
- “逃”——躲避强敌，寻找机会
- “杀”——斗智斗勇，杀出胜利

## 游戏特性

- 即开即玩，易于上手 + 战术复杂，难于精通
- 源于大逃杀原作的1 VS N混战精神
- 多样化的求胜路径和策略
- 令人眼花缭乱的攻击属性、合成链、技能树
- 在标准模式以外，还有随机卡片、荣耀、极速、SOLO、团战、PVE等多种模式
- 成就和集卡系统
- 大量引人会心一笑的ACG梗

## 技术特性

- 能将模块化源代码自动编译为高效执行代码的模块化引擎，兼顾代码组织、多人协作和运行效率优化
- 基于socket的驻留daemon，支持Linux和WIN

## 部署指南

0. 环境要求：php 7.0以上，推荐7.0至8.0（超过8.0版本尚未充分测试）。必须开启curl和socket两项扩展，并将php.ini中output_buffering一项打开（一般设为output_buffering = 4096）。建议将php所需内存修改为256M或更高，建议将php最大执行时间修改为180秒或更高。
1. 部署准备：把游戏根目录下的gamedata、include和templates三个文件夹全部chmod 0777，win7以上系统需要为IUSR用户添加完全访问权限，可自行查找相关教程进行设置。另外，去gamedata文件夹删掉install.lock文件。
2. 自助部署：访问install_NEW.php，一路照提示走，其中网站地址、数据库名、数据库用户名和密码、管理员用户名和密码应根据实际情况进行改动。安装程序会自动新建数据表并生成server.config.php、system.config.php、modulemng.config.php等本地配置文件，并自动修改___MOD_CODE_ADV一系列变量。
3. 检查基础文件完整性：访问首页，用刚才安装时输的管理员帐号登录，并测试游戏是否能正常开始和结束。到这一步如果没有错误，游戏已经可以运行，但是处于基础模式，执行效率极低，建议仅用于开发。

- 如果你想实际部署，请继续往下看。

4. 开启adv模式：在第3步登陆后获取了cookie的前提下，访问modulemng.php，根据需求开启ADV1 ADV2 ADV3和SRV共4个选项（生产环境建议全部开启），之后点击“重设代码缓存”-->“应用更改”，等系统自动处理完毕。
5. 检查daemon运行情况：访问daemonmng.php，如果显示有进程在运行，则游戏已进入daemon模式，可以高效运行了。根据需要，还可以运行游戏目录下的sh文件（linux环境）或者bat文件（WIN幻境）作为外部触发程序，保证一直有daemon进程在运行。

- 注意：开启adv和daemon模式后，所有对源代码的更改都必须通过modulemng.php重新编译才能生效。

6. 收尾工作：如果服务器对外开放，可酌情选择把install_NEW.php删掉。如果需要修改标题、关闭错误提示等，可用游戏管理员账号进入“管理后台”自行设置。
7. 定期维护：玩家数目充足的情况下，游戏大约会以每个月4G的速度积累游戏录像，如果服务器空间不够，可定期删除。

## Docker部署

1. 部署准备：同上，把游戏根目录下的gamedata、include和templates三个文件夹全部chmod 0777，win7以上系统需要为IUSR用户添加完全访问权限，可自行查找相关教程进行设置。另外，去gamedata文件夹删掉install.lock文件。
2. 启动docker：

    ```bash
    docker-compose build
    docker-compose up -d
    ```

    http服务器将会运行在8080端口。

3. 自助部署：同上，访问install_NEW.php，一路照提示走，其中数据库用户名为root，密码为password，数据库地址为mariadb。安装程序会自动新建数据表并生成server.config.php、system.config.php、modulemng.config.php等本地配置文件，并自动修改___MOD_CODE_ADV一系列变量。

4. 接下来的流程同上。