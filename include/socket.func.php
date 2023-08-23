<?php

function __SOCKET_ERRORLOG__($data)	//注意ERRORLOG将直接导致脚本退出
{
	global $___MOD_LOG_LEVEL; 
	if ($___MOD_LOG_LEVEL>=1)
	{
		$x=socket_last_error();
		//if ($x) $data.=' 错误信息：'.mb_convert_encoding(socket_strerror($x),'UTF-8').'（错误'.$x.'）';//根据系统不同，strerror可能出现乱码
		if ($x) $data.='（错误'.$x.'）';
		__SOCKET_WARNLOG__($data);
		global $___TEMP_runmode, $___TEMP_CONN_PORT, $___TEMP_script_uniqid;
		$data = $___TEMP_runmode.' #'.$___TEMP_script_uniqid.' on port '.$___TEMP_CONN_PORT.' : '.$data;
		date_default_timezone_set('Etc/GMT');
		$now = time() + 8*3600 + 0*60;   
		list($usec,$tsec)=explode(' ',microtime());
		$usec=round($usec*1000);
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
		$data = "{$year}年{$month}月{$day}日{$hour}时{$min}分{$sec}秒{$usec}毫秒 ".$data."\n";
		file_put_contents(GAME_ROOT.'./gamedata/tmp/log/socket.error.log', $data, FILE_APPEND);
	}
	if ($___TEMP_runmode=='Server') __SERVER_QUIT__(); else die();
}

function __SOCKET_WARNLOG__($data)
{
	global $___MOD_LOG_LEVEL; if ($___MOD_LOG_LEVEL<2) return;
	__SOCKET_LOG__($data);
	global $___TEMP_runmode, $___TEMP_CONN_PORT, $___TEMP_script_uniqid;
	$data = $___TEMP_runmode.' #'.$___TEMP_script_uniqid.' on port '.$___TEMP_CONN_PORT.' : '.$data;
	date_default_timezone_set('Etc/GMT');
	$now = time() + 8*3600 + 0*60;   
	list($usec,$tsec)=explode(' ',microtime());
	$usec=round($usec*1000);
	list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
	$data = "{$year}年{$month}月{$day}日{$hour}时{$min}分{$sec}秒{$usec}毫秒 ".$data. "\n";
	file_put_contents(GAME_ROOT.'./gamedata/tmp/log/socket.warn.log', $data, FILE_APPEND);
}

function __SOCKET_LOG__($data)
{
	global $___MOD_LOG_LEVEL; if ($___MOD_LOG_LEVEL<3) return;
	__SOCKET_DEBUGLOG__($data);
	global $___TEMP_runmode, $___TEMP_CONN_PORT, $___TEMP_script_uniqid;
	$data = $___TEMP_runmode.' #'.$___TEMP_script_uniqid.' on port '.$___TEMP_CONN_PORT.' : '.$data;
	date_default_timezone_set('Etc/GMT');
	$now = time() + 8*3600 + 0*60;   
	list($usec,$tsec)=explode(' ',microtime());
	$usec=round($usec*1000);
	list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
	$data = "{$year}年{$month}月{$day}日{$hour}时{$min}分{$sec}秒{$usec}毫秒 ".$data. "\n";
	file_put_contents(GAME_ROOT.'./gamedata/tmp/log/socket.log', $data, FILE_APPEND);
}

function __SOCKET_DEBUGLOG__($data)
{
	global $___MOD_LOG_LEVEL; if ($___MOD_LOG_LEVEL<4) return;
	global $___TEMP_runmode, $___TEMP_CONN_PORT, $___TEMP_script_uniqid;
	$data = $___TEMP_runmode.' #'.$___TEMP_script_uniqid.' on port '.$___TEMP_CONN_PORT.' : '.$data;
	date_default_timezone_set('Etc/GMT');
	$now = time() + 8*3600 + 0*60;   
	list($usec,$tsec)=explode(' ',microtime());
	$usec=round($usec*1000);
	list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
	$data = "{$year}年{$month}月{$day}日{$hour}时{$min}分{$sec}秒{$usec}毫秒 ".$data. "\n";
	file_put_contents(GAME_ROOT.'./gamedata/tmp/log/socket.debug.log', $data, FILE_APPEND);
}

