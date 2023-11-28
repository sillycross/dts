<?php

//策划阶段叫searchmemory（探索记忆），但文案里用的是“视野”。以下不再分别说明
//现分为两个层级：slot（视野）指界面直接显示并能无代价重新访问的道具/角色，record（记忆）指有记录但是需要单独开界面并且有风险才能访问的道具/角色

namespace searchmemory
{
	function init() {}
	
	$searchmemory_now_iids = Array();//当前记忆中所有物品iid组成的数组
	$searchmemory_now_pids = Array();//当前记忆中所有角色pid组成的数组
	
	//判定当前游戏类型（或者其他一些情况）是否开启探索记忆模式
	function searchmemory_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','searchmemory'));
		return !in_array($gametype, $searchmemory_disabled_gtype);
	}
	
	//初始化$searchmemory变量
	//传入数据库拉取的searchmemory字段，返回gedecode过的数据
	//传入空值或者不是合法字符串的任何其他东西都会返回空数组
	function init_searchmemory($smstring){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($smstring)) {
			$ret = Array();
		}
		elseif(is_string($smstring)) {
			$ret = gdecode($smstring,1);
			if(!is_array($ret)) $ret = Array();
		}
		return $ret;
	}
	
	//修改丢弃按钮的提示
	function init_playerdata() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain'));
		if(searchmemory_available()) {
			$itemmain_drophint = '将留在视野中';
		}
		$chprocess();
	}
	
	//玩家数据的处理
	function load_playerdata($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','searchmemory'));
		$chprocess($pdata);
		if(!is_array($searchmemory)) {
			$searchmemory = init_searchmemory($searchmemory);
		}
	}
	
	//对从数据库里读出来的raw数据的处理
	function playerdata_construct_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$data = $chprocess($data);
		if(!is_array($data['searchmemory'])) {
			$data['searchmemory'] = init_searchmemory($data['searchmemory']);
		}
		return $data;
	}
	
	//获得当前记忆中所有物品iid或者pid组成的数组
	//2023.09.27全部改成兼容操作其他玩家数据的
	function get_searchmemory_now_ids($tp = 'iid', &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		$smarr_all = & $pa['searchmemory'];
		$ret = Array();
		foreach($smarr_all as $val){
			if(isset($val[$tp])){
				$ret[] = $val[$tp];
			}
		}
		return $ret;
	}
	
	//保存玩家数据到数据库之前，把$searchmemory转义
	function player_save($data, $in_proc = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		$tmp_searchmemory = $data['searchmemory'];
		if(is_array($data['searchmemory'])) {
			$data['searchmemory'] = gencode($data['searchmemory']);
		}
		$chprocess($data, $in_proc);
		//哇，这只虫找了我两个小时
		//某些流程在player_save()后还会对$player数据进行处理，比如获胜，必须把$searchmemory还原回去
		//低于8.0的php不会报FATAL ERROR，非adv模式运行路径不同也不会报错，坑爹死我了
		$data['searchmemory'] = $tmp_searchmemory;			
	}
	
	//$searchmemory内的元素是数组，分为两种类型：
	//一个是只包含iid、pls、itm和unseen的道具数组，另一个只包含pid、Pname、pls、smtype和unseen的敌人数组，其中smtype决定是敌人、尸体、同伴还是谜（雾天）
	//区分是显示在视野中还是留在记录中，是根据当前视野数和moveflag判断的，$searchmemory的前视野数个的元素，并且unseen=0才能得到显示
	//unseen只在离开地图时统一修改，一是减少修改次数，二是如果视野临时变化，还能恢复一部分的可见元素
	
	//计算视野数
	//由于钦定（天气负面转化为正面）这种技能存在，需要单独抽离
	function calc_memory_slotnum(&$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
//		if(empty($pa)) {
//			eval(import_module('player'));
//			$pa = & $sdata;
//		}	
		eval(import_module('sys','searchmemory'));
		if(!isset($searchmemory_max_slotnum)) $searchmemory_max_slotnum = 1;
		$memory_loss = 0;
		if(isset($weather_memory_loss[$weather]))	$memory_loss = $weather_memory_loss[$weather];
		//钦定来得早，视野来得迟，为了避免循环继承，耦合就耦合了吧
		if(\skillbase\skill_query(245)) $memory_loss = -$memory_loss;
		$ret = $searchmemory_max_slotnum + $memory_loss;
		if($ret < 1) $ret = 1;
		return $ret;
	}
	
	//计算记录数
	function calc_memory_recordnum(&$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('searchmemory'));
		$ret = $searchmemory_max_recordnum;
		return $ret;
	}

	//把传入的$marr数组插入数组，也负责对数组初始化
	//加入的一定是可见的
	//返回$marr
	function add_memory_core($marr, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		if(empty($pa['searchmemory'])) $pa['searchmemory'] = Array();//当前玩家数据的$searchmemory其实已经初始化过了，这里是防御性的写法
		$smarr_all = & $pa['searchmemory'];
		if(!empty($marr['unseen'])) $marr['unseen'] = 0;
		//if(!empty($marr['smtype']) && 'unknown' == $marr['smtype']) $marr['smtype'] = 'enemy';
		array_push($smarr_all, $marr);
		return $marr;
	}
	
	//把道具加入视野和记忆列表，同时也触发丢失最早的视野/记忆
	function add_memory($marr, $showlog = 1, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		//必须开启视野且传入数组合法
		if(!searchmemory_available() || empty($marr)) return;
		
		//连斗后不会把尸体加入视野
		if(!empty($marr['smtype']) && $marr['smtype'] == 'corpse' && \gameflow_combo\is_gamestate_combo()) return;
		
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		$smarr_all = & $pa['searchmemory'];
		eval(import_module('sys','logger','searchmemory'));
	
		//获取实际的视野和记忆数
		$searchmemory_real_slotnum = calc_memory_slotnum($pa);
		$searchmemory_real_recordnum = calc_memory_recordnum($pa);
		
		//加入记忆一定加入视野，所以这里显示的是视野相关的提示
		$amflag = 0;
		if(isset($marr['itm'])){
			$marr = add_memory_itm_process($marr, $pa);
			$amn = $marr['itm'];
			$amflag = 1;
			if($showlog) {
				if(\player\check_fog()) $log .= '你能隐约看到'.$amn.'的位置。<br>';
				else $log .= '你设法保持对'.$amn.'的持续观察。<br>';
			}
		}elseif(isset($marr['Pname'])){
			$amn = $marr['Pname'];
			$amflag = 1;
			if($showlog) {
				if($marr['smtype'] == 'corpse' ) $log .=  '你设法保持对'.$amn.'的尸体的持续观察。<br>';
				elseif(\player\check_fog()) $log .= '你努力让那个人影保持在视野之内。<br>';
				else $log .= '在离开的同时，你设法保持对'.$amn.'的持续观察。<br>';
			}
		}
		//实际加入记忆
		if($amflag){
			add_memory_core($marr, $pa);
			//array_push($searchmemory, $marr);
		}
		//如果因为视野已满导致有道具被从视野挤到了记忆部分，提示并标注unseen
		//首先，只有总记忆数超过视野数的时候才做这个判定，很显然
		if(sizeof($smarr_all) > $searchmemory_real_slotnum){
			//只检测位于视野边界的那个道具
			$thatm = & $smarr_all[get_slot_edge($pa) - 1];
			//如果在同一地图且没有被标注unseen则提示一下
			if($showlog && empty($thatm['unseen']) && $thatm['pls'] == $pa['pls']) {
				$rmn = get_memory_name($thatm, $pa);
				if(\player\check_fog() && isset($thatm['Pname']) && $thatm['smtype'] != 'corpse') $log .= '先前的人影看不见了，但你仍记得其大致方位。<br>';
				else $log .= $rmn.'看不见了，但你仍记得其大致方位。<br>';
				//标记unseen，确保不反复被提示，也避免拾取道具之后又让移出视野的道具自己长脚跑回来
				$thatm['unseen'] = 1;
			}
		}
		//超出记忆范围则删掉最老的记忆
		while(sizeof($smarr_all) > $searchmemory_real_recordnum){
			remove_memory(0, $showlog, $pa);
		}
	
		return;
	}
	
	//在加入视野前对道具进行处理的函数，本模块不会进行任何处理。返回道具数组，注意不会对传入的道具数组做修改
	function add_memory_itm_process($marr, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $marr;
	}
	
	//获得当前视野边缘（仍位于视野）的记忆的下标（注意不是个数）
	function get_slot_edge(&$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		return sizeof($pa['searchmemory']) - calc_memory_slotnum($pa);
	}
	
	//判断一个下标是不是超出了视野
	function check_out_of_slot_edge($mn, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = false;
		if($mn < get_slot_edge($pa)) $ret = true;
		return $ret;
	}
	
	//查找特定iid或者pid的记忆，$id为需查找的id，$ikind为类别（默认为pid）
	//返回记忆数组下标
	function seek_memory_by_id($id, $ikind = 'pid', &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		$smarr_all = & $pa['searchmemory'];
		if(searchmemory_available()){
			foreach($smarr_all as $i => $sm){
				if(isset($sm[$ikind]) && $sm[$ikind] == $id){
					return $i;
				}
			}
		}
		return -1;
	}
	
	//把特定记忆改为不可见，目前主要是移动后用于把所有视野里的道具都改为不可见
	//$mn为修改哪一个下标的数组，默认是0，如果为-1则是最末位，如果是字符串'ALL'则把整个$searchmemory改为不可见
	//返回被修改的那个数组，如果是全部修改则返回整个$searchmemory
	function change_memory_unseen($mn = 0, $showlog = 1, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		$smarr_all = & $pa['searchmemory'];
		
		$ret = Array();
		
		if(empty($smarr_all)) return $ret;//如果视野本来就是空的就什么都不做
		
		if($mn === 'ALL'){
			$ret = $smarr_all;
			$flag = 0;
			foreach($smarr_all as &$v){
				if(empty($v['unseen']))
				{
					$v['unseen'] = 1;
					$flag = 1;
				}
			}
			if($flag && $showlog) {
				eval(import_module('logger'));
				$log .= '你先前所见的一切东西都离开了视线。<br>';
			}
			return $ret;
		}
		if($mn == -1){
			$mn = sizeof($smarr_all) - 1;
		}
		if(isset($smarr_all[$mn])){
			$ret = $smarr_all[$mn];
			$smarr_all[$mn]['unseen'] = 1;
		}
		return $ret;
	}
	
	//移除记忆的核心函数，根据传入的指令移除不同的记忆数组
	//$mn为移除哪一个下标的数组，默认是0，如果为-1则是最末位，如果是字符串'ALL'则重置整个$searchmemory数组
	//返回被移除的那个数组，如果是全部删除则返回整个$searchmemory
	function remove_memory_core($mn = 0, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		$smarr_all = & $pa['searchmemory'];
		$ret = Array();
		if($mn === 'ALL'){
			$ret = array_clone($smarr_all);
			$smarr_all = array();
			return $ret;
		}
		if($mn == -1){//把刚拿到的忘掉
			$mn = sizeof($smarr_all) - 1;
		}
		if(isset($smarr_all[$mn])){
			$ret = $smarr_all[$mn];
			array_splice($smarr_all,$mn,1);
		}
		return $ret;
	}
	
	//获得记忆数组的名称（道具名或者角色名）
	function get_memory_name($marr, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = '';
		if(isset($marr['itm'])) $ret = $marr['itm'];
		elseif(isset($marr['Pname'])) $ret = $marr['Pname'];
		return $ret;
	}
	
	//移除记忆
	//$mn为移除哪一个下标的数组，默认是0，如果为-1则是最末位，如果是字符串'ALL'则重置整个$searchmemory数组
	//$shwlog不为空则会对$log进行操作，1为正常显示，2某些不想显示的操作会用到
	function remove_memory($mn = 0, $shwlog = 1, &$pa=NULL){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			$pa = & get_var_in_module('sdata', 'player');
		}
		$smarr_all = & $pa['searchmemory'];
		eval(import_module('sys','logger'));
		if(searchmemory_available()){
			if($mn === 'ALL'){
				remove_memory_core($mn, $pa);
				if($shwlog) $log .= '你遗忘了所有遇到过的东西的位置。<br>';
			}else{
				$ra = remove_memory_core($mn, $pa);
				if(1==$shwlog){
					$rmn = get_memory_name($ra, $pa);
					if(\player\check_fog() && isset($ra['Pname']) && $ra['smtype'] != 'corpse') $log .= '你不再记得那个神秘人影的位置。<br>';
					else $log .= '你不再记得'.$rmn.'的位置。<br>';
				}
			}
		}
		return;
	}
	
	//搜到了记忆里的道具，移除记忆
	//因为iid在拿到手上时就改变了，必须在finditem()之前就判断是不是记忆里的道具
	//注意：目前探物姿态不会探到记忆里的道具，会绕过这一条判定。其他姿态会正常触发这里
	function focus_item($iarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(isset($iarr['iid'])){
			$smn = seek_memory_by_id($iarr['iid'], 'iid');
			if($smn >= 0) remove_memory($smn,2);
		}
		return $chprocess($iarr);
	}
	
	//丢弃道具时加入记忆
	function itemdrop($item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$res = $chprocess($item);
		if(!empty($res)) {
			$amarr = array('iid' => $res['iid'], 'itm' => $res['itm'], 'pls' => $pls, 'unseen' => 0, 'itmsk'=>$res['itmsk']);
			add_memory($amarr);
		}
		return $res;
	}
	
	//主命令函数中再探视野或记忆中道具的相关判断
	function act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$tmp_pls = $pls;
		if(searchmemory_available()){
			//再探的判断
			if ($mode == 'command' && strpos($command,'memory')===0){
				$smn = substr($command,6);
				searchmemory_discover($smn);
			//逃跑时记录敌人数据
			}elseif(($mode == 'combat' && $command == 'back')
				 || (!\gameflow_combo\is_gamestate_combo() && $mode == 'corpse' && ($command == 'menu' || (check_keep_corpse_in_searchmemory() && $command != 'destroy')))){//测试，荣耀模式只要不销毁尸体，视野都留着
				$eid = str_replace('enemy','',str_replace('corpse','',$action));
				$smedata = \player\fetch_playerdata_by_pid($eid);
				$amarr = array('pid' => $smedata['pid'], 'Pname' => $smedata['name'], 'pls' => $pls, 'smtype' => 'unknown', 'unseen' => 0);
				if($mode == 'combat' && !$fog) $amarr['smtype'] = 'enemy';
				elseif($mode == 'corpse') {
					$amarr['smtype'] = 'corpse';
					$check_corpse = 1;
				}
			}
		}
		
		$chprocess();
		
		if(!empty($amarr)) {//前一段是逃跑并记录敌人数据，才会满足这里的条件并执行
			if(!empty($check_corpse)){//如果是尸体，额外判定一次空尸体不会留在视野里
				$smedata = \player\fetch_playerdata_by_pid($eid);
				if(\metman\discover_player_filter_corpse($smedata)) {
					add_memory($amarr);
				}else {
					$log .= $smedata['name'].'的尸体上已经不剩什么了。<br>';
				}
			}else{
				add_memory($amarr);
			}
		}
		if($pls != $tmp_pls && 'move' != $command) {//如果因为移动之外的原因变更过地点，则把所有视野设为不可见
			change_memory_unseen('ALL');
			//$log .= '你先前所见的一切东西都离开了视线。<br>';
			//remove_memory('ALL');
		}
	}
