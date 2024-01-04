<?php

namespace team
{
	function init() {}
	
//	function check_alive_discover(&$edata)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		//团队模式下非雾天不会在探索中遇到队友
//		eval(import_module('sys','player','metman','logger'));
//		if($teamID && (!$fog) && $teamID == $edata['teamID'] && in_array($gametype,$teamwin_mode))
//		{
//			return 0;
//		}
//		return $chprocess($edata);
//	}
	
	//团队模式下非雾天不会在探索中遇到队友
	function discover_player_filter_alive(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($edata);
		eval(import_module('sys','player'));
		if($teamID && !$fog && $teamID == $edata['teamID'] && in_array($gametype,$teamwin_mode))
			$ret = false;	
		return $ret;
	}
	
	//判定当前环境能否识别出队友（要求队伍生效和无雾）
	function teammate_checkable(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		return (!$fog && team_available());
	}
	
	//判定队伍是否生效（要求非连斗，或者组队模式）
	function team_available(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		return (!\gameflow_combo\is_gamestate_combo() || in_array($gametype,$teamwin_mode));
	}
	
	//识别队友
	function meetman_alternative($edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if($edata['hp']>0 && !empty($teamID) && teammate_checkable() && $teamID == $edata['teamID'] && !in_array($gametype,$teamwin_mode))
		{
			findteam($edata);
			return;
		} 
		$chprocess($edata);
	}

	function findteam(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','metman','logger'));
		
		extract($edata,EXTR_PREFIX_ALL,'w');
		$action = 'team'.$edata['pid'];
		$sdata['keep_team'] = 1;
		$battle_title = '发现队友';
		\metman\init_battle(1);
		
		$log .= "你发现了队友<span class=\"yellow b\">{$tdata['name']}</span>！<br>";
		include template(MOD_TEAM_FINDTEAM);
		$cmd = ob_get_contents();
		ob_clean();
		$main = MOD_METMAN_MEETMAN;
		return;
	}
	
	function senditem_check($edata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','logger','player'));
		if(!isset($edata) || $pid==$edata['pid']){
			$log .= "对方不存在！<br>";
			return false;
		} elseif($edata['pls'] != $pls) {
			$log .= '<span class="yellow b">'.$edata['name'].'</span>已经离开了<span class="yellow b">'.$plsinfo[$pls].'</span>。<br>';
			return false;
		} elseif($edata['hp'] <= 0) {
			$log .= '<span class="yellow b">'.$edata['name'].'</span>已经死亡，不能接受物品。<br>';
			return false;
		} elseif(!senditem_check_teammate($edata)){
			return false;
		}
		return true;
	}
	
	//因为某些功能而单独拆出来
	function senditem_check_teammate($edata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if(!$teamID || $edata['teamID']!=$teamID || !team_available()){
			$log .= '<span class="yellow b">'.$edata['name'].'</span>并非你的队友，不能接受物品。<br>';
			return false;
		}
		return true;
	}

	function senditem(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','logger','player','metman'));
		
		if('back' == $command){
			$mode = 'command';
			return;
		}
	
		$mateid = str_replace('team','',$action);
		if(!$mateid || strpos($action,'team')===false){
			$log .= '<span class="yellow b">你没有遇到队友，或已经离开现场！</span><br>';
			
			$mode = 'command';
			return;
		}
		$edata=\player\fetch_playerdata_by_pid($mateid);
		$check = senditem_check($edata);
		if(!$check){
			$mode = 'command';
			return;
		}
		$message = get_var_input('message');
		if($message){
			$log .= "<span class=\"lime b\">你对{$edata['name']}说：“{$message}”</span><br>";
			$x = "<span class=\"lime b\">{$name}对你说：“{$message}”</span>";
			if(!$edata['type']) \logger\logsave($edata['pid'],$now,$x,'c');
		}
		
		$itmn = substr($command, 4);
		if (!${'itms'.$itmn}) {
			$log .= '此道具不存在！';
			
			$mode = 'command';
			return;
		}
		$itm = & ${'itm'.$itmn};
		$itmk = & ${'itmk'.$itmn};
		$itme = & ${'itme'.$itmn};
		$itms = & ${'itms'.$itmn};
		$itmsk = & ${'itmsk'.$itmn};
		
		$sendflag = 0;
		for($i = 1;$i <= 6; $i++){
			if(!$edata['itms'.$i]) {
				$edata['itm'.$i] = $itm; $edata['itmk'.$i] = $itmk; 
				$edata['itme'.$i] = $itme; $edata['itms'.$i] = $itms; $edata['itmsk'.$i] = $itmsk;
				
				$log .= "你将<span class=\"yellow b\">".$edata['itm'.$i]."</span>送给了<span class=\"yellow b\">{$edata['name']}</span>。<br>";
				$x = "<span class=\"yellow b\">$name</span>将<span class=\"yellow b\">".$edata['itm'.$i]."</span>送给了你。";
				
				if(!$edata['type']) \logger\logsave($edata['pid'],$now,$x,'t');
				addnews($now,'senditem',$name,$edata['name'],$itm);
				\player\player_save($edata);
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				
				$sendflag = 1;
				break;
			}
		}
		
		$sendflag = senditem_before_log_event($itmn, $sendflag, $edata);
		
		if(!$sendflag) {
			$log .= "<span class=\"yellow b\">{$edata['name']}</span> 的包裹已经满了，不能赠送物品。<br>";
		}
		
		$mode = 'command';
		return;
	}
	
	//赠送物品后，确定赠送成功还是失败之前的事件
	function senditem_before_log_event($itmn, $sendflag, &$edata) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $sendflag;
	}
	
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('player'));
		if(empty($sdata['keep_team']) && strpos($action, 'team')===0){
			$action = '';
			unset($sdata['keep_team']);
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if($mode == 'senditem') 
		{
			senditem();
			return;
		}
		
		if($mode == 'command' && $command == 'team') {
			$teamcmd = get_var_input('teamcmd');
			if($teamcmd == 'teamquit') {				
				teamquit();
			} else{
				teamcheck();
			}
			return;
		}
		
		if($mode == 'team') {
			list($nteamID,$nteamPass) = get_var_input('nteamID','nteamPass');
			if ($command=='teammake') 
			{
				teammake($nteamID,$nteamPass);
				return; 
			}
			if ($command=='teamjoin')
			{
				teamjoin($nteamID,$nteamPass);
				return;
			}
		}
				
		$chprocess();
	}
	
	function check_team_button_exist()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if($news == 'teammake') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$b}创建了队伍{$a}</span></li>";
		if($news == 'teamjoin') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$b}加入了队伍{$a}</span></li>";
		if($news == 'teamquit') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"red b\">{$b}退出了队伍{$a}</span></li>";
		if($news == 'senditem') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}将<span class=\"yellow b\">$c</span>赠送给了{$b}</span></li>";
			
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
