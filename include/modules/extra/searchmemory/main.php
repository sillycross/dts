<?php

namespace searchmemory
{
	function init() {}
	
	function fetch_playerdata($Pname){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//eval(import_module('sys'));
		$pdata = $chprocess($Pname);
		$pdata['searchmemory'] = array_decode($pdata['searchmemory']);
		return $pdata;
	}
	
	function fetch_playerdata_by_pid($pid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//eval(import_module('sys'));
		$pdata = $chprocess($pid);
		$pdata['searchmemory'] = array_decode($pdata['searchmemory']);
		return $pdata;
	}
	
	function player_save($data){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(is_array($data['searchmemory'])) $data['searchmemory'] = array_encode($data['searchmemory']);
		$chprocess($data);
	}
	
	function add_memory($marr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','searchmemory'));
		if($marr){
			if($searchmemory_max_slotnum <= 0) $searchmemory_max_slotnum = 1;
			while(sizeof($searchmemory) >= $searchmemory_max_slotnum){
				remove_memory();
			}
			$amflag = 0;
			if(isset($marr['itm'])){
				$amn = $marr['itm'];
				$amflag = 1;
			}elseif(isset($marr['Pname'])){
				$amn = $marr['Pname'];
				$amflag = 1;
			}
			if($amflag){
				array_push($searchmemory, $marr);
				$log .= '你设法保持对'.$amn.'的持续观察……<br>';
			}
		}
		return;
	}
	
	function seek_memory_by_id($id, $ikind = 'pid'){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		foreach($searchmemory as $i => $sm){
			if(isset($sm[$ikind]) && $sm[$ikind] == $id){
				return $i;
			}
		}
		return -1;
	}
	
	function remove_memory($mn = 0, $shwlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		//$searchmemory = array_decode($searchmemory);
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
			if($shwlog==1) $log .= $rmn.'的位置脱离了你的视线。<br>';
			//elseif($shwlog==2) $log .= $rmn.'的位置更新了。<br>';
		}
		return;
	}
	
	//因为iid在拿到手上时就改变了，必须在finditem()之前就判断是不是记忆里的道具
	function focus_item($iarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//eval(import_module('sys','player'));
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
	
	function act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','input'));
		if ($mode == 'command' && strpos($command,'memory')===0){
			$smn = substr($command,6);
			searchmemory_discover($smn);
		}elseif(($mode == 'combat' && $command == 'back') || ($mode == 'corpse' && $command == 'menu')){
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
		$chprocess();
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
		eval(import_module('sys','player','logger','input'));
		$mn = (int)$mn;
		//$searchmemory = array_decode($searchmemory);
		//$mn = (int)substr($schmode,6) - 1;
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
					$log .= '<span class="lime">'.$mem['Pname'].'还在原来的位置。</span><br>';
					$marr=$db->fetch_array($result);
					$sdata['sm_active_debuff'] = 1;//临时这么写写
					\team\meetman($mid);
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
	function calculate_active_obbs_multiplier(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','searchmemory'));
		$obbs = 1.0;
		if(isset($sdata['sm_active_debuff']) && $sdata['sm_active_debuff']) {
			$obbs *= $searchmemory_battle_active_debuff;
			$log .= '<span class="red">两次打扰同一玩家使你的先制率降低了。</span><br>';
		}
		return $obbs;
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