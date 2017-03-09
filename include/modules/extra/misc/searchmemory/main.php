<?php

namespace searchmemory
{
	function init() {}
	
	function fetch_playerdata($Pname){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$pdata = $chprocess($Pname);
		$pdata['searchmemory'] = array_decode($pdata['searchmemory']);
		return $pdata;
	}
	
	function fetch_playerdata_by_pid($pid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
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
		eval(import_module('sys','player','logger','searchmemory'));
		if($marr){
			if($max_searchmemory <= 0) $max_searchmemory = 1;
			while(sizeof($searchmemory) >= $max_searchmemory){
				remove_memory();
			}
			array_push($searchmemory, $marr);
			if(isset($marr['itm'])){
				$amn = $marr['itm'];
			}elseif(isset($marr['Pname'])){
				$amn = $marr['Pname'];
			}
			$log .= '你设法保持对'.$amn.'的持续观察……<br>';
		}
		return;
	}
	
	function seek_memory_by_id($id, $ikind = 'pid'){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		foreach($searchmemory as $i => $sm){
			if(isset($sm[$ikind]) && $sm[$ikind] == $id){
				return $i;
			}
		}
		return -1;
	}
	
	function remove_memory($mn = 0, $shwlog = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		//$searchmemory = array_decode($searchmemory);
		if($mn == -99){
			$searchmemory = array();
			if($shwlog) $log .= '你先前记下的一切东西都脱离了视线。<br>';
			return;
		}elseif($mn == -1){
			$mn = sizeof($searchmemory) - 1;
		}
		if(isset($searchmemory[$mn])){
			$sm = $searchmemory[$mn];
			if(isset($sm['itm'])) $rmn = $sm['itm'];
			elseif(isset($sm['Pname'])) $rmn = $sm['Pname'];
			array_splice($searchmemory,$mn,1);
			if($shwlog) $log .= $rmn.'的位置脱离了你的视线。<br>';
		}
		return;
	}
	
	function itemdrop($item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$res = $chprocess($item);
		if($res) list($dropid,$dropname) = $res;
		$amarr = array('iid' => $dropid, 'itm' => $dropname, 'pls' => $pls);
		add_memory($amarr);
		return $res;
	}
	
	function act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','input'));
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
//	function findenemy(&$edata){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player'));
//		$amarr = array('pid' => $edata['pid'], 'Pname' => $edata['name'], 'pls' => $pls, 'smtype' => 'enemy');
//		add_memory($amarr);
//		$chprocess($edata);
//	}
	
	function searchmemory_discover($mn){//参数值代表取几号位searchmemory的探索记忆
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
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
					\itemmain\focus_item($marr);
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
					\team\meetman($mid);
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
	
	function move_to_area($moveto){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(!empty($searchmemory)) {
			remove_memory(-99);
		}
		$chprocess($moveto);
	}
}
?>