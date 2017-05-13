<?php

namespace sys
{
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'newgame') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">第{$a}回ACFUN大逃杀开始了</span><br>\n";
		elseif($news == 'gameover') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">第{$a}回ACFUN大逃杀结束了</span><br>\n";
		elseif($news == 'newpc') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}({$b})进入了大逃杀战场</span><br>\n";
		elseif($news == 'newgm') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">管理员-{$a}({$b})华丽地乱入了战场</span><br>\n";
		elseif($news == 'end0') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">游戏出现故障，意外结束</span><br>\n";
		elseif($news == 'end1') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">参与者全部死亡！</span><br>\n";
		elseif($news == 'end2') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">优胜者——{$a}！</span><br>\n";
		elseif($news == 'end3') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}解除了精神锁定，游戏紧急中止</span><br>\n";
		elseif($news == 'end4') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">无人参加，游戏自动结束</span><br>\n";
		elseif($news == 'end5') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}引爆了核弹，毁坏了虚拟战场</span><br>\n";
		elseif($news == 'end6') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">本局游戏被GM中止</span><br>\n";
		elseif($news == 'end7') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"evergreen\">{$a}完成了他的使命。</span><br>\n";
		elseif($news == 'end8') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">限制时间到，本局游戏结束。</span><br>\n";
		elseif($news == 'wintutorial') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}完成了教程。</span><br>\n";
		elseif(strpos($news,'death') === 0) {
			if(isset($exarr['dword'])) $e0 = $exarr['dword'];
			if($news == 'death15') {
				return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">时空特使强行消除</span>{$e0}";
			} elseif($news == 'death16') {
				return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">由理直接拉入SSS团</span>{$e0}";
			} else {
				return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">不明原因</span>死亡{$e0}";
			}
		}
		elseif($news == 'corpseclear') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了凸眼鱼，{$b}具尸体被吸走了！</span><br>\n";
		elseif($news == 'sysaddarea') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">奇迹和魔法都是存在的！禁区提前增加了！</span><br>\n";
		elseif($news == 'syshackchg') {
			if($a){$hackword = '全部禁区都被解除了';$class = 'lime';}
			else{$hackword = '禁区恢复了未解除状态';$class = 'yellow';}
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"{$class}\">奇迹和魔法都是存在的！{$hackword}！</span><br>\n";
		} 
		elseif($news == 'sysgschg') {
			if($a == 20){
				$chgword = '当前游戏立即开始了！';
				$class = 'lime';
			}	elseif($a == 30){
				$chgword = '当前游戏停止激活！';
				$class = 'yellow';
			}	elseif($a == 40){
				$chgword = '当前游戏进入连斗阶段！';
				$class = 'red';
			}	elseif($a == 50){
				$chgword = '当前游戏进入死斗阶段！';
				$class = 'red';
			}	else{
				$chgword = '异常语句，请联系管理员！';
				$class = 'red';
			}
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"{$class}\">奇迹和魔法都是存在的！{$chgword}</span><br>\n";
		} 
		elseif($news == 'alive') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">神北 小毬许愿复活</span><br>\n";
		elseif($news == 'delcp') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}的尸体被时空特使别动队销毁了</span><br>\n";
		elseif($news == 'editpc') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}遭到了黑幕的生化改造！</span><br>\n";
		elseif($news == 'roominfo')
			return "<li>{$hour}时{$min}分{$sec}秒，本局游戏为<span class=\"yellow\">{$a}</span>，{$b}<br>\n";
		
		return "<li>$time,$news,$a,$b,$c,$d<br>\n";
	}
	
	function load_news($start = 0, $range = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
	
		//$file = $file ? $file : $newsfile;	
		//$ninfo = openfile($file);
		//if(0 == $range){$range = $newslimit;}
		//elseif(-1 == $range){$range = 16777215;}
		//if(16777215 == $range){startmicrotime();}
		$query = "SELECT * FROM {$tablepre}newsinfo ";
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
		$newsinfo = '<ul>';
		$nday = 0;
		//for($i = $start;$i <= $r;$i++) {
		//for($i = 0;$i < $nnum;$i++) {
		while($news0=$db->fetch_array($result)) {
			//$news0=$db->fetch_array($result);
			$time=$news0['time'];$news=$news0['news'];$a=$news0['a'];$b=$news0['b'];$c=$news0['c'];$d=$news0['d'];$e=$news0['e'];
			list($sec,$min,$hour,$day,$month,$year,$wday) = explode(',',date("s,i,H,j,n,Y,w",$time));
			if($day != $nday) {
				$newsinfo .= "<span class=\"evergreen\"><B>{$month}月{$day}日(星期$week[$wday])</B></span><br>";
				$nday = $day;
			}
			$exarr = parse_news_prepare($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
			$newsinfo .= parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
		}
		//if(16777215 == $range){logmicrotime('房间'.$room_prefix.'-第'.$gamenum.'局-拉取全部消息'.debug_backtrace()[1]['function']);}
		$newsinfo .= '</ul>';
		return $newsinfo;
			
	}
	
	function parse_news_prepare($news, $hour, $min, $sec, $a, $b, $c, $d, $e){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$exarr = array();
		if(strpos($news,'death') === 0){
			$dname = $a;
			if(isset($typeinfo[$b])) $dname = $typeinfo[$b].' '.$dname;
			if(!$e) $exarr['dword'] = "<span class=\"yellow\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else $exarr['dword'] = "<span class=\"yellow\">【{$dname}：“{$e}”】</span><br>\n";
		}
		return $exarr;
	}
}

?>