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
	
	if(isset($eb_pdata['gamevars'])) unset($eb_pdata['gamevars']);
	
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
	if(!empty($skills)) $sk_pdata['skills'] = $skills;
	if(!empty($o_card)) $sk_pdata['o_card'] = $o_card;
	
	//实际处理由skillbase等模块接管post_enterbattlefield_events()完成
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

?>