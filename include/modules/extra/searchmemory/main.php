<?php

namespace searchmemory
{
	function init() {}
	
	function searchmemory_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','searchmemory'));
		return !in_array($gametype, $searchmemory_disabled_gtype);
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
	
	function add_memory($marr, $showlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','searchmemory'));
		if(searchmemory_available() && $marr){
			$searchmemory_real_slotnum = $searchmemory_max_slotnum;
			if(isset($weather_memory_loss[$weather])) $searchmemory_real_slotnum += $weather_memory_loss[$weather];
//			if($weather == 8) $searchmemory_real_slotnum -= 2;//起雾视野-2（剩下3）
//			elseif($weather == 9 || $weather == 12) $searchmemory_real_slotnum -= 4;//浓雾暴风雪视野-4（剩下1）
			if($searchmemory_real_slotnum < 1) $searchmemory_real_slotnum = 1;
			while(sizeof($searchmemory) >= $searchmemory_real_slotnum){
				remove_memory();
			}
			$amflag = 0;
			if(isset($marr['itm'])){
				$amn = $marr['itm'];
				$amflag = 1;
				if($showlog) {
					if($fog) $log .= '你能隐约看到'.$amn.'在哪里。<br>';
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
				if($shwlog) $log .= '你先前记下的一切东西都脱离了视线。<br>';
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
				//|| ($mode == 'corpse' && $command != 'destroy')){//修改：尸体只要不销毁，视野都留着
				 || ($mode == 'corpse' && $command == 'menu')){
				$eid = str_replace('enemy','',str_replace('corpse','',$action));
				$edata = \player\fetch_playerdata_by_pid($eid);
				$amarr = array('pid' => $edata['pid'], 'Pname' => $edata['name'], 'pls' => $pls, 'smtype' => 'unknown');
				if($mode == 'combat') $amarr['smtype'] = 'enemy';
				elseif($mode == 'corpse') $amarr['smtype'] = 'corpse';
				add_memory($amarr);
	//			$smn = seek_memory_by_id($enemyid);
	//			if($smn >= 0){
	//				remove_memory($smn, 0);
	//			}
			}
		}
		$chprocess();
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
		if ($coldtimeon && $rmcdtime > 0)
		{
			$log .= '<span class="yellow">冷却时间尚未结束！</span><br>';
			$mode = 'command';
			return;
		}
	
		if(isset($searchmemory[$mn])){
			$mem = $searchmemory[$mn];
			remove_memory($mn,0);
			$mpls = $mem['pls'];
			if($pls != $mpls){
				$log .= '<span class="yellow">你和寻找对象不在同一地点。</span><br>';
				$mode = 'command';
				return;
			}elseif(isset($mem['itm'])){
				$mid = $mem['iid'];
				$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE iid = '$mid' AND pls = '$mpls'");
				$itemnum = $db->num_rows($result);
				if($itemnum <= 0){
					$log .= '<span class="yellow">道具已经不在原先的位置了，可能是被谁捡走了吧。</span><br>';
					$mode = 'command';
					return;
				}else{
					$log .= '<span class="lime">'.$mem['itm'].'还在原来的位置，你轻松拿到了它。</span><br>';
					if($coldtimeon) $cmdcdtime=\cooldown\get_search_coldtime();
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
					$log .= '<span class="yellow">角色已经不在原来的位置了，可能是已经离开了吧。</span><br>';
					$mode = 'command';
					return;
				}else{
					$marr=$db->fetch_array($result);
					if($marr['hp']<=0 && $mem['smtype'] != 'corpse') {
						$log .= '<span class="red">角色已经不在原来的位置了，地上只有一摊血迹……</span><br>';
						$mode = 'command';
						return;
					}elseif($marr['hp']<=0 && !\metman\discover_player_filter_corpse($marr)){
						$log .= '<span class="red">尸体好像已经被毁尸灭迹了。</span><br>';
						$mode = 'command';
						return;
					}
					if($fog && $mem['smtype'] != 'corpse') $log .= '<span class="lime">人影还在原来的位置。</span><br>';
					else $log .= '<span class="lime">'.$mem['Pname'].'还在原来的位置。</span><br>';
					$sdata['sm_active_debuff'] = 1;//临时这么写写
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
			//$log .= '<span class="red">两次打扰同一玩家使你的先制率降低了。</span><br>';
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
}
?>