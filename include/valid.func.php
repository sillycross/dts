<?php

//玩家进入战场的主函数，由于是老代码而且涉及很多用户输入，就保留文件位置
function enter_battlefield($xuser,$xpass,$xgender,$xicon,$card=0,$ip=NULL)
{
	include_once GAME_ROOT.'./include/user.func.php';
	eval(import_module('sys'));
	\sys\load_gameinfo();
	//基本的角色数据资料，如
	if ($xgender!='m' && $xgender!='f') $xgender='m';
	$validnum++;
	$alivenum++;
	
	//初始化角色数据
	//原本大量赋值全局变量是为了显示，现在已经跳过了需要显示的页面而直接进入游戏，不需要全局变量了，可以直接装进数组
	$eb_pdata = Array(
		'name' => $xuser,
		'pass' => create_storedpass($xuser,$xpass),
		'gd' => $xgender,
		'icon' => $xicon ? $xicon : rand(1,$iconlimit),
		'type' => 0,
		'endtime' => $now,
		'validtime' => $now,
		'sNo' => $validnum
	);
	$eb_pdata['hp'] = $eb_pdata['mhp'] = $hplimit;
	$eb_pdata['sp'] = $eb_pdata['msp'] = $splimit;
	$eb_pdata['ss'] = $eb_pdata['mss'] = 0;
	$rand = rand(0,15);
	$eb_pdata['att'] = 95 + $rand;
	$eb_pdata['def'] = 105 - $rand;
	$eb_pdata['pls'] = 0;
	$eb_pdata['killnum'] = $eb_pdata['npckillnum'] = 0;
	$eb_pdata['lvl'] = $eb_pdata['exp'] = $eb_pdata['skillpoint'] = 0;
	$eb_pdata['money'] = 20;
	$eb_pdata['rage'] = 0;
	$eb_pdata['pose'] = 3;
	$eb_pdata['tactic'] = 2;
	$eb_pdata['club'] = 0;
	$eb_pdata['state'] = 0;
	$eb_pdata['wp'] = $eb_pdata['wk'] = $eb_pdata['wc'] = $eb_pdata['wg'] = $eb_pdata['wd'] = $eb_pdata['wf'] = 0;
	$eb_pdata['inf'] = $eb_pdata['teamID'] = $eb_pdata['teamPass'] = '';
	
	foreach(Array('wep','arb','arh','ara','arf','art') as $pos){
		$eb_pdata[$pos] = $eb_pdata[$pos.'k'] = $eb_pdata[$pos.'sk'] = '';
		$eb_pdata[$pos.'e'] = $eb_pdata[$pos.'s'] = 0;
	}
	for ($i=0; $i<=6; $i++){
		${'itm'.$i} = ${'itmk'.$i} = ${'itmsk'.$i} = '';
		${'itme'.$i} = ${'itms'.$i} = 0;
	}
	
	//禁区不为0时经验补偿
	$eb_pdata['exp'] += $areanum * 20;
	
	//调用itemmain模块的初始化武器装备
	//各模式特殊的初始装备在各对应模块里接管这个函数实现
	$eb_pdata = \itemmain\init_enter_battlefield_items($eb_pdata);
	
	//游戏账户判定以及留言等的赋值
	global $gamefounder, $cuser;
	if($xuser != $cuser) {
		$r = fetch_udata_by_username($xuser, 'groupid,ip,motto,killmsg,lastword');
		if(empty($r)) return;
	}else{
		$r = $cudata;
	}
	$groupid = $r['groupid']; //这个不在pdata结构里
	$eb_pdata['motto'] = $r['motto'];
	$eb_pdata['killmsg'] = $r['killmsg'];
	$eb_pdata['lastword'] = $r['lastword'];
	
	//如果没有提供ip，则自行查询
	if(empty($ip)) {
		$ip = $r['ip'];
	}
	
	//权限和一些名字特判，不应该放在模块里，就放这里吧
	if ($xuser == $gamefounder || $groupid >= 5) {
		$eb_pdata['itm6'] = '权限狗的ID卡'; $eb_pdata['itmk6'] = 'Z'; $eb_pdata['itme6'] = 1; $eb_pdata['itms6'] = 1; $eb_pdata['itmsk6'] = '';
	}elseif($xuser == '霜火协奏曲') {
		$eb_pdata['art'] = '击败思念的纹章'; $eb_pdata['artk'] = 'A'; $eb_pdata['arte'] = 1; $eb_pdata['arts'] = 1; $eb_pdata['artsk'] = 'zZ';
	}elseif($xuser == '时期') {
		$eb_pdata['art'] = '击败鬼畜级思念的纹章'; $eb_pdata['artk'] = 'A'; $eb_pdata['arte'] = 1; $eb_pdata['arts'] = 1; $eb_pdata['artsk'] = 'zZ';
	}elseif($xuser == '枪毙的某神' || $xuser == '精灵们的手指舞') {
		$eb_pdata['art'] = 'TDG地雷的证明'; $eb_pdata['artk'] = 'A'; $eb_pdata['arte'] = 1; $eb_pdata['arts'] = 1; $eb_pdata['artsk'] = 'zZ';
	}
	
	//////////////////////////////////////////////////////////////////////////
	/////////////////////////////////卡片处理/////////////////////////////////
	//////////////////////////////////////////////////////////////////////////
	
	$prefix = '';
	
	if(defined('MOD_CARDBASE')){
		eval(import_module('cardbase'));
		//判定某些特殊情况对卡片的修改
		$card = \cardbase\get_enter_battlefield_card($card);
		
		//房间特殊规则，由于房间不是模块，先放这里，回头再改
		if(isset($roomvars['current_game_option'])){
			if(isset($roomvars['current_game_option']['special-rule'])){
				$spr = $roomvars['current_game_option']['special-rule'];
				if('4000lp' == $spr)//掘豆模式
					$card = 154;
			}
		}	
		
		//卡片效果的实际处理
		list($eb_pdata, $skills, $prefix) = \cardbase\enter_battlefield_cardproc($eb_pdata, $card);
		
		//记录原本的卡
		if(!empty($eb_pdata['o_card'])) {
			$o_card = $eb_pdata['o_card'];
			unset($eb_pdata['o_card'], $eb_pdata['cardchange']);
		}
	}
	
	//在技能载入前先在数据库插入玩家数据
	$db->array_insert("{$tablepre}players", $eb_pdata);
	
	//更新用户账户最后局数
	update_udata_by_username(array('lastgame' => $gamenum), $xuser);
	
	//////////////////////////////////////////////////////////////////////////
	///////////////////////////技能等入场后事件处理///////////////////////////
	//////////////////////////////////////////////////////////////////////////
	
	//为了兼容性（部分技能要求数据库里已有玩家这条记录），先插入玩家数组，在技能判定前再fetch回来
	
	$sk_pdata=\player\fetch_playerdata($xuser);
	
	//把skills、$o_card数组存回$sk_pdata方便调用
	$sk_pdata['skills'] = $skills;
	$sk_pdata['o_card'] = $o_card;
	
	//实际处理由skillbase接管post_enterbattlefield_events()完成
	\player\post_enterbattlefield_events($sk_pdata);
	
	//第二次保存数据
	\player\player_save($sk_pdata);
	
	///////////////////////////////////////////////////////////////
	//发布游戏内消息

	if($gamestate >= 30 && ($groupid >= 6 || $xuser == $gamefounder)){
		addnews($now,'newgm', $prefix.' '.$xuser,"{$sexinfo[$sk_pdata['gd']]}{$sk_pdata['sNo']}号");
	}else{
		addnews($now,'newpc', $prefix.' '.$xuser,"{$sexinfo[$sk_pdata['gd']]}{$sk_pdata['sNo']}号");
	}
	
	//在这里判定一次游戏停止激活……？还有这种事？
	if($validnum >= $validlimit && $gamestate == 20){
		$gamestate = 30;
	}
	save_gameinfo();
}

