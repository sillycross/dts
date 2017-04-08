<?php

namespace sys
{	
	global $mode, $command, $db, $url, $uip;
	//玩家数据池，fetch的时候先判断池里存不存在，如果有则优先调用池里的；
	//万一以后pdata_pool要变成引用呢？所以多一个origin池
	global $pdata_pool, $pdata_origin_pool; $pdata_origin_pool = $pdata_pool = array();
	
	function init()
	{
		global $gtablepre, $tablepre, $wtablepre, $room_prefix, $room_id, $moveut, $moveutmin;
		global ${$gtablepre.'user'}, ${$gtablepre.'pass'}, $___MOD_SRV;
		if (isset($_COOKIE))
		{
			$_COOKIE=gstrfilter($_COOKIE);
			foreach ($_COOKIE as $key => $value)
			{
				if ($key==$gtablepre.'user' || $key==$gtablepre.'pass')
				{
					$$key=$value;
				}
			}
		}
		
		ob_clean();
		ob_start();
		
		global $db; 
		if (!isset($db))
		{
//			global $database, $dbhost, $dbuser, $dbpw, $dbname, $pconnect;
			$db = init_dbstuff();
//			require GAME_ROOT.'./include/db/db_'.$database.'.class.php';
//			$db = new \dbstuff;
//			$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
//			unset($GLOBALS['dbhost'], $GLOBALS['dbuser'], $GLOBALS['dbpw'], $GLOBALS['dbname'], $GLOBALS['pconnect']);
		}
		
		global $___LOCAL_INPUT__VARS__INPUT_VAR_LIST;
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'user']))
			${$gtablepre.'user'}=$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'user'];
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'pass']))
			${$gtablepre.'pass'}=$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'pass'];
		//进入当前用户房间判断
		$room_prefix = '';
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST['___GAME_ROOMID']))
		{
			$room_prefix = ((string)$___LOCAL_INPUT__VARS__INPUT_VAR_LIST['___GAME_ROOMID']);
		}
		else  
		{
			if (isset(${$gtablepre.'user'}))
			{
				$result = $db->query("SELECT roomid FROM {$gtablepre}users where username='".${$gtablepre.'user'}."'");
				if ($db->num_rows($result))
				{
					$rarr = $db->fetch_array($result);
					$room_prefix = $rarr['roomid'];
				}
			}
		}

		$room_status = 0;
		$room_id = 0;
		if(strpos($room_prefix,'s')===0) $room_id = substr($room_prefix,1);
		
		//因为房间有可能已经关闭，必须在load_gameinfo之前单独判断一次
		$room_status = 0;
		$result = $db->query("SELECT groomstatus FROM {$gtablepre}game where groomid='".$room_id."'");
		if ($db->num_rows($result))
		{
			$rarr = $db->fetch_array($result);
			$room_status = $rarr['groomstatus'];
			if ($room_status==0) {
				$room_prefix = '';
				$room_id = 0;
			}
		}
		//当前用户房间判断结束，为$tablepre赋值，之后除game表之外的数据库操作都被引入对应前缀的数据表
		if($room_prefix) $tablepre = $gtablepre.$room_prefix.'_';
		else $tablepre = $gtablepre;
		
		if ($room_prefix=='') $wtablepre = $gtablepre;
		else $wtablepre = $gtablepre.($room_prefix[0]);
		
		room_auto_init();//自动初始化表
		
		//游戏时间变量初始化
		date_default_timezone_set('Etc/GMT');
		global $now; $now = time() + $moveut*3600 + $moveutmin*60;   
		global $sec,$min,$hour,$day,$month,$year,$wday;
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));
		
		//$gameinfo初始化，初次global这些变量，其余与load_gameinfo功能相同
		$result = $db->query("SELECT * FROM {$gtablepre}game WHERE groomid='$room_id'");
		global $gameinfo; $gameinfo = $db->fetch_array($result);
		foreach ($gameinfo as $key => $value)
		{
			global ${$key};
			${$key}=$value;
		}
		$arealist = explode(',',$arealist);
		
		//房间内，如果游戏在结束状态，把房间状态设为开放
		if ($room_status==2 && $gamestate==0 && $room_prefix!='' && $room_prefix[0]=='s')
		{
			$db->query("UPDATE {$gtablepre}game SET groomstatus=1 WHERE groomid='$room_id'");
		}
		
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
	}
}

?>
