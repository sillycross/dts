<?php

//房间运转的大部分重要函数
//房间的主要信息储存在game数据表，gamedata/tmp/rooms下的文件现在只起一个开关作用。

//正常会有三个地方对房间做修改：刷新首页，来自roomcmd.php和roomupdate.php的指令，还有一个藏得比较深的是sys模块的init()函数内
//房间部分的判定又多又绕，挺屎山的，但是我也没啥好办法，求代码高手解救

//检查并刷新所有房间状态，一般从首页调用。
//会导致严重的脏数据问题，当前已废弃，改为从首页多次发送对单个房间的routine()
function room_all_routine($nowroom = NULL){
	eval(import_module('sys'));
	//startmicrotime();
	$o_room_id = NULL === $nowroom ? $room_id : $nowroom;
	$result = $db->query("SELECT groomid,groomstatus FROM {$gtablepre}game WHERE groomstatus>=40");
	$wtablepre = $gtablepre.'s';
	while($rarr = $db->fetch_array($result)){
		$room_id = $rarr['groomid'];
		$room_prefix = room_id2prefix($room_id);
		if($room_id != $o_room_id) {
			$tablepre = room_get_tablepre();
			\sys\routine();
			if(!$gamestate) {
				$db->query("UPDATE {$gtablepre}game SET groomstatus=0 WHERE groomid='{$rarr['groomid']}'");
				if(file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$rarr['groomid'].'.txt')) unlink(GAME_ROOT.'./gamedata/tmp/rooms/'.$rarr['groomid'].'.txt');
			}
		}
	}
	$room_id = $o_room_id;
	$room_prefix = room_id2prefix($room_id);
	$wtablepre = !$room_id ? $gtablepre : $gtablepre.'s';
	$tablepre = room_get_tablepre();
	//logmicrotime($GLOBALS['___MOD_SRV'] ? 'daemon模式' : '通常模式');
	return;
}

//更新单个房间状态
function update_roomstate(&$roomdata, $runflag)
{
	eval(import_module('sys'));
	
	global $roomtypelist;
	$flag=1;
	$tmp_min_team = (int)room_get_vars($roomdata, 'min-team');
	$tmp_pnum = room_get_vars($roomdata, 'pnum');
	$tmp_leader_position = room_get_vars($roomdata, 'leader-position');
	$tmp_team_ids = Array();
	for ($i=0; $i < $tmp_pnum; $i++) {//人没满就不能开
		if(!$roomdata['player'][$i]['forbidden']) {
			if ($roomdata['player'][$i]['name']=='') {
				$flag = 0;
			}elseif(!in_array($tmp_leader_position[$i], $tmp_team_ids)) {
				$tmp_team_ids[] = $tmp_leader_position[$i];
			}
		}
	}
	//队伍数过少也不能开
	if(sizeof($tmp_team_ids) < $tmp_min_team) $flag = 0;
	$changeflag = 0;
	if (!$runflag && $flag && $roomdata['readystat']==0)
	{
		$roomdata['readystat']=1;
		$ready_expired_time = !empty($roomtypelist[$roomdata['roomtype']]['ready-expire-time']) ? $roomtypelist[$roomdata['roomtype']]['ready-expire-time'] : 30;
		$roomdata['kicktime']=time()+$ready_expired_time;
		$roomdata['timestamp']++;
		for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++) $roomdata['player'][$i]['ready']=0;
		$changeflag = 1;
	}
	
	if (!$flag) { $roomdata['readystat']=0; $changeflag = 1; }
	return $changeflag;
}

//将房间状态保存进数据库
//直接存进game表
function roomdata_save($roomid, &$roomdata, $rgametype=NULL, $rgroomstatus=NULL){
	eval(import_module('sys'));
	$roomvars = gencode($roomdata);
	$extraquery = '';
	if(NULL!==$rgametype) $extraquery .= "gametype='$rgametype', ";
	if(NULL!==$rgroomstatus) $extraquery .= "groomstatus='$rgroomstatus', ";
	return $db->query("UPDATE {$gtablepre}game SET $extraquery roomvars='$roomvars' WHERE groomid='$roomid'");
}


