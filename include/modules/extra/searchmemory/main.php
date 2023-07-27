<?php

//策划阶段叫searchmemory（探索记忆），但文案里用的是“视野”。以下不再分别说明

namespace searchmemory
{
	function init() {}
	
	function searchmemory_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','searchmemory'));
		return !in_array($gametype, $searchmemory_disabled_gtype);
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
	
	function load_playerdata($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$chprocess($pdata);
		if(is_string($searchmemory)) $searchmemory = gdecode($searchmemory,1);//听丑陋的
	}
	
	//对从数据库里读出来的raw数据的处理
	function playerdata_construct_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$data = $chprocess($data);
		$data['searchmemory'] = gdecode($data['searchmemory'],1);
		return $data;
	}
	
//	function fetch_playerdata($Pname, $Ptype = 0, $ignore_pool = 0){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		//eval(import_module('sys'));
//		$pdata = $chprocess($Pname, $Ptype, $ignore_pool);
//		$pdata['searchmemory'] = gdecode($pdata['searchmemory'],1);
//		return $pdata;
//	}
//	
//	function fetch_playerdata_by_pid($pid){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		//eval(import_module('sys'));
//		$pdata = $chprocess($pid);
//		$pdata['searchmemory'] = gdecode($pdata['searchmemory'],1);
//		return $pdata;
//	}
	
	function player_save($data){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(is_array($data['searchmemory'])) $data['searchmemory'] = gencode($data['searchmemory']);
		$chprocess($data);
	}
	
	//计算探索记忆格子数，由于钦定（天气负面转化为正面）这种技能存在，需要单独抽离
	function calc_memory_slotnum(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','searchmemory'));
		if(!isset($searchmemory_max_slotnum)) $searchmemory_max_slotnum = 1;
		$memory_loss = 0;
		if(isset($weather_memory_loss[$weather]))	$memory_loss = $weather_memory_loss[$weather];
		if(\skillbase\skill_query(245)) $memory_loss = -$memory_loss;
		return $searchmemory_max_slotnum + $memory_loss;
	}
	
	//把道具加入探索记忆列表，同时也触发丢失最早的记忆
	function add_memory($marr, $showlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','searchmemory'));
		if(searchmemory_available() && $marr){
			//计算天气等造成的记忆格子减少
			$searchmemory_real_slotnum = calc_memory_slotnum();
			if($searchmemory_real_slotnum < 1) $searchmemory_real_slotnum = 1;
			while(sizeof($searchmemory) >= $searchmemory_real_slotnum){
				remove_memory();
			}
			$amflag = 0;
			if(isset($marr['itm'])){
				$amn = $marr['itm'];
				$amflag = 1;
				if($showlog) {
					if($fog) $log .= '你能隐约看到'.$amn.'的位置。<br>';
					else $log .= '你设法保持对'.$amn.'的持续观察。<br>';
				}
			}elseif(isset($marr['Pname'])){
				$amn = $marr['Pname'];
				$amflag = 1;
				if($showlog) {
					if($marr['smtype'] == 'corpse' )$log .=  '你设法保持对'.$amn.'的尸体的持续观察。<br>';
					elseif($fog) $log .= '你努力让那个人影保持在视野之内。<br>';
					else $log .= '你一边躲开，一边设法继续观察着'.$amn.'。<br>';
				}
			}
			if($amflag){
				array_push($searchmemory, $marr);
			}
		}
		return;
	}
	
	function seek_memory_by_id($id, $ikind = 'pid'){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(searchmemory_available()){
			foreach($searchmemory as $i => $sm){
				if(isset($sm[$ikind]) && $sm[$ikind] == $id){
					return $i;
				}
			}
		}
		return -1;
	}
	
	function remove_memory($mn = 0, $shwlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if(searchmemory_available()){
			if($mn === 'ALL'){
				$searchmemory = array();
				if($shwlog) $log .= '你先前所见的一切东西都离开了视线。<br>';
				return;
			}elseif($mn == -1){//把刚拿到的忘掉
				$mn = sizeof($searchmemory) - 1;
			}
			if(isset($searchmemory[$mn])){
				$sm = $searchmemory[$mn];
				if(isset($sm['itm'])) $rmn = $sm['itm'];
				elseif(isset($sm['Pname'])) $rmn = $sm['Pname'];
				array_splice($searchmemory,$mn,1);
				if($shwlog==1){
					if($fog && isset($sm['Pname']) && $sm['smtype'] != 'corpse') $log .= '先前那个人影脱离了你的视线。<br>';
					else $log .= $rmn.'的位置脱离了你的视线。<br>';
				}
				//elseif($shwlog==2) $log .= $rmn.'的位置更新了。<br>';
			}
		}
		return;
	}
	
	//因为iid在拿到手上时就改变了，必须在finditem()之前就判断是不是记忆里的道具
	function focus_item($iarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(isset($iarr['iid'])){
			$smn = seek_memory_by_id($iarr['iid'], 'iid');
			if($smn >= 0) remove_memory($smn,2);
		}
		return $chprocess($iarr);
	}
	
	function itemdrop($item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		$res = $chprocess($item);
		if(!empty($res)) {
			list($dropid,$dropname) = $res;
			$amarr = array('iid' => $dropid, 'itm' => $dropname, 'pls' => $pls);
			add_memory($amarr);
		}
		return $res;
	}
	
