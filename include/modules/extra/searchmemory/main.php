<?php

namespace searchmemory
{
	function init() {}
	
	function fetch_playerdata($Pname){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$pdata = $chprocess($Pname);
		$pdata['searchmemory'] = array_gz_decode($pdata['searchmemory']);
		return $pdata;
	}
	
	function fetch_playerdata_by_pid($pid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$pdata = $chprocess($pid);
		$pdata['searchmemory'] = array_gz_decode($pdata['searchmemory']);
		return $pdata;
	}
	
	function player_save(&$data){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(is_array($data['searchmemory'])) $data['searchmemory'] = array_gz_encode($data['searchmemory']);
		$chprocess($data);
	}
	
	function add_memory($marr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if($marr){
//			if($max_searchmemory <= 0) $max_searchmemory = 1;
//			while(sizeof($searchmemory) >= $max_searchmemory){
//				remove_memory();
//			}
			array_push($searchmemory, $marr);
			if($marr['itm']){
				$amn = $marr['itm'];
			}elseif($marr['Pname']){
				$amn = $marr['Pname'];
			}
			$log .= '你设法保持对'.$amn.'的持续观察……<br>';
		}
		return;
	}
	
	function remove_memory($mn = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		//$searchmemory = array_gz_decode($searchmemory);
		if($mn == -1){
			$searchmemory = array();
			$log .= '你先前记下的一切东西都脱离了视线。<br>';
		}elseif(isset($searchmemory[$mn])){
			$rm = array_slice($searchmemory,$mn,1);
			if($rm['itm']) $rmn = $rm['itm'];
			elseif($rm['Pname']) $rmn = $rm['Pname'];
			$log .= $rmn.'的位置脱离了你的视线。<br>';
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
	
	function discover($schmode){//参数值以memory开头接数字，则代表取几号位memory的探索记忆
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if(strpos($schmode,'memory')===0){
			//$searchmemory = array_gz_decode($searchmemory);
			$mn = (int)substr($schmode,6) - 1;
			if(isset($searchmemory[$mn])){
				$mem = $searchmemory[$mn];
				remove_memory($mn);
				$mpls = $mem['pls'];
				if($pls != $mpls){
					$log .= '<span class="yellow">你和要找的东西不在同一地点。</span><br>';
					$mode = 'command';
					return;
				}elseif(isset($mem['itm'])){
					$mid = $mem['iid'];
					$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE mid = '$mid' AND pls = '$mpls'");
					$itemnum = $db->num_rows($result);
					if($itemnum <= 0){
						$log .= '<span class="yellow">道具已经不在原先的位置了，可能是被谁捡走了吧。</span><br>';
						$mode = 'command';
						return;
					}else{
						$log .= '<span class="lime">你转身拿到了道具'.$mem['itm'].'</span><br>';
						$marr=$db->fetch_array($result);
						focus_item($marr);
						return;
					}
				}elseif(isset($mem['Pname'])){
				}
			}else{
				$log .= '探索记忆参数有误。<br>';
				$mode = 'command';
				return;
			}
		}
		$chprocess($schmode);
	}
}

?>