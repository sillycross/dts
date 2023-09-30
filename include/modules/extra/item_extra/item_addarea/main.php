<?php

namespace item_addarea
{
	$item_addarea_nlist = array('不要按这个按钮','好想按这个按钮','这个是什么按钮');
	$item_delayarea_nlist = array('你不准增加禁区');
	
	function init() 
	{
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if(strpos($k,'Y')===0 || strpos($k,'Z')===0){
			eval(import_module('item_addarea'));
			if (in_array($n, $item_addarea_nlist)){
				$ret .= '使用后会将下次禁区时间提前到1分钟以后';
			}elseif(in_array($n, $item_delayarea_nlist)){
				$ret .= '使用后会将下次禁区时间延迟30分钟';
			}
		}
		return $ret;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','map'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {	
			eval(import_module('item_addarea'));
			if (in_array($itm, $item_addarea_nlist)) {
				$log .= '你按下了<span class=\"yellow b\">「'.$itm.'」</span>。<br>';
				if($areatime - $now > 60) {
					if('不要按这个按钮' == $itm) $log .= '一个声音传来：<span class="ltcrimson b">“你们都干了些什么？没有看见上面写的“不要按这个按钮”吗？<br><br><br>…………好吧，这只是个不起眼的小按钮。祝你们玩得愉快。”</span><br>';
					elseif('好想按这个按钮' == $itm) $log .= '一个声音传来：<span class="ltazure b">“干得好！按钮不就是用来按的吗？”</span><br>';
					elseif('这个是什么按钮' == $itm) $log .= '一个声音传来：<span class="linen b">“建议将三个按钮全部删除，幻境暂时停止增加禁区！谢谢！金龙通讯社不能让触手之风蔓延下去！”</span><br>';
					$log .= '天边隐约传来轰鸣声，你惊奇地发现<span class="red b">下次禁区提前到来了！</span><br>';
					$areatime = $now + 60;
					save_gameinfo();
					addnews($now, 'item_addarea', $name, $itm);
					\sys\systemputchat($now,'hack2');
					\itemmain\itms_reduce($theitem);
				}else{
					$log .= "好像什么都没发生……<br>";
				}				
				return;
			}elseif(in_array($itm, $item_delayarea_nlist)){
				$log .= '你使用了<span class=\"yellow b\">「'.$itm.'」</span>。<br>';
				$log .= '天边隐约传来轰鸣声，你惊奇地发现<span class="red b">下次禁区时间竟然延后了！</span><br>';
				$areatime += 60*30;
				save_gameinfo();
				addnews($now, 'item_delayarea', $name, $itm);
				\sys\systemputchat($now,'hack4');
				\itemmain\itms_reduce($theitem);
				return;
			}
		}
		$chprocess($theitem);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
				
		if($news == 'item_addarea') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}按下了{$b}，使禁区时间提前了！</span></li>";
		elseif($news == 'item_delayarea') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}使用了{$b}，使禁区时间延后了！</span></li>";
	
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>