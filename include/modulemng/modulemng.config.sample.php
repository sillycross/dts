<?php

//修改了本文件任何设置后务必在modulemng.php里[进入编辑模式]=>[保存]=>[应用更改]

//是否预处理模块初始化代码modinit
//代码预处理可以大幅提升模块加载速度
//如果开启了这个选项，任何对modules文件夹的修改都必须在modulemng.php里重新[进入编辑模式]=>[保存]=>[应用更改]才能真正生效
//除非处于调试状态，否则强烈建议开启
$___MOD_CODE_ADV1 = 0;

//是否展开函数初始化代码eval(__MAGIC__)
//要求CODE_ADV1开启方可开启
//这会大幅降低模块加载速度，但提高模块执行速度
//如果开启了这个选项，任何对modules文件夹的修改都必须在modulemng.php里重新[进入编辑模式]=>[保存]=>[应用更改]才能真正生效
//建议配合daemon模式使用，如未开启daemon模式建议关闭，调试时建议关闭，在单机版中建议关闭
//只要不要写过于奇葩的语句（比如eval(import_module(eval('echo \'sys\';')));这种……）一般都不会出问题
//如果调用各种import函数/宏的时候参数都是常量或简单变量，就肯定不会有事
$___MOD_CODE_ADV2 = 0;

//是否将同名函数合并
//要求CODE_ADV2开启方可开启，否则不会执行
//会几乎消除函数调用时间，但可能与一些偏门写法如闭包等不兼容
//如果开启了这个选项，任何对modules文件夹的修改都必须在modulemng.php里重新[进入编辑模式]=>[保存]=>[应用更改]才能真正生效
//建议在开启CODE_ADV2的时候默认开启
//由于兼容性不理想，且性能提升有限，目前不再维护，建议生产环境关闭，仅在需要查看执行顺序时开启
$___MOD_CODE_COMBINE = 0;

//是否开启模板html预处理
//要求CODE_ADV1与CODE_ADV2均开启方可开启
//这可以大幅降低流量消耗（降低70%）
//此外，开启后才能记录游戏录像
$___MOD_CODE_ADV3 = 0;

//是否使用daemon模式，daemon模式可以完全消除模块加载时间
//要求CODE_ADV1和CODE_ADV2均开启方可开启
//要求php开启socket扩展，须从服务器命令行中启动acdts-daemonctl.sh
//服务器版中有条件则建议开启，单机版中关闭就行
$___MOD_SRV = 0;

//是否允许php自动接力启动驻留进程。只在acdts-daemonctl.sh不在运行时才有效。
$___MOD_SRV_AUTO = 1;
//是否允许php在有用户指令时，冷启动驻留进程
$___MOD_SRV_COLD_START = 1;

//daemon模式下连接密码，各项命令都要验证密码
$___MOD_CONN_PASSWD = '233';
//daemon模式下端口号范围
$___MOD_CONN_PORT_LOW = 20000;
$___MOD_CONN_PORT_HIGH = 21000;
//daemon模式下最终结果通过数据库还是文件返回（1为数据库），推荐选择文件，数据库似乎实践中表现极差
//××××××警告：使用数据库的选项现在已经被在代码中被废弃。现在唯一可用选项就是文件。××××××
$___MOD_CONN_W_DB = 0;
//daemon模式下，如最终结果通过文件返回，临时文件存放目录
//默认使用游戏内目录
$___MOD_TMP_FILE_DIRECTORY = GAME_ROOT.'./gamedata/tmp/response/';
//daemon模式下驻留进程最小执行时间。如果服务器max_execution_time小于这个值，会以这个值为准；超过则以max_execution_time为准
$___MOD_SRV_MIN_EXECUTION_TIME = 180;
//daemon模式下驻留进程最大执行时间。
$___MOD_SRV_MAX_EXECUTION_TIME = 1800;
//daemon模式下server无连接情况下的唤醒时间（秒），daemon的实际执行时间大约是服务器设置的max_execution_time减去这个值
//不要超过服务器设置max_execution_time的五分之一，尽可能低一些，但不要低于5秒。WIN下应适当增加这个时间（POWERSHEL执行速度慢）
$___MOD_SRV_WAKETIME = 30;
//daemon模式下，如果一台非根daemon在主动退出前已经超过这个时间（秒）没有接到任何命令了，它将不会要求启动一台新daemon替代它
$___MOD_VANISH_TIME = 300;
//daemon模式下日志级别 
//4=全部日志（极大量，仅供调试）, 3=仅重要日志、警告和错误, 2=仅警告和错误, 1=仅错误, 0=不记录任何日志
$___MOD_LOG_LEVEL = 2;

//默认修正
//非command页面不使用代码缓存后的结果，加快加载速度（因为非游戏界面无法利用daemon）
if (!defined('IN_MODULEMNG') && !defined('IN_COMMAND') && CURSCRIPT!=='news') $___MOD_CODE_ADV2 = 0;

?>
