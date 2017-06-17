<?php

namespace item_uee
{
	global $hack_obbs; $hack_obbs = 40;
	
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['EE'] = '电脑设备';
	}
	
	function calculate_hack_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('item_uee'));
		return $hack_obbs;
	}
	
	function post_hack_events($itmn = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		$itm = & ${'itm'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};
		
		$hack_dice2 = rand(0,99);

		if($hack_dice2 < 5) {
			$log .= '由于你的不当操作，禁区系统防火墙锁定了你的电脑并远程引爆了它。幸好你本人的位置并没有被发现。<br>';
			$itm = $itmk = $itmsk = '';
			$itme = $itms = 0;
		} elseif($hack_dice2 < 8) {
			$log .= "<span class=\"evergreen\">“小心隔墙有耳哦。”</span>——林无月<br>";
			$log .= '你擅自入侵禁区控制系统，被控制系统远程消灭！<br>';
			$state = 14;
			\player\update_sdata(); $sdata['sourceless'] = 1; $sdata['attackwith'] = '';
			\player\kill($sdata,$sdata);
			\player\player_save($sdata);
			\player\load_playerdata($sdata);
		}
	}
	
	function itemuse_uee($itmn = 0) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));

		$itm = & ${'itm'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};

		if(!$itms) {
			$log .= '此道具不存在，请重新选择。<br>';
			$mode = 'command';
			return;
		}

		if(!$itme) {
			$log .= "<span class=\"yellow\">$itm</span>已经没电，请寻找<span class=\"yellow\">电池</span>充电。<br>";
			$mode = 'command';
			return;
		}

		$hack_dice = rand(0,99);
		$hack_proc = calculate_hack_proc_rate();
		if ($hack_dice < $hack_proc) 
		{
			$hack = 1;
			$log .= '入侵禁区控制系统成功了！全部禁区都被解除了！<br>';
			//\map\movehtm();
			addnews($now,'hack',$name);
			save_gameinfo();
		} else {
			$log .= '可是，入侵禁区控制系统失败了……<br>';
		}
		
		$itme--;
		$log .= "消耗了<span class=\"yellow\">$itm</span>的电力。<br>";
		if($itme <= 0) {
			$log .= "<span class=\"red\">$itm</span>的电池耗尽了。";
		}
		
		post_hack_events($itmn);
		return;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'EE' ) === 0) {
			itemuse_uee($theitem['itmn']);
			return;
		}
		$chprocess($theitem);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'hack') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}启动了hack程序，全部禁区解除！</span></li>";
		
		if(isset($exarr['dword'])) $e0 = $exarr['dword'];
			
		if($news == 'death14') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因<span class=\"red\">入侵禁区系统失败</span>死亡{$e0}</li>";
	
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
