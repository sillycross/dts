<?php

namespace boxes
{
	$room_mode_can_get_card_from_boxes = Array(0,1,4,6,18,19);//能够从礼品盒开出卡片的游戏模式
	
	//各礼品盒对应的文件
	$item_boxes_list_file = Array(
		'p' => '/config/present.config.php',
		'p2' => '/config/present2.config.php',
		'ygo' => '/config/ygobox.config.php',
		'ygo2' => '/config/newygobox.config.php',
		'fy' => '/config/fybox.config.php',
		'kj3' => '/config/kj3box.config.php',
		'prd' => '/config/rdoll.config.php',
	);
		
	function init()
	{
		eval(import_module('itemmain'));
		$iteminfo['p'] = '礼物';
		$iteminfo['p2'] = '礼盒';
		$iteminfo['ygo'] = '卡包';
		$iteminfo['ygo2'] = '卡包';
		$iteminfo['fy'] = '全图唯一的野生浮云礼盒';
		$iteminfo['kj3'] = '礼包';
		$iteminfo['prd'] = '礼盒？';
	}
	
	//各种礼物盒的核心函数，从对应的文件里读取记录并随机选择一个
	function itemuse_boxes(&$theitem){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger','boxes'));
		
		if(!empty($itms0)) {
			$log .= '<span class="yellow b">'.$iteminfo[$itmk0].'正被你攥着呢，还是先把它放下再打开吧！</span><br>';
			$mode = 'command';
			return;
		}
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		$log .= "你打开了<span class=\"yellow b\">$itm</span>。<br>";
		$file = __DIR__.$item_boxes_list_file[$itmk];
		$tmp_itm = $itm;
		\itemmain\itms_reduce($theitem);
		$plist = openfile($file);
		do
		{
			$rand = rand(0,count($plist)-1);
			list($in,$ik,$ie,$is,$isk) = explode(',',$plist[$rand]);
			$itm0 = $in;$itmk0=$ik;$itme0=$ie;$itms0=$is;$itmsk0=$isk;
		}
		while(!in_array($gametype,$room_mode_can_get_card_from_boxes) && substr($ik,0,2)=='VO');//房间模式内开不出卡
		
		addnews($now,'present',$name,$tmp_itm,$in);
		\itemmain\itemget();		
		return;
	}

	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger','boxes'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if(in_array($itmk, array_keys($item_boxes_list_file))){
			itemuse_boxes($theitem);
			return;
		}
		$chprocess($theitem);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'present') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}打开了{$b}，获得了{$c}！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
		
}

?>