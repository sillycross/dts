<?php

namespace instance10
{
	function init() {
		eval(import_module('skillbase'));
		$valid_skills[20] = array(181,951,952,960);
	}
	
	//公路模式自动选择肉鸽来客
	function get_enter_battlefield_card($card){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$card = $chprocess($card);
		if (20 == $gametype){
			$card=1001;
		}
		return $card;
	}
	
	//公路模式自动选择肉鸽来客，禁止其他卡片
	function card_validate_get_forbidden_cards($card_disabledlist, $card_ownlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$card_disabledlist = $chprocess($card_disabledlist, $card_ownlist);
		if (20 == $gametype)
		{
			foreach($card_ownlist as $cv){
				if(1001 != $cv) $card_disabledlist[$cv][]='e3';
			}
		}
		return $card_disabledlist;
	}
	
	//公路模式选卡界面特殊显示
	function card_validate_display($cardChosen, $card_ownlist, $packlist, $hideDisableButton){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase'));
		list($cardChosen, $card_ownlist, $packlist, $hideDisableButton) = $chprocess($cardChosen, $card_ownlist, $packlist, $hideDisableButton);	
		if (20 == $gametype)
		{
			$cardChosen = 1001;//自动选择肉鸽来客
			$card_ownlist[] = 1001;
			$packlist[] = $cards[1001]['pack'] = 'Divine Random';
			$hideDisableButton = 0;
		}
		return array($cardChosen, $card_ownlist, $packlist, $hideDisableButton);
	}
	
	//公路模式入场道具
	function init_enter_battlefield_items($ebp){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ebp = $chprocess($ebp);
		eval(import_module('sys'));
		if (20 == $gametype){
			$ebp['itm5'] = '全恢复药剂'; $ebp['itmk5'] = 'Ca'; $ebp['itme5'] = 1; $ebp['itms5'] = 3;$ebp['itmsk5'] = '';
		}
		return $ebp;
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance10'));
		if (20 == $gametype){
			return $npcinfo_instance10;
		}else return $chprocess();
	}
	
	function get_shopconfig(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','instance10'));
		if (20 == $gametype){
			$file = __DIR__.'/config/shopitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_itemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (20 == $gametype){
			$file = __DIR__.'/config/mapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_startingitemfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (20 == $gametype){
			$file = __DIR__.'/config/stitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_startingwepfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (20 == $gametype){
			$file = __DIR__.'/config/stwep.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	function get_trapfilecont(){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if (20 == $gametype){
			$file = __DIR__.'/config/trapitem.config.php';
			$l = openfile($file);
			return $l;
		}else return $chprocess();
	}
	
	//不会连斗
	function checkcombo($time){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','gameflow_combo'));
		if(20 == $gametype) return;
		$chprocess($time);
	}
	
	//开局天气初始化；开局时，只有随机8个地点不为禁区
	function rs_game($xmode = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($xmode);
		eval(import_module('sys'));
		if ((20 == $gametype)&&($xmode & 2)) 
		{
			$weather = 1;
			//添加禁区
			eval(import_module('map'));
			$plsnum = sizeof($plsinfo) - 1;
			$areanum += $plsnum - 8;
		}
	}
	
