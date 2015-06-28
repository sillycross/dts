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
		if(!$noisetime){$noisetime = 0;}
		if(!$noisepls){$noisepls = 0;}
		if(!$noiseid){$noiseid = 0;}
		if(!$noiseid2){$noiseid2 = 0;}
		$combatinfo = "<?php\n\nif(!defined('IN_GAME')){exit('Access Denied');}\n\n\$hdamage = {$hdamage};\n\$hplayer = '{$hplayer}';\n\$noisetime = {$noisetime};\n\$noisepls = {$noisepls};\n\$noiseid = {$noiseid};\n\$noiseid2 = {$noiseid2};\n\$noisemode = '{$noisemode}';\n\n?>";
		//$combatinfo = "{$hdamage},{$hplayer},{$noisetime},{$noisepls},{$noiseid},{$noiseid2},{$noisemode},\n";
		$dir = GAME_ROOT.'./gamedata/';
		if($fp = fopen("{$dir}combatinfo.php", 'w')) {
			if(flock($fp,LOCK_EX)) {
				fwrite($fp, $combatinfo);
			} else {
				exit("Couldn't save combat info !");
			}
			fclose($fp);
		} else {
			gexit('Can not write to cache files, please check directory ./gamedata/ .', __file__, __line__);
		}
		return;
	}
	
	function addnews($t = 0, $n = '',$a='',$b='',$c = '', $d = '', $e = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$t = $t ? $t : $now;
		$newsfile = GAME_ROOT.'./gamedata/newsinfo.php';
		touch($newsfile);
		if(is_array($a)){
			$a=implode('_',$a);
		}
	//	$newsfile = GAME_ROOT.'./gamedata/newsinfo.php';
	//	$newsdata = readover($newsfile); //file_get_contents($newsfile);
	//	if(is_array($a)) {
	//		$news = "$t,$n,".implode('-',$a).",$b,$c,$d,\n";
	//	} elseif(isset($n)) {
	//		$news = "$t,$n,$a,$b,$c,$d,\n";
	//	}
	//	$newsdata = substr_replace($newsdata,$news,53,0);
	//	writeover($newsfile,$newsdata,'wb');
	
		//这尼玛写的太坑了吧…… 不管了直接import map模块进来了……
		eval(import_module('map'));
		if(strpos($n,'death11') === 0  || strpos($n,'death32') === 0) {
			$result = $db->query("SELECT lastword FROM {$tablepre}users WHERE username = '$a'");
			$e = $lastword = $db->result($result, 0);
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$t','$a','$plsinfo[$c]','$lastword')");
		}	elseif(strpos($n,'death15') === 0 || strpos($n,'death16') === 0) {
			$result = $db->query("SELECT lastword FROM {$tablepre}users WHERE username = '$a'");
			$e = $lastword = $db->result($result, 0);
			$result = $db->query("SELECT pls FROM {$tablepre}players WHERE name = '$a' AND type = '0'");
			$place = $db->result($result, 0);
			$db->query("INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$t','$a','$plsinfo[$place]','$lastword')");
		}
		$db->query("INSERT INTO {$tablepre}newsinfo (`time`,`news`,`a`,`b`,`c`,`d`,`e`) VALUES ('$t','$n','$a','$b','$c','$d','$e')");
	}

	function parse_chat($chat)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($chat['type'] == '0') {
			$msg = "【{$chatinfo[$chat['type']]}】{$chat['send']}：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'<br>';
		} elseif($chat['type'] == '1') {
			$msg = "<span class=\"clan\">【{$chatinfo[$chat['type']]}】{$chat['send']}：{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		} elseif($chat['type'] == '3') {
			if ($chat['msg']){
				$msg = "<span class=\"red\">【{$chat['recv']}】{$chat['send']}：{$chat['msg']} ".date("\(H:i:s\)",$chat['time']).'</span><br>';
			} else {
				$msg = "<span class=\"red\">【{$chat['recv']}】{$chat['send']} 什么都没说就死去了 ".date("\(H:i:s\)",$chat['time']).'</span><br>';
			}
		} elseif($chat['type'] == '4') {
			$msg = "<span class=\"yellow\">【{$chatinfo[$chat['type']]}】{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		} elseif($chat['type'] == '5') {
			$msg = "<span class=\"yellow\">【{$chatinfo[$chat['type']]}】{$chat['msg']}".date("\(H:i:s\)",$chat['time']).'</span><br>';
		}
		return $msg;
	}
	
	function getchat($last,$team='',$limit=0) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$limit = $limit ? $limit : $chatlimit;
		$result = $db->query("SELECT * FROM {$tablepre}chat WHERE cid>'$last' AND (type!='1' OR (type='1' AND recv='$team')) ORDER BY cid desc LIMIT $limit");
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
}

?>