//你们卡片系统没有自己的函数吗？
function check_card_in_ownlist($card, $card_ownlist){
	global $gametype;
	if(in_array($card,$card_ownlist) || (5==$gametype && in_array($card,array(182, 183, 184, 185)))) return true;
	return false;
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
	if(in_array($gametype, array(2,4,18,19))) //只有卡片模式、无限复活模式、荣耀房、极速房才限制卡片
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
	$card_error['e3'] = '这张卡片在本游戏模式下禁止使用！';
	
	if(0==$gametype) //标准模式禁用挑战者以外的一切卡
	{
		foreach($card_ownlist as $cv){
			if($cv) $card_disabledlist[$cv][]='e3';
		}
		global $cardChosen,$hideDisableButton;
		$cardChosen = 0;//自动选择挑战者
		$hideDisableButton = 0;
	}
	elseif (1==$gametype)	//除错模式自动选择工程师
	{
		foreach($card_ownlist as $cv){
			if(93 != $cv) $card_disabledlist[$cv][]='e3';
		}
		global $cc,$cardChosen,$card_ownlist,$packlist,$cards,$hideDisableButton;
		$cc = $cardChosen = 93;//自动选择软件测试工程师
		$card_ownlist[] = 93;
		$packlist[] = $cards[93]['pack'] = 'Testing Fan Club';
		$hideDisableButton = 0;
	}
	elseif (5==$gametype)	//圣诞模式只允许某4张卡
	{
		$tmp_add_hidden_list = array(182, 183, 184, 185);
		foreach($card_ownlist as $cv){
			if(!in_array($cv, $tmp_add_hidden_list)) $card_disabledlist[$cv][]='e3';
		}
		global $cc,$cardChosen,$card_ownlist,$packlist,$cards,$hideDisableButton;
		$cardChosen = $cc;
		if(!in_array($cardChosen, $tmp_add_hidden_list)) {
			$cc = $cardChosen = $tmp_add_hidden_list[0];//自动选择简单难度
		}
		foreach ($tmp_add_hidden_list as $adv){
			$card_ownlist[] = $adv;
			$cards[$adv]['pack'] = 'Difficulty';
		}
		$packlist[] = 'Difficulty';
		$hideDisableButton = 0;
	}
	elseif (2==$gametype)	//deathmatch模式禁用蛋服和炸弹人
	{
		if (in_array(97,$card_ownlist)) $card_disabledlist[97][]='e3';
		if (in_array(144,$card_ownlist)) $card_disabledlist[144][]='e3';
	}
	elseif (19==$gametype)//极速模式禁用6D和CTY
	{
		if (in_array(123,$card_ownlist)) $card_disabledlist[123][]='e3';
		if (in_array(124,$card_ownlist)) $card_disabledlist[124][]='e3';
	}
	
	return array($card_disabledlist,$card_error);
}
?>