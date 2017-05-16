<?php

function enter_battlefield($xuser,$xpass,$xgender,$xicon,$card=0)
{
	eval(import_module('sys'));
	if ($xgender!='m' && $xgender!='f') $xgender='m';
	$validnum++;
	$alivenum++;
	$name = $xuser;
	$pass = $xpass;
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
	
	//solo局补给增加，配发探测器
	if (in_array($gametype,$elorated_mode))
	{
		$itms[1] = 50; $itms[2] = 50;
		$itm[5] = '生命探测器'; $itmk[5] = 'ER'; $itme[5] = 5; $itms[5] = 1;
	}
	
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
		$itm[5] = '权限狗的ID卡'; $itmk[5] = 'Z'; $itme[5] = 1; $itms[5] = 1;$itmsk[5] = '';
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
	
	if($gametype == 17) {//教程模式
		$card = 1000;
		$itm[3] = '紧急药剂'; $itmk[3] = 'Ca'; $itme[3] = 1; $itms[3] = 10;
	}
	
	$state = 0;
	$bid = 0;

	$inf = $teamID = $teamPass = '';
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

?>