<?php

namespace player
{
	global $db_player_structure, $gamedata, $cmd, $main, $sdata;
	global $fog,$upexp,$lvlupexp,$iconImg,$iconImgB,$ardef;
	global $hpcolor,$spcolor,$newhpimg,$newspimg,$splt,$hplt, $tpldata; 
	
	function init()
	{
		eval(import_module('sys'));
		
		global $db_player_structure, $tpldata; $db_player_structure=Array(); $tpldata=Array();
		$result = $db->query("DESCRIBE {$tablepre}players");
		while ($pdata = $db->fetch_array($result))
		{
			global ${$pdata['Field']}; 
			array_push($db_player_structure,$pdata['Field']);
		}
	}
	
	//注意这个函数只能找人
	function fetch_playerdata($Pname)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$Pname' AND type = 0");
		if(!$db->num_rows($result)) return NULL;
		$pdata = $db->fetch_array($result);
		//备份取出数据库时的player state
		//然后如果player state在写回时没有变，就直接unset掉
		//真正的防并发复活问题是用player_dead_flag这个单向的变量保证的，
		//但这个可以保证在并发问题发生时，绝大多数情况下UI不出问题（否则就会出现UI显示玩家死了却不显示死因的奇怪问题）
		//虽然理论上如果是在玩家触发state变化的那一瞬间（比如进入睡眠状态）被杀UI还是会挂，但是这几率太小了无视
		$pdata['state_backup']=$pdata['state'];
		return $pdata;
	}
	
	function fetch_playerdata_by_pid($pid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid = '$pid'");
		if(!$db->num_rows($result)) return NULL;
		$pdata = $db->fetch_array($result);
		$pdata['state_backup']=$pdata['state'];	//见上个函数注释
		return $pdata;
	}
	
	function load_playerdata($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		foreach ($pdata as $key => $value) $$key=$value;
		$sdata=Array();
		foreach ($db_player_structure as $key)
			$sdata[$key]=&$$key;
		$sdata['state_backup']=$pdata['state_backup'];	//见上个函数注释
	}
	
	function get_player_killmsg(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($pdata['type']==0)
		{
			$result = $db->query ( "SELECT killmsg FROM {$gtablepre}users WHERE username = '{$pdata['name']}'" );
			$kilmsg = $db->result ( $result, 0 );
			return $kilmsg;
		}
		return '';
	}
	
	function get_player_lastword(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if ($pdata['type']==0)
		{
			$result = $db->query ( "SELECT lastword FROM {$gtablepre}users WHERE username = '{$pdata['name']}'" );
			$lstwd = $db->result ( $result, 0 );
			return $lstwd;
		}
		return '';
	}
	
	function init_playerdata(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		$iconImg = $gd.'_'.$icon.'.gif';
		$iconImgB = $gd.'_'.$icon.'a.gif';
		$ardef = $arbe + $arhe + $arae + $arfe;

		if(!$weps) {
			$wep = $nowep;$wepk = 'WN';$wepsk = '';
			$wepe = 0; $weps = $nosta;
		}
		if(!$arbs) {
			$arb = $noarb;$arbk = 'DN'; $arbsk = '';
			$arbe = 0; $arbs = $nosta;
		}
	}
		
	function init_profile(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		$ardef = $arbe + $arhe + $arae + $arfe;
		$karma = ($rp * $killnum - $def )+ $att;

		$hpcolor = 'clan';
		if($hp <= 0 ){
			$hpcolor = 'red';
		} elseif($hp <= $mhp*0.2){
			$hpcolor = 'red';
		} elseif($hp <= $mhp*0.5){
			$hpcolor = 'yellow';
		} elseif($inf == ''){
		}
		
		if($sp <= $msp*0.2){
			$spcolor = 'grey';
		} elseif($sp <= $msp*0.5){
			$spcolor = 'yellow';
		} else {
			$spcolor = 'clan';
		}
		
		$newhppre = 6+floor(155*(1-$hp/$mhp));
		$newhpimg = '<img src="img/hpman.gif" style="position:absolute; clip:rect('.$newhppre.'px,55px,160px,0px);">';
		$newsppre = 6+floor(155*(1-$sp/$msp));
		$newspimg = '<img src="img/spman.gif" style="position:absolute; clip:rect('.$newsppre.'px,55px,160px,0px);">';
		$spltp = 3+floor(155*(1-$sp/$msp));
		$splt = '<img src="img/splt.gif" style="position:absolute; clip:rect('.$spltp.'px,55px,160px,0px);">';
		$hpltp = 3+floor(155*(1-$hp/$mhp));
		$hplt = '<img src="img/hplt.gif" style="position:absolute; clip:rect('.$hpltp.'px,55px,160px,0px);">';
		return;
	}

	function add_new_killarea($where,$atime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map'));
		$plsnum = sizeof($plsinfo) - 1;
		if ($areanum >= sizeof($plsinfo) - 1) return $chprocess($where);
		$query = $db->query("SELECT * FROM {$tablepre}players WHERE pls={$where} AND type=0 AND hp>0");
		while($sub = $db->fetch_array($query)) 
		{
			$pid = $sub['pid'];
			if (($gamestate >= 40 && (!$areaesc && ($sub['tactic']!=4))) || $areanum >= $plsnum)
			{
				$hp = 0;
				$state = 11;
				$deathpls = $sub['pls'];
				$bid = 0;
				$endtime = $atime;
				$db->query("UPDATE {$tablepre}players SET hp='$hp', bid='$bid', state='$state', endtime='$endtime' WHERE pid=$pid");
				addnews($endtime,"death$state",$sub['name'],$sub['type'],$deathpls);
				$deathnum++;
			}
			else
			{	
				$pls = $arealist[rand($areanum+1,$plsnum)];
				$db->query("UPDATE {$tablepre}players SET pls='$pls' WHERE pid=$pid");
			}
		} 
		$alivenum = $db->result($db->query("SELECT COUNT(*) FROM {$tablepre}players WHERE hp>0 AND type=0"), 0);
		$chprocess($where,$atime);
	}
	
	function update_sdata()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function player_save(&$data)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		if(isset($data['pid']))
		{
			$spid = $data['pid'];
			//unset($data['pid']);
			$ndata=Array();
			foreach ($db_player_structure as $key)
			{
				if ($key!='pid' && isset($data[$key])) $ndata[$key]=$data[$key];
			}
			//建国后不准成精，你们复活别想啦
			if ($ndata['hp']<=0) 
				$ndata['player_dead_flag'] = 1;
			
			//player_dead_flag单向，只能向数据库写入1，不能改回0
			if (isset($ndata['player_dead_flag']) && !$ndata['player_dead_flag']) 
				unset($ndata['player_dead_flag']);
				
			//如果state没变就不写回state了，防止并发问题发生时UI挂掉（见fetch_playerdata注释）
			if (isset($ndata['state_backup']) && $ndata['state']==$data['state_backup'])
				unset($ndata['state']);
				
			if (sizeof($ndata)>0)
				$db->array_update("{$tablepre}players",$ndata,"pid='$spid'");
		}
		return;
	}
	
	function rs_game($xmode)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$chprocess($xmode);
		
		eval(import_module('sys'));
		$sqldir = GAME_ROOT.'./gamedata/sql/';
		
		if ($xmode & 4) {
			//echo " - 角色数据库初始化 - ";
			$sql = file_get_contents("{$sqldir}players.sql");
			$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
			$db->queries($sql);
			//runquery($sql);
			$validnum = $alivenum = $deathnum = 0;
		}
		
		save_gameinfo();
	}

	function deathnews(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','map','player'));
		$lwname = $typeinfo [$pd['type']] . ' ' . $pd['name'];
		$lstwd = \player\get_player_lastword($pd);
		$db->query ( "INSERT INTO {$tablepre}chat (type,`time`,send,recv,msg) VALUES ('3','$now','$lwname','{$plsinfo[$pd['pls']]}','$lstwd')" );
		if ($pd['sourceless']) $x=''; else $x=$pa['name'];
		addnews ( $now, 'death' . $pd['state'], $pd['name'], $pd['type'], $x , $pa['attackwith'], $lstwd );
	}
	
	//请自己设置好$pd['state']再调用，$pa为伤害来源，$pd为死者，$pa['attackwith']为死亡途径描述，返回$killmsg
	//如没有伤害来源，请把$pa设为&$pd，然后把$pd['sourceless']设为true
	//注意，“没有伤害来源”和“伤害来源是自己”是不同的！
	//例：常规击杀，有伤害来源，$pa为击杀者，$pd为死者，$pa['attackwith']为武器名
	//例：死于自己设置的陷阱，有伤害来源（来源是自己），$pa为死者自己，$pd亦为死者自己，$pa['attackwith']为陷阱名，$pd['sourceless']为假
	//例：死于野生陷阱，无伤害来源，$pa为死者自己，$pd亦为死者自己，$pa['attackwith']为陷阱名，$pd['sourceless']为真
	//调用完了记得player_save（而且如果是自己还需要再load_playerdata）双方数据才能生效！！
	function kill(&$pa, &$pd) 
	{	
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		$pd['hp'] = 0; 
		if (!isset($pd['sourceless']) || $pd['sourceless']==0) $pd['bid'] = $pa['pid'];
		
		if ($pa['pid'] != $pd['pid'])
			$kilmsg = \player\get_player_killmsg($pa);
		else  $kilmsg = '';
		
		if ($pd['type']==0 && $pd['pid']!=$pa['pid']) $pa['killnum']++;
	
		deathnews($pa, $pd);
		
		$deathnum ++;
		if ($pd['type']==0) $alivenum--; 

		$pd['endtime'] = $now;
		save_gameinfo ();
		
		return $kilmsg;
	}
	
	function pre_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if ($player_dead_flag) $hp = 0;
		if ($hp<=0) $player_dead_flag = 1;
	}
	
	function act()	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','input'));

			if($command == 'menu') {
				$mode = 'command';
				$action = '';
			} elseif($mode == 'command') {
				if($command == 'special') {
				/*
					if($sp_cmd == 'sp_word'){
						include_once GAME_ROOT.'./include/game/special.func.php';
						getword();
						$mode = $sp_cmd;
					}elseif($sp_cmd == 'sp_adtsk'){
						include_once GAME_ROOT.'./include/game/special.func.php';
						adtsk();
						$mode = 'command';
					}else{
				*/
						$mode = $sp_cmd;
				//	}
					
				} 
			} 
			/*
			elseif($mode == 'special') {
				include_once GAME_ROOT.'./include/game/special.func.php';
				if(strpos($command,'chkp') === 0) {
					$itmn = substr($command,4,1);
					chkpoison($itmn);
				}
			*/
			/*
			} elseif($mode == 'chgpassword') {
				include_once GAME_ROOT.'./include/game/special.func.php';
				chgpassword($oldpswd,$newpswd,$newpswd2);
			} elseif($mode == 'chgword') {
				include_once GAME_ROOT.'./include/game/special.func.php';
				chgword($newmotto,$newlastword,$newkillmsg);
			}
			*/
	}
	
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function prepare_response_content()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$gamedata['innerHTML']['notice'] = ob_get_contents();
	}
	
	//这个函数是game.php里调用的，上面那个是command.php里调用的。好像有点猎奇的小区别…… 
	function prepare_initial_response_content()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
}

?>