//储存房间广播信息（改变玩家状态等）
//储存在roomlisteners表
function room_save_broadcast($roomid, &$roomdata)
{
	//保存数据并广播
	eval(import_module('sys'));
	//$result = $db->query("SELECT groomid,groomstatus,groomtype,roomvars FROM {$gtablepre}game WHERE groomid = '$roomid'");
	$rarr = fetch_roomdata($roomid);
	$runflag = 0;
	if(!empty($rarr) && $rarr['groomstatus']>=40) $runflag = 1; 
	
	update_roomstate($roomdata,$runflag);
	roomdata_save($roomid, $roomdata);
	//writeover(GAME_ROOT.'./gamedata/tmp/rooms/'.$roomid.'.txt', gencode($roomdata));
	touch(GAME_ROOT.'./gamedata/tmp/rooms/'.$roomid.'.txt');
	$result = $db->query("SELECT * FROM {$gtablepre}roomlisteners WHERE roomid = '$roomid' AND timestamp < '{$roomdata['timestamp']}'");
	if ($db->num_rows($result))
	{
		$str='('; $lis=Array();
		while ($data=$db->fetch_array($result))
		{
			$str.="('".$data['port']."','".$data['roomid']."','".$data['timestamp']."','".$data['uniqid']."'),";
			array_push($lis,$data['port']);
		}
		$str=substr($str,0,-1).')';
		$db->query("DELETE FROM {$gtablepre}roomlisteners WHERE (port,roomid,timestamp,uniqid) IN $str");
		foreach ($lis as $port)
		{
			$___TEMP_socket=socket_create(AF_INET,SOCK_STREAM,SOL_TCP);  
			if ($___TEMP_socket===false) continue;
			$___TEMP_connected=socket_connect($___TEMP_socket,'127.0.0.1',$port);
			if (!$___TEMP_connected) continue;
			socket_shutdown($___TEMP_socket);
		}
	}
}
	
//房间初始化
function room_init($roomtype)
{
	$a['roomtype']=$roomtype;
	//数据库中的groomstatus字段意义：
	//0 房间关闭（上局游戏已结束）
	//10 房间开启（游戏未开始）
	//40 房间开启（游戏已开始）
	//20和30本来是留给readystat的，后来想想分开也好
	
	//readystat在数据库groomstatus字段为1时才有意义
	//0 等待玩家
	//1 人数已满（等待所有玩家点击准备，并进入踢人倒计时）
	//2 即将开始（正在进行游戏初始化工作）
	
	$a['readystat']=0;
	$a['roomfounder']='';
	
	//踢人时间，由使roomstat进入1的操作者负责设置
	$a['kicktime']=0;
	
	//各位置信息
	global $roomtypelist;
	$rdpnum = $roomtypelist[$roomtype]['pnum'];
	for ($i=0; $i<$rdpnum; $i++)
	{
		//在该位置的玩家名
		$s['name']='';
		//准备状态（roomstat=1有效，由使roomstat进入1的操作者负责重置）
		$s['ready']=0;
		//该位置是否被禁入
		$s['forbidden']=0;
		//该位置所属队伍（如等于该位置自身编号，则为队长，队长可以将本队其他位置设置禁入）
		$s['leader']=$roomtypelist[$roomtype]['leader-position'][$i];
		$a['player'][$i]=$s;
		unset($s);
	}
	
	//时间戳，用于更新
	$a['timestamp']=1;
	
	//最近10条聊天信息
	$a['chatdata']=Array();
	for ($i=0; $i<10; $i++)
	{
		$s['cid'] = -1;
		$s['data'] = '';
		$a['chatdata'][$i]=$s;
		unset($s);
	}
	
	//游戏选项
	$a['current_game_option']=room_init_game_option($roomtype);
	return $a;
}

