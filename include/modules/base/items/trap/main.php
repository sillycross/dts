<?php

namespace trap
{
	global $playerflag, $selflag, $trname, $trtype, $trprefix;
		
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
			$itemlist = get_trapfilecont();
			$in = sizeof($itemlist);
			$an = $areanum ? ceil($areanum/$areaadd) : 0;
			for($i = 1; $i < $in; $i++) {
				if(!empty($itemlist[$i]) && strpos($itemlist[$i],',')!==false){
					list($iarea,$imap,$inum,$iname,$ikind,$ieff,$ista,$iskind) = explode(',',$itemlist[$i]);
					if(strpos($iskind,'=')===0){
						$tmp_pa_name = substr($iskind,1);
						$iskind = '';
						$result = $db->query("SELECT pid FROM {$tablepre}players WHERE name='$tmp_pa_name' AND type>0");
						if($db->num_rows($result)){
							$ipid = $db->fetch_array($result);
							$iskind = $ipid['pid'];
						}
					}
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
	
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		$file = __DIR__.'/config/trapitem.config.php';
		$l = openfile($file);
		return $l;
	}
	
	function calculate_real_trap_obbs()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','trap'));
		//最小值
		$real_trap_obbs = $trap_min_obbs;
		//地图上每有1个雷+0.25%
		$trapresult = $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls' ORDER BY itmk DESC");
		$trpnum = $db->num_rows($trapresult);
		$real_trap_obbs += $trpnum/4;
		//把奇怪的加成值去掉了

		return $real_trap_obbs;
	}
		