function __SOCKET_CHECK_WITH_TIMEOUT__($socket, $optype, $tsec, $tusec = 0)
{
	//带timeout的检查socket是否有连接（'a'）可读（'r'）或可写（'w'）
	$temp_null=NULL; $arr=Array($socket);
	if ($optype=='r' || $optype=='a')
	{
		$num_changed_sockets = socket_select($arr, $temp_null, $temp_null, $tsec, $tusec);
	}
	else  if ($optype=='w')
	{
		$num_changed_sockets = socket_select($temp_null, $arr, $temp_null, $tsec, $tusec);
	}
  if ($num_changed_sockets === false || $num_changed_sockets === 0)
		return $num_changed_sockets;//false;
	else  return true;
}

function __SOCKET_SAVE_RESPONSE__($file_uid, $data)
{
	global $___MOD_TMP_FILE_DIRECTORY, $___LOCAL_INPUT__VARS__COOKIE_VAR_LIST;
	writeover($___MOD_TMP_FILE_DIRECTORY.$file_uid, $data);
	if(!empty($___LOCAL_INPUT__VARS__COOKIE_VAR_LIST))
		writeover($___MOD_TMP_FILE_DIRECTORY.$file_uid.'.setcookie', gencode($___LOCAL_INPUT__VARS__COOKIE_VAR_LIST));
}