//初始化游戏特殊参数
function room_init_game_option($roomtype){
	eval(import_module('sys'));
	global $roomtypelist;
	$r = array();
	if (isset($roomtypelist[$roomtype]['game-option'])){
		foreach ($roomtypelist[$roomtype]['game-option'] as $gokey => $goval){
			foreach($goval['options'] as $oval){
				if(isset($oval['default']) && $oval['default']){
					$r[$gokey] = $oval['value'];
					break;
				}
			}
			if(!isset($r[$gokey])) $r[$gokey]=NULL;
		}
	}
	return $r;
}

//检查游戏特殊参数是否合法
function room_check_game_option($roomtype, $gokey, $oval){
	eval(import_module('sys'));
	global $roomtypelist;
	if(!isset($roomtypelist[$roomtype]['game-option'])) {return false;}
	$go = $roomtypelist[$roomtype]['game-option'];
	if(!isset($go[$gokey])) {return false;}
	if($go[$gokey]['type'] == 'number')
	{
		if (isset($go[$gokey]['min']) && ((int)$oval < $go[$gokey]['min'])) return false;
		if (isset($go[$gokey]['max']) && ((int)$oval > $go[$gokey]['max'])) return false;
		return true;
	}
	else
	{
		$ovlist = array();
		foreach($go[$gokey]['options'] as $ov){
			$ovlist[] = $ov['value'];
		}
		if(!in_array($oval, $ovlist)) {return false;}
		else {return true;}
	}
}

//加载游戏特殊参数
function room_set_game_option(&$roomdata, $gokey, $oval){
	if(!room_check_game_option($roomdata['roomtype'], $gokey, $oval)) return false;
	$roomdata['current_game_option'][$gokey] = $oval;
}

//获取数据库中的房间数据；$roomid取值可以是'ALL'
//如果无符合条件，返回NULL；如果$roomid为ALL则返回多元素的数组，否则返回第一个找到的房间
function fetch_roomdata($roomid, $roomstate=NULL){
	eval(import_module('sys'));
	$rdata = Array();
	$query = "SELECT groomid,groomstatus,groomtype,roomvars FROM {$gtablepre}game ";
	if('ALL' == $roomid) {
		$query .= "WHERE groomid > 0 ";
	}else {
		$roomid = (int)$roomid;
		$query .= "WHERE groomid = '$roomid' ";
	}
	if(NULL!==$roomstate){
		$roomstate = (int)$roomstate;
		$query .= "AND groomstate = '$roomstate' ";
	}
	$query .= "ORDER BY groomid";
	$result = $db->query($query);
	while($rsingle = $db->fetch_array($result)){
		$rdata[] = $rsingle;
	}
	if(!$rdata) return NULL;
	elseif('ALL' !== $roomid) return $rdata[0];
	else return $rdata;
}

//获得房间参数，如果房间数组没有就去$roomtypelist里找
function &room_get_vars(&$roomdata, $varname){
	global $roomtypelist;
	$null = NULL;
	$r = &$null;
	if(isset($roomdata[$varname])) $r = &$roomdata[$varname];
	elseif(isset($roomtypelist[$roomdata['roomtype']][$varname])) $r = &$roomtypelist[$roomdata['roomtype']][$varname];
	elseif(isset($roomdata['current_game_option'][$varname])) $r = &$roomdata['current_game_option'][$varname];
	
	//pnum特判
	if('pnum' == $varname && isset($roomdata['current_game_option']['group-num'])) {
		$val = $roomdata['current_game_option']['group-num'] * 5;//没法传引用了，应该不会踩雷吧
		$r = &$val;
	}
	return $r;
}

//根据$roomtypelist的设定，从$gtype反查对应的房间（特殊局）下标
//比如通过$gtype==10（SOLO模式）反查到特殊局下标为0
function room_gettype_from_gtype($gtype){
	global $roomtypelist;
	$r = NULL;
	foreach($roomtypelist as $rtk => $rtv){
		if($rtv['gtype'] == $gtype) {
			$r = $rtk;
			break;
		}
	}
	return $r;
}

//获得房间内玩家数和所需玩家数
function room_participant_get($roomdata){
	$rdplist = room_get_vars($roomdata, 'player');
	$rdpnum = $m = room_get_vars($roomdata, 'pnum');
	$p = 0;
	for ($i=0; $i < $rdpnum; $i++)
	{
		if ($rdplist[$i]['name']!='')
		{
			$p++;
		}
		else  if ($rdplist[$i]['forbidden'])
		{
			$m--;
		}
	}
	return array($p, $m);
}

