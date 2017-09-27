<?php

function enter_battlefield($xuser,$xpass,$xgender,$xicon,$card=0)
{
	include_once GAME_ROOT.'./include/user.func.php';
	eval(import_module('sys'));
	\sys\load_gameinfo();
	if ($xgender!='m' && $xgender!='f') $xgender='m';
	$validnum++;
	$alivenum++;
	$name = $xuser;
	$pass = create_storedpass($xuser,$xpass);
	global $gd; $gd = $xgender;
	$type = 0;
	$endtime = $now;
	global $sNo; $sNo = $validnum;
	global $hp,$mhp,$sp,$msp,$att,$def,$wep,$itm,$icon; 
	$hp = $mhp = $hplimit;
	$sp = $msp = $splimit;
	$ss = $mss = 0;
	$rand = rand(0,15);
	$att = 95 + $rand;
	$def = 105 - $rand;
	$pls = 0;
	$killnum = 0;
	$lvl = 0;
	$skillpoint = 0;
	$exp = $areanum * 20;
	$money = 20;
	$rage = 0;
	$pose = 3;
	$tactic = 2;
	$icon = $xicon ? $xicon : rand(1,$iconlimit);
	$club = 0;

	$wp=$wk=$wg=$wc=$wd=$wf=0;
	$arb = $gd == 'm' ? '男生校服' : '女生校服';
	$arbk = 'DB'; $arbe = 5; $arbs = 15; $arbsk = '';
	$arh = $ara = $arf = $art = '';
	$arhk = $arak = $arfk = $artk = '';
	$arhsk = $arask = $arfsk = $artsk = '';
	$arhe = $arae = $arfe = $arte = 0;
	$arhs = $aras = $arfs = $arts = 0;
	
	for ($i=0; $i<=6; $i++){$itm[$i] = $itmk[$i] = $itmsk[$i] = ''; $itme[$i] = $itms[$i] = 0;}
	$itm[1] = '面包'; $itmk[1] = 'HH'; $itme[1] = 100; $itms[1] = 30;
	$itm[2] = '矿泉水'; $itmk[2] = 'HS'; $itme[2] = 100; $itms[2] = 30;
	
	$weplist = openfile(config('stwep',$gamecfg));
	do { 
		$index = rand(1,count($weplist)-1); 
		list($wep,$wepk,$wepe,$weps,$wepsk) = explode(",",$weplist[$index]);
	} while(!$wepk);

	$stitemlist = openfile(config('stitem',$gamecfg));
	do { 
		$index = rand(1,count($stitemlist)-1); 
		list($itm[3],$itmk[3],$itme[3],$itms[3],$itmsk[3]) = explode(",",$stitemlist[$index]);
	} while(!$itmk[3]);
	do { 
		$index = rand(1,count($stitemlist)-1); 
		list($itm[4],$itmk[4],$itme[4],$itms[4],$itmsk[4]) = explode(",",$stitemlist[$index]);
	} while(!$itmk[4] || ($itmk[3] == $itmk[4]));

	if(strpos($wepk,'WG') === 0){
		$itm[3] = '手枪子弹'; $itmk[3] = 'GB'; $itme[3] = 1; $itms[3] = 12; $itmsk[3] = '';
	}

	global $gamefounder;
	$result = $db->query("SELECT groupid FROM {$gtablepre}users WHERE username='$xuser'");
	$groupid = $db->fetch_array($result)['groupid'];
	if ($name == $gamefounder||$groupid >= 5) {
		$itm[6] = '权限狗的ID卡'; $itmk[6] = 'Z'; $itme[6] = 1; $itms[6] = 1;$itmsk[6] = '';
//		$msp += 100;$mhp += 100;$hp += 100;$sp += 100;
//		$att += 100;$def += 100;
//		$exp += 10;$money = 20000;$rage = 255;$pose = 1;$tactic = 3;
//		$itm[1] = '胜利型核口可乐'; $itmk[1] = 'HB'; $itme[1] = 1500; $itms[1] = 400; $itmsk[1] = 'z';
//		$itm[2] = '移动PC'; $itmk[2] = 'EE'; $itme[2] = 50; $itms[2] = 1;
//		$itm[3] = '超光速快子雷达'; $itmk[3] = 'ER'; $itme[3] = 32; $itms[3] = 1;$itmsk[3] = 2;
//		$itm[4] = '凸眼鱼'; $itmk[4] = 'Y'; $itme[4] = 1; $itms[4] = 30;$itmsk[4] = '';
//		$itm[5] = '楠叶特制营养剂'; $itmk[5] = 'ME'; $itme[5] = 100; $itms[5] = 25;
//		$itm[6] = '紧急药剂'; $itmk[6] = 'Ca'; $itme[6] = 1; $itms[6] = 50;
//		$wep = '普罗米修斯发射器';$wepk = 'WJ';$wepe = 43210;$weps = 876;$wepsk = 'rnd';
//		$arb = '歪曲力场';$arbk = 'DB'; $arbe = 4600; $arbs = 100; $arbsk = 'Bz';
//		$arh = '加布里埃尔的旋钮';$arhk = 'DH'; $arhe = 2400; $arhs = 120; $arhsk = 'cb';
//		$ara = '次世代决斗盘';$arak = 'DA'; $arae = 2400; $aras = 120; $arask = 'Aa';
//		$arf = '悬浮滑板';$arfk = 'DF'; $arfe = 2400; $arfs = 120; $arfsk = 'Mm';
//		$art = 'Untainted Glory';$artk = 'A'; $arte = 9999; $arts = 999; $artsk = 'Hh';
//		$wp=$wk=$wg=$wc=$wd=$wf=600;
	}elseif($name == '霜火协奏曲') {
		$art = '击败思念的纹章';$artk = 'A'; $arte = 1; $arts = 1; $artsk = 'zZ';
	}elseif($name == '时期') {
		$art = '击败鬼畜级思念的纹章';$artk = 'A'; $arte = 1; $arts = 1; $artsk = 'zZ';
	}elseif($name == '枪毙的某神' || $name == '精灵们的手指舞') {
		$art = 'TDG地雷的证明';$artk = 'A'; $arte = 1; $arts = 1; $artsk = 'zZ';
	}
	
	$state = 0;
	$bid = 0;

	$inf = $teamID = $teamPass = '';
	
	//特殊开局规则
	
	//solo局补给增加，配发探测器
	if (in_array($gametype,$elorated_mode))
	{
		$itms[1] = 50; $itms[2] = 50;
		$itm[5] = '生命探测器'; $itmk[5] = 'ER'; $itme[5] = 5; $itms[5] = 1;
	}
	
	//除错模式专用卡（软件测试工程师）
	if (1==$gametype){
		$card=93;
	}
	//宝石乱斗模式专用卡（虹光塑师）
	elseif (3==$gametype){
		$card=151;
	}	
	//教程模式专用卡（教程技能+开局紧急药剂）
	elseif(17==$gametype && defined('MOD_SKILL1000')) {
		$card = 1000;
	}	
	//高级模式专用卡（空降技能+开局紧急药剂）
	elseif(18==$gametype && defined('MOD_SKILL1001')) {
		$card = 1001;
	}	
	//标准模式禁用任何卡片
	elseif(0==$gametype){
		$card = 0;
	}
	
	//特殊规则
	if(isset($roomvars['current_game_option'])){
		if(isset($roomvars['current_game_option']['special-rule'])){
			$spr = $roomvars['current_game_option']['special-rule'];
			if('4000lp' == $spr)//掘豆模式
				$card = 154;
		}
	}
	
	///////////////////////////////////////////////////////////////
	eval(import_module('cardbase'));
	
	if ($card==81){
		$arr=array('0');
		$r=rand(1,100);
		if ($r<=20){
			$arr=$cardindex['S'];
		}else if($r<=60){
			$arr=$cardindex['A'];
		}else if($r<=80){
			$arr=$cardindex['B'];
		}else{
			$arr=$cardindex['C'];
		}
		$c=count($arr)-1;
		$card=$arr[rand(0,$c)];
	}
	$card_valid_info=$cards[$card]['valid'];
	$cardname=$cards[$card]['name'];
	$cardrare=$cards[$card]['rare'];
	///////////////////////////////////////////////////////////////
	foreach ($card_valid_info as $key => $value){
		if (substr($key,0,3)=="itm"){
			$tt=substr($key,-1);
			$ts=substr($key,0,strlen($key)-1);
			${$ts}[$tt]=$value;
		}else{
			${$key}=$value;
		}
	}
	///////////////////////////////////////////////////////////////
	$db->query("INSERT INTO {$tablepre}players (name,pass,type,endtime,gd,sNo,icon,club,hp,mhp,sp,msp,ss,mss,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,itm6,itmk6,itme6,itms6,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5,itmsk6,card,cardname,skillpoint) VALUES ('$name','$pass','$type','$endtime','$gd','$sNo','$icon','$club','$hp','$mhp','$sp','$msp','$ss','$mss','$att','$def','$pls','$lvl','$exp','$money','$bid','$inf','$rage','$pose','$tactic','$state','$killnum','$wp','$wk','$wg','$wc','$wd','$wf','$teamID','$teamPass','$wep','$wepk','$wepe','$weps','$arb','$arbk','$arbe','$arbs','$arh','$arhk','$arhe','$arhs','$ara','$arak','$arae','$aras','$arf','$arfk','$arfe','$arfs','$art','$artk','$arte','$arts','$itm[0]','$itmk[0]','$itme[0]','$itms[0]','$itm[1]','$itmk[1]','$itme[1]','$itms[1]','$itm[2]','$itmk[2]','$itme[2]','$itms[2]','$itm[3]','$itmk[3]','$itme[3]','$itms[3]','$itm[4]','$itmk[4]','$itme[4]','$itms[4]','$itm[5]','$itmk[5]','$itme[5]','$itms[5]','$itm[6]','$itmk[6]','$itme[6]','$itms[6]','$wepsk','$arbsk','$arhsk','$arask','$arfsk','$artsk','$itmsk[0]','$itmsk[1]','$itmsk[2]','$itmsk[3]','$itmsk[4]','$itmsk[5]','$itmsk[6]','$card','$cardname','$skillpoint')");
	$db->query("UPDATE {$gtablepre}users SET lastgame='$gamenum' WHERE username='$name'");
	
	///////////////////////////////////////////////////////////////
	$pp=\player\fetch_playerdata($name);

	//为了灵活性，直接处理所有技能，在固定称号的时候记得要写入skills不然进游戏就没技能了
	//if (isset($card_valid_info['club'])){
	//	\clubbase\club_acquire($card_valid_info['club'],$pp);
	//}
	if (is_array($card_valid_info['skills'])){
		foreach ($card_valid_info['skills'] as $key=>$value){
			if (defined('MOD_SKILL'.$key)){
				\skillbase\skill_acquire($key,$pp);
				if ($value>0){
					\skillbase\skill_setvalue($key,'lvl',$value,$pp);
				}
			}	
		}
	}
	
	\player\post_enterbattlefield_events($pp);
	
	\player\player_save($pp);
	///////////////////////////////////////////////////////////////
	$rarecolor = $card_rarecolor[$cardrare];
//	if ($cardrare=="S"){
//		$rarecolor="orange";
//	}else if ($cardrare=='A'){
//		$rarecolor="linen";
//	}else if ($cardrare=='B'){
//		$rarecolor="brickred";
//	}else if ($cardrare=='C'){
//		$rarecolor="seagreen";
//	}
	$result = $db->query("SELECT groupid FROM {$gtablepre}users WHERE username='$cuser'");
	$udata = $db->fetch_array($result);
	if($udata['groupid'] >= 6 || $cuser == $gamefounder){
		addnews($now,'newgm',"<span class=\"".$rarecolor."\">".$cardname.'</span> '.$name,"{$sexinfo[$gd]}{$sNo}号");
	}else{
		addnews($now,'newpc',"<span class=\"".$rarecolor."\">".$cardname.'</span> '.$name,"{$sexinfo[$gd]}{$sNo}号");
	}
	
	if($validnum >= $validlimit && $gamestate == 20){
		$gamestate = 30;
	}
	//$gamestate = $validnum < $validlimit ? 20 : 30;
	save_gameinfo();
}


