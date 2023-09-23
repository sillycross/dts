<?php

$server_address = 'http://127.0.0.1/dts';//本地服务器地址，用于daemon，建议这里用127.0.0.1等本地IP，最大程度加快速度
	
$gameurl = 'http://127.0.0.1/dts';//游戏外部地址，用于界面链接和部分数据传输功能
	
$database = 'mysqli';//MySQL版本。目前只支持mysqli

$dbcharset = 'utf8';//MySQL 字符集 可选'gbk', 'big5', 'utf8', 'latin1', 留空为按照游戏字符集设定

$dbhost = '127.0.0.1';//数据库服务器域名，用于数据库连接。会被install_NEW.php修改

$dbuser = 'root';//数据库用户名。会被install_NEW.php修改

$dbpw = 'root';//数据库密码。会被install_NEW.php修改

$dbname = 'acdts';//数据库名。会被install_NEW.php修改

$dbreport = 1;//是否发送数据库错误报告，0为否，1为是

$cookiedomain = '';//cookie 作用域

$cookiepath = '/';//cookie 作用路径

$headercharset = 0;//强制设置字符集。残留代码，基本没用

$onlinehold = 900;//在线保持时间,单位秒。残留代码，基本没用

$pconnect = true;//数据库持久连接。考虑到php7.0以上强制使用mysqli，基本没用

$gamefounder = 'admin';//游戏创始人UID，相当于权限10。会被install_NEW.php修改

$moveut = 8;//手动时区设置，默认东8区。如果本地时间跟服务器时间有时差，在此处更改

$moveutmin = 0;//如果本地时间跟服务器时间有分钟差，在此处更改，单位分钟

$gtablepre = 'acbra2_';// 表名前缀, 同一数据库安装多个游戏请修改此处。也用于其他多处重要判断。会被install_NEW.php修改

$authkey = 'bra';	//游戏加密密钥，要与插件密钥相同。残留代码，基本没用。

$charset = 'utf-8';// 游戏默认字符集 可选'gbk', 'big5', 'utf-8'

$tplrefresh = 1;// 模板自动刷新开关 0=关闭, 1=打开。dz模板遗留参数，建议常时开启（因为部分游戏机制会刷新网页文件）

$bbsurl = 'https://bbs.brdts.online/';//论坛地址，用于顶部链接

$homepage = 'http://amarillonmc.github.io/';//官方网站地址，用于顶部链接

$gtitle = 'ACFUN 大 逃 杀';//游戏标题

$errorinfo = 1;				//是否开启错误信息提示，0为关闭，1为开启。开启会泄漏游戏安装路径

/* End of file server.config.sample.php */
/* Location: /include/modules/core/sys/config/server.config.sample.php */