//获得$user所在房间的位置，如果不在房间内则返回-1，没赋值$user的话默认用$cuser
function room_upos_check($roomdata, $user=NULL){
	//global $roomtypelist;
	if(!$user) {
		eval(import_module('sys'));
		$user = $cuser;
	}
	$upos = -1;
	$rdplist = room_get_vars($roomdata, 'player');
	$rdpnum = room_get_vars($roomdata, 'pnum');
	if(empty($rdpnum)) //会出现这个情况一般是从daemon调用的
	{
		global $roomtypelist;
		include GAME_ROOT.'./include/roommng/roommng.config.php';
		$rdpnum = room_get_vars($roomdata, 'pnum');
	}
	for ($i=0; $i < $rdpnum; $i++) {
		if (!$rdplist[$i]['forbidden'] && $rdplist[$i]['name']==$user){
			$upos = $i;
			break;
		}
	}
	return $upos;
}

//人数已满又长时间不准备的话自动踢人
function room_auto_kick_check(&$roomdata){
	$changed = 0;
	if (room_get_vars($roomdata, 'readystat')==1 && time()>=room_get_vars($roomdata, 'kicktime'))
	{
		$rdplist = & room_get_vars($roomdata, 'player');
		$rdpnum = room_get_vars($roomdata, 'pnum');
		for ($i=0; $i < $rdpnum; $i++) 
			if (!$rdplist[$i]['forbidden'] && !$rdplist[$i]['ready'] && $rdplist[$i]['name']!='')
			{
				room_new_chat($roomdata,"<span class=\"grey b\">{$rdplist[$i]['name']}因为长时间未准备，被系统踢出了位置。</span><br>");
				$rdplist[$i]['name']='';
			}
		$changed = 1;
	}
	return $changed;
}

//提供队长位置，把该队伍所有的位置设为开启
function room_refresh_team_pos(&$roomdata,$pos){
	//global $roomtypelist;
	$rdplist = & room_get_vars($roomdata, 'player');
	$rdpnum = room_get_vars($roomdata, 'pnum');
	for ($i=0; $i < $rdpnum; $i++)
		if ($pos == room_team_leader_check($roomdata,$i) && $rdplist[$i]['forbidden'])
			{
				$rdplist[$i]['forbidden']=0;
				$rdplist[$i]['name']='';
				$rdplist[$i]['ready']=0;
			}
}

//得到房主位置，目前条件只有处于0号格子
function room_creater_check($pos) {
	return 0==$pos;
}

//得到一个位置所属队伍的队长位置
function room_team_leader_check($roomdata,$pos) {
	//global $roomtypelist;
	return room_get_vars($roomdata, 'leader-position')[$pos];
}