//	function itemuse(&$theitem)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','logger'));
//		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
//		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
//		if ($itm == '凸眼鱼') {
//			$db->query("UPDATE {$tablepre}players SET searchmemory='' WHERE type=0");
//		}
//		$chprocess($theitem);
//	}
	
	function act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','input'));
		$tmp_pls = $pls;
		if(searchmemory_available()){
			if ($mode == 'command' && strpos($command,'memory')===0){
				$smn = substr($command,6);
				searchmemory_discover($smn);
			}elseif(($mode == 'combat' && $command == 'back')
				 || ($gamestate < 40 && $mode == 'corpse' && ($command == 'menu' || (check_keep_corpse_in_searchmemory() && $command != 'destroy')))){//测试，荣耀模式只要不销毁尸体，视野都留着
				$eid = str_replace('enemy','',str_replace('corpse','',$action));
				$edata = \player\fetch_playerdata_by_pid($eid);
				$amarr = array('pid' => $edata['pid'], 'Pname' => $edata['name'], 'pls' => $pls, 'smtype' => 'unknown');
				if($mode == 'combat') $amarr['smtype'] = 'enemy';
				elseif($mode == 'corpse') {
					$amarr['smtype'] = 'corpse';
					$check_corpse = 1;
				}
			}
		}
		$chprocess();
		if(!empty($edata)) {
			if(!empty($check_corpse)){//空尸体不会留在视野里
				$edata = \player\fetch_playerdata_by_pid($eid);
				if(\metman\discover_player_filter_corpse($edata)) {
					add_memory($amarr);
				}else {
					eval(import_module('logger'));
					$log .= $edata['name'].'的尸体上已经不剩什么了。<br>';
				}
			}else{
				add_memory($amarr);
			}
		}
		if($pls != $tmp_pls && !empty($searchmemory)) {
			remove_memory('ALL');
		}
	}
//	

	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		//eval(import_module('sys','logger','player','metman'));
		//var_dump($sid);
		$smn = seek_memory_by_id($sid, 'pid');
		if($smn >= 0) remove_memory($smn,2);
		$chprocess($sid);
	}
	
//	function findenemy(&$edata){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player'));
//		$smn = seek_memory_by_id($edata['pid'], 'pid');
//		if($smn >= 0) remove_memory($smn,2);
//		$chprocess($edata);
//	}
		
	function searchmemory_discover($mn){//参数值代表取几号位searchmemory的探索记忆
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','input','cooldown'));
		$mn = (int)$mn;
		//$searchmemory = array_decode($searchmemory);
		//$mn = (int)substr($schmode,6) - 1;
		//正在CD时无法探索视野里的东西
		if(\cooldown\check_in_coldtime()) return;
	
		if(isset($searchmemory[$mn])){
			$mem = $searchmemory[$mn];
			remove_memory($mn,0);
			$mpls = $mem['pls'];
			if($pls != $mpls){
				$log .= '<span class="yellow b">你和寻找对象不在同一地点。</span><br>';
				$mode = 'command';
				return;
			}elseif(isset($mem['itm'])){
				$mid = $mem['iid'];
				$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE iid = '$mid' AND pls = '$mpls'");
				$itemnum = $db->num_rows($result);
				if($itemnum <= 0){
					$log .= '<span class="yellow b">道具已经不在原先的位置了，可能是被谁捡走了吧。</span><br>';
					$mode = 'command';
					return;
				}else{
					$log .= '<span class="lime b">'.$mem['itm'].'还在原来的位置，你轻松拿到了它。</span><br>';
					\cooldown\set_coldtime(\cooldown\get_search_coldtime());
					$marr=$db->fetch_array($result);
					focus_item($marr);
					\itemmain\itemget();
					return;
				}
			}elseif(isset($mem['Pname'])){
				$mid = $mem['pid'];
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid = '$mid' AND pls = '$mpls'");
				$pnum = $db->num_rows($result);
				if($pnum <= 0){
					$log .= '<span class="yellow b">角色已经不在原来的位置了，可能是已经离开了吧。</span><br>';
					$mode = 'command';
					return;
				}else{
					$marr=$db->fetch_array($result);
					if($marr['hp']<=0 && $mem['smtype'] != 'corpse') {
						$log .= '<span class="red b">角色已经不在原来的位置了，地上只有一摊血迹……</span><br>';
						$mode = 'command';
						return;
					}elseif($marr['hp']<=0 && !\metman\discover_player_filter_corpse($marr)){
						$log .= '<span class="red b">尸体好像已经被毁尸灭迹了。</span><br>';
						$mode = 'command';
						return;
					}
					if($fog && $mem['smtype'] != 'corpse') $log .= '<span class="lime b">人影还在原来的位置。</span><br>';
					else $log .= '<span class="lime b">'.$mem['Pname'].'还在原来的位置。</span><br>';
					\cooldown\set_coldtime(\cooldown\get_search_coldtime());
					$sdata['sm_active_debuff'] = 1;
					\metman\meetman($mid);
					unset($sdata['sm_active_debuff']);
					return;
				}
			}
		}else{
			$log .= '探索记忆参数有误。<br>';
			$mode = 'command';
			return;
		}
		$chprocess($schmode);
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
		eval(import_module('player'));
		if(!empty($searchmemory)) {
			remove_memory('ALL');
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
}
?>