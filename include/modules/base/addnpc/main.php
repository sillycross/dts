<?php

namespace addnpc
{
	function init() {}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if(strpos($k,'Y')===0 || strpos($k,'Z')===0){
			if ($n == '挑战者之印'){
				$ret .= '召唤3名「幻影执行官」进入战场，击败他们能缴获「社员的ID卡」以合成「游戏解除钥匙」';
			}elseif ($n == '破灭之诗') {
				$ret .= '暂时解除禁区，天气变为极光，同时让拟似意识在雏菊之丘登场，通往『幻境解离』的必经之路';
			}elseif ($n == '黑色碎片') {
				$ret .= '召唤「Dark Force幼体」进入战场，把她彻底击倒能缴获「游戏解除钥匙」';
			}
		}
		return $ret;
	}
		
	function addnpc($xtype,$xsub,$num,$newspls = 0,$time = 0) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger','addnpc','lvlctl','skillbase'));
		$time = $time == 0 ? $now : $time;
		$anpcs = get_addnpclist();
		$npc=array_merge($npcinit,$anpcs[$xtype]);
		if(!$npc){
			return;
		} else {
			$pls_available = \map\get_safe_plslist();
			$summon_ids = array();
			for($i=0;$i< $num;$i++){
				$npc = array_merge($npc,$npc['sub'][$xsub]);
				$npc['type'] = $xtype;
				$npc['sNo'] = $i;
				$npc = \npc\init_npcdata($npc,$pls_available);	
				$npc=\player\player_format_with_db_structure($npc);
				$db->array_insert("{$tablepre}players", $npc);
				$summon_ids[] = $db->insert_id();
				$newsname=$typeinfo[$xtype].' '.$npc['name'];
				if($newspls) addnews($now, 'addnpc_pls', $newsname, '', $npc['pls']);
				else addnews($now, 'addnpc', $newsname);
			}
		}
		return $summon_ids;
	}
	
	function get_addnpclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','addnpc'));
		return $anpcinfo;
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) 
		{
			if ($itm == '挑战者之印') {
				if (in_array($gametype,$pve_ignore_mode))
				{
					$log.="你使用了{$itm}，但是什么也没有发生（当前游戏模式下不允许PVE）。<br>";
					return;
				}
				$log .= '你已经呼唤了幻影执行官，现在寻找并击败他们，<br>并且搜寻他们的ID卡吧！<br>';
				addnpc ( 7, 0,1);
				addnpc ( 7, 1,1);
				addnpc ( 7, 2,1);
				addnews ($now , 'secphase', $name);
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				return;
			} elseif ($itm == '破灭之诗') {
				if (in_array($gametype,$pve_ignore_mode))
				{
					$log.="你使用了{$itm}，但是什么也没有发生（当前游戏模式下不允许PVE）。<br>";
					return;
				}
				$rp = 0;
				$log .= '在你唱出那单一的旋律的霎那，<br>整个虚拟世界起了翻天覆地的变化……<br>';
				addnpc ( 4, 0,1);
				eval(import_module('weather'));
				$log .= '世界响应着这旋律，产生了异变……<br>';
				\weather\wthchange( $itm, 95);//现在破灭之诗使用后天气一定是极光
				addnews ($now , 'thiphase', $name);
				$hack = 1;
				$log .= '因为破灭之歌的作用，全部锁定被打破了！<br>';
				//\map\movehtm();
				addnews($now,'hackb',$name);
				\sys\systemputchat($now,'hack');
				$gamevars['forbid_antiAFK'] = 1;//现在破灭之诗使用后阻止反挂机
				save_gameinfo();
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				return;
			} elseif ($itm == '黑色碎片') {
				if (in_array($gametype,$pve_ignore_mode))
				{
					$log.="你使用了{$itm}，但是什么也没有发生（当前游戏模式下不允许PVE）。<br>";
					return;
				}
				$log .= '你已经呼唤了一个未知的存在，现在寻找并击败她，<br>并且搜寻她的游戏解除钥匙吧！<br>';
				addnews ($now , 'dfphase', $name);
				addnpc ( 12, 0,1);
				
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				return;
			}
		}
		$chprocess($theitem);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	//烧伤发作死亡新闻
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'addnpc') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}乱入战场！</span></li>";
		elseif($news == 'addnpcs') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$b}名{$a}加入战斗！</span></li>";
		elseif($news == 'addnpc_pls') {
			eval(import_module('map'));
			$plsword = $plsinfo[$c];//注意是c
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}乱入了{$plsword}！</span></li>";
		}elseif($news == 'addnpcs_pls') {
			eval(import_module('map'));
			$plsword = $plsinfo[$c];
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$b}名{$a}乱入了{$plsword}！</span></li>";
		}elseif($news == 'secphase') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}使用了挑战者之证，让3名幻影执行官加入了战场！打倒他们去获得ID卡来解除游戏吧！</span></li>";
		elseif($news == 'thiphase') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}触发了对虚拟现实的救济！虚拟意识已经在■■■■活性化！</span></li>";
		elseif($news == 'dfphase') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}使用了黑色碎片，让1名未知存在加入了战场！打倒她去获得ID卡来解除游戏吧！</span></li>";
		elseif($news == 'hackb') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}启动了救济程序，暂时解除了全部禁区！</span></li>";
		elseif($news == 'dfsecphase') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}闯了大祸，打破了Dark Force的封印！</span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>