function __SOCKET_SEND_TO_SERVER__()
{
	global $___MOD_CONN_W_DB;
	global $___TEMP_tablepre, $___TEMP_db;
	
	//准备用户输入数据
	$___TEMP_data=Array();
	$_COOKIE=gstrfilter($_COOKIE);
	foreach ($_COOKIE as $key => $value) $___TEMP_data[$key]=$value;
	$_POST=gstrfilter($_POST);
	foreach ($_POST as $key => $value) $___TEMP_data[$key]=$value;
		
	global $cli_pagestartime;

	$___TEMP_data['___PAGE_STARTTIME_VALUE'] = $cli_pagestartime;
	if (isset($___TEMP_data['game_roomid'])) 
		$game_roomid = $___TEMP_data['game_roomid'];
	else  $game_roomid = '';//非command的返回数据（开局、结尾、用daemon加速的幸存之类）会存到'_'文件夹，这个文件夹会在游戏开局时定期清空
	$___TEMP_data['___GAME_ROOMID'] = $game_roomid;
	
	//防止注入，去掉不合法变量名，去掉可能的global名称
	$___TEMP_data_keys = array_keys($___TEMP_data);
	foreach ($___TEMP_data_keys as $keyc) 
		if (!($keyc!='' && (('a'<=$keyc[0] && $keyc[0]<='z') || ('A'<=$keyc[0] && $keyc[0]<='Z') || $keyc[0]=='_') && check_alnumudline($keyc)))
			unset($___TEMP_data[$keyc]);
	if (isset($___TEMP_data['_COOKIE'])) unset($___TEMP_data['_COOKIE']);
	if (isset($___TEMP_data['_POST'])) unset($___TEMP_data['_POST']);
	if (isset($___TEMP_data['_REQUEST'])) unset($___TEMP_data['_REQUEST']);
	if (isset($___TEMP_data['_GLOBALS'])) unset($___TEMP_data['_GLOBALS']);
	if (isset($___TEMP_data['GLOBALS'])) unset($___TEMP_data['GLOBALS']);
	
	//存储用户输入数据
	$___TEMP_uid=uniqid('',true);		//获取唯一ID
	//DEBUG
	$___TEMP_data_debug = $___TEMP_data;
	ob_clean();
	var_export($___TEMP_data);
	$___TEMP_data=ob_get_contents();
	ob_clean();
	if ($___MOD_CONN_W_DB)
	{
		$___TEMP_db->query("INSERT INTO {$___TEMP_tablepre}temp (sid,value) VALUES ('{$___TEMP_uid}','".base64_encode($___TEMP_data)."')");
	}
	else 
	{
		global $___MOD_TMP_FILE_DIRECTORY;
		if (!file_exists($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_'))
		{
			create_dir($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_');
		}
		else  if (!is_dir($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_'))
		{
			unlink($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_');
			create_dir($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_');
		}
		
		writeover($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_/'.$___TEMP_uid,$___TEMP_data);
	}
	
	//连接server
	global $___TEMP_CONN_PORT;
	$___TEMP_socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);  
	if ($___TEMP_socket===false) __SOCKET_ERRORLOG__("socket_create失败。");
	$___TEMP_connected=socket_connect($___TEMP_socket,'127.0.0.1',$___TEMP_CONN_PORT);
	if (!$___TEMP_connected) __SOCKET_ERRORLOG__("socket_connect失败。");
	
	/*
	//允许3秒等待
	if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'w', 3, 0)) __SOCKET_ERRORLOG__("socket_write等待时间过长。"); 
	*/
	
	//发送消息给server
	global $___MOD_CONN_PASSWD;
	if (!socket_write($___TEMP_socket,$___MOD_CONN_PASSWD.$game_roomid.'_/'.$___TEMP_uid."\n")) __SOCKET_ERRORLOG__("socket_write失败");

	__SOCKET_DEBUGLOG__("消息已发送，等待回应。");
	
	/*
	//允许3秒等待
	if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'r', 3, 0)) __SOCKET_ERRORLOG__("socket_read等待时间过长。"); 
	*/
	
	$___TEMP_ret = socket_read($___TEMP_socket, 1024, PHP_NORMAL_READ);
	
	if ($___TEMP_ret===false)
	{
		socket_shutdown($___TEMP_socket);
		__SOCKET_ERRORLOG__("socket_read失败。关闭连接。");
	}
	else  
	{
		__SOCKET_DEBUGLOG__("回应已读取，关闭连接。");
		socket_shutdown($___TEMP_socket);	
	}
	
	$___TEMP_ret = substr($___TEMP_ret,0,-1);	//去掉换行
	if ($___TEMP_ret!=$___MOD_CONN_PASSWD.'_ok') __SOCKET_ERRORLOG__("未知返回信息 ".$___TEMP_ret.' 。');
	
	if ($___MOD_CONN_W_DB)
	{
		$result = $___TEMP_db->query("SELECT value FROM {$___TEMP_tablepre}temp WHERE sid='{$___TEMP_uid}'");
		if(!$___TEMP_db->num_rows($result)) __SOCKET_ERRORLOG__("数据库中没有记录名为 {$___TEMP_uid} 的记录。");
		$___TEMP_res = $___TEMP_db->fetch_array($result);
		$___TEMP_res = $___TEMP_res['value'];
		$___TEMP_res = base64_decode($___TEMP_res);
		$___TEMP_db->query("DELETE FROM {$___TEMP_tablepre}temp WHERE sid='{$___TEMP_uid}'");
	}
	else
	{
		global $___MOD_TMP_FILE_DIRECTORY;
		$___TEMP_res=file_get_contents($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_/'.$___TEMP_uid);
		if (!defined('MOD_REPLAY')) 	//如果录像模式开启，最后删缓存的工作由录像模块进行
			unlink($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_/'.$___TEMP_uid);
	}
	
	$___TEMP_res_setcookie = array();
	if(file_exists($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_/'.$___TEMP_uid.'.setcookie'))
	{
		$___TEMP_res_setcookie=file_get_contents($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_/'.$___TEMP_uid.'.setcookie');
		$___TEMP_res_setcookie=gdecode($___TEMP_res_setcookie,1);
		unlink($___MOD_TMP_FILE_DIRECTORY.$game_roomid.'_/'.$___TEMP_uid.'.setcookie');
		foreach($___TEMP_res_setcookie as $tmp_rc_k => $tmp_rc_v) 
			gsetcookie($tmp_rc_k, $tmp_rc_v['value'], $tmp_rc_v['life'], $tmp_rc_v['prefix']);
	}
	
	__SOCKET_DEBUGLOG__("已载入回应文件。");
	
	global $cli_pagestartime;
	$timecost = get_script_runtime($cli_pagestartime);
	if ($timecost > 0.15) {
		__SOCKET_WARNLOG__("警告：本次操作耗时较长，耗时为 ".$timecost." 秒。页面：".$___TEMP_data_debug['page']."；操作信息：".(!empty($___TEMP_data_debug['command']) ? $___TEMP_data_debug['command'] : '-')."；操作者：".$___TEMP_data_debug['acbra2_user']);
	}
	/*
	$___TEMP_res = str_replace('_____PAGE_RUNNING_TIME_____',(string)$timecost,$___TEMP_res);
	*/
	ob_clean();
	echo $___TEMP_res;
	ob_end_flush();
	
	//file_put_contents('a.txt', var_export($___TEMP_data_debug,1));
}

function __SOCKET_LOAD_DATA__(&$___TEMP_connection)
{
	// 从客户端取得信息  
	if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_connection, 'r', 0, 200000))	//200毫秒等待时间
	{
		__SOCKET_WARNLOG__("警告：socket_read等待时间过长。结束流程。"); 
		return false;
	}
	$___TEMP_socket_data = @socket_read($___TEMP_connection, 1024, PHP_NORMAL_READ); 
	__SOCKET_DEBUGLOG__("已读取信息。"); 
	
	$___TEMP_socket_data = substr($___TEMP_socket_data,0,-1);	//去掉换行
	
	global $___MOD_CONN_PASSWD;
	if (substr($___TEMP_socket_data,0,strlen($___MOD_CONN_PASSWD))!=$___MOD_CONN_PASSWD)
	{
		__SOCKET_WARNLOG__("警告：连接密码错误。其提供的连接密码为 ".substr($___TEMP_socket_data,0,strlen($___MOD_CONN_PASSWD))." 。结束流程。");
		return false;
	}
	
	$___TEMP_socket_data=substr($___TEMP_socket_data,strlen($___MOD_CONN_PASSWD));
	
	if ($___TEMP_socket_data=='stop')
	{
		__SOCKET_LOG__("收到指令要求退出，紧急退出。");
		__SERVER_QUIT__();
	}
	
	if ($___TEMP_socket_data=='touch')
	{
		__SOCKET_DEBUGLOG__("收到了测试命令。");
		global $___TEMP_is_root; if (!$___TEMP_is_root) $x='Received'; else $x='Received_root';
		if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_connection, 'w', 0, 200000))	
		{
			__SOCKET_WARNLOG__("警告：socket_write等待时间过长。结束流程。"); 
		}
		else  if (!socket_write($___TEMP_connection,$x."\n")) 
		{ 
			__SOCKET_WARNLOG__("警告：socket_write失败。结束流程。"); 
		}  
		return false;
	}
		
	global $___MOD_CONN_W_DB;
	global $___TEMP_tablepre, $___TEMP_db;
	
	$___TEMP_PLAYER_CMD = NULL;
	if ($___MOD_CONN_W_DB)
	{
		$result = $___TEMP_db->query("SELECT value FROM {$___TEMP_tablepre}temp WHERE sid='{$___TEMP_socket_data}'");
		if(!$___TEMP_db->num_rows($result)) { __SOCKET_WARNLOG__("警告：数据库中没有记录名为 {$___TEMP_socket_data} 的记录。结束流程。"); return false; }
		$x = $___TEMP_db->fetch_array($result);
		$x = $x['value'];
		$x = base64_decode($x);
	}
	else
	{
		global $___MOD_TMP_FILE_DIRECTORY;
		if (!file_exists($___MOD_TMP_FILE_DIRECTORY.$___TEMP_socket_data))
		{
			__SOCKET_WARNLOG__("警告：文件 {$___TEMP_socket_data} 不存在。结束流程。");
			return false;
		}
		$x=file_get_contents($___MOD_TMP_FILE_DIRECTORY.$___TEMP_socket_data);
	}  
	
	__SOCKET_DEBUGLOG__("已读取信息文件。");
	
	eval('$___TEMP_PLAYER_CMD='.$x.';');
			
	__SOCKET_DEBUGLOG__("已载入信息文件。");
	
	global $___LOCAL_INPUT__VARS__INPUT_VAR_LIST;
	$___LOCAL_INPUT__VARS__INPUT_VAR_LIST=Array();
	foreach ($___TEMP_PLAYER_CMD as $key => $value)
	{
		$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$key]=$value;
	}
	
	//其他模块都已经在上次执行完成后重新初始化过了，现在只需初始化一下SYS模块即可（这是因为SYS模块的信息是需要不断更新的）
	global $___TEMP_MOD_LIST_n, $___TEMP_MOD_NAME;
	for ($i=1; $i<=$___TEMP_MOD_LIST_n; $i++) 
		if (strtoupper($___TEMP_MOD_NAME[$i])=='SYS') 
		{
			$funcname = $___TEMP_MOD_NAME[$i].'\\___pre_init'; $funcname();
			$funcname = $___TEMP_MOD_NAME[$i].'\\init'; $funcname();
			$funcname = $___TEMP_MOD_NAME[$i].'\\___post_init'; $funcname();
		}
					
	__SOCKET_DEBUGLOG__("初始化信息成功。开始执行。");
	
	return $___TEMP_socket_data;
}  

function __SEND_STOP_CMD__($port)
{
	$___TEMP_socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);  
	if ($___TEMP_socket===false) { __SOCKET_WARNLOG__("警告：socket_create失败。"); return false; }
	$___TEMP_connected=socket_connect($___TEMP_socket,'127.0.0.1',$port);
	if (!$___TEMP_connected) { __SOCKET_WARNLOG__("警告：socket_connect失败。"); return false; }
	
	//允许3秒等待
	if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'w', 3, 0)) { __SOCKET_WARNLOG__("警告：socket_write等待时间过长。"); return false; } 
						
	//发送消息给server
	global $___MOD_CONN_PASSWD;
	if (!socket_write($___TEMP_socket,$___MOD_CONN_PASSWD.'stop'."\n")) { __SOCKET_WARNLOG__("警告：socket_write失败"); return false; }
	
	__SOCKET_DEBUGLOG__("消息已成功发送，关闭连接。");
	socket_shutdown($___TEMP_socket);	//关闭socket
	return true;
}

function __SEND_TOUCH_CMD__($port)
{
	$___TEMP_socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);  
	if ($___TEMP_socket===false) return "socket_create失败。";
	$___TEMP_connected=socket_connect($___TEMP_socket,'127.0.0.1',$port);
	if (!$___TEMP_connected) return "socket_connect失败。";
	
	//允许3秒等待
	$___TEMP_socket_TOUCH_STATE = __SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'w', 3, 0);
	if(0===$___TEMP_socket_TOUCH_STATE) return "写阻塞。(".socket_last_error().")"; 
	elseif(false===$___TEMP_socket_TOUCH_STATE) return "写失败。(".socket_last_error().")"; 
	//if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'w', 3, 0)) return "socket_write等待时间过长。"; 
	
	//发送消息给server
	global $___MOD_CONN_PASSWD;
	if (!socket_write($___TEMP_socket,$___MOD_CONN_PASSWD.'touch'."\n")) return "socket_write失败"; 
	
	$___TEMP_socket_TOUCH_STATE = __SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'r', 3, 0);
	if(0===$___TEMP_socket_TOUCH_STATE) return "读阻塞。(".socket_last_error().")"; 
	elseif(false===$___TEMP_socket_TOUCH_STATE) return "读失败。(".socket_last_error().")"; 
	//if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'r', 3, 0)) return "socket_read等待时间过长。"; 
	
	$___TEMP_ret = socket_read($___TEMP_socket, 1024, PHP_NORMAL_READ);
	
	if ($___TEMP_ret===false)
	{
		socket_shutdown($___TEMP_socket);
		return "socket_read失败。";
	}
	else  
	{
		socket_shutdown($___TEMP_socket);	
		if ($___TEMP_ret=="Received\n" || $___TEMP_ret=="Received_root\n")
			if ($___TEMP_ret=="Received\n") return 'ok'; else return 'ok_root';
		else  return '回复信息异常。';
		
	}
}

function __SERVER_QUIT__()
{
	global $___TEMP_CONN_PORT;
	clear_dir(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT);
	__SOCKET_LOG__("已经退出。");
	global $___TEMP_EXPECTED_DEATH;
	$___TEMP_EXPECTED_DEATH = 1;
	die();
}

function __STOP_ALL_SERVER__()
{
	global $___TEMP_runmode,$___TEMP_CONN_PORT;
	$___TEMP_runmode = 'Admin';
	$___TEMP_CONN_PORT = -1;
	__SOCKET_LOG__("收到命令，立即停止所有服务器。");
		
	if ($handle=opendir(GAME_ROOT.'./gamedata/tmp/server')) 
	{
		while (($sid=readdir($handle))!==false) 
		{
			if ($sid=='.' || $sid=='..') continue;
			$sid=(int)$sid; 
			__STOP_SINGLE_SERVER__($sid);
		}
			
		if (file_exists(GAME_ROOT.'./gamedata/tmp/server/request_new_server'))
			unlink(GAME_ROOT.'./gamedata/tmp/server/request_new_server');
		
		if (file_exists(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server'))
			unlink(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
	}
	else  __SOCKET_ERRORLOG__('无法打开gamedata/tmp/server目录。');
}

function __STOP_SINGLE_SERVER__($sid)
{
	if (is_dir(GAME_ROOT.'./gamedata/tmp/server/'.(string)$sid))
	{
		__SOCKET_LOG__("开始向端口号为 {$sid} 的服务器发送停止指令。");
		if (!__SEND_STOP_CMD__($sid))
		{
			__SOCKET_WARNLOG__("消息发送失败，强行删除文件夹 {$sid} 。");
			clear_dir(GAME_ROOT.'./gamedata/tmp/server/'.(string)$sid);
		}
	}
}

function MODSRV_shutDownFunction()
{
	global $___TEMP_EXPECTED_DEATH;
	if (!$___TEMP_EXPECTED_DEATH)
	{
		$error = error_get_last();
		__SOCKET_ERRORLOG__('意外退出。错误信息：错误 '.$error['type'].' , '.$error['message'].' 于文件 '.$error['file'].' 的第 '.$error['line'].' 行。');
	}
	die();
}
?>