//建立新房间，由于永续房和荣耀房等一些怪东西，判定有些绕
//注意这只是建立房间，开启游戏是在PVP房开始或者永续房进房等操作时才进行的
function room_create($roomtype)
{
	eval(import_module('sys'));
	if ($disable_newgame || $disable_newroom) {
		gexit('系统维护中，暂时不开放新房间',__file__,__line__);
		return;
	}
	global $roomtypelist,$max_room_num, $max_private_room_num;
	
	$roomtype=(int)$roomtype;
	if (!isset($roomtypelist[$roomtype])){
		gexit('房间参数错误',__file__,__line__);
		return;
	}
	elseif(!check_room_available($roomtypelist[$roomtype])){
		gexit('该房间类型暂不能使用',__file__,__line__);
		return;
	}
	$rchoice = -1;
	$rsetting = $roomtypelist[$roomtype];
	$rdata = fetch_roomdata('ALL');
	
	if(!empty($rsetting['soleroom'])){//永续房特判
		$rid = -1;
		$rids = range(1,$max_room_num);
		foreach($rdata as $rd){
			$rid = $rd['groomid'];
			$rids = array_diff($rids, Array($rid));
			if($rd['groomtype'] == $roomtype && $rd['groomstatus'] > 0){//永续房存在的情况下直接进
				$rchoice = $rid;
				break;
			}elseif($rd['groomstatus'] == 0){//房间关闭状态，改成永续房
				$rchoice = $rid;
				$db->query("UPDATE {$gtablepre}game SET gamestate = 0, groomstatus = 10, groomtype = '$roomtype',  roomvars='' WHERE groomid = '$rid'");
				break;
			}
		}
		if(!empty($rids) && $rchoice < 0){//否则新建房间
			$rchoice = $rids[0];
			$db->query("INSERT INTO {$gtablepre}game (groomid,groomstatus,groomtype) VALUES ('$rchoice',10,'$roomtype')");
			//$db->query("UPDATE {$gtablepre}rooms SET status = 1, roomtype = '$roomtype' WHERE roomid = '$rid'");
		}
	}else{
		//非永续房：
		//第一步，需要先拉取所有房间状态，获取类别数目等数据备用
		$result = $db->query("SELECT groomid, groomtype, groomstatus, roomvars FROM {$gtablepre}game ORDER BY groomid");
		$soleroomnum = 0;
		$max_room_num_temp = $max_room_num;
		$roomarr = array();
		$exist_roomnum_bytype = array();
		$founded_roomnum = 0;
		$founded_roomnum_bytype = array();
		//挨个房间记录类别和数量
		while($rrs = $db->fetch_array($result)){
			$rrs['roomvars'] = gdecode($rrs['roomvars'],1);
			$rrsid = $rrs['groomid'];
			$roomarr[$rrsid] = $rrs;
			//永续房跳过，同时增加计数房间上限
			if($roomtypelist[$rrs['groomtype']]['soleroom']) {
				$max_room_num_temp++;
				continue;
			}
			//文件不存在则跳过
			$file = GAME_ROOT.'./gamedata/tmp/rooms/'.$rrsid.'.txt';
			if(!file_exists($file)) {
				continue;
			}
			//writeover('a.txt',$file,'ab+');
			if(isset($exist_roomnum_bytype[$rrs['groomtype']])) $exist_roomnum_bytype[$rrs['groomtype']]++;
			else $exist_roomnum_bytype[$rrs['groomtype']] = 1;
			
			//单个类别房间达限
			if(!empty($roomtypelist[$rrs['groomtype']]['globalnum']) && $exist_roomnum_bytype[$rrs['groomtype']] >= $roomtypelist[$rrs['groomtype']]['globalnum']	&& $rrs['groomtype'] == $roomtype) {
				gexit("{$roomtypelist[$rrs['groomtype']]['name']}房间数目已达上限，请在其中任一房间游戏结束后再尝试创建房间！",__file__,__line__);
				return;
			}
			
			//个人建房总数达限
			if($rrs['groomstatus'] > 0 && isset($rrs['roomvars']['roomfounder']) && $rrs['roomvars']['roomfounder']==$cuser && !$roomtypelist[$rrs['groomtype']]['soleroom']){
				$founded_roomnum++;					
				if(isset($founded_roomnum_bytype[$rrs['groomtype']])) $founded_roomnum_bytype[$rrs['groomtype']]++;
				else $founded_roomnum_bytype[$rrs['groomtype']] = 1;
				
				if(!$roomtypelist[$rrs['groomtype']]['without-ready'] && !empty($roomtypelist[$rrs['groomtype']]['privatenum']) && $founded_roomnum_bytype[$rrs['groomtype']] >= $roomtypelist[$rrs['groomtype']]['privatenum']) {
					gexit("你已经创建了{$roomtypelist[$rrs['groomtype']]['privatenum']}个{$roomtypelist[$rrs['groomtype']]['name']}房间，请在其中任一房间游戏结束后再尝试创建房间！",__file__,__line__);
					return;
				}elseif($founded_roomnum >= $max_private_room_num){
					gexit("你已经创建了{$max_private_room_num}个以上的房间，请在其中任一房间游戏结束后再尝试创建房间",__file__,__line__);
					return;
				}
			}
		}
		//第二步，遍历房号，找到空的房号插入数据，或者覆盖关闭中的房间
		for ($i=1; $i<=$max_room_num_temp; $i++)
		{
			//有空房号，在game表插入数据
			if(!isset($roomarr[$i])) 
			{
				$db->query("INSERT INTO {$gtablepre}game (gamestate,groomid,groomstatus,groomtype) VALUES (0,'$i',10,'$roomtype')");
				$rchoice = $i; break;
			}
			else 
			{
				//有关闭中的房间，则修改数据并设为打开
				if ($roomarr[$i]['groomstatus']==0)
				{
					$db->query("UPDATE {$gtablepre}game SET gamestate = 0, groomstatus = 10, groomtype = '$roomtype', roomvars='' WHERE groomid = '$i'");
					$rchoice = $i; break;
				}
			}
		}
	}	
	if ($rchoice == -1)
	{
		gexit('房间数目已经达到上限，请加入一个已存在的房间',__file__,__line__);
		return;
	}
	//房间等待变量初始化
	$roomdata = room_init($roomtype);
	//房间数据库初始化
	room_init_db_process($rchoice);
	$roomdata['player'][0]['name']=$cuser;
	$roomdata['roomfounder']=$cuser;
	touch(GAME_ROOT.'./gamedata/tmp/rooms/'.$rchoice.'.txt');
	roomdata_save($rchoice, $roomdata, $roomtypelist[$roomtype]['gtype']);
	//writeover(GAME_ROOT.'./gamedata/tmp/rooms/'.$rchoice.'.txt', gencode($roomdata));
	$db->query("DELETE from {$gtablepre}roomlisteners WHERE roomid = '$rchoice'"); 
//	if($rsetting['soleroom']){
//		room_enter($rchoice);
//	}
	return $rchoice;
}