	function calculate_real_trap_obbs_change($var)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $var;
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
				$w_log = "<span class=\"red b\">{$name}触发了你设置的陷阱{$itm0}并被杀死了！</span><br>";
				\logger\logsave ( $itmsk0, $now, $w_log ,'b');
			}elseif($playerflag && !$selflag){
				$w_log = "<span class=\"yellow b\">{$name}触发了你设置的陷阱{$itm0}！</span><br>";
				\logger\logsave ( $itmsk0, $now, $w_log ,'b');
			}
		}
		else
		{
			if($playerflag && !$selflag){
				$w_log = "<span class=\"yellow b\">{$name}回避了你设置的陷阱{$itm0}！</span><br>";
				\logger\logsave ( $itmsk0, $now, $w_log ,'b');
			}
		}
	}
	
	//陷阱的乘法伤害修正，类似战斗伤害修正，返回的是一个数组
	function get_trap_damage_multiplier(&$pa, &$pd, $trap, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return Array();
	}
	
	//陷阱的最终伤害修正，类似战斗伤害，先执行提高类修正，再执行降低类修正
	function get_trap_final_damage_modifier_up(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $damage;
	}
	
	function get_trap_final_damage_modifier_down(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $damage;
	}
	
	//非升高也非降低类的修正
	function get_trap_final_damage_change(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $damage;
	}
	
	//陷阱命中后事件
	function post_traphit_events(&$pa, &$pd, $tritm, $damage)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function trap_deal_damage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','trap','logger'));
		
		$bid = $itmsk0;
		if(!empty($bid)) {
			if($bid == $pid) {
				$pa = $sdata;
			}else{
				$pa=\player\fetch_playerdata_by_pid($bid);
			}
		}else {
			$pa=\player\create_dummy_playerdata();
		}
		$log .= "糟糕，你触发了{$trprefix}陷阱<span class=\"yellow b\">$itm0</span>！";
		$damage = get_trap_damage();
		
		
	
		$tritm=Array();
		$tritm['itm']=$itm0; $tritm['itmk']=$itmk0; 
		$tritm['itme']=$itme0; $tritm['itms']=$itms0; $tritm['itmsk']=$itmsk0;
		$multiplier = get_trap_damage_multiplier($pa, $sdata, $tritm, $damage);
		
		if (count($multiplier)>0)
		{
			$fin_dmg=$damage; $mult_words='';
			foreach ($multiplier as $key)
			{
				$fin_dmg=$fin_dmg*$key;
				$mult_words.="×{$key}";
			}
			$fin_dmg=round($fin_dmg);
			if ($fin_dmg < 1) $fin_dmg = 1;
			$log .= "你受到了{$damage}{$mult_words}＝<span class=\"dmg\">{$fin_dmg}</span>点伤害。<br>";
			$damage = $fin_dmg;
		}
		else
		{
			$log.="你受到了<span class=\"dmg\">$damage</span>点伤害！<br>";
		}
		
		$damage = get_trap_final_damage_modifier_up($pa, $sdata, $tritm, $damage);
		
		$damage = get_trap_final_damage_modifier_down($pa, $sdata, $tritm, $damage);
		
		$damage = get_trap_final_damage_change($pa, $sdata, $tritm, $damage);

		$hp -= $damage;
		
		if ($damage>0) post_traphit_events($pa, $sdata, $tritm, $damage);
		
		if($playerflag){
			addnews($now,'trap',$name,$trname,$itm0,$damage);
		}
		
		send_trap_enemylog(1);
	}
	
	function trap_hit()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','itemmain','trap','logger'));
		
		trap_deal_damage();
		
		if($hp <= 0) {
			$log .= "<span class=\"red b\">你被{$trprefix}陷阱杀死了！</span>";
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
				$log .= "<span class=\"yellow b\">{$trname}对你说：“{$killmsg}”</span><br>";
			}				
		}
		else
		{
			trap_survive();
		}
		
		$itm0 = $itmk0 = $itmsk0 = '';
		$itme0 = $itms0 = 0;
	}
	
	function trap_survive()	//受到陷阱伤害并幸存
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
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
		$log .= "你发现了{$trprefix}陷阱<span class=\"yellow b\">$itm0</span>，不过你并没有触发它。陷阱看上去还可以重复使用。<br>";			
		$itmsk0 = '';$itmk0 = str_replace('TO','TN',$itmk0);
		$mode = 'itemfind';
	}
	
	function trap_miss_broken()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','trap'));
		$log .= "你发现了{$trprefix}陷阱<span class=\"yellow b\">$itm0</span>，不过你成功地回避了它。<br>";
		$itm0 = $itmk0 = $itmsk0 = '';
		$itme0 = $itms0 = 0;
		$mode = 'command';
	}
	
	function trap_miss()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','trap','logger'));
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
			$trname = $wdata['name'];$trtype = $wdata['type'];$trprefix = '<span class="yellow b">'.$trname.'</span>设置的';
		}elseif($selflag){
			$trname = $name;$trtype = 0;$trprefix = '你自己设置的';
		}else{
			$trname = $trtype = $trprefix = '';
		}
		
		trap();
	}
	
	function get_traplist() 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		return $db->query("SELECT * FROM {$tablepre}maptrap WHERE pls = '$pls' ORDER BY itmk DESC");
	}
	
	function trapcheck()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','itemmain','trap'));
		$real_trap_obbs = calculate_real_trap_obbs();
		$real_trap_obbs = calculate_real_trap_obbs_change($real_trap_obbs);
		
		//好神奇的算法……在下面那个函数先40%判定是不是进入踩雷判断，再在这里用0-39的随机数判断是不是踩陷阱
		//概率跟踩雷上限40%概率是一样的
		$trap_dice=rand(0,$trap_max_obbs-1);
		if($trap_dice < $real_trap_obbs){//踩陷阱判断
			$trapresult = get_traplist();
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
		//echo 'trap ';
		eval(import_module('trap','logger'));
		$trap_dice=rand(0,99);//随机数，开始判断是否踩陷阱
		if($trap_dice < $trap_max_obbs){
			if (trapcheck()) return false; //踩陷阱肯定是没有发现东西
		}			
				
		return $chprocess($schmode);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'trap') 
			if ($d>0)
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}中了{$b}设置的陷阱{$c}，受到了{$d}点伤害！</span></li>";
			else  return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}中了{$b}设置的陷阱{$c}</span></li>";
		if($news == 'trapmiss') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}回避了{$b}设置的陷阱{$c}</span></li>";
		if($news == 'death27') {
			$dname = $typeinfo[$b].' '.$a;
			if(!$e)
				$e0="<span class=\"yellow b\">【{$dname} 什么都没说就死去了】</span><br>\n";
			else  $e0="<span class=\"yellow b\">【{$dname}：“{$e}”】</span><br>\n";
			if($c){
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因触发了<span class=\"yellow b\">$c</span>设置的陷阱<span class=\"red b\">$d</span>被杀死{$e0}</li>";
			} else {
				return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">$a</span>因触发了陷阱<span class=\"red b\">$d</span>被杀死{$e0}</li>";
			}
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if(strpos($k,'TNc')===0 || strpos($k,'TOc')===0) {
			$ret .= '埋设后拥有必杀效果';
		}elseif(strpos($k,'T')===0){
			$theitem = Array('itm' => $n, 'itmk' => $k, 'itme' => $e, 'itms' => $s, 'itmsk' => $sk);
			$trape = get_trap_itme_limit($theitem);
			$ret .= '埋设后效果值为'.$trape;
		}
		return $ret;
	}
	
	function get_trap_itme_limit(&$theitem, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if(!$pa) $pa = &$sdata;
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if(strpos($itmk, 'TNc')===0) return $itme;//奇迹雷不判定这个
		
		$trape0 = round($itme * 0.75); //基础伤害是陷阱效果值的75%
		$trape1 = $pa['lvl'] * 25;//阈值1，等级*25
		$trape2 = $pa['wd'] * 4;//阈值2，爆熟*4
		$trape_add = max($trape1, $trape2);//取上述较大那个
		//也就是，0级0爆熟下阔剑只有600效果，8级或者50爆熟才能满伤
		//0级0爆熟下JJ雷只有1500效果，20级或者125爆熟才能满伤
		
		$trape = min($trape0 + $trape_add, $itme);
		//$trape = $trape0 + $trape_add < $itme ? $trape0 + $trape_add : $itme;
		return $trape;
	}
	
	function trap_use(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		$log .= "设置了陷阱<span class=\"red b\">$itm</span>。<br>";
		
		$trape = get_trap_itme_limit($theitem);
		if($trape >= $itme) {
			$trape = $itme;
		}else{
			$log .= "<span class=\"yellow b\">你笨拙的技术让陷阱的最大伤害限制在了<span class=\"red b\">$trape</span>点。</span><br>";
		}
		
		$log .= "小心，自己也很难发现。<br>";
		
		$trapk = str_replace('TN','TO',$itmk);
		$db->query("INSERT INTO {$tablepre}maptrap (itm, itmk, itme, itms, itmsk, pls) VALUES ('$itm', '$trapk', '$trape', '1', '$pid', '$pls')");
		
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