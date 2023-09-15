<?php

namespace item_uee
{
	global $hack_obbs; $hack_obbs = 40;
	
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['EE'] = '干扰设备';
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if(strpos($k,'EE')===0) {
			$ret .= '可干扰虚拟幻境系统。<br>如果成功，所有禁区解除，直到下一次增加禁区为止。<br>注意：不论成功与否，都有被反击代码反杀的风险。';
		}
		return $ret;
	}
	
	function calculate_hack_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('item_uee'));
		return $hack_obbs;
	}
	
	//返回两个数字，第一个是设备毁坏概率，第二个是被杀死概率，不叠加（叠加在外部程序进行）
	function calculate_post_hack_proc_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return array(5,3);
	}
	
	function get_uee_deathlog () {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return '<span class="ltcrimson b">“就算是我这种代码白痴，一样能使用林无月留下的力量哦？”</span>——<span class="red b">红暮</span><br>';
	}
	
	function post_hack_events($itmn = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','item_uee'));
		
		$hack_dice2 = rand(0,99);
		list($ph_rate1, $ph_rate2) = calculate_post_hack_proc_rate();
		if($hack_dice2 < $ph_rate1) {
			$log .= '由于你操作不当，幻境反击代码定位了你的设备，并将它直接抹消了。幸好你本人安然无恙。<br>';
			$itm = & ${'itm'.$itmn};
			$itmk = & ${'itmk'.$itmn};
			$itme = & ${'itme'.$itmn};
			$itms = & ${'itms'.$itmn};
			$itmsk = & ${'itmsk'.$itmn};
			$itm = $itmk = $itmsk = '';
			$itme = $itms = 0;
		} elseif($hack_dice2 < $ph_rate1 + $ph_rate2) {
			
			$log .= '<br>'.get_uee_deathlog();
			$log .= '你擅自攻击虚拟幻境系统，被反击代码远程消灭！<br>';
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
			$log .= "<span class=\"yellow b\">$itm</span>已经没电，请寻找<span class=\"yellow b\">电池</span>充电。<br>";
			$mode = 'command';
			return;
		}
		
		$ret = itemuse_uee_core($itmn);
		if($ret) {
			$itme--;
			$log .= "消耗了<span class=\"yellow b\">$itm</span>的电力。<br>";
			if($itme <= 0) {
				$log .= "<span class=\"red b\">$itm</span>的电池耗尽了。";
			}
		}
		
		post_hack_events($itmn);
		return;
	}
	
	function itemuse_uee_core($itmn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$hack_dice = rand(0,99);
		$hack_proc = calculate_hack_proc_rate();
		if ($hack_dice < $hack_proc) 
		{
			$hack = 1;
			$log .= '干扰成功了！全部禁区都被解除了！<br>';
			//\map\movehtm();
			addnews($now,'hack',$name);
			\sys\systemputchat($now,'hack');
			save_gameinfo();
		} else {
			$log .= '可是，干扰失败了……<br>';
		}
		return true;
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
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}成功干扰了幻境的运转，全部禁区暂时解除了！</span></li>";
		
		if(isset($exarr['dword'])) $e0 = $exarr['dword'];
			
		if($news == 'death14') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因<span class=\"red b\">遭到幻境系统清缴</span>死亡{$e0}</li>";
	
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>