//发送房内聊天
//只保留10条记录，嗯……不愧是sc，思路清奇
//会无视空的消息
function room_new_chat(&$roomdata,$str)
{
	if(!empty($str)) {
		for ($i=1; $i<=9; $i++) $roomdata['chatdata'][$i-1]=$roomdata['chatdata'][$i];
		$roomdata['chatdata'][9]['cid']=max($roomdata['chatdata'][8]['cid'],0)+1;
		$roomdata['chatdata'][9]['data']=$str;
	}
	room_timestamp_tick($roomdata);
}

//房间时间计数+1，用来推送消息
function room_timestamp_tick(&$roomdata)
{
	$roomdata['timestamp']++;
}

//进入房间
//$force_reset==1则强制reset房间（重开游戏）仅对不需要准备的房间有效，需要准备的房间在开启时才重开
function room_enter($id, $force_reset = 0)
{
	eval(import_module('sys'));
	$id=(int)$id;
	$rd = fetch_roomdata($id);
	//$result = $db->query("SELECT groomid,groomstatus,groomtype,roomvars FROM {$gtablepre}game WHERE groomid = '$id'");
	//if(!$db->num_rows($result)) 
	if(empty($rd)) 
	{
		gexit('房间'.$id.'数据记录不存在',__file__,__line__);
		return;
	}
	//$rd=$db->fetch_array($result);
	if ($rd['groomstatus']==0)
	{
		gexit('房间'.$id.'已关闭',__file__,__line__);
		return;
	}
	
	if (!file_exists(GAME_ROOT.'./gamedata/tmp/rooms/'.$id.'.txt')) 
	{
		gexit('房间'.$id.'缓存文件不存在',__file__,__line__);
		return;
	}
	//$roomdata = gdecode(file_get_contents(GAME_ROOT.'./gamedata/tmp/rooms/'.$id.'.txt'),1);
	$roomdata = gdecode($rd['roomvars'], 1);
	//global $cuser;
	global $roomtypelist, $gametype, $startime, $now, $room_prefix, $alivenum, $soleroom_resettime, $soleroom_private_resettime;
	//不需要点击准备的房间，主要是永续房（教程）或者荣耀等随时开放的房间。
	//这类房间在游戏开启后是不显示准备界面的，永续房进入后会跳过选卡界面直接进入游戏，其他房间进入后显示首页，可以点选加入游戏或者查看进行状态
	if($roomtypelist[$rd['groomtype']]['without-ready']){
		//系统维护中，不能通过加入房间来创建新房间
		if ($rd['groomstatus'] < 40 && ($disable_newgame || $disable_newroom)) {
			gexit('系统维护中，暂时不能加入房间',__file__,__line__);
			return;
		}
		//获得房间编号等基本数据
		$room_prefix = room_id2prefix($id);
		$room_id = $id;
		//$tablepre = $gtablepre.$room_prefix.'_';
		$tablepre = room_get_tablepre();
		$wtablepre = $gtablepre.room_prefix_kind($room_prefix);
		//载入房间对应的当前局数据
		\sys\load_gameinfo();
		$init_state = room_init_db_process($room_id); 
		//判定是否需要重置房间
		//暂定需要修改这里：只有新创建房间或者点击开始时才重置，旧房结束后不会自动重置
		$need_reset = $rd['groomstatus'] == 10 && $force_reset ? true : false;
		if($roomtypelist[$rd['groomtype']]['soleroom'] && !($init_state & 4)){//教程房特殊设定，读取最后有玩家行动的时间，如果超时则需要重置，防止房间各种记录飙得太长
			$result = $db->query("SELECT endtime FROM {$tablepre}players WHERE type=0 ORDER BY endtime DESC LIMIT 1");
			if($db->num_rows($result)){
				$lastendtime = $db->fetch_array($result)['endtime'];				
				if($now - $lastendtime > $soleroom_resettime) $need_reset = 1;
			}
		}
		//重置房间：把房间状态设为进行中，同时初始化$gamestate、$gametype等当前局数据
		if($need_reset){
			//这段基本没法复用，就这么写吧（需要准备的房间有一套更琐碎的流程）
			$groomstatus = 40;
			$gamestate = 0;
			$gametype = $roomtypelist[$rd['groomtype']]['gtype'];
			$starttime = $now;
			\sys\save_gameinfo(0);
			\sys\routine();
		}
		//如果不显示选卡界面就直接进入房间，在这里处理（目前只有教程）
		if($roomtypelist[$rd['groomtype']]['without-valid']){
			$pname = (string)$cuser;
			global $cudata;
			$udata = $cudata;
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE name = '$pname' AND type = 0");
			if(!$db->num_rows($result)){//从未进入过则直接进入战场
				include_once GAME_ROOT.'./include/valid.func.php';
				enter_battlefield($udata['username'],$udata['password'],$udata['gender'],$udata['icon'],$pcard,$udata['ip']);
			}elseif($roomtypelist[$rd['groomtype']]['soleroom']){//教程房特判，离开超过一定时间则清空数据从头开始
				$pdata = $db->fetch_array($result);
				$ppid = $pdata['pid'];
				$pendtime = $pdata['endtime'];
				if($now - $pendtime > $soleroom_private_resettime){
					$db->query("DELETE FROM {$tablepre}players WHERE name = '$pname' AND type = 0");
					$db->query("DELETE FROM {$tablepre}players WHERE type>0 AND teamID = '$ppid'");
					$alivenum --;
					include_once GAME_ROOT.'./include/valid.func.php';
					enter_battlefield($udata['username'],$udata['password'],$udata['gender'],$udata['icon'],$pcard,$udata['ip']);
				}
			}
		}
		//最后设定跳转到的页面
		//新开始游戏或者永续房，则直接转入游戏页面
		if($gamestate < 30 && ($need_reset || $roomtypelist[$rd['groomtype']]['soleroom'])) $header = 'game.php';
		//否则打开首页房间小窗
		else $header = 'index.php';
	}else{
		//需要准备的房间，只是加入房间准备页面
		$header = 'index.php';
	}
	room_new_chat($roomdata,"<span class=\"grey b\">{$cuser}进入了房间</span><br>");
	room_save_broadcast($id,$roomdata);
	set_current_roomid($id);
	//update_udata_by_username(array('roomid' => $id), $cuser);
	//给js前端发指令强制跳转
	echo 'redirect:'.$header;
	return 1;
}


