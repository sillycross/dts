<?php

namespace sys
{
	function load_gameinfo() {	
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$result = $db->query("SELECT * FROM {$tablepre}game");
		$gameinfo = $db->fetch_array($result);
		foreach ($gameinfo as $key => $value) $$key=$value;
		$arealist = explode(',',$arealist);
		return;
	} 
	
	function save_gameinfo() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!isset($gamenum)||!isset($gamestate)){return;}
		if($alivenum < 0){$alivenum = 0;}
		if($deathnum < 0){$deathnum = 0;}
		if(empty($afktime)){$afktime = $now;}
		if(empty($optime)){$optime = $now;}
		$ngameinfo=Array();
		foreach ($gameinfo as $key => $value) $ngameinfo[$key]=$$key;
		$ngameinfo['arealist'] = implode(',',$ngameinfo['arealist']);
		$gameinfo=$ngameinfo;
		$db->array_update("{$tablepre}game",$ngameinfo,1);
		return;
	}
	
	function save_combatinfo(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!$hdamage){$hdamage = 0;}
		if(!$hplayer){$hplayer = '';}
		if(!$noisetime){$noisetime = 0;}
		if(!$noisepls){$noisepls = 0;}
		if(!$noiseid){$noiseid = 0;}
		if(!$noiseid2){$noiseid2 = 0;}
		if(!$noisemode){$noisemode = '';}
		$db->query("UPDATE {$tablepre}game SET hdamage='$hdamage', hplayer='$hplayer', noisetime='$noisetime', noisepls='$noisepls', noiseid='$noiseid', noiseid2='$noiseid2', noisemode='$noisemode'");
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

	function parse_chat($chat)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$msg = '【'.$chatinfo[$chat['type']].'】'.$chat['send'].'：'.$chat['msg'].'('.date("H:i:s",$chat['time']).')';
		$premsg = '';
		$postmsg = '</span><br>';
		
		if($chat['type'] == '0') {
			$premsg = "<span class='chat0'>";
			//$msg = "【{$chatinfo[$chat['type']]}】{$chat['send']}：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'<br>';
		} elseif($chat['type'] == '1') {
			$premsg = "<span class='clan chat1'>";
			//$msg = "<span class=\"clan\">【{$chatinfo[$chat['type']]}】{$chat['send']}：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		} elseif($chat['type'] == '3') {
			$premsg = "<span class='red chat3'>";
			if ($chat['msg']){				
				//$msg = "<span class=\"red\">【{$chat['recv']}】{$chat['send']}：{$chat['msg']} ".date("\(H:i:s\)",$chat['time']).'</span><br>';
			} else {
				$msg = '【'.$chatinfo[$chat['type']].'】'.$chat['send'].'什么都没说就死去了 ('.date("H:i:s",$chat['time']).')';
				//$msg = "<span class=\"red\">【{$chat['recv']}】{$chat['send']} 什么都没说就死去了 ".date("\(H:i:s\)",$chat['time']).'</span><br>';
			}
		} elseif($chat['type'] == '4') {
			$premsg = "<span class='yellow chat4'>";
			$msg = '【'.$chatinfo[$chat['type']].'】'.$chat['send'].$chat['msg'].'('.date("H:i:s",$chat['time']).')';//有冒号的区别
			//$msg = "<span class=\"yellow\">【{$chatinfo[$chat['type']]}】{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		} elseif($chat['type'] == '5') {
			$premsg = "<span class='yellow chat5'>";
			$msg = '【'.$chatinfo[$chat['type']].'】'.$chat['send'].$chat['msg'].'('.date("H:i:s",$chat['time']).')';
			//$msg = "<span class=\"yellow\">【{$chatinfo[$chat['type']]}】{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		}
		return $premsg.$msg.$postmsg;
	}
	
	function getchat($last,$team='',$chatpid=0,$limit=0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$limit = $limit ? $limit : $chatlimit;
		$result = $db->query("SELECT * FROM {$tablepre}chat WHERE cid>'$last' AND (recv='' OR (type='1' AND recv='$team') OR (type!='1' AND recv='$chatpid')) ORDER BY cid desc LIMIT $limit");
		$chatdata = Array('lastcid' => $last, 'msg' => '');
		if(!$db->num_rows($result)){$chatdata = array('lastcid' => $last, 'msg' => '');return $chatdata;}
		
		while($chat = $db->fetch_array($result)) {
			//if(!$chatdata['lastcid']){$chatdata['lastcid'] = $chat['cid'];}
			if($chatdata['lastcid'] < $chat['cid']){$chatdata['lastcid'] = $chat['cid'];}
			$chat['msg'] = htmlspecialchars($chat['msg']);
			$msg = parse_chat($chat);
			$chatdata['msg'][$chat['cid']] = $msg;
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
	
	function room_auto_init(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		global $room_prefix,$tablepre,$wtablepre,$gtablepre,$db;
		//eval(import_module('sys'));
		//自动初始化表
		if ($room_prefix!='')
		{
			$result = $db->query("show tables like '{$wtablepre}winners';");
			if (!$db->num_rows($result))
			{
				//某个非主房间是第一次使用，则创建表并初始化
				$db->query("create table if not exists {$wtablepre}winners like {$gtablepre}winners;");
			}
			
			$result = $db->query("show tables like '{$tablepre}game';");
			if (!$db->num_rows($result))
			{
				//某个非主房间是第一次使用，则创建表并初始化
				$db->query("create table if not exists {$tablepre}game like {$gtablepre}game;");
		
				$result = $db->query("SELECT count(*) as cnt FROM {$tablepre}game");
				if (!$db->num_rows($result)) 
					$cnt=0;
				else 
				{
					$zz = $db->fetch_array($result);
					$cnt=$zz['cnt'];
				}
				if ($cnt==0) $db->query("insert into {$tablepre}game (gamenum) values (0);");
					
				$result = $db->query("SELECT count(*) as cnt FROM {$wtablepre}winners");
				if (!$db->num_rows($result)) 
					$cnt=0;
				else 
				{
					$zz = $db->fetch_array($result);
					$cnt=$zz['cnt'];
				}
				if ($cnt==0) $db->query("insert into {$wtablepre}winners (gid) values (0);");
				
				$sql = file_get_contents(GAME_ROOT.'./gamedata/sql/reset.sql');
				$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
				$db->queries($sql);
				
				$sql = file_get_contents(GAME_ROOT.'./gamedata/sql/players.sql');
				$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
				$db->queries($sql);
			}
		}
	}
}

?>
