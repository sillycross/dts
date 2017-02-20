<?php

namespace sys
{	
	global $mode, $command, $db, $url, $uip;
	global $pdata_pool; $pdata_pool = array();//玩家数据池，fetch的时候先判断池里存不存在，如果有则优先调用池里的
	
	function init()
	{
		global $gtablepre, $tablepre, $wtablepre, $room_prefix, $moveut, $moveutmin;
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
			global $dbhost, $dbuser, $dbpw, $dbname, $pconnect, $database;
			require GAME_ROOT.'./include/db_'.$database.'.class.php';
			$db = new \dbstuff;
			$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
			//$db->select_db($dbname);
			unset($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
		}
		
		global $___LOCAL_INPUT__VARS__INPUT_VAR_LIST;
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'user']))
			${$gtablepre.'user'}=$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'user'];
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'pass']))
			${$gtablepre.'pass'}=$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$gtablepre.'pass'];
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
					$zz = $db->fetch_array($result);
					$room_prefix = $zz['roomid'];
				}
				else  $room_prefix = '';
			}
			else  $room_prefix = '';
		}
		
		$room_status = 0;
		
		if ($room_prefix!='' && $room_prefix!='n' && $room_prefix[0]!='s')
		{
			$room_prefix = '';
		}
		else
		{
			if ($room_prefix!='' && $room_prefix[0]=='s')
			{
				$result = $db->query("SELECT status FROM {$gtablepre}rooms where roomid='".substr($room_prefix,1)."'");
				if ($db->num_rows($result))
				{
					$zz = $db->fetch_array($result);
					$room_status = $zz['status'];
					if ($zz['status']==0) $room_prefix = '';
				}
				else  $room_prefix = '';
			}
		}
		$tablepre = $gtablepre.$room_prefix;
		
		if ($room_prefix=='') $wtablepre = $gtablepre; else $wtablepre = $gtablepre.($room_prefix[0]);
		
		room_auto_init();//自动初始化表
		
		//$errorinfo ? error_reporting(E_ALL) : error_reporting(0);
		date_default_timezone_set('Etc/GMT');
		//$now = time() + $moveutmin*60;
		global $now; $now = time() + $moveut*3600 + $moveutmin*60;   
		global $sec,$min,$hour,$day,$month,$year,$wday;
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));


		//if($attackevasive) {
		//	include_once GAME_ROOT.'./include/security.inc.php';
		//}
		
		//COMBAT INFO INIT
		//已经一起做进数据库里了
		//global $hdamage,$hplayer,$noisetime,$noisepls,$noiseid,$noiseid2,$noisemode;
		//include GAME_ROOT.'./gamedata/combatinfo.php';
		
		//GAME INFO INIT
		global $now,$db,$tablepre;
		$result = $db->query("SELECT * FROM {$tablepre}game");
		global $gameinfo; $gameinfo = $db->fetch_array($result);
		foreach ($gameinfo as $key => $value)
		{
			global $$key;
			$$key=$value;
		}
		
		if ($room_status==2 && $gamestate==0 && $room_prefix!='' && $room_prefix[0]=='s')
		{
			$db->query("UPDATE {$gtablepre}rooms SET status=1 WHERE roomid='".substr($room_prefix,1)."'");
		}
		
		$arealist = explode(',',$arealist);
		
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
