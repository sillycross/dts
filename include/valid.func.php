<?php

function enter_battlefield($xuser,$xpass,$xgender,$xicon)
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
	$rand = rand(0,15);
	$att = 95 + $rand;
	$def = 105 - $rand;
	$pls = 0;
	$killnum = 0;
	$lvl = 0;
	$exp = $areanum * 20;
	$money = 20;
	$rage = 0;
	$pose = 0;
	$tactic = 0;
	$icon = $xicon ? $xicon : rand(1,$iconlimit);
	$club = 0;

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
	if (in_array($gametype,Array(10,11,12,13)))
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
	
//	$itm[5] = '好人卡'; $itmk[5] = 'Y'; $itme[5] = 1; $itms[5] = 20; $itmsk[5] = '';
	//$itm[5] = '特别赠礼'; $itmk[5] = 'p'; $itme[5] = 1; $itms[5] = 1; $itmsk[5] = '';
//	$shenzhuang = rand(1,10);
//	switch ($shenzhuang) {
//		case 1:
//			$itm[5] = '圭一少年的球棒'; $itmk[5] = 'WP'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'e';
//		break;
//		case 2:
//			$itm[5] = '简称为UCW的三弦'; $itmk[5] = 'WK'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'p';
//		break;
//		case 3:
//			$itm[5] = '燃素粒子火焰炮'; $itmk[5] = 'WG'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'u';
//		break;
//		case 4:
//			$itm[5] = '水晶的超级球'; $itmk[5] = 'WC'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'ir';
//		break;
//		case 5:
//			$itm[5] = '久违的KEY系催泪弹'; $itmk[5] = 'WD'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'd';
//		break;
//		case 6:
//			$itm[5] = '梦想天生'; $itmk[5] = 'WF'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'd';
//		break;
//		case 7:
//			$itm[5] = '这样的装备没问题么的铠甲'; $itmk[5] = 'DB'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'E';
//		break;
//		case 8:
//			$itm[5] = '这样的装备没问题么的头盔'; $itmk[5] = 'DH'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'q';
//		break;
//		case 9:
//			$itm[5] = '这样的装备没问题么的手套'; $itmk[5] = 'DA'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'U';
//		break;
//		case 10:
//			$itm[5] = '这样的装备没问题么的靴子'; $itmk[5] = 'DF'; $itme[5] = 1800; $itms[5] = 100; $itmsk[5] = 'I';
//		break;
//	}
	if ($name == 'Amarillo_NMC') {
		$msp += 500;$mhp += 500;$hp += 500;$sp += 500;
		$att += 200;$def += 200;
		$exp += 3000;$money = 20000;$rage = 255;$pose = 1;$tactic = 3;
		$itm[1] = '死者苏生'; $itmk[1] = 'HB'; $itme[1] = 2000; $itms[1] = 400; $itmsk[1] = '';
		$itm[2] = '移动PC'; $itmk[2] = 'EE'; $itme[2] = 50; $itms[2] = 1;
		$itm[3] = '超光速快子雷达'; $itmk[3] = 'ER'; $itme[3] = 32; $itms[3] = 1;$itmsk[3] = 2;
		$itm[4] = '凸眼鱼'; $itmk[4] = 'Y'; $itme[4] = 1; $itms[4] = 30;$itmsk[4] = '';
		$itm[5] = '楠叶特制营养剂'; $itmk[5] = 'ME'; $itme[5] = 50; $itms[5] = 12;
		$itm[6] = '测试道具'; $itmk[6] = 'ME'; $itme[6] = 50; $itms[6] = 12;
		$wep = '神圣手榴弹';$wepk = 'WC';$wepe = 8765;$weps = 876;$wepsk = 'd';
		$arb = '守桥人的长袍';$arbk = 'DB'; $arbe = 3200; $arbs = 100; $arbsk = 'A';
		$arh = '千年积木';$arhk = 'DH'; $arhe = 1600; $arhs = 120; $arhsk = 'c';
		$ara = '皇家钻戒';$arak = 'DA'; $arae = 1600; $aras = 120; $arask = 'a';
		$arf = '火弩箭';$arfk = 'DF'; $arfe = 1600; $arfs = 120; $arfsk = 'M';
		$art = '贤者之石';$artk = 'A'; $arte = 9999; $arts = 999; $artsk = 'H';
		$wp=$wk=$wg=$wc=$wd=$wf=600;
	
	}elseif($name == '霜火协奏曲') {
		$art = '击败思念的纹章';$artk = 'A'; $arte = 1; $arts = 1; $artsk = 'zZ';
	}elseif($name == '时期') {
		$art = '击败鬼畜级思念的纹章';$artk = 'A'; $arte = 1; $arts = 1; $artsk = 'zZ';
	}elseif($name == '枪毙的某神' || $name == '精灵们的手指舞') {
		$art = 'TDG地雷的证明';$artk = 'A'; $arte = 1; $arts = 1; $artsk = 'zZ';
	}