//重载房间准备界面
function room_showdata($roomdata, $user)
{
	global $room_id;
	include GAME_ROOT.'./include/roommng/roommng.config.php';
	$upos = room_upos_check($roomdata, $user);
//	$upos = -1;
//	for ($i=0; $i<$roomtypelist[$roomdata['roomtype']]['pnum']; $i++)
//		if (!$roomdata['player'][$i]['forbidden'] && $roomdata['player'][$i]['name']==$user)
//			$upos = $i;
			
	ob_clean();
	ob_start();
	include template('roommain');
	$gamedata['innerHTML']['roommain'] = ob_get_contents();
	if ($roomdata['readystat']==2) $gamedata['innerHTML']['roomchatarea'] = '<div></div>';
	$gamedata['value']['timestamp'] = $roomdata['timestamp'];
	if ($roomdata['readystat']!=2) $gamedata['lastchat']=$roomdata['chatdata'];
	ob_clean();
	echo gencode($gamedata);
}

//用于news页面显示队伍数据
function room_getteamhtml(&$roomdata, $u)
{
	global $roomtypelist;
	$str='';
	$rdplist = room_get_vars($roomdata, 'player');
	$rdpnum = room_get_vars($roomdata, 'pnum');
	for ($i=0; $i < $rdpnum; $i++)
		if (!$rdplist[$i]['forbidden'] && $rdplist[$i]['name']!='' && room_get_vars($roomdata, 'leader-position')[$i]==$u)
		{
			$str.=$rdplist[$i]['name'].',';
		}
	if ($str!='') $str=substr($str,0,-1);
	return $str;
}

