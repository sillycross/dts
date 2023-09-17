<?php

if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function define_am_logfile()
{
	return GAME_ROOT.'./gamedata/tmp/log/auto_maintain.txt';
}

//记录维护日志
function am_log($mlog)
{
	$am_logfile = define_am_logfile();
	writeover($am_logfile, $mlog."\r\n", 'ab+');
}

//判定上一次维护是不是足够久（超过24小时），是的话返回true也就是允许维护
function am_is_last_maintain_old_enough(){
	$am_logfile = define_am_logfile();
	if(file_exists($am_logfile) && time() - filemtime($am_logfile) < 86400) return false;
	return true;
}

//维护主程序
function am_main($mcode, $forced = 0)
{
	global $db, $gtablepre, $now, $replay_remote_storage;
	//24小时内执行过维护，则返回
	if(!$forced && !am_is_last_maintain_old_enough()) return;
	
	am_log(date("Y-m-d H:i:s")."\r\nAuto-maintaining code started.");

	if(empty($mcode)) $mcode = 0;
	if($mcode) {
		//清空1个月以上的被删除邮件
		$dtime = $now - 86400*30;
		$db->query("DELETE FROM {$gtablepre}del_messages WHERE dtimestamp <= $dtime");
		$am_num = $db->affected_rows();
		am_log("Dustbin messages created over 30 days ago deleted. Affected $am_num records.");
	}
	if($mcode & 2) {
		//清空replays 文件夹下的所有非dat文件
		$dirpath = GAME_ROOT.'./gamedata/replays';
		$am_num = 0;
		if ($handle=opendir($dirpath)) 
		{
			while (($entry=readdir($handle))!==false)
			{   
				if($entry != '.' && $entry != '..'){
					$exname = pathinfo($entry, PATHINFO_EXTENSION);
					if($exname != 'dat' && $exname != 'gitignore') {
						unlink($dirpath.'/'.$entry);
						$am_num ++;
					}
				}
			}
		}
		am_log("All non .dat files in /gamedata/replays/ deleted. Affected $am_num files.");
	}
	if($mcode & 4) {
		if(!empty($replay_remote_storage)) {
			//把录像传到远端。消耗时间可能较长
			ignore_user_abort(1);
			set_time_limit(0);
			include_once GAME_ROOT.'./include/auto_maintain/auto_maintain_replay.func.php';
			amr_main();
			am_log("All replay files sent to remote server. You can check /gamedata/tmp/log/auto_maintain_replay.txt for detailed infomations.");
		}
	}
	if($mcode & 8) {
		if(!empty($replay_remote_storage)) {
			//删除远端已有备份且超过1个月的本地录像。
			include_once GAME_ROOT.'./include/auto_maintain/auto_maintain_replay.func.php';
			list($amr_progress_no, $amr_progress_sno, $amr_failure_no, $amr_failure_sno) = amr_maintain_progress_get();
			$source = GAME_ROOT.'./gamedata/replays';
			$am_num = 0;
			if ($handle=opendir($source)) 
			{
				while (($entry=readdir($handle))!==false)
				{  
					if( $entry!="." && $entry!=".." && !is_dir($source."/".$entry) && substr($entry, strlen($entry)-4)=='.dat')
					{
						if(amr_check_replay_sent($entry) && time() - filemtime($source."/".$entry) > 86400 * 30){
							unlink($source."/".$entry);
							$am_num ++;
						}
					}
				}
			}
			am_log("All replay files sent to remote server and created over 30 days ago deleted. Affected $am_num files.");
		}
	}
	if($mcode & 16) {
		global $checkstr;
		//保留3天的用户数据
		$dir = dir_init(GAME_ROOT.'./gamedata/cache/user_backup');
		//加锁，不然如果穿透就爆数据了
		$lstate = check_lock($dir.'/', 'userdb_auto_backup', 60);
		if(!$lstate) create_lock($dir.'/', 'userdb_auto_backup');
		if(file_exists($dir.'/'.'userdb_0.php')) unlink($dir.'/'.'userdb_0.php');
		if(file_exists($dir.'/'.'userdb_1.php')) rename($dir.'/'.'userdb_1.php', $dir.'/'.'userdb_0.php');
		if(file_exists($dir.'/'.'userdb_2.php')) rename($dir.'/'.'userdb_2.php', $dir.'/'.'userdb_1.php');
		$file = 'userdb_2.php';
		writeover($dir.'/'.$file, $checkstr);
		//这里只读取本地数据库
		$result = $db->query("SELECT * FROM {$gtablepre}users");
		$am_num = 0;
		while($r = $db->fetch_array($result)){
			writeover($dir.'/'.$file, json_encode($r, JSON_UNESCAPED_UNICODE)."\r\n", 'ab+');
			$am_num ++;
		}
		release_lock($dir.'/', 'userdb_auto_backup');
		am_log("Userdb backuped. Affected $am_num records.");
	}
	if($mcode & 32) {
		//清空cache文件夹下超过1天的htm
		$source = GAME_ROOT.'./gamedata/cache';
		$am_num = 0;
		if ($handle=opendir($source)) 
		{
			while (($entry=readdir($handle))!==false)
			{  
				if( $entry!="." && $entry!=".." && !is_dir($source."/".$entry) && substr($entry, strlen($entry)-4)=='.htm')
				{
					if(time() - filemtime($source."/".$entry) > 86400){
						unlink($source."/".$entry);
						$am_num ++;
					}
				}
			}
		}
		am_log("Miscellaneous temp files deleted. Affected $am_num records.");
	}

	am_log("Auto-maintaining finished.\r\n-------------------------------------------------");
}
/* End of file auto_maintain_misc.func.php */
/* Location: /include/auto_maintain/auto_maintain_misc.func.php */