//	
	//探到了记忆里的角色，移除记忆
	//目前不会探到记忆里的角色，这一条基本绕过了
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		//eval(import_module('sys','logger','player','metman'));
		//var_dump($sid);
		$smn = seek_memory_by_id($sid, 'pid');
		if($smn >= 0) remove_memory($smn,2);
		$chprocess($sid);
	}
	
	//探物姿态自动回避已经探到的道具，其他姿态不变
	//在从数据库获取道具时就把记忆里已有的过滤掉
	function discover_item_filter($iarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//先初始化两个数组
		eval(import_module('player','searchmemory'));
		if(empty($searchmemory_now_iids)) $searchmemory_now_iids = get_searchmemory_now_ids('iid');
		if(3 == $pose && in_array($iarr['iid'], $searchmemory_now_iids)) {
			//echo '搜寻到id:'.$iarr['iid'].'名称:'.$iarr['itm'].'已回避<br>';
			return false;
		}
		return $chprocess($iarr);
	}
	
	//探物姿态自动回避已经探到的角色，其他姿态不变
	//在从数据库获取角色时就把记忆里已有的过滤掉
	
	//暂时取消这个功能
	
//	function discover_player_filter($edata){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		//先初始化两个数组
//		eval(import_module('player','searchmemory'));
//		if(empty($searchmemory_now_pids)) $searchmemory_now_pids = get_searchmemory_now_ids('pid');
//		if(3 == $pose && in_array($edata['pid'], $searchmemory_now_pids)) {
//			//echo '搜寻到id:'.$edata['pid'].'名称:'.$edata['name'].'已回避<br>';
//			return false;
//		}
//		return $chprocess($edata);
//	}
	