function card_validate($udata){
	eval(import_module('sys','cardbase'));
	
	$card = $udata['card'];
	
	$userCardData = \cardbase\get_user_cardinfo($udata['username']);
	$card_ownlist = $userCardData['cardlist'];
	$card_energy = $userCardData['cardenergy'];
	$cardChosen = $userCardData['cardchosen'];
	
	/*
	 * $card_disabledlist id => errid
	 * id: 卡片ID errid: 不能使用这张卡的原因
	 * 原因可以叠加
	 * e0: S卡总体CD
	 * e1: 单卡CD
	 * e2: 有人于本局使用了同名卡
	 * e3: 本游戏模式不可用
	 *
	 * $card_error errid => msg
	 */
	$card_disabledlist=Array();
	$card_error=Array();
	
	$energy_recover_rate = \cardbase\get_energy_recover_rate($card_ownlist, $udata['gold']);
	
	//最低优先级错误原因：同名非C卡
	$result = $db->query("SELECT card FROM {$tablepre}players WHERE type = 0");
	$t=Array();
	while ($cdata = $db->fetch_array($result)) $t[$cdata['card']]=1;
	if(in_array($gametype, array(2,4))) //只有标准模式和无限复活模式才限制卡片
		foreach ($card_ownlist as $key)
			if (!in_array($cards[$key]['rare'], array('C', 'M')) && isset($t[$key])) 
			{
				$card_disabledlist[$key][] = 'e2';
				$card_error['e2'] = '这张卡片暂时不能使用，因为本局已经有其他人使用了这张卡片<br>请下局早点入场吧！';
			}
	
	//次高优先级错误原因：单卡CD
	foreach ($card_ownlist as $key)
		if ($card_energy[$key]<$cards[$key]['energy'])
		{
			$t=($cards[$key]['energy']-$card_energy[$key])/$energy_recover_rate[$cards[$key]['rare']];
			$card_disabledlist[$key][] = 'e1'.$key;
			$card_error['e1'.$key] = '这张卡片暂时不能使用，因为它目前正处于蓄能状态<br>这张卡片需要蓄积'.$cards[$key]['energy'].'点能量方可使用，预计在'.convert_tm($t).'后蓄能完成';
		}
	
	//最高优先级错误原因：卡片类别时间限制
	foreach($cardtypecd as $ct => $ctcd){
		if(!empty($ctcd)){
			$ctcdstr = seconds2hms($ctcd);
			$card_error['e0'.$ct] = '这张卡片暂时不能使用，因为最近'.$ctcdstr.'内你已经使用过'.$ct.'卡了<br>在'.convert_tm($ctcd-($now-$udata['cd_'.strtolower($ct)])).'后你才能再次使用'.$ct.'卡';
	
			if (($now-$udata['cd_'.strtolower($ct)]) < $ctcd){
				foreach ($card_ownlist as $key)
					if ($cards[$key]['rare']==$ct)
						$card_disabledlist[$key][] = 'e0'.$ct;
			}
		}
	}
	
	//最高优先级错误原因：本游戏模式不可用
	$card_error['e3'] = '这张卡片在本游戏模式下禁止使用！<br>';
	
	if ($gametype==2)	//deathmatch模式禁用蛋服和炸弹人
	{
		if (in_array(97,$card_ownlist)) $card_disabledlist[97][]='e3';
		if (in_array(144,$card_ownlist)) $card_disabledlist[144][]='e3';
	}
	
	return array($card_disabledlist,$card_error);
}
?>