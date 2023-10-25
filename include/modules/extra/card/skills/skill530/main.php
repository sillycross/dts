<?php

namespace skill530
{
	function init() 
	{
		define('MOD_SKILL530_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[530] = '降维';
	}
	
	function acquire530(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost530(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked530(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['wepe'] >= 7000;
	}
	
	//实现：先处理自身数据（抹掉装备道具金钱和部分技能），再在对应房间创造一个玩家，然后自杀，最后发送一个页面跳转请求
	function process_530(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		//前置条件
		if (!\skillbase\skill_query(530)) 
		{
			$log.='你没有这个技能。';
			$mode = 'command';
			$command = '';
			return;
		}elseif(!check_unlocked530($sdata)){
			$log.='<span class="red b">弱小，无知！还没有傲慢的资格。</span><br>';
			$mode = 'command';
			$command = '';
			return;
		}elseif(!empty($gamevars['skill530_jumped'])){
			$log.='<span class="red b">这里已经被维度跳跃产生的「慢雾」笼罩，不能再跳跃了。</span><br>';
			$mode = 'command';
			$command = '';
			return;
		}
		
		include_once './include/roommng/room.func.php';
		$starttime_threshold = $now - 900;//不可跳到开启时间小于15分钟的房间
		//第一步，获取适用的房间：房间开启且游戏已开放，但不判定连斗（就算连斗也能空降过去），房间类别合适，且不存在同名玩家
		$result = $db->query("SELECT groomid,gametype FROM {$gtablepre}game WHERE groomid != '$room_id' AND groomstatus>=40 AND gamestate >= 20 AND starttime <= '$starttime_threshold'");
		$gamepool = Array();
		while($rarr = $db->fetch_array($result)){
			if(!in_array($rarr['gametype'], Array(1, 15, 17))) {//不会空降到除错、伐木、教程房
				
				//判定一下该房间有没有同名玩家
				$tmp_prefix = room_id2prefix($rarr['groomid']);
				$tmp_tablepre = room_get_tablepre($tmp_prefix);
				//file_put_contents('c.txt', $rarr['groomid'].'--'.$tmp_prefix.'--'.$tmp_tablepre."\r\n\r\n", FILE_APPEND);
				$r2 = $db->query("SELECT pid FROM {$tmp_tablepre}players WHERE name='$name' AND type=0");
				if(!$db->num_rows($r2)){
					$gamepool[] = $rarr['groomid'];
				}
			}
		}
		if(empty($gamepool)) {
			$log.='<span class="red b">当前没有合适的维度可供跳跃！</span><br>';
			$mode = 'command';
			$command = '';
			return;
		}
		//writeover('a.txt', var_export($gamepool,1));
		shuffle($gamepool);
		$objroomid = $gamepool[0];

		//第二步，修改当前玩家的数值，主要是抹掉道具、金钱数和技能，一方面这么做比较方便，第二也是防止刷积分
		eval(import_module('itemmain','skillbase','achievement_base'));
		$pls = 13;//移至三体星
		$money = 0;
		foreach($equip_list as $v){//抹掉装备道具
			if(strpos($v, 'ar')===0){
				${$v} = ${$v.'k'} = ${$v.'sk'} = '';
				${$v.'e'} = ${$v.'s'} = 0;
			}elseif(strpos($v, 'itm')===0){
				$n = substr($v, 3, 1);
				${'itm'.$n} = ${'itmk'.$n} = ${'itmsk'.$n} = '';
				${'itme'.$n} = ${'itms'.$n} = 0;
			}
		}
		$wep = '维度跌落【二向箔】';//武器的改变
		$wepk = 'WF'; 
		$wepe = 7000;
		$weps = 1;
		$wepsk = 'dy';
		
		\skillbase\skill_lost(530); //失去当前技能防止恶用
		
		//失去所有竞速挑战对应的技能
		foreach($achlist[2] as $av){
			\skillbase\skill_lost($av);
		}
		
		//第三步，在对应房间新建一个玩家

		//临时改变房间号
		$o_room_id = $room_id;
		$room_id = $objroomid;
		$room_prefix = room_id2prefix($room_id);
		$tablepre = room_get_tablepre();
		//复制当前玩家数据
		$tmp_p = array_clone($sdata);
		unset($tmp_p['pid']);
		$tmp_p['acquired_list'] = $acquired_list;
		$tmp_p['parameter_list'] = $parameter_list;
		\skillbase\skillbase_save($tmp_p, 1);
		$tmp_p = \player\player_format_with_db_structure($tmp_p);
		$db->array_insert("{$tablepre}players", $tmp_p);
		$tmp_pid = $db->insert_id();
		
		//改变目标房间的validnum和alivenum，由于是近乎于hack的跨房操作，不能正常用save_gameinfo，直接update
		$result = $db->query("SELECT validnum,alivenum FROM {$gtablepre}game WHERE groomid='$room_id'");
		$rarr = $db->fetch_array($result);
		$db->array_update("{$gtablepre}game",Array('validnum' => $rarr['validnum']+1, 'alivenum' => $rarr['alivenum']+1),"groomid='$room_id'");
		//发进行状况，这是抵达的
		addnews ( $now, 'jumpin530', $name );
		//提前发一个log
		\logger\logsave($tmp_pid, $now, '<span class="cyan b">你成功地抵达了这个维度！</span>但作为代价，你的装备几乎都遗失了。' ,'s');

		//把房间号改回来
		$room_id = $o_room_id;
		$room_prefix = room_id2prefix($room_id);
		$tablepre = room_get_tablepre();
		
		//设置全局变量，记录本局游戏已经有玩家跳跃过一次了
		$gamevars['skill530_jumped'] = 1;//设一个全局变量
		save_gameinfo();
		
		//第四步，自杀@v@
		
		//抹掉所有的成就，也就是跳跃后你无法在原房间完成任何成就
		foreach($achlist as $av1){
			foreach($av1 as $av2)
				\skillbase\skill_lost($av2);
		}
		
		$wep = $wepk = $wepsk = '';
		$wepe = $weps = 0;
		
		$hp = 0;
		$state = 46;
		$sdata['sourceless'] = 1; 
		\player\kill($sdata,$sdata);
		
		//第五步，修改cookie的房间号
		//include_once './include/user.func.php';
		set_current_roomid($objroomid);
		
		//第六步，赋值页面跳转请求。这样本进程会正常执行到结束，玩家跳转之后就是进入新房间并读取那个房间的角色数据
		$gamedata['url']='game.php';
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','input','logger'));
	
		if ($mode == 'special' && $command == 'skill530_special') 
		{
			if (!\skillbase\skill_query(530)) 
			{
				$log.='你没有这个技能。';
				$mode = 'command';
				$command = '';
				return;
			}
			process_530();
			return;
		}
		$chprocess();
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'death46') 
		{
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$a}发动了技能「降维」并把自己的复制体送去了另一个维度，而其本体已在此过程中彻底湮灭！</span></li>";
		}elseif($news == 'jumpin530') 
		{
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}从另一个维度跳跃而来！</span></li>";
		} 
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>