//	
//	if(strpos($ip,'124.226.190')===0){
//		$msp = $sp = 16;$mhp = $hp = 6666;
//		$att = 1;$def = 1;$lvl = 0;
//		$money = 0;$club=17;
//		$itm[1] = '管理员之怒1'; $itmk[1] = 'HH'; $itme[1] = 100; $itms[1] = 30; $itmsk[1] = '';
//		$itm[2] = '管理员之怒2'; $itmk[2] = 'HS'; $itme[2] = 15; $itms[2] = 30; $itmsk[2] = '';
//		$itm[3] = '废物'; $itmk[3] = 'Y'; $itme[3] = 1; $itms[3] = 1; $itmsk[3] = '';
//		$itm[4] = '废物'; $itmk[4] = 'Y'; $itme[4] = 1; $itms[4] = 1; $itmsk[4] = '';
//		$wep = '啊哈哈哈我已经天下无敌了！';$wepk = 'WF';$wepe = 1;$weps = 8765;$wepsk = '';
//		$arb = '超级无敌纸防御';$arbk = 'DB'; $arbe = 30000; $arbs = 1; $arbsk = '';
//		$arh = '超级无敌纸防御';$arhk = 'DH'; $arhe = 30000; $arhs = 1; $arhsk = '';
//		$ara = '超级无敌纸防御';$arak = 'DA'; $arae = 30000; $aras = 1; $arask = '';
//		$arf = '超级无敌纸防御';$arfk = 'DF'; $arfe = 30000; $arfs = 1; $arfsk = '';
//		$art = '不发装备了，这个收好';$artk = 'A'; $arte = 1; $arts = 1; $artsk = 'HcM';
//	}

//	if ($name == '内衣') {
//		$itm[3] = '奖品-泽克西斯之荣耀模样的杏仁豆腐'; $itmk[3] = 'HB'; $itme[3] = 50; $itms[3] = 15; $itmsk[2] = 'z';
//		$itm[4] = '奖品-Flint Lock模样的杏仁豆腐'; $itmk[4] = 'HB'; $itme[4] = 50; $itms[4] = 15; $itmsk[3] = 'z';
//		$itm[5] = '『灵魂宝石』模样的杏仁豆腐'; $itmk[5] = 'HB'; $itme[5] = 50; $itms[5] = 15; $itmsk[4] = 'Z';
//		$wep = '奖品-福林洛克';$wepk = 'WP';$wepe = 85;$weps = 85;$wepsk = 'dZ';
//		$arb = '奖品-黑暗星云之祝福';$arbk = 'DB'; $arbe = 85; $arbs = 85; $arbsk = 'AaZ';
//		$arh = '奖品-黄色铃铛';$arhk = 'DH'; $arhe = 85; $arhs = 85; $arhsk = 'AaZ';
//		$ara = '奖品-地元素挂饰';$arak = 'DA'; $arae = 85; $aras = 85; $arask = 'AaZ';
//		$arf = '奖品-福林克之靴';$arfk = 'DF'; $arfe = 85; $arfs = 85; $arfsk = 'AaZ';
//		$art = '奖品-泽克西斯菁英';$artk = 'A'; $arte = 85; $arts = 85; $artsk = 'AaZ';
//	}
	$state = 0;
	$bid = 0;

	$inf = $teamID = $teamPass = '';
	$db->query("INSERT INTO {$tablepre}players (name,pass,type,endtime,gd,sNo,icon,club,hp,mhp,sp,msp,att,def,pls,lvl,`exp`,money,bid,inf,rage,pose,tactic,killnum,state,wp,wk,wg,wc,wd,wf,teamID,teamPass,wep,wepk,wepe,weps,arb,arbk,arbe,arbs,arh,arhk,arhe,arhs,ara,arak,arae,aras,arf,arfk,arfe,arfs,art,artk,arte,arts,itm0,itmk0,itme0,itms0,itm1,itmk1,itme1,itms1,itm2,itmk2,itme2,itms2,itm3,itmk3,itme3,itms3,itm4,itmk4,itme4,itms4,itm5,itmk5,itme5,itms5,itm6,itmk6,itme6,itms6,wepsk,arbsk,arhsk,arask,arfsk,artsk,itmsk0,itmsk1,itmsk2,itmsk3,itmsk4,itmsk5,itmsk6) VALUES ('$name','$pass','$type','$endtime','$gd','$sNo','$icon','$club','$hp','$mhp','$sp','$msp','$att','$def','$pls','$lvl','$exp','$money','$bid','$inf','$rage','$pose','$tactic','$state','$killnum','$wp','$wk','$wg','$wc','$wd','$wf','$teamID','$teamPass','$wep','$wepk','$wepe','$weps','$arb','$arbk','$arbe','$arbs','$arh','$arhk','$arhe','$arhs','$ara','$arak','$arae','$aras','$arf','$arfk','$arfe','$arfs','$art','$artk','$arte','$arts','$itm[0]','$itmk[0]','$itme[0]','$itms[0]','$itm[1]','$itmk[1]','$itme[1]','$itms[1]','$itm[2]','$itmk[2]','$itme[2]','$itms[2]','$itm[3]','$itmk[3]','$itme[3]','$itms[3]','$itm[4]','$itmk[4]','$itme[4]','$itms[4]','$itm[5]','$itmk[5]','$itme[5]','$itms[5]','$itm[6]','$itmk[6]','$itme[6]','$itms[6]','$wepsk','$arbsk','$arhsk','$arask','$arfsk','$artsk','$itmsk[0]','$itmsk[1]','$itmsk[2]','$itmsk[3]','$itmsk[4]','$itmsk[5]','$itmsk[6]')");
	$db->query("UPDATE {$gtablepre}users SET lastgame='$gamenum' WHERE username='$name'");
	if($udata['groupid'] >= 6 || $cuser == $gamefounder){
		addnews($now,'newgm',$name,"{$sexinfo[$gd]}{$sNo}号",$ip);
	}else{
		addnews($now,'newpc',$name,"{$sexinfo[$gd]}{$sNo}号",$ip);
	}
	
	if($validnum >= $validlimit && $gamestate == 20){
		$gamestate = 30;
	}
	//$gamestate = $validnum < $validlimit ? 20 : 30;
	save_gameinfo();
}

?>