	//开局2小时后禁区
	function rs_areatime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(20 == $gametype){
			return $starttime + 60*120;//1禁恒为120分钟
		}
		return $chprocess();
	}
	
	//禁区时结束游戏
	function check_addarea_gameover($atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map'));
		if (20 == $gametype){
			if($alivenum <= 0){
				\sys\gameover($atime,'end1');
				return;
			}
			if ($areanum>=$areaadd){//限时1禁
				//胜利条件待修改
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE hp>0 AND type=0 ORDER BY card LIMIT 1");
				$wdata = $db->fetch_array($result);
				$winner = $wdata;
				\sys\gameover($atime,'end8',$winner);
				return;
			}
			\sys\rs_game(16+32);
			return;
		}
		$chprocess($atime);
	}
	
	//商店除无月外，在随机4个地点生成
	function check_in_shop_area($p)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (20 == $gametype)
		{
			if (!isset($gamevars['instance10_shops']))
			{
				$gamevars['instance10_shops'] = array_randompick(range(1, 33), 4);
				$gamevars['instance10_shops'][] = 0;
			}
			save_gameinfo();
			return in_array($p, $gamevars['instance10_shops']);
		}
		else return $chprocess($p);
	}
	
	//合成产物的效果、耐久、属性可能发生变化
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (20 == $gametype)
		{
			if (in_array($itmk0[0], array('W','D','M','V','H','P')))
			{
				$itme0 = max(round((80 + rand(0,40))/100 * $itme0), 1);
				if ($itms0 != $nosta) $itms0 = max(round((80 + rand(0,40))/100 * $itms0), 1);
				if ($itmk0[0] == 'W')
				{
					$dice = rand(0,99);
					if ($dice < 3)
					{
						$tmpsk = array_randompick(array('f','k','t','d','r','n','y'));
						if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
					}
					elseif ($dice < 30)
					{
						$tmpsk = array_randompick(array('u','e','i','w','p','N','H','z'));
						if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
					}
				}
				elseif ($itmk0[0] == 'D')
				{
					$dice = rand(0,99);
					if ($dice < 3)
					{
						$tmpsk = array_randompick(array('B','b','Z','h'));
						if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
					}
					elseif ($dice < 30)
					{
						$tmpsk = array_randompick(array('A','a','P','K','G','C','D','F','R','q','U','I','E','W','H','M','m','z'));
						if (!\itemmain\check_in_itmsk($tmpsk, $itmsk0)) $itmsk0 .= $tmpsk;
					}
				}
			}
		}
		$chprocess();
	}
	
	//记录吃技能核心次数
	function use_skcore_success(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (20 == $gametype)
		{
			if (\skillbase\skill_query(951,$pa))
			{
				$sc_count = (int)\skillbase\skill_getvalue(951,'sc_count',$pa);
				\skillbase\skill_setvalue(951,'sc_count',$sc_count+1,$pa);
			}
		}
		$chprocess($pa);
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];	
		if (20 == $gametype)
		{
			//无法使用移动PC
			if (strpos($itmk, 'EE') === 0)
			{
				$log .= "你使用了{$itm}，却发现没有可以连接上的网络。怎么会这样？<br>";
				return;
			}
			//每个人只能吃7个技能核心
			elseif (strpos($itmk, 'SC') === 0)
			{
				$sc_count = (int)\skillbase\skill_getvalue(951,'sc_count',$sdata);
				if ($sc_count >= 7)
				{
					$log .= "<span class=\"yellow b\">你已经使用过7个技能核心，无法再使用了。</span><br>";
					return;
				}
			}
		}
		$chprocess($theitem);
	}
	
	//进场后随机获得三个1级任务
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('sys'));
		if (20 == $gametype)
		{
			\skill960\get_rand_task($pa, 1, 3);
		}
	}
	
	//失去任务时，根据调查度获得新的任务
	function remove_task(&$pa, $taskid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $taskid);
		eval(import_module('sys'));
		if (20 == $gametype)
		{
			\skill960\get_rand_task($pa, get_newtask_rank($pa), 1);
		}
	}
	
	//获取任务奖励约等于完成任务，在这里加禁区解锁的判定
	function get_task_reward(&$pa, $taskid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa, $taskid);
		eval(import_module('sys'));
		if (20 == $gametype)
		{
			if (!isset($gamevars['instance10_topinv'])) $gamevars['instance10_topinv'] = 0;
			$invscore = (int)\skillbase\skill_getvalue(960,'invscore',$pa);
			if ($invscore > $gamevars['instance10_topinv'])
			{
				//最高调查度每加10，减少4个禁区
				$map_unlock = floor($invscore/10) - floor($gamevars['instance10_topinv']/10);
				if ($map_unlock > 0)
				{
					eval(import_module('map','logger'));
					$log .= "<span class=\"yellow b\">你发现了新的地点！</span><br>";
					$areanum -= 4 * $map_unlock;
					$areanum = max($areanum, 0);
				}
				$gamevars['instance10_topinv'] = $invscore;
			}
			save_gameinfo();
		}
	}
	
	//由调查度决定新任务等级
	function get_newtask_rank(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$invscore = (int)\skillbase\skill_getvalue(960,'invscore',$pa);
		//以后可能需要细调，不写成算式了
		if ($invscore < 10) return 1;
		elseif ($invscore < 20) return 2;
		elseif ($invscore < 30) return 3;
		elseif ($invscore < 40) return 4;
		elseif ($invscore < 50) return 5;
		elseif ($invscore < 60) return 6;
		else return 7;
	}
	
}

?>