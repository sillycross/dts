<?php

//以下内容在游戏安装时初始化，不能通过游戏后台修改
// [EN]	Set below parameters according to your account information provided by your hosting
// [CH] 以下变量请根据空间商提供的账号参数修改 如有疑问,请联系服务器提供者

	$server_address = 'http://127.0.0.1/dts'; 			//本地服务器域名，用于daemon，建议这里用本地IP，最大程度加快速度
	
	$dbhost = '127.0.0.1';			// database server
						// 数据库服务器

	$dbuser = 'root';			// database username
						// 数据库用户名

	$dbpw = 'root';			// database password
						// 数据库密码

	$dbname = 'acdts';			// database name
						// 数据库名

	$dbreport = 1;				// send db error report? 1=yes
						// 是否发送数据库错误报告? 0=是 1=否

// [EN] If you have problems logging in Discuz!, then modify the following parameters, else please leave default
// [CH] 如您?cookie 作用范围有特殊要求或游戏登录不正常,请修改下面变量否则请保持默认

	$cookiedomain = ''; 			// cookie domain
						// cookie 作用域

	$cookiepath = '/';			// cookie path
						// cookie 作用路径


// [EN] Special parameters, DO NOT modify these unless you are an expert in Discuz!
// [CH] 以下变量为特别选项,一般情况下没有必要修改

	$headercharset = 0;			// force outputing charset header
						// 强制设置字符集，只乱码时使用

	$onlinehold = 900;			// time span of online recording
						// 在线保持时间,单位秒

	$pconnect = true;				// persistent database connection, 0=off, 1=on
						// 数据库持久连接 false=关闭, true=打开 mysql之外的模式可能不适用

	$gamefounder = 'admin';			// super administrator's UID
						// 游戏创始人UID，相当于权限10

	$moveut = 8; //set the difference of server time and client time
			//如果本地时间跟服务器时间有时差，在此处更改

	$moveutmin = 0; //set the difference of server time and client time, by minutes
	//如果本地时间跟服务器时间有分钟差，在此处更改，单位分钟

// [EN] !ATTENTION! Do NOT modify following after your board was settle down
// [CH] 游戏投入使用后不能修改的变量

	$gtablepre = 'acbra2_';   			// 表名前缀, 同一数据库安装多个游戏请修改此处
						// table prefix, modify this when you are installingmore than 1 Discuz! in the same database.

	$authkey = 'bra';		//game encrypt key ,the same of plus key
						//游戏加密密钥，要与插件密钥相同

// [EN] !ATTENTION! Preservation or debugging for developing
// [CH] 切勿修改以下变量,仅供程序开发调试用!

	$database = 'mysql';			// 'mysql' for MySQL version and 'pgsql' for PostgreSQL version
						// MySQL 版本请设置'mysql', MySQLi 版本请设置'mysqli'

	$charset = 'utf-8';			// default character set, 'gbk', 'big5', 'utf-8' are available
						// 游戏默认字符集 可选'gbk', 'big5', 'utf-8'

	$dbcharset = 'utf8';			// default database character set, 'gbk', 'big5', 'utf8', 'latin1' and blank are available
						// MySQL 字符集 可选'gbk', 'big5', 'utf8', 'latin1', 留空为按照游戏字符集设定

	$tplrefresh = 1;			// auto check validation of templates, 0=off, 1=on
						// 模板自动刷新开关 0=关闭, 1=打开, 在不修改页面的情况下可以关闭

	$bbsurl = 'http://6666.76573.org/';    //the bbs url for the game plus
									//安装游戏插件的论坛地址

	$gameurl = 'http://127.0.0.1/dts';    // the url of game program files,for the full-window mode
									//游戏域名地址，用于界面链接

	$homepage = 'http://soul573.com/';      // game homepage
									//官方网站地址

	$gtitle = 'ACFUN 大 逃 杀';     //game title
							//游戏标题

	$errorinfo = 1;				//是否开启错误信息提示，1为开启，0为关闭。开启会泄漏游戏安装路径

	
// ============================================================================

?>
