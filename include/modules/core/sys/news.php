<?php

namespace sys
{
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'newgame') {
			$gprefix = $groomtype ? '房间局' : '';
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$gprefix}第{$a}回ACFUN大逃杀开始了</span></li>";
		}
		elseif($news == 'gameover') {
			$gprefix = $groomtype ? '房间局' : '';
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$gprefix}第{$a}回ACFUN大逃杀结束了</span></li>";
		}
		elseif($news == 'newpc') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}({$b})进入了大逃杀战场</span></li>";
		elseif($news == 'newgm') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">管理员-{$a}({$b})华丽地乱入了战场</span></li>";
		elseif($news == 'end0') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">游戏出现故障，意外结束</span></li>";
		elseif($news == 'end1') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">参与者全部死亡！</span></li>";
		elseif($news == 'end2') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">优胜者——{$a}！</span></li>";
		elseif($news == 'end3') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}解除了精神锁定，游戏紧急中止</span></li>";
		elseif($news == 'end4') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">无人参加，游戏自动结束</span></li>";
		elseif($news == 'end5') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}引爆了核弹，毁坏了虚拟战场</span></li>";
		elseif($news == 'end6') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">本局游戏被GM中止</span></li>";
		elseif($news == 'end7') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"evergreen b\">{$a}完成了他的使命。</span></li>";
		elseif($news == 'end8') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">限制时间到，本局游戏结束。</span></li>";
		elseif($news == 'wintutorial') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}完成了教程。</span></li>";
		elseif(strpos($news,'death') === 0) {
			if(isset($exarr['dword'])) $e0 = $exarr['dword'];
			if($news == 'death15') {
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"red b\">时空特使强行消除</span>{$e0}</li>";
			} elseif($news == 'death16') {
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"red b\">由理直接拉入SSS团</span>{$e0}</li>";
			} else {
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因<span class=\"red b\">不明原因</span>死亡{$e0}</li>";
			}
		}
		elseif($news == 'corpseclear') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}使用了凸眼鱼，{$b}具尸体被吸走了！</span></li>";
		elseif($news == 'sysaddarea') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">奇迹和魔法都是存在的！禁区提前增加了！</span></li>";
		elseif($news == 'syshackchg') {
			if($a){$hackword = '全部禁区都被解除了';$class = 'lime b';}
			else{$hackword = '禁区恢复了未解除状态';$class = 'yellow b';}
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"{$class}\">奇迹和魔法都是存在的！{$hackword}！</span></li>";
		} 
		elseif($news == 'sysgschg') {
			if($a == 20){
				$chgword = '当前游戏立即开始了！';
				$class = 'lime b';
			}	elseif($a == 30){
				$chgword = '当前游戏停止激活！';
				$class = 'yellow b';
			}	elseif($a == 40){
				$chgword = '当前游戏进入连斗阶段！';
				$class = 'red b';
			}	elseif($a == 50){
				$chgword = '当前游戏进入死斗阶段！';
				$class = 'red b';
			}	else{
				$chgword = '异常语句，请联系管理员！';
				$class = 'red b';
			}
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"{$class}\">奇迹和魔法都是存在的！{$chgword}</span></li>";
		} 
		elseif($news == 'alive') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>被<span class=\"yellow b\">神北 小毬许愿复活</span></li>";
		elseif($news == 'delcp') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}的尸体被时空特使别动队销毁了</span></li>";
		elseif($news == 'editpc') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}遭到了黑幕的生化改造！</span></li>";
		elseif($news == 'roominfo')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，本局游戏为<span class=\"yellow b\">{$a}</span>，{$b}{$c}{$d}{$e}</li>";
		
		return "<li>$time,$news,$a,$b,$c,$d<br>\n";
	}
	
	function load_news($start = 0, $range = 0, $noday = 0, $rprefix=''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(!$rprefix) $rprefix=$room_prefix;
		$ntablepre = room_get_tablepre($rprefix);
		//$file = $file ? $file : $newsfile;	
		//$ninfo = openfile($file);
		//if(0 == $range){$range = $newslimit;}
		//elseif(-1 == $range){$range = 16777215;}
		//if(16777215 == $range){startmicrotime();}
		$query = "SELECT * FROM {$ntablepre}newsinfo ";
		if($start) $query .= "WHERE nid > $start ";
		$query .= "ORDER BY nid DESC ";
		if($range > 0) $query .= "LIMIT $range";
		//if($start) $result = $db->query("SELECT * FROM {$tablepre}newsinfo WHERE nid > $start ORDER BY nid DESC LIMIT $range");
		$result = $db->query($query);
		//$r = sizeof($ninfo) - 1;
	//	$rnum=$db->num_rows($result);
	//	if($range && ($range <= $rnum)) {
	//		$nnum = $range;
	//	} else{
	//		$nnum = $rnum;
	//	}
		$nday = 0;
		//for($i = $start;$i <= $r;$i++) {
		//for($i = 0;$i < $nnum;$i++) {
		$newslist = array();
		while($news0=$db->fetch_array($result)) {
			//$news0=$db->fetch_array($result);
			$nid=$news0['nid'];$time=$news0['time'];$news=$news0['news'];$a=$news0['a'];$b=$news0['b'];$c=$news0['c'];$d=$news0['d'];$e=$news0['e'];
			list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$time));
			if(!$noday && $day != $nday) {//跨日消息
				$newslist['nday'.$nday] = "<div class=\"evergreen b\"><B>{$month}月{$day}日(星期$week[$wday])</B></div>";
				$nday = $day;
			}
			$exarr = parse_news_prepare($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e);
			//一般消息
			$tmp_np = parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
			if($tmp_np) $newslist['nid'.$nid] = $tmp_np;
		}
		//if(16777215 == $range){logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-拉取全部消息'.debug_backtrace()[1]['function']);}
		//rsort($newslist);
		return $newslist;
	}
	
	function parse_news_prepare($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$exarr = array();
		if(strpos($news,'death') === 0){
			$dname = $a;
			if(isset($typeinfo[$b])) $dname = $typeinfo[$b].' '.$dname;
			if(!$e) $exarr['dword'] = "<span class=\"yellow b\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else $exarr['dword'] = "<span class=\"yellow b\">【{$dname}：“{$e}”】</span><br>\n";
		}
		return $exarr;
	}
	
	function getnews($start=0, $range=0, $room_prefix=''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$newslist = load_news($start, $range, 1, $room_prefix);
		$lastnid=$start;
		if(!empty($newslist)){
			$nkey = array_keys($newslist);
			do {
				$lastnid = array_splice($nkey,0,1);
			}while(strpos($lastnid[0],'nid')===false);
			$lastnid = str_replace('nid','',$lastnid[0]);
		}
		$ret = array(
			'news' => $newslist,
			'lastnid' => $lastnid
		);
		return $ret;
	}
}

?>