//房间数据库初始化
function room_init_db_process($room_id){
	global $gtablepre,$db;
	$room_prefix = room_id2prefix($room_id);
	$init_state = 0;
	
	$tablepre = room_get_tablepre();
	$wtablepre = $gtablepre.'s';
	//$tablepre = $gtablepre.$room_prefix.'_';
	//创建对应类型的优胜列表
	$result = $db->query("SHOW TABLES LIKE '{$wtablepre}history';");
	if (!$db->num_rows($result))
	{
		$db->query("CREATE TABLE IF NOT EXISTS {$wtablepre}history LIKE {$gtablepre}history;");
		$db->query("INSERT INTO {$wtablepre}history (gid) VALUES (0);");
		$init_state += 1;
	}

	//如果该房间对应的各数据表不存在（以players表为判断依据），则创建
	$result = $db->query("SHOW TABLES LIKE '{$tablepre}players';");
	$r2 = $db->num_rows($result);
	if (!$r2)
	{
		$sql = file_get_contents(GAME_ROOT.'./gamedata/sql/reset.sql');
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		$db->queries($sql);
		
		$sql = file_get_contents(GAME_ROOT.'./gamedata/sql/players.sql');
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		$db->queries($sql);
		
		$sql = file_get_contents(GAME_ROOT.'./gamedata/sql/shopitem.sql');
		$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$tablepre, $sql));
		$db->queries($sql);
		$init_state += 4;
	}
	return $init_state;
}

//判定房间类别是否满足代码上的开放条件（开关开启、时间开启、前置mod开启）
function check_room_available($roomtypedata){
	eval(import_module('sys'));
	$ret = true;	
	if(!$roomtypedata['available']) $ret = false;
	if(!empty($roomtypedata['available-start']) && $now < $roomtypedata['available-start']) $ret = false;
	if(!empty($roomtypedata['available-end']) && $now > $roomtypedata['available-end']) $ret = false;
	if(!empty($roomtypedata['req-mod']) && !defined('MOD_'.strtoupper($roomtypedata['req-mod']))) $ret = false;
	return $ret;
}

//关闭房间，如果不提供$rid则关闭玩家当前所在的房间
//只有当前存活玩家数为0的时候，房主才能关闭房间
//
function room_close_in_game($rid = 0){
	eval(import_module('sys'));
}

/* End of file roommng.func.php */
/* Location: /include/roommng/roommng.func.php */