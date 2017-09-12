<?php

namespace sys
{
	//文件进程锁，对game表如果有操作，建议在操作前加锁
	//如果本进程已经加过锁则不会进行任何操作
	function process_lock($locktype = LOCK_EX) {//可使用LOCK_SH LOCK_EX LOCK_UN
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$res = NULL;
		$dir = GAME_ROOT.'./gamedata/tmp/processlock/';
		$file = 'process_'.$groomid.'.lock';
		if(!file_exists($dir)) mymkdir($dir);
		if(!file_exists($dir.$file)) touch($dir.$file);
		//startmicrotime();
		if(empty($plock)) {
			$plock=fopen($dir.$file,'w+');
			$res = flock($plock,$locktype);
		}
		return $res;
	}
	
	//文件进程解锁，对game表操作完请解锁，否则会在脚本结束才解锁，如果是daemon模式目测会卡死
	//如果本进程已经加过锁则不会进行任何操作
	function process_unlock() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//logmicrotime('锁时间'.debug_backtrace()[1]['function']);
		if(!empty($plock)) {
			fclose($plock);
		}
		$plock = NULL;
	}
	
	function load_gameinfo() {	//sys模块初始化的时候并没有调用这个函数
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$result = $db->query("SELECT * FROM {$gtablepre}game WHERE groomid='$room_id'");
		$gameinfo = $db->fetch_array($result);
		foreach ($gameinfo as $key => $value) ${$key}=$value;
		load_gameinfo_post_work();
		return;
	} 
	
	function load_gameinfo_post_work(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//对$arealist进行格式化
		$arealist = explode(',',$arealist);
		//对部分字段进行解码，转换成数组
		foreach(array('noisevars','roomvars','gamevars') as $val){
			${$val}=gdecode(${$val},1);
		}
		return;
	}
	
	//把游戏全局变量存回数据库
	//$ignore_room=1 忽略与房间有关的变量，默认开启，防止高并发操作带来的脏数据导致房间状态意外改变
	function save_gameinfo($ignore_room = 1) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!isset($gamenum)||!isset($gamestate)){return;}
		gameinfo_audit();
		$ngameinfo = save_gameinfo_prepare_work($gameinfo, $ignore_room);
		$db->array_update("{$gtablepre}game",$ngameinfo,"groomid='$room_id'");
		return;
	}
	
	//修正$gameinfo的非正常数据（比如激活数为负等）
	function gameinfo_audit(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//用全局变量的同名函数替换$gameinfo的同名键值，其实就是一个同步的工作
		foreach ($gameinfo as $key => $value)
			$gameinfo[$key]=$$key;
		if($alivenum < 0){$alivenum = 0;}
		if($deathnum < 0){$deathnum = 0;}
		if(!$afktime){$afktime = $now;}
		if(!$hdamage){$hdamage = 0;}
		if(!$hplayer){$hplayer = '';}
		//if(empty($optime)){$optime = $now;}
		return;
	}
	
	//对数据进行准备（按数据库格式进行格式化）
	//输入$gameinfo的副本数组，返回修正过的数组	
	function save_gameinfo_prepare_work($ginfo, $ignore_room = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		foreach(array('noisevars','roomvars','gamevars') as $val){
			$ginfo[$val]=gencode($ginfo[$val]);
		}
		$ginfo['arealist'] = implode(',',$ginfo['arealist']);
		if($ignore_room)
			unset($ginfo['groomid'],$ginfo['groomtype'],$ginfo['groomstatus'],$ginfo['roomvars']);
		return $ginfo;
	}
	
	function save_combatinfo(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		gameinfo_audit();
		$ngameinfo = save_gameinfo_prepare_work($gameinfo);
		$nginfo = array();
		foreach(array('hdamage','hplayer','noisevars') as $nval){
			$nginfo[$nval] = $ngameinfo[$nval];
		}
		$db->array_update("{$gtablepre}game",$nginfo,"groomid='$room_id'");
//		if(!$hdamage){$hdamage = 0;}
//		if(!$hplayer){$hplayer = '';}
//		if(!$noisetime){$noisetime = 0;}
//		if(!$noisepls){$noisepls = 0;}
//		if(!$noiseid){$noiseid = 0;}
//		if(!$noiseid2){$noiseid2 = 0;}
//		if(!$noisemode){$noisemode = '';}
//		$db->query("UPDATE {$gtablepre}game SET hdamage='$hdamage', hplayer='$hplayer', noisetime='$noisetime', noisepls='$noisepls', noiseid='$noiseid', noiseid2='$noiseid2', noisemode='$noisemode' WHERE groomid='$room_id'");
		return;
	}
	
	function addnews($t = 0, $n = '',$a='',$b='',$c = '', $d = '', $e = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$t = $t ? $t : $now;
		$newsfile = GAME_ROOT.'./gamedata/tmp/news/newsinfo_'.$room_prefix.'.php';
		touch($newsfile);
		if(is_array($a)){
			$a=implode('_',$a);
		}
	
		//这尼玛写的太坑了吧…… 不管了直接import map模块进来了……
		eval(import_module('map'));
		if(strpos($n,'death11') === 0  || strpos($n,'death32') === 0) {
			$result = $db->query("SELECT lastword FROM {$gtablepre}users WHERE username = '$a'");
			$e = $lastword = $db->result($result, 0);
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$t','【{$plsinfo[$c]}】 $a','','$lastword')");
		}	elseif(strpos($n,'death15') === 0 || strpos($n,'death16') === 0) {
			$result = $db->query("SELECT lastword FROM {$gtablepre}users WHERE username = '$a'");
			$e = $lastword = $db->result($result, 0);
			$result = $db->query("SELECT pls FROM {$tablepre}players WHERE name = '$a' AND type = '0'");
			$place = $db->result($result, 0);
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$t','【{$plsinfo[$place]}】 $a','','$lastword')");
		}
		$db->query("INSERT INTO {$tablepre}newsinfo (`time`,`news`,`a`,`b`,`c`,`d`,`e`) VALUES ('$t','$n','$a','$b','$c','$d','$e')");
	}
	
	function addchat($ctype, $msg,  $csender = '', $creceiver = '', $ctime = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!$ctime) $ctime = $now;
		$aarr = array(
			'type' => $ctype,
			'time' => $ctime,
			'send' => $csender,
			'recv' => $creceiver,
			'msg' => $msg
		);
		$result = $db->array_insert("{$tablepre}chat", $aarr);
		return $result;
		//$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('$ctype', '$ctime', '$csender', '$creceiver', '$msg')");
	}

	function parse_chat($chat)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$msg = '【'.$chatinfo[$chat['type']].'】'.$chat['send'].'：'.$chat['msg'].'('.date("H:i:s",$chat['time']).')';
		$premsg = '<span id="cid'.$chat['cid'].'"';
		$postmsg = '<br></span>';
		
		//0=公聊； 1=队聊； 2=私聊； 3=遗言； 4=系统； 5=公告； 6=剧情
		if(0 == $chat['type']) {
			$premsg .= ' class="chat0">';
		} elseif(1 == $chat['type']) {
			$premsg .= ' class="clan chat1">';
		} elseif(3 == $chat['type']) {
			$premsg .= ' class="red chat3">';
			if ($chat['msg']){
			} else {
				$msg = '【'.$chatinfo[$chat['type']].'】'.$chat['send'].'什么都没说就死去了 ('.date("H:i:s",$chat['time']).')';
			}
		} elseif(4 == $chat['type']) {
			$premsg .= ' class="yellow chat4">';
		} elseif(5 == $chat['type']) {
			$premsg .= ' class="yellow chat5">';
			$msg = '【'.$chatinfo[$chat['type']].'】'.$chat['msg'].'('.date("H:i:s",$chat['time']).')';
		} elseif(6 == $chat['type']) {
			$premsg .= ' class="lime chat6">';
			$msg = '【'.$chatinfo[$chat['type']].'】'.$chat['msg'].'('.date("H:i:s",$chat['time']).')';
		}
		return $premsg.$msg.$postmsg;
	}
	
	function getchat($last,$team='',$chatpid=0,$limit=0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$limit = $limit ? $limit : $chatlimit;
		$result = $db->query("SELECT * FROM {$tablepre}chat WHERE cid>'$last' AND (recv='' OR (type='1' AND recv='$team') OR (type!='1' AND recv='$chatpid')) ORDER BY cid desc LIMIT $limit");
		$chatdata = Array('lastcid' => $last, 'msg' => array());
		if(!$db->num_rows($result)){$chatdata = array('lastcid' => $last, 'msg' => array());return $chatdata;}
		
		while($chat = $db->fetch_array($result)) {
			//if(!$chatdata['lastcid']){$chatdata['lastcid'] = $chat['cid'];}
			if($chatdata['lastcid'] < $chat['cid']){$chatdata['lastcid'] = $chat['cid'];}
			$chat['msg'] = htmlspecialchars($chat['msg']);
			$msg = parse_chat($chat);
			$chatdata['msg']['cid'.$chat['cid']] = $msg;
		}
		return $chatdata;
	}

	function systemputchat($time,$type,$msg = ''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!$time){$time = $now;}
		if($type == 'areaadd' || $type == 'areawarn'){
			$alist = $msg;
			$msg = '';
			global $plsinfo;
			foreach($alist as $ar) {
				$msg .= "$plsinfo[$ar] ";
			}
			if($type == 'areaadd'){
				$msg = '增加禁区：'.$msg;
			}elseif($type == 'areawarn'){
				$msg = '警告，以下区域即将成为禁区：'.$msg;
			}
		}elseif($type == 'combo'){
			$msg = '游戏进入连斗阶段！';
		}elseif($type == 'comboupdate'){
			$msg = '连斗死亡判断数修正为'.$msg.'人！';
		}elseif($type == 'duel'){
			$msg = '游戏进入死斗模式！';
		}elseif($type == 'newgame'){
			$msg = '游戏开始！';
		}elseif($type == 'gameover'){
			$msg = '游戏结束！';
		}
		$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,msg) VALUES ('5','$time','','$msg')");
		return;
	}
	
	function user_set_gamevars_list_init($registered_gamevars = array()){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		return $registered_gamevars;
	}
	
	//由玩家设定下一局游戏的值
	function user_set_gamevars($input_gamevars){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		$ret = array(
			'notice' => array()
		);
		$valid_gamevars = array();
		$registered_gamevars = user_set_gamevars_list_init();
		foreach ($registered_gamevars as $rgkey){
			if(isset($input_gamevars[$rgkey])){
				if (isset($gamevars[$rgkey])){
					$ret['notice'][$rgkey] = '你已经设定过下一局的'.$gamevarsinfo[$rgkey].'了。';
				}else{
					$ret['notice'][$rgkey] = user_set_gamevars_process($rgkey, $input_gamevars[$rgkey], $valid_gamevars);
//					$ret['notice'][$rgkey] = '已设定下一局游戏的'.$gamevarsinfo[$rgkey].'！';
//					$valid_gamevars[$rgkey] = $input_gamevars[$rgkey];
				}
			}
		}
		if(!empty($valid_gamevars)) $gamevars = $valid_gamevars;
		save_gameinfo();
		return $ret;
	}
	
	function user_set_gamevars_process($gamevar_key,$gamevar_val,&$valid_gamevars){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$valid_gamevars[$gamevar_key] = $gamevar_val;
		$retlog = '已设定下一局游戏的'.$gamevarsinfo[$gamevar_key].'！';
		return $retlog;
	}
	
	function user_display_gamevars_setting($show = array()){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $show;
	}
}

?>
