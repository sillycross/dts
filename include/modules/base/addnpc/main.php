<?php

namespace addnpc
{
	function init() {}
		
	function addnpc($xtype,$xsub,$num,$time = 0) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','map','logger','addnpc','lvlctl','skillbase'));
		$time = $time == 0 ? $now : $time;
		$plsnum = sizeof($plsinfo);
		$npc=array_merge($npcinit,$anpcinfo[$xtype]);
		//$npcwordlist = Array();
		$summon_pid = -1;
		if(!$npc){
			return;
		} else {
			for($i=0;$i< $num;$i++){
				$npc = array_merge($npc,$npc['sub'][$xsub]);		
				$npc['type'] = $xtype;
				$npc['endtime'] = $time;
				$npc['exp'] = round(($npc['lvl']*2+1)*$baseexp);
				$npc['sNo'] = $i;
				$npc['hp'] = $npc['mhp'];
				$npc['sp'] = $npc['msp'];
				$spid = uniqid('',true);
				$npc['pass']=$spid;
				if(!isset($npc['state'])){$npc['state'] = 0;}
				$npc['wp'] = $npc['wk'] = $npc['wg'] = $npc['wc'] = $npc['wd'] = $npc['wf'] = $npc['skill'];
				if($npc['gd'] == 'r'){$npc['gd'] = rand(0,1) ? 'm':'f';}
				if($npc['pls'] == 99){
					$areaarr = array_slice($arealist,$areanum+1);
					if(empty($areaarr)){
						$npc['pls'] = 0;
					}else{
						shuffle($areaarr);
						$npc['pls'] = $areaarr[0];
					}
					//$npc['pls'] = rand(1,$plsnum-1);
				}			
				$db->query("INSERT INTO {$tablepre}players (name,pass,type,endtime,gd,sNo,icon,club,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,itm6,itmk6,itme6,itms6,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5,itmsk6) VALUES ('".$npc['name']."','".$npc['pass']."','".$npc['type']."','".$npc['endtime']."','".$npc['gd']."','".$npc['sNo']."','".$npc['icon']."','".$npc['club']."','".$npc['hp']."','".$npc['mhp']."','".$npc['sp']."','".$npc['msp']."','".$npc['att']."','".$npc['def']."','".$npc['pls']."','".$npc['lvl']."','".$npc['exp']."','".$npc['money']."','".$npc['bid']."','".$npc['inf']."','".$npc['rage']."','".$npc['pose']."','".$npc['tactic']."','".$npc['killnum']."','".$npc['state']."','".$npc['wp']."','".$npc['wk']."','".$npc['wg']."','".$npc['wc']."','".$npc['wd']."','".$npc['wf']."','".$npc['teamID']."','".$npc['teamPass']."','".$npc['wep']."','".$npc['wepk']."','".$npc['wepe']."','".$npc['weps']."','".$npc['arb']."','".$npc['arbk']."','".$npc['arbe']."','".$npc['arbs']."','".$npc['arh']."','".$npc['arhk']."','".$npc['arhe']."','".$npc['arhs']."','".$npc['ara']."','".$npc['arak']."','".$npc['arae']."','".$npc['aras']."','".$npc['arf']."','".$npc['arfk']."','".$npc['arfe']."','".$npc['arfs']."','".$npc['art']."','".$npc['artk']."','".$npc['arte']."','".$npc['arts']."','".$npc['itm0']."','".$npc['itmk0']."','".$npc['itme0']."','".$npc['itms0']."','".$npc['itm1']."','".$npc['itmk1']."','".$npc['itme1']."','".$npc['itms1']."','".$npc['itm2']."','".$npc['itmk2']."','".$npc['itme2']."','".$npc['itms2']."','".$npc['itm3']."','".$npc['itmk3']."','".$npc['itme3']."','".$npc['itms3']."','".$npc['itm4']."','".$npc['itmk4']."','".$npc['itme4']."','".$npc['itms4']."','".$npc['itm5']."','".$npc['itmk5']."','".$npc['itme5']."','".$npc['itms5']."','".$npc['itm6']."','".$npc['itmk6']."','".$npc['itme6']."','".$npc['itms6']."','".$npc['wepsk']."','".$npc['arbsk']."','".$npc['arhsk']."','".$npc['arask']."','".$npc['arfsk']."','".$npc['artsk']."','".$npc['itmsk0']."','".$npc['itmsk1']."','".$npc['itmsk2']."','".$npc['itmsk3']."','".$npc['itmsk4']."','".$npc['itmsk5']."','".$npc['itmsk6']."')");
				$newsname=$typeinfo[$xtype].' '.$npc['name'];
				//$npcwordlist[] = $typeinfo[$type].' '.$npc['name'];
				addnews($now, 'addnpc', $newsname);
				$result = $db->query("SELECT pid FROM {$tablepre}players where pass='$spid' AND type>0");
				if ($db->num_rows($result))
				{
					$zz = $db->fetch_array($result);
					$summon_pid = $zz['pid'];
				}
				else
				{
					//出BUG了
					$summon_pid = -1;
				}
				if (is_array($npc['skills'])){
					$qry="SELECT * FROM {$tablepre}players WHERE type>'0' ORDER BY pid DESC LIMIT 1";
					$result=$db->query($qry);
					$pr=$db->fetch_array($result);
					$pp=\player\fetch_playerdata_by_pid($pr['pid']);
					foreach ($npc['skills'] as $key=>$value){
						if (defined('MOD_SKILL'.$key)){
							\skillbase\skill_acquire($key,$pp);
							if ($value>0){
								\skillbase\skill_setvalue($key,'lvl',$value,$pp);
							}
						}	
					}
					\player\player_save($pp);
				}
			}
		}
		/*
		if($num > $npc['num']){
		//if($num > 1){
			$newsname=$typeinfo[$xtype];
			addnews($time, 'addnpcs', $newsname,$i);
		}else{
	//		for($i=0;$i< $num;$i++){
	//			addnews($time, 'addnpc', $npcwordlist[$i]);
	//		}
			//$newsname=$typeinfo[$type].' '.$npc['name'];
			//addnews($time, 'addnpc', $newsname);
		}
		*/
		return $summon_pid;
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
				if (in_array($gametype,Array(10,11,12,13)))
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
				if (in_array($gametype,Array(10,11,12,13)))
				{
					$log.="你使用了{$itm}，但是什么也没有发生（当前游戏模式下不允许PVE）。<br>";
					return;
				}
				$rp = 0;
				$log .= '在你唱出那单一的旋律的霎那，<br>整个虚拟世界起了翻天覆地的变化……<br>';
				addnpc ( 4, 0,1);
				eval(import_module('weather'));
				$log .= '世界响应着这旋律，产生了异变……<br>';
				\weather\wthchange( $itm,$itmsk);
				addnews ($now , 'thiphase', $name);
				$hack = 1;
				$log .= '因为破灭之歌的作用，全部锁定被打破了！<br>';
				\map\movehtm();
				addnews($now,'hack2',$name);
				save_gameinfo();
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				return;
			} elseif ($itm == '黑色碎片') {
				if (in_array($gametype,Array(10,11,12,13)))
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
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)	//烧伤发作死亡新闻
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'addnpc') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}乱入战场！</span><br>\n";
		if($news == 'addnpcs') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$b}名{$a}加入战斗！</span><br>\n";
		if($news == 'secphase') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了挑战者之证，让3名幻影执行官加入了战场！打倒他们去获得ID卡来解除游戏吧！</span><br>\n";
		if($news == 'thiphase') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}触发了对虚拟现实的救济！虚拟意识已经在■■■■活性化！</span><br>\n";
		if($news == 'dfphase') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}使用了黑色碎片，让1名未知存在加入了战场！打倒她去获得ID卡来解除游戏吧！</span><br>\n";
		if($news == 'hack2') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"yellow\">{$a}启动了救济程序，全部禁区解除！</span><br>\n";
		if($news == 'dfsecphase') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}闯了大祸，打破了Dark Force的封印！</span><br>\n";
		
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
