<?php

defined('IN_GAME') || define('IN_GAME', TRUE);
defined('IN_COMMAND') || define('IN_COMMAND', TRUE);
defined('CURSCRIPT') || define('CURSCRIPT', 'game');
defined('GAME_ROOT') || define('GAME_ROOT', dirname(__FILE__).'/');
require GAME_ROOT.'./include/modulemng/modulemng.config.php';
$___TEMP_script_uniqid = uniqid();
$___TEMP_script_uniqid = substr($___TEMP_script_uniqid, strlen($___TEMP_script_uniqid)-5);

if ($___MOD_SRV)
{
	if (isset($_POST['conn_passwd']) && $_POST['conn_passwd']==$___MOD_CONN_PASSWD && isset($_POST['command']) && $_POST['command']=='start')
	{
		define('IN_DAEMON', TRUE);
		define('GEXIT_RETURN_JSON', TRUE);
		
		//脚本以server模式被启动
		//让调用端不再等待立即退出
		ob_end_clean();
		header("Connection: close");
		header("HTTP/1.1 200 OK");
		ob_start();
		echo "received";
		$size=ob_get_length();
		header("Content-Length: $size");
		ob_end_flush();
		flush();
		unset($size);

		//开始执行
		if (!empty($_POST['is_root'])) $___TEMP_is_root=1; else $___TEMP_is_root=0;
		
		unset($_COOKIE); unset($_POST); unset($_GET); unset($_REQUEST); unset($_FILES);
		
		//执行时间设定，介于$___MOD_SRV_MIN_EXECUTION_TIME与$___MOD_SRV_MAX_EXECUTION_TIME之间
		$___TEMP_max_time = ini_get('max_execution_time');
		if ($___TEMP_max_time == 0 || $___TEMP_max_time > $___MOD_SRV_MAX_EXECUTION_TIME) $___TEMP_max_time = $___MOD_SRV_MAX_EXECUTION_TIME;
		elseif ($___TEMP_max_time < $___MOD_SRV_MIN_EXECUTION_TIME) $___TEMP_max_time = $___MOD_SRV_MIN_EXECUTION_TIME;
		set_time_limit($___TEMP_max_time);
		
		$___TEMP_server_start_time = time();
		
		ignore_user_abort(1);
		
		require GAME_ROOT.'./include/common.inc.php';
		require GAME_ROOT.'./include/socket.func.php';
		
		$___TEMP_EXPECTED_DEATH = 0;
		register_shutdown_function('MODSRV_shutDownFunction');
		
		$___TEMP_runmode = 'Server';
		$___TEMP_CONN_PORT = '-1';
		
		global $___PRESET_SYS__VARS__db; 
		$___PRESET_SYS__VARS__db=$db; 	//不重复连接db
					
		$___TEMP_db=$db;
		$___TEMP_tablepre=$tablepre;
		$___TEMP_newsrv_flag = 0;
		$___TEMP_last_cmd = 0;
		
		__SOCKET_LOG__("新驻留进程被启动，开始工作。"); 
		$___TEMP_socket=socket_create(AF_INET,SOCK_STREAM,getprotobyname("tcp"));  
		if ($___TEMP_socket===false) __SOCKET_ERRORLOG__('socket_create失败。'); 
		if (socket_set_option($___TEMP_socket,SOL_SOCKET,SO_REUSEADDR,1)===false) __SOCKET_ERRORLOG__('socket_set_option失败。'); 
		//socket_set_nonblock($___TEMP_socket);
		while (1)
		{
			$___TEMP_CONN_PORT_TRY=rand($___MOD_CONN_PORT_LOW,$___MOD_CONN_PORT_HIGH);
			__SOCKET_DEBUGLOG__("正在尝试绑定端口".$___TEMP_CONN_PORT_TRY."...");
			if (socket_bind($___TEMP_socket,'127.0.0.1',$___TEMP_CONN_PORT_TRY) === false) 
				__SOCKET_DEBUGLOG__("绑定端口".$___TEMP_CONN_PORT_TRY.'失败。（'.socket_strerror(socket_last_error()).'）'); 
			else
			{
				__SOCKET_LOG__("绑定端口".$___TEMP_CONN_PORT_TRY.'成功。'); 
				$___TEMP_CONN_PORT = $___TEMP_CONN_PORT_TRY;
				break;
			}
		}
		if (socket_listen($___TEMP_socket,5)===false) __SOCKET_ERRORLOG__('socket_listen失败。'); 
		
		//以端口号为名创建文件夹，其中可能存在busy文件，start_time文件，is_root文件，worknum文件
		mymkdir(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT);
		touch(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/start_time');
		if($___TEMP_is_root) touch(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/is_root');
		
		//进入闲置状态
		if (file_exists(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/busy'))
			unlink(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/busy');
				
		//__SOCKET_WARNLOG__("file: ".xdebug_get_profiler_filename());
		
		__SOCKET_DEBUGLOG__("开始监听端口..");
		while (true) 
		{  
			//新建驻留进程最优先，避免长期无人访问导致驻留进程全部关闭
			$request_new_root_server = $request_new_server = 0;
			if(file_exists(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server')) $request_new_root_server = 1;
			if(file_exists(GAME_ROOT.'./gamedata/tmp/server/request_new_server')) $request_new_server = 1;
			if ($___MOD_SRV_AUTO && $___TEMP_is_root && ($request_new_root_server || $request_new_server)){
				//获取shell daemon（外部bash或者bat）的状态
				$t=max((int)file_get_contents(GAME_ROOT.'./gamedata/tmp/server/scriptalive.txt'), (int)filemtime(GAME_ROOT.'./gamedata/tmp/server/scriptalive.txt'));
				//如果shell daemon（外部bash或者bat）不在运行，才进入自动新建逻辑
				if(time()-$t > 10) {
					//判定自己是不是最新的根进程
					$thisnewest = 1;
					$___TEMP_runned_time = time()-$___TEMP_server_start_time;
					foreach(gdir(GAME_ROOT.'./gamedata/tmp/server', 'dir') as $sid) {
						$sid=(int)$sid; 
						//自己、端口号非法、并非根进程，都无效
						if ($sid == $___TEMP_CONN_PORT || $sid<$___MOD_CONN_PORT_LOW || $sid>$___MOD_CONN_PORT_HIGH || !file_exists(GAME_ROOT.'./gamedata/tmp/server/'.$sid.'/is_root')) continue;
						if(time()-(int)filemtime(GAME_ROOT.'./gamedata/tmp/server/'.$sid.'/start_time') < $___TEMP_runned_time) {
							$thisnewest = 0;
							break;
						}
					}
					//如果是最新启动的进程，且监测到有启动新进程的请求，自动新开一个驻留进程。会临时进入忙碌状态
					if($thisnewest){
						__SOCKET_DEBUGLOG__("进入忙碌状态。");
						touch(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/busy');
						if($request_new_server){
							unlink(GAME_ROOT.'./gamedata/tmp/server/request_new_server');
							__SOCKET_LOG__("接受请求，启动新驻留进程。");
							curl_new_server($___MOD_CONN_PASSWD);
						}
						if($request_new_root_server) {
							unlink(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
							__SOCKET_LOG__("接受请求，启动新的根驻留进程。");
							curl_new_server($___MOD_CONN_PASSWD,1);
						}
						unlink(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/busy');
						__SOCKET_DEBUGLOG__("进入闲置状态。");
					}
				}
			}
			
			if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'a', $___MOD_SRV_WAKETIME, 0))
			{
				$___TEMP_runned_time = time()-$___TEMP_server_start_time;
				if ($___TEMP_runned_time+$___MOD_SRV_WAKETIME+5>$___TEMP_max_time)
				{
					//没有下一次唤醒了，主动退出
					__SOCKET_LOG__("已经运行了 ".$___TEMP_runned_time."秒，接近".$___TEMP_max_time."秒的限制。主动退出。");
					if (!$___TEMP_newsrv_flag)
						__SOCKET_LOG__("由于过长时间没有收到命令且不是惟一的驻留进程，没有要求启动替代者。");
					__SERVER_QUIT__();
				}
				elseif ($___TEMP_runned_time+$___MOD_SRV_WAKETIME*2+5>$___TEMP_max_time && !$___TEMP_newsrv_flag)
				{
					//老server即将在下一次唤醒时主动退出，发信息给脚本启动一台新server。
					if ($___TEMP_runned_time-$___TEMP_last_cmd<=$___MOD_VANISH_TIME || $___TEMP_is_root)
					{
						__SOCKET_LOG__("已经运行了 ".$___TEMP_runned_time."秒，稍后将退出，已请求脚本启动新驻留进程。");
						$___TEMP_newsrv_flag = 1;
						if ($___TEMP_is_root)
							touch(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
						else  touch(GAME_ROOT.'./gamedata/tmp/server/request_new_server');
					}
				}
				continue;
			}
			//执行循环开始
			$___TEMP_last_cmd = time()-$___TEMP_server_start_time;
			while (__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_socket, 'a', 0, 0))	//处理全部现有消息队列
			{
				$___TEMP_connection = socket_accept($___TEMP_socket); 
				if($___TEMP_connection)
				{  
					$___TEMP_pagestarttime=microtime(true);
					__SOCKET_DEBUGLOG__("收到了一个新连接。");
					if (($___TEMP_uid=__SOCKET_LOAD_DATA__($___TEMP_connection))!==false)//注意这里$___TEMP_uid是带房间号前缀的
					{
						$___TEMP_WORKFLAG=1;
						//有确定指令的时候才进入忙碌状态
						__SOCKET_DEBUGLOG__("进入忙碌状态。");
						touch(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/busy');
						
						//更新访问数
						$___TEMP_WORKNUM=1;
						if(file_exists(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/worknum')) {
							$___TEMP_WORKNUM += (int)file_get_contents(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/worknum');
						}
						file_put_contents(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/worknum', $___TEMP_WORKNUM);
						
						eval(import_module('sys','map','player','logger','itemmain','input'));
						\sys\routine();

						$___TEMP_EXEC_START_TIME=microtime(true);;
						
						//用try catch机制来中断执行（gexit）并且直接输出
						try {
							include GAME_ROOT.'./command.php';
						}
						catch (Exception $e) {
							echo $e->getMessage();
						}
						
						$___TEMP_WORKFLAG=0;
						
						$___TEMP_tiused=get_script_runtime($___TEMP_EXEC_START_TIME);
						__SOCKET_DEBUGLOG__("执行完成。command.php本体耗时 ".$___TEMP_tiused." 秒。");
						
						$jgamedata = ob_get_contents();
						ob_end_flush();
						
						if ($___MOD_CONN_W_DB)
						{
							$___TEMP_db->query("UPDATE {$___TEMP_tablepre}temp SET value='".base64_encode($jgamedata)."' WHERE sid='{$___TEMP_uid}'");
						}
						else
						{
							__SOCKET_SAVE_RESPONSE__($___TEMP_uid, $jgamedata);							
						}
						//返回消息给client
						if (!__SOCKET_CHECK_WITH_TIMEOUT__($___TEMP_connection, 'w', 0, 200000))	
						{
							//允许最多0.2秒等待，这应该已经非常非常宽松了……
							__SOCKET_WARNLOG__("警告：socket_write等待时间过长。结束流程。"); 
						}
						elseif (!socket_write($___TEMP_connection,$___MOD_CONN_PASSWD.'_ok'."\n")) 
						{ 
							__SOCKET_WARNLOG__("警告：socket_write失败。结束流程。"); 
						}  
					}
					socket_close($___TEMP_connection);  
					__SOCKET_DEBUGLOG__("关闭连接。");
					
					//以下处理只有非测试指令才进行
					if($___TEMP_uid) {
						//只有游戏内指令需要储存成录像
						if(!isset($page) || 'command' == $page) {
							if (defined('MOD_REPLAY') && $___MOD_SRV && $___MOD_CODE_ADV3 && !in_array($gametype, $replay_ignore_mode)) 
							{
								if (!isset($jgamedata['url']))
								{
									$pid=(int)$pid;
									$rdir = GAME_ROOT.'./gamedata/tmp/replay/'.$room_prefix.'_/'.$pid;
									if (!file_exists($rdir))
									{
										create_dir($rdir);
									}
									elseif (!is_dir($rdir))
									{
										unlink($rdir);
										create_dir($rdir);
									}
									$rfile = $rdir.'/replay.php';
									$rcont = \replay\replay_record_op($oprecorder).','.($___PAGE_STARTTIME_VALUE-$starttime+$moveut*3600+$moveutmin*60).','.$___MOD_TMP_FILE_DIRECTORY.$___TEMP_uid.','."\n";
									if(!file_exists($rfile)) {
										$rcont = "<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>\n".$rcont;
									}
									file_put_contents($rfile,$rcont,FILE_APPEND);
								}
							}
						}else{
							//非游戏指令则删除对应的回应文件
							//蛋疼，不能删，socket是异步的
							//unlink($___MOD_TMP_FILE_DIRECTORY.$___TEMP_uid);
						}
						__SOCKET_DEBUGLOG__("清空变量。");
						//清除进程锁，避免烂代码导致daemon卡死
						//为了防止未来可能会绕过文件末尾那个判定的情况，放在这里
						if(!empty($plock)) {
							\sys\process_unlock();
						}
						\player\release_player_lock_from_pool();
						release_user_lock_from_pool();
						//收尾工作，清除所有全局变量
						$___TEMP_remain_list=Array('_SERVER','GLOBALS','magic_quotes_gpc','module_hook_list','language','_ERROR');
								
						$___TEMP_a=Array();
						$___TEMP_a=array_keys(get_defined_vars());
						foreach ($___TEMP_a as $___TEMP_key) 
						{
							if (strpos($___TEMP_key,'___LOCAL_')===0 && strpos($___TEMP_key,'___LOCAL_INPUT')!==0) continue;
							if (strpos($___TEMP_key,'___PRESET_')===0) continue;
							if (strpos($___TEMP_key,'___PRIVATE_')===0) continue;
							if (strpos($___TEMP_key,'___TEMP')===0) continue;
							if (strpos($___TEMP_key,'___MOD')===0) continue;
							if (in_array($___TEMP_key,$___TEMP_remain_list)) continue;
							unset($$___TEMP_key);
						}
						unset($___TEMP_a);
						
						//执行模拟载入代码，为下一次执行做准备
						__SOCKET_DEBUGLOG__("模拟载入。");
						$___LOCAL_INPUT__VARS__INPUT_VAR_LIST=Array();
						
						for ($i=1; $i<=$___TEMP_MOD_LIST_n; $i++) 
							if (strtoupper($___TEMP_MOD_NAME[$i])!='INPUT') 
							{
								$funcname = $___TEMP_MOD_NAME[$i].'\\___pre_init'; $funcname();
								$funcname = $___TEMP_MOD_NAME[$i].'\\init'; $funcname();
								$funcname = $___TEMP_MOD_NAME[$i].'\\___post_init'; $funcname();
								unset($funcname);
							}
						unset($i);
					}
					
		
					//system('sync && echo 3 > /proc/sys/vm/drop_caches');  
					
					$___TEMP_tiused=get_script_runtime($___TEMP_pagestarttime);
					__SOCKET_DEBUGLOG__("执行完成。核心占用时间 ".$___TEMP_tiused." 秒。");
				}
			}
			//现在待处理队列为空
			$___TEMP_runned_time = time()-$___TEMP_server_start_time;
			if ($___TEMP_runned_time+$___MOD_SRV_WAKETIME+5>$___TEMP_max_time)
			{
				//没有下一次唤醒了，主动退出
				__SOCKET_LOG__("已经运行了 ".$___TEMP_runned_time."秒。主动退出。");
				__SERVER_QUIT__();
			}
			elseif ($___TEMP_runned_time+$___MOD_SRV_WAKETIME*2+5>$___TEMP_max_time && !$___TEMP_newsrv_flag)
			{
				//老server即将在下一次唤醒时主动退出，发信息给脚本启动一台新server。
				//与进程一开始不同，这里不借助curl_new_server()
				if ($___TEMP_runned_time-$___TEMP_last_cmd<=$___MOD_VANISH_TIME || $___TEMP_is_root)
				{
					__SOCKET_LOG__("已经运行了 ".$___TEMP_runned_time."秒，稍后将退出，已请求脚本启动新驻留进程。");
					$___TEMP_newsrv_flag = 1;
					if ($___TEMP_is_root)
						touch(GAME_ROOT.'./gamedata/tmp/server/request_new_root_server');
					else  touch(GAME_ROOT.'./gamedata/tmp/server/request_new_server');
				}
			}
			//进入闲置状态
			__SOCKET_DEBUGLOG__("进入闲置状态。");
			if (file_exists(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/busy'))
				unlink(GAME_ROOT.'./gamedata/tmp/server/'.$___TEMP_CONN_PORT.'/busy');
		}
	}
	elseif (empty($___TEMP_WORKFLAG))
	{
		ignore_user_abort(1);
		
		$___TEMP_runmode = 'Client';
		$___TEMP_CONN_PORT = -1;
			
		//来自浏览器的调用，应该转发给server
		$cli_pagestartime=microtime(true); 

		define('NO_MOD_LOAD', TRUE);	//不在common.inc载入任何模块
		define('NO_SYS_UPDATE', TRUE);
		require GAME_ROOT.'./include/common.inc.php';
		require GAME_ROOT.'./include/socket.func.php';
		
		__SOCKET_DEBUGLOG__('Client开始执行。');
		
		//最大执行时间的计算
		$___TEMP_max_time = ini_get('max_execution_time');
		if ($___TEMP_max_time == 0 || $___TEMP_max_time > $___MOD_SRV_MAX_EXECUTION_TIME) $___TEMP_max_time = $___MOD_SRV_MAX_EXECUTION_TIME;
		elseif ($___TEMP_max_time < $___MOD_SRV_MIN_EXECUTION_TIME) $___TEMP_max_time = $___MOD_SRV_MIN_EXECUTION_TIME;
		__SOCKET_DEBUGLOG__('开始选择进程。'); 
		$dirlist = gdir(GAME_ROOT.'./gamedata/tmp/server', 'dir');
		if (NULL !== $dirlist) 
		{
			$srvlist = array(); $chosen=-1; $touch_error_list=array(); $loopcount = 0;
			//外层循环体：如果有效进程不存在，则curl_new_server()并sleep一小段时间，再次判断
			do{
				//无驻留进程，或者所有驻留进程都异常，此时touch是没用的，必须curl。
				if ($loopcount && (!$dirlist || !$srvlist)) {
					if(!$touch_error_list) $tmp_log = '未找到在线的驻留进程。';
					else $tmp_log = '所有在线驻留进程都异常。';
					if($___MOD_SRV_COLD_START && $loopcount < 5) {//若允许冷启动，则自动发送启动指令
						__SOCKET_LOG__($tmp_log.'已请求启动新的根进程，并休眠2秒。');
						curl_new_server($___MOD_CONN_PASSWD, 1);
						sleep(2);//暂停2秒
						__SOCKET_DEBUGLOG__('休眠结束，再次检查在线的驻留进程。');
						$dirlist = gdir(GAME_ROOT.'./gamedata/tmp/server', 'dir');
						//__SOCKET_ERRORLOG__('新的根进程已启动，中断本次执行。');
					}else{
						__SOCKET_ERRORLOG__($tmp_log);
					}
					unset($tmp_log);
				}
				$srvlist = array();
				//内层循环体：选择有效进程
				foreach($dirlist as $sid) {
					$sid=(int)$sid; 
					$srvlist[]=$sid;
					//端口号超限，记录错误并跳过
					if ($sid<$___MOD_CONN_PORT_LOW || $sid>$___MOD_CONN_PORT_HIGH) {
						$touch_error_list[]=$sid;
						//从可选进程数组中移除
						array_pop($srvlist);
						__SOCKET_DEBUGLOG__("进程{$sid}端口号无效，跳过。"); 
						continue;
					}
					//进程忙碌，跳过
					if (file_exists(GAME_ROOT.'./gamedata/tmp/server/'.((string)$sid).'/busy') && time() - filemtime(GAME_ROOT.'./gamedata/tmp/server/'.((string)$sid).'/busy') < 15) {
						__SOCKET_DEBUGLOG__("进程{$sid}忙碌，跳过。"); 
						$buzyflag = 1;
						continue;
					}
					//进程异常，记录错误并跳过
					__SOCKET_DEBUGLOG__("向进程{$sid}发送测试命令。"); 
					$touchflag = __SEND_TOUCH_CMD__($sid);
					if ('ok' != $touchflag && 'ok_root' != $touchflag) {
						$touch_error_list[]=$sid;
						//从可选进程数组中移除
						array_pop($srvlist);
						__SOCKET_DEBUGLOG__("进程{$sid}异常，跳过。"); 
						continue;
					}
					//该进程即将结束，跳过（比主动结束早15秒）
					if(time()-filemtime(GAME_ROOT.'./gamedata/tmp/server/'.((string)$sid).'/start_time')+$___MOD_SRV_WAKETIME+15>$___TEMP_max_time){
						__SOCKET_DEBUGLOG__("进程{$sid}即将结束，跳过。"); 
						continue;
					}
					//不忙碌，选择之
					$chosen = $sid; 
					break;
				}
				$loopcount++;
			}while(!$dirlist || !$srvlist);
			
			//没有选到驻留进程，此时至少有1个不异常的进程
			if ($chosen == -1) 
			{
				$z=rand(0,count($srvlist)-1); $chosen=$srvlist[$z];//随机选择一个。也就是说其实这里允许并发
				if(!empty($buzyflag)){
					touch(GAME_ROOT.'./gamedata/tmp/server/request_new_server');
					__SOCKET_LOG__("没有驻留进程空闲，已请求脚本启动新驻留进程。");
				}
			}
			__SOCKET_DEBUGLOG__("选择了端口号为 ".$chosen.'的驻留进程 。');
			$___TEMP_CONN_PORT=$chosen;
			
			$auto_server_file = GAME_ROOT.'./gamedata/tmp/server/auto_requested_new_server';
			//请求daemonmng.php关闭检测到的异常进程。选到才会关闭
			if (!empty($touch_error_list)) 
			{
				if(!file_exists($auto_server_file) || filemtime($auto_server_file) < time() - 300){
					__SOCKET_LOG__("已请求daemonmng.php关闭检测到的异常进程。");
					touch($auto_server_file);
					$daemonmng_url = url_dir().'daemonmng.php';
					foreach($touch_error_list as $tev){
						$daemonmng_context = array('action' => 'stop'.$tev, 'in_game_pass' => substr(base64_encode($___MOD_CONN_PASSWD),0,6));
					}
					curl_post($daemonmng_url, $daemonmng_context, NULL, 0.1);
					unset($daemonmng_url, $daemonmng_context);
				}else{
					__SOCKET_LOG__("驻留进程连接错误，且5分钟之内尝试自动重启驻留进程失败。");
				}		
			}else{
				if(file_exists($auto_server_file)) unlink($auto_server_file);
			}
			unset($touch_error_list, $auto_server_file, $touchflag, $daemonmng_url, $get_var);
		}
		else  __SOCKET_ERRORLOG__('无法打开gamedata/tmp/server目录。');
		
		__SOCKET_SEND_TO_SERVER__();
		
		__SOCKET_DEBUGLOG__('Client执行完成。');
	
		die();
	}
	else
	{
		//否则是由server自行引用的command.php，开始执行
		$pagestartime=microtime(true); 
	}
}
else	//未开启server-client模式，正常执行准备流程
{
	$pagestartime=microtime(true); 

	require GAME_ROOT.'./include/common.inc.php';
	
	$timecost2 = get_script_runtime($pagestartime);
	
}

////////////////////////////////////////////////////////////////////////////
//////////////////////////调用daemon进行的全局操作//////////////////////////////
////////////////////////////////////////////////////////////////////////////

if(isset($command)){
	if('area_timing_refresh' == $command){//刷新禁区时间
		//\sys\routine();
		\map\init_areatiming();
		$gamedata = array('timing' => $uip['timing']);
		ob_clean();
		$jgamedata = gencode($gamedata);
		echo $jgamedata;
		ob_end_flush();
		return;
	}elseif('room_routine' == $command){//刷新房间内游戏状态
		include_once './include/roommng/roommng.func.php';
		room_all_routine($nowroom);
		return;
	}elseif('maintain' == $command || 3 == date('H', $now)){//凌晨3点有访问时自动维护，也可以手动启动维护
		include_once GAME_ROOT.'./include/auto_maintain/auto_maintain_misc.func.php';
		am_main(1+2+4+8+16+32);
		if('maintain' == $command) return;
	}
}

////////////////////////////////////////////////////////////////////////////
//////////////////////////执行页面//////////////////////////////
////////////////////////////////////////////////////////////////////////////

if(!isset($page) || 'command' == $page) {
	$___CURSCRIPT = 'ACT';
	include GAME_ROOT.'./include/pages/command_act.php';
}elseif(in_array($page, array('command_game','command_roomcmd','command_valid','command_end','command_winner','command_rank','command_alive','command_help','command_news','command_aranking'))) {
	$___tmp_disable_codeadv3 = 1;//暂时还做不到游戏外页面解压文字
	$___CURSCRIPT = strtoupper(substr($page,strpos($page,'_')+1));
	if('command_help' == $page) {
		$___IN_HELP = 1;//代替常量IN_HELP
	}
	include GAME_ROOT.'./include/pages/'.$page.'.php';
}

//清除进程锁，避免烂代码导致daemon卡死
if(!empty($plock)) {
	\sys\process_unlock();
}
//清除玩家锁
\player\release_player_lock_from_pool();
//清除用户锁
release_user_lock_from_pool();

/* End of file command.php */
/* Location: /command.php */ 