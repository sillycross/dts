<?php

namespace sys
{
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if($news == 'newgame') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">第{$a}回ACFUN大逃杀开始了</span><br>\n";
		if($news == 'gameover') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">第{$a}回ACFUN大逃杀结束了</span><br>\n";
		if($news == 'newpc') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}({$b})进入了大逃杀战场</span><br>\n";
		if($news == 'newgm') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">管理员-{$a}({$b})华丽地乱入了战场</span><br>\n";
		if($news == 'end0') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">游戏出现故障，意外结束</span><br>\n";
		if($news == 'end1') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">参与者全部死亡！</span><br>\n";
		if($news == 'end2') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">优胜者——{$a}！</span><br>\n";
		if($news == 'end3') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}解除了精神锁定，游戏紧急中止</span><br>\n";
		if($news == 'end4') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">无人参加，游戏自动结束</span><br>\n";
		if($news == 'end5') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}引爆了核弹，毁坏了虚拟战场</span><br>\n";
		if($news == 'end6') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">本局游戏被GM中止</span><br>\n";
		if(strpos($news,'death') === 0) {
			$dname = $typeinfo[$b].' '.$a;
			if(!$e){
				$e0="<span class=\"yellow\">【{$dname} 什么都没说就死去了】</span><br>\n";
			}else{
				$e0="<span class=\"yellow\">【{$dname}：“{$e}”】</span><br>\n";
			}
			if($news == 'death15') {
				return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">时空特使强行消除</span>{$e0}";
			} elseif($news == 'death16') {
				return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"red\">由理直接拉入SSS团</span>{$e0}";
			} else {
				return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">不明原因</span>死亡{$e0}";
			}
		}
		if($news == 'corpseclear') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了凸眼鱼，{$b}具尸体被吸走了！</span><br>\n";
		if($news == 'sysaddarea') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">奇迹和魔法都是存在的！禁区提前增加了！</span><br>\n";
		if($news == 'syshackchg') {
			if($a){$hackword = '全部禁区都被解除了';$class = 'lime';}
			else{$hackword = '禁区恢复了未解除状态';$class = 'yellow';}
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"{$class}\">奇迹和魔法都是存在的！{$hackword}！</span><br>\n";
		} 
		if($news == 'sysgschg') {
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
		if($news == 'alive') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>被<span class=\"yellow\">神北 小毬许愿复活</span><br>\n";
		if($news == 'delcp') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}的尸体被时空特使别动队销毁了</span><br>\n";
		if($news == 'editpc') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}遭到了黑幕的生化改造！</span><br>\n";
		if($news == 'roominfo')
			return "<li>{$hour}时{$min}分{$sec}秒，本局游戏为<span class=\"yellow\">{$a}</span>，{$b}<br>\n";
		
		return "<li>$time,$news,$a,$b,$c,$d<br>\n";
	}
	
	function  nparse_news($start = 0, $range = 0  ){//$type = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
	
		//$file = $file ? $file : $newsfile;	
		//$ninfo = openfile($file);
		$range = $range == 0 ? $newslimit : $range ;
		$result = $db->query("SELECT * FROM {$tablepre}newsinfo ORDER BY nid DESC LIMIT $start,$range");
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
			$newsinfo .= parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
		}

		$newsinfo .= '</ul>';
		return $newsinfo;
			
	}
}

?>
