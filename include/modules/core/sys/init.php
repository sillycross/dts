<?php

namespace sys
{	
	global $mode, $command, $db, $url;
	
	function init()
	{
		global $tablepre, $moveut, $moveutmin;
		global ${$tablepre.'user'}, ${$tablepre.'pass'}, $___MOD_SRV;
		if (isset($_COOKIE))
		{
			$_COOKIE=gstrfilter($_COOKIE);
			foreach ($_COOKIE as $key => $value)
			{
				if ($key==$tablepre.'user' || $key==$tablepre.'pass')
				{
					$$key=$value;
				}
			}
		}
		
		global $___LOCAL_INPUT__VARS__INPUT_VAR_LIST;
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$tablepre.'user']))
			${$tablepre.'user'}=$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$tablepre.'user'];
		if (isset($___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$tablepre.'pass']))
			${$tablepre.'pass'}=$___LOCAL_INPUT__VARS__INPUT_VAR_LIST[$tablepre.'pass'];
		
		//$errorinfo ? error_reporting(E_ALL) : error_reporting(0);
		date_default_timezone_set('Etc/GMT');
		//$now = time() + $moveutmin*60;
		global $now; $now = time() + $moveut*3600 + $moveutmin*60;   
		global $sec,$min,$hour,$day,$month,$year,$wday;
		list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$now));


		//if($attackevasive) {
		//	include_once GAME_ROOT.'./include/security.inc.php';
		//}
		
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
		
		//COMBAT INFO INIT
		global $hdamage,$hplayer,$noisetime,$noisepls,$noiseid,$noiseid2,$noisemode;
		include GAME_ROOT.'./gamedata/combatinfo.php';
		
		//GAME INFO INIT
		global $now,$db,$tablepre;
		$result = $db->query("SELECT * FROM {$tablepre}game");
		global $gameinfo; $gameinfo = $db->fetch_array($result);
		foreach ($gameinfo as $key => $value)
		{
			global $$key;
			$$key=$value;
		}
		$arealist = explode(',',$arealist);
		
		global $cuser, $cpass;
		$cuser = ${$tablepre.'user'};
		$cpass = ${$tablepre.'pass'};
		
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
