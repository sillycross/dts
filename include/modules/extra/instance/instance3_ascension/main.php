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
		//如果开局没有连斗，那么2禁前都不会判定连斗
		if ($gametype==13 && \map\get_area_wavenum() <= 2 && $alivenum>0 ){
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
		eval(import_module('sys'));
		if ($gametype==13){
			if($alivenum <= 0){//全灭
				\sys\gameover($atime,'end1');
			}
			elseif (\map\get_area_wavenum() >= 2){//限时2禁
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0 ORDER BY card LIMIT 1");
				$wdata = $db->fetch_array($result);
				//杀杀杀
				$wdata['state'] = 50;
				$wdata['sourceless'] = 1; 
				\player\kill($wdata,$wdata);
				\player\player_save($wdata);
				\sys\gameover($atime,'end1');
			}
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
					$ebp['itm6'] = '闪回的心伤'; $ebp['itmk6'] = 'Z'; $ebp['itme6'] = 1; $ebp['itms6'] = 1;$ebp['itmsk6'] = 'O';
					if ($alvl >= 22)
					{
						$ebp['itm5'] = '难抑的焦虑'; $ebp['itmk5'] = 'Z'; $ebp['itme5'] = 1; $ebp['itms5'] = 1;$ebp['itmsk5'] = 'O';
						if ($alvl >= 26)
						{
							$ebp['arb'] = '躯体化的恐惧'; $ebp['arbk'] = 'DB'; $ebp['arbe'] = 10; $ebp['arbs'] = 9999;$ebp['arbsk'] = 'O';
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
				$card = array(303,304,305)[floor(max($alvl-1,0)/10)];
				return $card;
			}
			else return 303;
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
			if(0 == $pls && !empty($gamevars['crimson_dead']))
			{
				eval(import_module('event'));
				$event_obbs = 50;
			}
		}
		
		return $chprocess();
	}
	
	//击倒红暮时会有提示信息
	function kill(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa,$pd);
		eval(import_module('sys'));
		if(13 == $gametype)
		{
			if(($pd['type'] == 1) && ($pd['hp'] <= 0))
			{
				$gamevars['crimson_dead'] = 1;
				save_gameinfo();
				$option = $roomvars['current_game_option'];
				if(isset($option['lvl']))
				{
					$alvl = (int)$option['lvl'];
					//高进阶保险箱事件的提示
					if ($alvl >= 20)
					{
						eval(import_module('logger'));
						$log .= "<br><span class=\"red b\">红暮的幻影正在消散。一把钥匙掉落在地上，发出沉闷的响声，随后融化在地面上的血污中。<br>不远处传来隆隆的响声，似乎有什么机关开启了。</span><br>";
					}
				}
			}
		}
		return $ret;
	}
	
	function event_core($dice, $dice2)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		$ret = $chprocess($dice, $dice2);
		if(!$ret && 13 == $gametype){
			if(0 == $pls && $gamevars['crimson_dead'])
			{
				if(\skillbase\skill_query(1003,$sdata) && !\skillbase\skill_getvalue(1003,'instance3_flag0',$sdata)) 
				{
					$alvl = !empty($roomvars['current_game_option']['lvl']) ? (int)$roomvars['current_game_option']['lvl'] : 0;
					$log .= '一个高台突兀地悬浮在一片烟尘和血污中。从周围地面破碎的痕迹看来，它不久之前才刚刚冲破伪装，从地面升起。<br><br>
					你踏上高台并仔细检查。<br>';
					if($alvl < 20) {
						$log .= '那是一圈展示着幻境各处情况的大显示屏，以及看起来像是操作面板一样的仪器，风格在这个时代可以说是相当复古。你猜测这是幻境的总控制台，虽然它们并不接受你的命令。<br>';
					}else{
						$log .= '几乎所有仪器和操作面板都已经遭到了严重的腐蚀破坏。看起来这并不是值得久留之地。<br>';
					}
					$log .= '你还发现了一个保险箱。在暴力破拆之后，你找到了一些聊胜于无的资料。<br><br>';//待补充
					
					$doc = Array(
						'『林氏软件的现任董事长……或者说，假冒林无月女儿的人，现在依然在静坐。除了往公司里硬塞了一百来个鱼腩，看不出有什么动作。<br>但就凭查不清底细这一点，她就不是善茬。必须警惕。』',
						'『我委托冰炎去分析时空特使的装备，以及……那个东西了。结果还没有出来。林无月曾经暗示过一些事，但我直到现在才想清楚应该如何去验证，以及如果验证了该怎么做。<br>我真希望是她杞人忧天，而不是我后知后觉。』',
						'『不少人对蓝凝的「不务正业」颇有微词。确实一开始连我也很惊讶，但我觉得未尝不能将计就计。不只是为了狡兔三窟，我有一种感觉，有些东西只有她才能做到……』',
					);
					
					$log .= array_randompick($doc).'<br><br>';
					
					if ($alvl >= 20)
					{
						$log .= '除此之外，你还发现了<span class="yellow b">76573</span>元的纸币。<br>';
						\event\event_get_money(76573);
					}
					
					\skillbase\skill_setvalue(1003,'instance3_flag0','1',$sdata);
					$ret = 1;
				}else{
					$log .= '一个高台突兀地悬浮在一片烟尘和血污中。不过你确信刚才已经仔细检查过了。<br>';
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
		elseif ($alvl <= 10) return 200*$alvl;
		elseif ($alvl < 20) return 500*$alvl-3000;
		elseif ($alvl == 20) return 12000;
		elseif ($alvl < 25) return 1000*$alvl-5000;
		else return 2000*$alvl-30000;
	}
	
	function post_winnercheck_events($winner)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($winner);
		eval(import_module('sys'));
		if ($winmode == 7 && $gametype == 13)
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