//	function findenemy(&$edata){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player'));
//		$smn = seek_memory_by_id($edata['pid'], 'pid');
//		if($smn >= 0) remove_memory($smn,2);
//		$chprocess($edata);
//	}
	
	//再探记忆主函数
	//参数值代表访问searchmemory的元素的下标（0是最早的记忆）
	function searchmemory_discover($mn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','cooldown'));
		$mn = (int)$mn;
		
		if(!isset($searchmemory[$mn])) {
			$log .= '探索记忆参数有误。<br>';
			$mode = 'command';
			return;
		}
		
		$marr = $searchmemory[$mn];
		
		//探索可行性判定
		if(!searchmemory_discover_valid_check($marr)) return;
		
		//如果下标超出视野，临时把unseen标为1
		if(check_out_of_slot_edge($mn) && empty($marr['unseen'])) $marr['unseen'] = 1;
		
		//记忆格子的额外判定：先进行一次探索流程，然后把那个记忆对应的东西加到视野最新的地方
		//这里调用search_area()但不触发search()函数本体，所以不会触发冷却，需要额外判定
		$search_flag = 0;
		if($marr['unseen']) {
			$schsp = \explore\allow_search_check();
	
			if(false !== $schsp && $hp) {
				$sp -= $schsp;
				$log .= "消耗<span class=\"yellow b\">{$schsp}</span>点体力，你向记忆中的地点探索而去……<br>";
				//执行探索冷却时间
				if($coldtimeon) \cooldown\set_coldtime(\cooldown\get_search_coldtime());
				//把视野里的都标注成不可见
				change_memory_unseen('ALL');
				$search_flag = \explore\search_area();//判定这次探索是否有别的结果
			}else{
				return;
			}
			//探索流程有可能造成死亡，所以需要存活才继续
			if(!$hp) return;
		}
		
		//接下来的流程无论结果，都可以把该记忆元素删掉了
		//2023.11.25由于前面的探索流程有可能改变了视野数组的顺序，这里需要用id来删除
		if(isset($marr['pid'])) {
			remove_memory(seek_memory_by_id($marr['pid'], 'pid'), 0);
		}elseif(isset($marr['iid'])) {
			remove_memory(seek_memory_by_id($marr['iid'], 'iid'), 0);
		}
		//调用核心函数
		searchmemory_discover_core($marr, $search_flag);
	}
	
	//探索特定视野可行性判定，可行返回true，否则返回false
	//会直接对$log输出一些错误提示
	function searchmemory_discover_valid_check($marr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		//正在CD时无法探索视野里的东西
		if(\cooldown\check_in_coldtime()) {
			$mode = 'command';
			return false;
		}
		
		eval(import_module('sys','player','logger'));
		
		//需要在同一地点
		if($pls != $marr['pls']){
			$log .= '<span class="yellow b">你和寻找对象不在同一地点。</span><br>';
			$mode = 'command';
			return false;
		}
		
		return true;
	}
	
	//具体探索特定视野或者记忆的核心函数
	//传参为单个视野数组和记录之前是否有探索结果的布尔值变量$search_flag
	function searchmemory_discover_core($marr, $search_flag = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$mpls = $marr['pls'];
		if(isset($marr['itm'])){//道具的判定
			$mid = $marr['iid'];
			$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE iid = '$mid' AND pls = '$mpls'");
			$itemnum = $db->num_rows($result);
			if($itemnum <= 0){
				$log .= '<span class="yellow b">道具已经不在原先的位置了，可能是被谁捡走了吧。</span><br>';
				$mode = 'command';
				return;
			}
			//原本不在视野中，并且探索有其他结果，那么只是加入视野
			if($marr['unseen'] && (!empty($search_flag) || !empty($cmd))) {
				$log .= '此外，你看到<span class="lime b">'.$marr['itm'].'还在原来的位置。</span><br>';
				add_memory_core($marr);
				return;
			}
			$log .= '<span class="lime b">'.$marr['itm'].'还在原来的位置，你轻松拿到了它。</span><br>';
			
			\cooldown\set_coldtime(\cooldown\get_search_coldtime());
			$nmarr=$db->fetch_array($result);
			focus_item($nmarr);
			\itemmain\itemget();
			
		}elseif(isset($marr['Pname'])){
			$mid = $marr['pid'];
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid = '$mid' AND pls = '$mpls'");
			$pnum = $db->num_rows($result);
			if($pnum <= 0){
				$log .= '<span class="yellow b">角色已经不在原来的位置了，可能是已经离开了吧。</span><br>';
				$mode = 'command';
				return;
			}
			$nmarr=$db->fetch_array($result);
			if(!\metman\discover_player_filter_alive($nmarr)){//死斗后不会遇到NPC
				$log .= '<span class="red b">角色已经不在原来的位置了……</span><br>';
				$mode = 'command';
				return;
			}elseif($nmarr['hp']<=0 && $marr['smtype'] != 'corpse') {
				$log .= '<span class="red b">角色已经不在原来的位置了，地上只有一摊血迹……</span><br>';
				$mode = 'command';
				return;
			}elseif($nmarr['hp']<=0 && !\metman\discover_player_filter_corpse($nmarr)){
				$log .= '<span class="red b">尸体好像已经被毁尸灭迹了。</span><br>';
				$mode = 'command';
				return;
			}
			//原本不在视野中的，只是加入视野
			if($marr['unseen']) {
				if($fog && 'corpse' != $marr['smtype']) {
					$log .= '<span class="lime b">你隐约看到原来的位置有个人影。</span><br>';
				}elseif('unknown' == $marr['smtype']){
					$log .= '你看到<span class="lime b">'.$marr['Pname'].'站在你记忆中的位置。</span><br>';
					$marr['smtype'] = 'enemy';
				}else{
					$log .= '你看到<span class="lime b">'.$marr['Pname'].'还在原来的位置。</span><br>';
				}
				if('corpse' != $marr['smtype']) $log .= '你决定暂时不去惊动对方。<br>';
				add_memory_core($marr);
			}else{
				if($fog && 'corpse' != $marr['smtype']){
					$log .= '<span class="lime b">人影还在原来的位置。</span><br>';
				}elseif('unknown' == $marr['smtype']){
					$log .= '你看到<span class="lime b">'.$marr['Pname'].'站在你刚才看到的位置。</span><br>';
				}	else {
					$log .= '你看到<span class="lime b">'.$marr['Pname'].'还在原来的位置。</span><br>';
				}
				\cooldown\set_coldtime(\cooldown\get_search_coldtime());
				$sdata['sm_active_debuff'] = 1;
				\metman\meetman($mid);
				unset($sdata['sm_active_debuff']);
			}
		}
	}
	
	//迎战探索记忆的敌人时，玩家先制率debuff
	function calculate_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','searchmemory'));
		$ret = $chprocess($ldata,$edata);
		if(isset($sdata['sm_active_debuff']) && $sdata['sm_active_debuff']) {
			//$log .= '<span class="red b">两次打扰同一玩家使你的先制率降低了。</span><br>';
			$ldata['active_words'] = \attack\add_format($searchmemory_battle_active_debuff, $ldata['active_words'],0);
			$ret += $searchmemory_battle_active_debuff;
		}
		return $ret;
	}
	
	//移动后丢失所有探索记忆
	function move_to_area($moveto){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if(searchmemory_available()) {
			change_memory_unseen('ALL');
			//$log .= '你先前所见的一切东西都离开了视线。<br>';
			//remove_memory('ALL');
		}
		$chprocess($moveto);
	}
	
	//探索记忆的冷却时间
	function get_searchmemory_coldtime()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('searchmemory'));
		return $searchmemorycoldtime;
	}
	
	//是否能在捡取尸体之后让尸体留在视野中
	function check_keep_corpse_in_searchmemory()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if(15 == $gametype || 16 == $gametype) return true;//仅单人房可用
		return false;
	}
	
	//如果允许摸尸体后尸体依然留在视野里，那么摸尸体必须有个CD时间
	function getcorpse_action(&$edata, $item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//上一次从尸体上捡东西后若干秒内无法立刻访问同一个尸体上的东西，需要skill1003支持
		if(check_keep_corpse_in_searchmemory() && !in_array($item, array('back','menu','destroy')) && \skillbase\skill_query(1003)) {
			eval(import_module('sys','player','logger','searchmemory'));
			$last_corpse_time = floor(\skillbase\skill_getvalue(1003,'last_corpse_time'));//注意这个是以毫秒为单位
			$last_corpse_pid = (int)\skillbase\skill_getvalue(1003,'last_corpse_pid');
			$ct = floor(getmicrotime()*1000);
			if($last_corpse_pid == $edata['pid'] && $ct - $last_corpse_time < $searchmemorycoldtime){
				$log .= '<span class="yellow b">'.($searchmemorycoldtime/1000).'秒内不能再次拾取同一尸体的道具。</span><br>';
				$action = '';
				$mode = 'command';
				return;
			}
			//记录上一次摸尸体的时间和pid，需要skill1003支持
			//也就是说如果你交错摸两个尸体是不会受干扰的（花费的时间差不多也3秒了）
			$last_corpse_time = floor(getmicrotime()*1000);//注意这个是以毫秒为单位
			$last_corpse_pid = $edata['pid'];
			\skillbase\skill_setvalue(1003,'last_corpse_time',$last_corpse_time);
			\skillbase\skill_setvalue(1003,'last_corpse_pid',$last_corpse_pid);
		}
		$chprocess($edata, $item);
//		if(check_keep_corpse_in_searchmemory() && !in_array($item, array('back','menu','money','destroy'))) {
//			\cooldown\set_coldtime(get_searchmemory_coldtime());
//		}
	}
	
	//显示视野和记忆按钮的准备工作
	//最终返回$sm_slots和$sm_records两个数组，都是倒序形式表示，用list接收
	function searchmemory_display_prepare(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','searchmemory'));
		$edge = get_slot_edge();
		$sm_slots = $sm_records = Array();
		foreach($searchmemory as $k => $v){
			$v['smn'] = $k;//记录实际下标，因为要倒序
			//满足3个条件才能作为视野显示：下标在视野范围内，没有标注unseen，地点正确
			if($k >= $edge && empty($v['unseen']) && $v['pls'] == $pls){
				$sm_slots[] = $v;
			}else{
				$sm_records[] = $v;
			}
		}
		//返回倒序便于显示
		return Array(array_reverse($sm_slots), array_reverse($sm_records));
	}
	
	//把道具送给对方，如果对方包裹没空位则会送到视野。因为这个处理很常见所以单独拆分出来
	//传参&$theitem为标准道具格式，&$pa和&$pd为标准玩家格式，其中$pa为发送方
	//如果送到对方包裹里则返回1，如果送到视野返回2。如果有“未成功”的判定，返回0
	function searchmemory_senditem(&$theitem, &$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//这里只是一个接口，具体的判定请在模块里继承并重写
		return 0;
	}
	
	//界面显示用的判定函数，本模块直接返回false主要用于临时视野模块重载
	function is_searchmemory_extra_displayed()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return false;
	}
}
?>