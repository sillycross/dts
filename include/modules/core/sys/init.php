<?php

namespace sys
{	
	//$mode决定给玩家显示哪个界面
	//$command是玩家提交的命令也是act()判断的依据
	//$db是数据库类
	//$plock进程锁的文件
	//$url如果存在，ajax将会直接跳转
	//$uip其他要传给界面的变量请写在这里
	global $mode, $command, $db, $plock, $url, $uip;
	//玩家数据池，fetch的时候先判断池里存不存在，如果有则优先调用池里的；
	//万一以后pdata_pool要变成引用呢？所以多一个origin池
	//此外玩家池兼任玩家数据锁记录器
	//daemon进程结束以及commmand_act.php结束时都会检查并释放玩家池对应的锁文件
	global $pdata_pool, $pdata_origin_pool, $pdata_lock_pool;
	$pdata_origin_pool = $pdata_pool = $pdata_lock_pool = array();
	
	function init()
	{
		global $gtablepre, $tablepre, $wtablepre, $ctablepre, $room_prefix, $room_id, $moveut, $moveutmin, $u_templateid, $new_messages;
		global ${$gtablepre.'user'}, ${$gtablepre.'pass'}, $___MOD_SRV;
		if (isset($_COOKIE))
		{
			$_COOKIE=gstrfilter($_COOKIE);
			foreach ($_COOKIE as $key => $value)
			{
				if (in_array($key, array($gtablepre.'user',$gtablepre.'pass')))
				{
					$$key=$value;
				}
				elseif(in_array($key, array('templateid')))
				{
					${'u_'.$key} = $value;
				}
			}
		}
		
		ob_clean();
		ob_start();
		
		//数据库初始化，且只初始化1次
		global $db; 
		if (!isset($db)) $db = init_dbstuff();
		
		//游戏时间变量初始化
		date_default_timezone_set('Etc/GMT');
		global $now; $now = time() + $moveut*3600 + $moveutmin*60;   
		global $sec,$min,$hour,$day,$month,$year,$wday;
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
		
		
		//从玩家提交的信息（一般是$_POST）里获取用户名和密码
		global $___LOCAL_INPUT__VARS__INPUT_VAR_LIST;
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'user']))
			${$gtablepre.'user'}=$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'user'];
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'pass']))
			${$gtablepre.'pass'}=$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'pass'];
		
		//获取玩家提交的模板编号
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST['templateid']))
			$u_templateid = $___LOCAL_INPUT__VARS__INPUT_VAR_LIST['templateid'];
		
		//统一获取一些用户数据备用
		if (isset(${$gtablepre.'user'})){
			$result = $db->query("SELECT u_templateid,roomid FROM {$gtablepre}users where username='".${$gtablepre.'user'}."'");
			if ($db->num_rows($result)) {
				$rarr = $db->fetch_array($result);
			}
		}
		
		if(empty($u_templateid) && !empty($rarr['u_templateid'])) $u_templateid = $rarr['u_templateid'];
		//进入当前用户房间判断
		$room_prefix = '';
		$room_id = 0;
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST['___GAME_ROOMID']) && '' !== $___LOCAL_INPUT__VARS__INPUT_VAR_LIST['___GAME_ROOMID'])
		{
			$room_id = ((int)$___LOCAL_INPUT__VARS__INPUT_VAR_LIST['___GAME_ROOMID']);
			///test code
//			if(isset(${$gtablepre.'user'}) && $room_id != $rarr['roomid']){
//				writeover('tmp_roomid_log_1.txt', ${$gtablepre.'user'}."'s roomid ".$room_id.' -> '.$rarr['roomid'].' at '.$now."\r\n",'ab+');
//				$rarr['room_id'] = $room_id;
//				$db->query("UPDATE {$gtablepre}users SET roomid = '{$room_id}' WHERE username = '".${$gtablepre.'user'}."'");
//			}
		}
		else  
		{
			if (isset(${$gtablepre.'user'}))
			{
				$room_id = $rarr['roomid'];
			}
		}
		
		$room_prefix = room_id2prefix($room_id);

		//$room_status = 0;
		//$room_id = room_prefix2id($room_prefix);
		
		//判断所在房间是否存在/是否已经关闭，如果不存在或关闭则将玩家所在房间调整为0（主游戏）
		global $gameinfo; 
		$gameinfo = NULL;
		//$room_status = 0;
		$result = $db->query("SELECT * FROM {$gtablepre}game where groomid='".$room_id."'");
		if ($db->num_rows($result))
		{
			$gameinfo = $db->fetch_array($result);
			//如果房间关闭则退出到主房间
			if ($gameinfo['groomstatus']==0) {
				$room_id = 0;
				$room_prefix = room_id2prefix(0);
				$gameinfo = NULL;
			}
			//如果房间是开启状态，但游戏在结束状态，则把房间状态设为打开
			elseif ($gameinfo['groomstatus'] > 0 && $gameinfo['gamestate']==0 && room_check_subroom($room_prefix))
			{
				$db->query("UPDATE {$gtablepre}game SET groomstatus=10 WHERE groomid='$room_id'");
				$gameinfo['groomstatus'] = 10;
//				$room_prefix = '';
//				$room_id = 0;
//				$gameinfo = NULL;
			}
		}
		else
		{
			$room_prefix = '';
			$room_id = 0;
			$gameinfo = NULL;
		}
		//如果之前没读到房间的gameinfo，则读主游戏的gameinfo
		if(!$room_id || empty($gameinfo)){
			$result = $db->query("SELECT * FROM {$gtablepre}game where groomid='0'");
			$gameinfo = $db->fetch_array($result);
		}
		//$gameinfo初始化，初次global这些变量
		//注意这里并没有对$arealist等变量进行处理，真正的处理是在common.inc.php调用routine()调用load_gameinfo()时
		foreach ($gameinfo as $key => $value)
		{
			global ${$key};
			${$key}=$value;
		}
		//$arealist = explode(',',$arealist);
		
		//为$tablepre赋值，之后除game表之外的数据库操作都被引入对应前缀的数据表
		$tablepre = room_get_tablepre();
		$ctablepre = $tablepre;
		
		if ($room_prefix=='') $wtablepre = $gtablepre;
		else $wtablepre = $gtablepre.room_prefix_kind($room_prefix);
		
		//room_auto_init();//新建房间时，自动初始化房间表
		//实际上不应该放在这里，应该只在新建房间时调用
		
		//当前用户名和密码变量初始化
		global $cuser, $cpass;
		$cuser = ${$gtablepre.'user'};
		$cpass = ${$gtablepre.'pass'};
		
		//这里实在没办法，一堆文件都直接引用mode和command这两个来自input的变量，但又不能让所有文件都依赖input…… 只能恶心一下了……
		global $mode, $command, $___MOD_SRV;
		if ($___MOD_SRV)
		{
			global $___LOCAL_INPUT__VARS__mode, $___LOCAL_INPUT__VARS__command;
			global $___LOCAL_INPUT__VARS__INPUT_VAR_LIST;
			if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST['mode']))
				$mode = $___LOCAL_INPUT__VARS__INPUT_VAR_LIST['mode'];
			else  $mode=$___LOCAL_INPUT__VARS__mode;
			if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST['command']))
				$command = $___LOCAL_INPUT__VARS__INPUT_VAR_LIST['command'];
			else  $command=$___LOCAL_INPUT__VARS__command;
		}
		else
		{
			global $___LOCAL_INPUT__VARS__mode, $___LOCAL_INPUT__VARS__command;
			$mode=$___LOCAL_INPUT__VARS__mode;
			$command=$___LOCAL_INPUT__VARS__command;
		}
		
		//只要登陆就检查一下有没有新站内信，由于就几行，不需要直接include整个messages.func.php
		$new_messages = 0;
		if($cuser){
			$result = $db->query("SELECT mid FROM {$gtablepre}messages WHERE receiver='$cuser' AND rd=0");
			$new_messages = $db->num_rows($result);
		}
		
	}
	
}

?>