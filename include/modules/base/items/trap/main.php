<?php

namespace trap
{
	global $playerflag, $selflag, $trname, $trtype, $trperfix;	//注意这个脑残的变量名拼写typo，是perfix不是prefix
		
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['T'] = '陷阱';
	}
	
	function rs_game($xmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys','map','itemmain','trap'));
		if ($xmode & 16) {	//地图陷阱初始化
			$plsnum = sizeof($plsinfo);
			$iqry = '';
			$file = __DIR__.'/config/trapitem.config.php';
			$itemlist = openfile($file);
			$in = sizeof($itemlist);
			$an = $areanum ? ceil($areanum/$areaadd) : 0;
			for($i = 1; $i < $in; $i++) {
				if(!empty($itemlist[$i]) && strpos($itemlist[$i],',')!==false){
					list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
					if(($iarea == $an)||($iarea == 99)) {
						for($j = $inum; $j>0; $j--) {
							if ($imap == 99)
							{
								do {
									$rmap = rand(0,$plsnum-1);
								} while (in_array($rmap,$map_noitemdrop_arealist));
							}
							else  $rmap = $imap;
							$iqry .= "('$iname', '$ikind','$ieff','$ista','$iskind','$rmap'),";
						}
					}
				}
			}
			if(!empty($iqry)){
				$iqry = "INSERT INTO {$tablepre}maptrap (itm,itmk,itme,itms,itmsk,pls) VALUES ".substr($iqry, 0, -1);
				$db->query($iqry);
			}
		}
	}
	
	function calculate_real_trap_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','trap'));
		$trapresult = $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls' ORDER BY itmk DESC");
		$trpnum = $db->num_rows($trapresult);
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='$pls' AND pid!='$pid' AND state!='16'");
		$pnum=$db->num_rows($result);
		$exr=0;
		if ($pnum<=4) $exr=0.5;
		if ($pnum<=2) $exr=0.9;
		if ($pnum==1) $exr=1.4;
		if ($pnum==0) $exr=0.2;
		$real_trap_obbs = $trap_min_obbs + $trpnum/4+$exr;
		return $real_trap_obbs;
		/*
		if($club == 6){$real_trap_obbs-=5;}//人肉搜索称号遭遇陷阱概率减少
		*/
	}
		
			
	function get_trap_escape_rate()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','itemmain','trap'));
		$escrate = 8 + $lvl/3 ;
		/*
		if ($club==6) $escrate +=15;
		*/
		if ($selflag) $escrate += 50; //自己设置的陷阱容易躲避
		return $escrate;
	}
	
	function get_trap_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$damage = round(rand(0,$itme0/2)+($itme0/2));
		return $damage;
	}
	
	function send_trap_enemylog($is_hit)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','trap','logger'));
		if ($is_hit)
		{
			if($playerflag && !$selflag && $hp<=0){
				$w_log = "<span class=\"red\">{$name}触发了你设置的陷阱{$itm0}并被杀死了！</span><br>";
				\logger\logsave ( $itmsk0, $now, $w_log ,'b');
			}elseif($playerflag && !$selflag){
				$w_log = "<span class=\"yellow\">{$name}触发了你设置的陷阱{$itm0}！</span><br>";
				\logger\logsave ( $itmsk0, $now, $w_log ,'b');
			}
		}
		else
		{
			if($playerflag && !$selflag){
				$w_log = "<span class=\"yellow\">{$name}回避了你设置的陷阱{$itm0}！</span><br>";
				\logger\logsave ( $itmsk0, $now, $w_log ,'b');
			}
		}
	}
	
	function trap_deal_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','trap','logger'));
		
		$bid = $itmsk0;
		$damage = get_trap_damage();
		$hp -= $damage;
		
		if($playerflag){
			addnews($now,'trap',$name,$trname,$itm0,$damage);
		}
		$log .= "糟糕，你触发了{$trperfix}陷阱<span class=\"yellow\">$itm0</span>！受到<span class=\"dmg\">$damage</span>点伤害！<br>";
	
		send_trap_enemylog(1);
	}
	
	function trap_hit()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','itemmain','trap','logger'));
		
		trap_deal_damage();
		
		if($hp <= 0) {
			$log .= "<span class=\"red\">你被{$trperfix}陷阱杀死了！</span>";
			$state = 27;
			\player\update_sdata();
			if (!$selflag && $playerflag) 	//有来源且不是自己
			{
				$edata = \player\fetch_playerdata_by_pid($itmsk0);
			}
			else 
			{
				$edata = &$sdata;		//无来源或来源是自己
				if (!$playerflag) $sdata['sourceless']=1;	//无来源
			}
			
			$edata['attackwith'] = $itm0;
			$killmsg = \player\kill($edata, $sdata);
			
			if (!$selflag && $playerflag)
			{
				\player\player_save($edata);
			}
			\player\player_save($sdata);
			\player\load_playerdata($sdata);
			
			if (isset($sdata['sourceless'])) unset($sdata['sourceless']);
			if($killmsg != ''){
				$log .= "<span class=\"yellow\">{$trname}对你说：“{$killmsg}”</span><br>";
			}				
		}
		
		$itm0 = $itmk0 = $itmsk0 = '';
		$itme0 = $itms0 = 0;
	}
		
	function calculate_trap_reuse_rate()	//重复利用陷阱的概率
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','trap'));
		$fdrate = 5 + $lvl/3;
		$fdrate = $selflag ? $fdrate + 50 : $fdrate;
		return $fdrate;
	}
	
	function trap_miss_reused()	//回避且重用了陷阱
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','trap'));
		$log .= "你发现了{$trperfix}陷阱<span class=\"yellow\">$itm0</span>，不过你并没有触发它。陷阱看上去还可以重复使用。<br>";			
		$itmsk0 = '';$itmk0 = str_replace('TO','TN',$itmk0);
		$mode = 'itemfind';
	}
	
	function trap_miss_broken()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','trap'));
		$log .= "你发现了{$trperfix}陷阱<span class=\"yellow\">$itm0</span>，不过你成功地回避了它。<br>";
		$itm0 = $itmk0 = $itmsk0 = '';
		$itme0 = $itms0 = 0;
		$mode = 'command';
	}
	
	function trap_miss()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','trap','logger'));
		if ($playerflag)
		{
			addnews($now,'trapmiss',$name,$trname,$itm0);
		}
		
		send_trap_enemylog(0);
		
		$dice = rand(0,99);
		$fdrate = calculate_trap_reuse_rate();
		if($dice < $fdrate){
			trap_miss_reused();
		}else{
			trap_miss_broken();
		}
	}
	
	function trap()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','trap'));
		
		$escrate = get_trap_escape_rate();
		$escrate = $escrate >= 90 ? 90 : $escrate;//最大回避率

		$dice=rand(0,99);
		if($dice >= $escrate){
			trap_hit();
		} else {
			trap_miss();
		}
	}

	function trapget($mi)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','trap'));
		$itm0=$mi['itm'];
		$itmk0=$mi['itmk'];
		$itme0=$mi['itme'];
		$itms0=$mi['itms'];
		$itmsk0=$mi['itmsk']; 
		$tid=$mi['tid'];
		$db->query("DELETE FROM {$tablepre}maptrap WHERE tid='$tid'");
		
		$playerflag = is_numeric($itmsk0) ? true : false;
		$selflag = ($playerflag && ($itmsk0 == $pid)) ? true : false;
		
		if($playerflag && !$selflag){
			$wdata = \player\fetch_playerdata_by_pid($itmsk0);
			$trname = $wdata['name'];$trtype = $wdata['type'];$trperfix = '<span class="yellow">'.$trname.'</span>设置的';
		}elseif($selflag){
			$trname = $name;$trtype = 0;$trperfix = '你自己设置的';
		}else{
			$trname = $trtype = $trperfix = '';
		}
		
		trap();
	}
	
	function trapcheck()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','itemmain','trap'));
		$real_trap_obbs = calculate_real_trap_obbs();
		$trap_dice=rand(0,$trap_max_obbs-1);
		if($trap_dice < $real_trap_obbs){//踩陷阱判断
			$trapresult = $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls' ORDER BY itmk DESC");
			$trpnum = $db->num_rows($trapresult);
			if ($trpnum == 0) return 0;
			$itemno = rand(0,$trpnum-1);
			$db->data_seek($trapresult,$itemno);
			$mi=$db->fetch_array($trapresult);
			trapget($mi);
			return 1;
		}
		return 0;
	}
	
	function discover($schmode) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('trap','logger'));
		$trap_dice=rand(0,99);//随机数，开始判断是否踩陷阱
		if($trap_dice < $trap_max_obbs)
			if (trapcheck()) 
				return;
				
		$chprocess($schmode);
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'trap') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"red\">{$a}中了{$b}设置的陷阱{$c}，受到了{$d}点伤害！</span><br>\n";
		if($news == 'trapmiss') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}回避了{$b}设置的陷阱{$c}</span><br>\n";
		if($news == 'death27') {
			$dname = $typeinfo[$b].' '.$a;
			if(!$e)
				$e0="<span class=\"yellow\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else  $e0="<span class=\"yellow\">【{$dname}：“{$e}”】</span><br>\n";
			if($c){
				return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因触发了<span class=\"yellow\">$c</span>设置的陷阱<span class=\"red\">$d</span>被杀死{$e0}";
			} else {
				return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">$a</span>因触发了陷阱<span class=\"red\">$d</span>被杀死{$e0}";
			}
		}
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
	
	function trap_use(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		$trapk = str_replace('TN','TO',$itmk);
		$db->query("INSERT INTO {$tablepre}maptrap (itm, itmk, itme, itms, itmsk, pls) VALUES ('$itm', '$trapk', '$itme', '1', '$pid', '$pls')");
		$log .= "设置了陷阱<span class=\"red\">$itm</span>。<br>小心，自己也很难发现。<br>";
		
		\lvlctl\getexp(1);
		$wd++;
			
		\itemmain\itms_reduce($theitem);
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'T' ) === 0) 
		{
			trap_use($theitem);
			return;
		}
		$chprocess($theitem);
	}

		
}

?>
