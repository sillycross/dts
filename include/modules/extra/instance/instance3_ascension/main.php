<?php

namespace instance3
{		
	function init() {}
	
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (13 == $gametype){
			$file = __DIR__.'/config/shopitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (13 == $gametype){
			$file = __DIR__.'/config/mapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance3'));
		if (13 == $gametype){
			$ret = $npcinfo_instance3;
			//根据不同试炼等级，修改NPC情况
			$alvl = (int)$roomvars['current_game_option']['lvl'];
			if($alvl > 10) {
				$ret[1]['sub'][0]['name'] = '红暮 DUMMY';
			}
			return $ret;
		}else return $chprocess();
	}
	
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_combo'));
		if ($gametype==13 && $areanum < $areaadd*2 && $alivenum>0 ){
			return;
		}
		$chprocess($time);
	}
	
	function rs_game($xmode = 0) 	//开局天气初始化
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys'));
		if (($gametype==13)&&($xmode & 2)) 
		{
			$weather = 1;
		}
	}
	
	function rs_areatime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(13==$gametype){
			return $starttime + 60*40;//1禁恒为40分钟
		}
		return $chprocess();
	}
	
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if ($gametype==13){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');
				return;
			}
			if ($areanum>=($areaadd*2)){//限时2禁
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0 ORDER BY card LIMIT 1");
				$wdata = $db->fetch_array($result);
				//杀杀杀
				$wdata['state'] = 50;
				$wdata['sourceless'] = 1; 
				\player\kill($wdata,$wdata);
				\player\player_save($wdata);
				\sys\gameover($atime,'end1');
				return;
			}
			\sys\rs_game(16+32);
			return;
		}
		$chprocess($atime);
	}
	
	//无法最后幸存
	function gameover($time = 0, $gmode = '', $winname = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ((13==$gametype) && ((($gmode=='') && ($alivenum==1)) || ($gmode=='end2'))) return;
		$chprocess($time,$gmode,$winname);
	}
	
	function init_enter_battlefield_items($ebp)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ebp = $chprocess($ebp);
		eval(import_module('sys'));
		if(13==$gametype){
			$ebp['art'] = 'Untainted Glory'; $ebp['artk'] = 'A'; $ebp['arte'] = 1; $ebp['arts'] = 1;$ebp['artsk'] = 'Z';
			$ebp['itm4'] = '生命探测器'; $ebp['itmk4'] = 'ER'; $ebp['itme4'] = 5; $ebp['itms4'] = 1;$ebp['itmsk4'] = '';
			$ebp['itm5'] = '全恢复药剂'; $ebp['itmk5'] = 'Ca'; $ebp['itme5'] = 1; $ebp['itms5'] = 3;$ebp['itmsk5'] = '';
			$option = $roomvars['current_game_option'];
			if(isset($option['lvl']))
			{
				$alvl = (int)$option['lvl'];
				if ($alvl >= 14)
				{
					$ebp['itm5'] = '沉重的枷锁'; $ebp['itmk5'] = 'Z'; $ebp['itme5'] = 1; $ebp['itms5'] = 1;$ebp['itmsk5'] = 'O';
					if ($alvl >= 22)
					{
						$ebp['itm6'] = '沉重的枷锁'; $ebp['itmk6'] = 'Z'; $ebp['itme6'] = 1; $ebp['itms6'] = 1;$ebp['itmsk6'] = 'O';
						if ($alvl >= 26)
						{
							$ebp['arb'] = '受缚之躯'; $ebp['arbk'] = 'DB'; $ebp['arbe'] = 10; $ebp['arbs'] = 9999;$ebp['arbsk'] = 'O';
						}
					}
				}
			}
		}
		return $ebp;
	}
	
	//开局卡片设置
	function get_enter_battlefield_card($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(13==$gametype){
			$option = $roomvars['current_game_option'];
			if(isset($option['lvl']))
			{
				$alvl = (int)$option['lvl'];
				$card = array(90,282,129)[floor(max($alvl-1,0)/10)];
				return $card;
			}
			else return 90;
		}
		return $chprocess($card);
	}
	
	//开局技能设置
	function enter_battlefield_cardproc($ebp, $card)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$ret = $chprocess($ebp, $card);
		if(13==$gametype){
			$option = $roomvars['current_game_option'];
			if(isset($option['lvl']))
			{
				$alvl = (int)$option['lvl'];
				$ret = instance3_add_skills($ret, $alvl);
				if ($alvl >= 4)
				{
					$ret[0]['msp'] -= 200;
					$ret[0]['sp'] -= 200;
					if ($alvl >= 8)
					{
						$ret[0]['mhp'] -= 100;
						$ret[0]['hp'] -= 100;
					}
					elseif ($alvl >= 5)
					{
						$ret[0]['mhp'] -= 50;
						$ret[0]['hp'] -= 50;
					}
				}
			}
		}
		return $ret;
	}
	
	function instance3_add_skills($proc, $alvl)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//厌食
		if ($alvl >= 9) $proc[1][474] = '0';
		//神弃
		if ($alvl >= 26) $proc[1][901] = '5';
		elseif ($alvl >= 13) $proc[1][901] = '4';
		elseif ($alvl >= 12) $proc[1][901] = '3';
		elseif ($alvl >= 7) $proc[1][901] = '2';
		elseif ($alvl >= 1) $proc[1][901] = '1';
		//恶敌
		if ($alvl >= 25) $proc[1][902] = '4';
		elseif ($alvl >= 15) $proc[1][902] = '3';
		elseif ($alvl >= 3) $proc[1][902] = '2';
		elseif ($alvl >= 2) $proc[1][902] = '1';
		//厄运
		if ($alvl >= 30) $proc[1][903] = '5';
		elseif ($alvl >= 29) $proc[1][903] = '4';
		elseif ($alvl >= 28) $proc[1][903] = '3';
		elseif ($alvl >= 27) $proc[1][903] = '2';
		elseif ($alvl >= 6) $proc[1][903] = '1';
		//脆弱
		if ($alvl >= 21) $proc[1][904] = '3';
		elseif ($alvl >= 19) $proc[1][904] = '2';
		elseif ($alvl >= 10) $proc[1][904] = '1';
		//蔽目
		if ($alvl >= 24) $proc[1][905] = '3';
		elseif ($alvl >= 17) $proc[1][905] = '2';
		elseif ($alvl >= 11) $proc[1][905] = '1';
		//蚀骨
		if ($alvl >= 18) $proc[1][906] = '2';
		elseif ($alvl >= 16) $proc[1][906] = '1';
		//干扰
		if ($alvl >= 23) $proc[1][907] = '0';
		return $proc;
	}
	
	//进场记录进阶等级
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('sys'));
		if(13==$gametype){
			$option = $roomvars['current_game_option'];
			if(isset($option['lvl']))
			{
				$alvl = (int)$option['lvl'];
				if (\skillbase\skill_query(1003,$pa)) \skillbase\skill_setvalue(1003,'instance3_lvl',$alvl,$pa);
			}
		}
	}
	
	function itemuse(&$theitem) //使用试炼解除钥匙立刻结束游戏
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) {
			if ($itm == '试炼解除钥匙') 
			{
				if(13==$gametype){
					addnews ( $now, 'itemuse', $name, $itm );
					$log .= '<span class="red b">钥匙中涌出无边的黑暗，将你的身体和灵魂吞噬了。</span><br>';
					$state = 50;
					$sdata['sourceless'] = 1; 
					\player\kill($sdata,$sdata);
					return;
				}else{
					$log .= '这玩意究竟是怎么冒出来的呢？<br>';
					$mode = 'command';
					return;
				}
			}
			elseif ($itm == '游戏解除钥匙' && 13==$gametype) 
			{
				$log .= '<span class="ltcrimson">『忘了我说的话了？』</span><br><br><span class="yellow b">看起来这把钥匙没有任何功能。</span><br>';
				$mode = 'command';
				return;
			}
			elseif ($itm == '奇怪的按钮' && 13==$gametype) 
			{
				$log .= '<span class="ltcrimson">『忘了我说的话了？』</span><br><br><span class="yellow b">看起来这个按钮没有任何功能。</span><br>';
				$mode = 'command';
				return;
			}
		}
		$chprocess($theitem);
	}
	
	//在无月没有NPC后会发现一个保险箱
	function search_area()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(13 == $gametype)
		{
			if(0 == $pls)
			{
				$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='0' AND type>0 AND hp>0");
				if(!$db->num_rows($result))
				{
					eval(import_module('event'));
					$event_obbs = 100;
				}
			}
		}
		
		return $chprocess();
	}
	
	function event()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		$ret = $chprocess();
		if(13 == $gametype){
			if((0 == $pls) && \skillbase\skill_query(1003,$sdata) && !\skillbase\skill_getvalue(1003,'instance3_flag0',$sdata))
			{
				$result = $db->query("SELECT pid FROM {$tablepre}players WHERE pls='0' AND type>0 AND hp>0");
				if(!$db->num_rows($result))
				{
					$log .= '你发现了一个破旧的保险箱，其中有一些废弃的资料。<br>';//待补充
					$option = $roomvars['current_game_option'];
					if(isset($option['lvl'])) 
					{
						$alvl = (int)$option['lvl'];
						//高进阶开局连斗，补一点钱
						if ($alvl >= 20)
						{
							$log .= '除此之外，你还发现了大约<span class="yellow b">76573</span>元的纸币。<br>';
							$money += 76573;
						}
					}
					\skillbase\skill_setvalue(1003,'instance3_flag0','1',$sdata);
					$ret = 1;
				}
			}
		}
		
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'death50') 
		{
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}被黑暗吞噬了</span></li>";
		}
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
	function instance3_calc_qiegao_prize($alvl)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($alvl == 0) return 100;
		elseif ($alvl <= 10) return 400*$alvl;
		elseif ($alvl < 20) return 1000*$alvl-6000;
		elseif ($alvl < 30) return 1000*$alvl-2000;
		else return 30000;
	}
	
	function post_winnercheck_events($winner)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($winner);
		eval(import_module('sys'));
		if ($winmode == 7)
		{
			$pa = \player\fetch_playerdata($winner);
			$alvl = (int)\skillbase\skill_getvalue(1003,'instance3_lvl',$pa);
			$qiegao_prize = instance3_calc_qiegao_prize($alvl);
			if ($qiegao_prize)
			{
				include_once './include/messages.func.php';
				message_create(
					$pa['name'],
					'试炼模式奖励',
					'祝贺你在房间第'.$gamenum.'局试炼模式获得了奖励！<br>',
					'getqiegao_'.$qiegao_prize
				);
			}
		}
	}
	
	//高层数NPC名字的失真化
	function init_battle($ismeet = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','metman'));
		$chprocess($ismeet);
		if(13==$gametype && !empty($roomvars['current_game_option']['lvl']) && defined('MOD_ENDING')) {
			$alvl = $roomvars['current_game_option']['lvl'];
			if($alvl > 10) {//第11层开始有失真
				$dice = rand(0,99);
				$glitch_scale = 0;//失真比例，0为没有
				if($alvl <= 20 && $dice < $alvl) {//20层之前随机出现失真，偷懒直接用alvl值当阈值
					$glitch_scale = $alvl;
				}elseif($alvl > 20 && $dice < $alvl * 2) {//20层之后失真概率翻倍
					$glitch_scale = $alvl * 2;
				}
				if(!empty($glitch_scale)) {
					$tdata['name'] = \ending\ending_psyche_attack_txt_parse($tdata['name'] , $glitch_scale);
				}
			}
		}
	}
	
	//高层数NPC台词的失真化
	function npcchat_get_chatlog($chattag,$sid,$nchat)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($chattag,$sid,$nchat);
		eval(import_module('sys'));
		if(13==$gametype && !empty($roomvars['current_game_option']['lvl']) && defined('MOD_ENDING')) {
			$alvl = $roomvars['current_game_option']['lvl'];
			if($alvl > 10) {//第11层开始有失真
				$dice = rand(0,99);
				$glitch_scale = 0;//失真比例，0为没有
				if($alvl <= 20 && $dice <= $alvl) {//20层之前随机出现失真，偷懒直接用alvl值当阈值
					$glitch_scale = $alvl;
				}elseif($alvl > 20 && $dice <= $alvl * 2) {//20层之后失真概率翻倍
					$glitch_scale = $alvl * 2;
				}
				if(!empty($glitch_scale)) {
					$ret = \ending\ending_psyche_attack_txt_parse($ret, $glitch_scale);
				}
			}
		}
		return $ret;
	}
}

?>