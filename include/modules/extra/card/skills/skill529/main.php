<?php

namespace skill529
{
	function init() 
	{
		define('MOD_SKILL529_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[529] = '幻林';//开局获得NPC技能或者道具的共通技能
	}
	
	function acquire529(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(529,'activated',0,$pa);
	}
	
	//因为lvl是在加载技能后写入的，判定获得道具和技能的效果得后置到玩家入场自动刷新界面时
	function post_load_profile_event(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(529) && empty(\skillbase\skill_getvalue(529,'activated')))
		{
			eval(import_module('sys','player'));
			//玩家第一次进入游戏的界面是不会player_save的，要正常执行，应该在玩家自动刷新界面时生成
			if(!empty($command)){
				//lvl=1，获得时自动获取场上NPC的一件道具
				$skill529lvl = \skillbase\skill_getvalue(529,'lvl',$sdata);
				if(1 == $skill529lvl){
					skill529_get_item($sdata);
				//lvl=2，获得时自动复制场上NPC的一个技能
				}elseif(2 == $skill529lvl){
					skill529_get_skill($sdata, 1);
				//lvl=3，获得时自动复制场上玩家的一个技能
				}elseif(3 == $skill529lvl){
					skill529_get_skill($sdata, 0);
				}
				\skillbase\skill_setvalue(529,'activated',1,$sdata);
			}
		}
		$chprocess();
	}
	
	//$gettype=1表示获得NPC，=0表示获得玩家
	function skill529_get_skill(&$pa, $gettype = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$spool = skill529_get_skillpool($gettype);
		//gwrite_var('a.txt', $spool);
		if(!empty($spool)){
			$spool_keys = Array_keys($spool);
			list($nid, $getskillid) = explode('_',array_randompick($spool_keys));
			
			\skillbase\skill_acquire($getskillid,$pa);
			
			$getskillval = $spool[$nid.'_'.$getskillid];
			//gwrite_var('a.txt', $getskillid);
			if(!empty($getskillval)){
				foreach($getskillval as $gk => $gv){
					\skillbase\skill_setvalue($getskillid, $gk, $gv, $pa);//获得对应技能
				}
			}
		}
	}
	
	//获取全部存活玩家或者NPC的技能编号数据
	//返回$spool[$nid_$skillid][$skillval_key] = $skillval_value
	function skill529_get_skillpool($gettype = 1){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if(empty($gettype)) $query = "SELECT * FROM {$tablepre}players WHERE type = 0 AND hp > 0 AND pid != '$pid'";
		else $query = "SELECT * FROM {$tablepre}players WHERE type > 0 AND hp > 0";
		$result = $db->query($query);
		$spool = Array();
		while($npc = $db->fetch_array($result)){
			\skillbase\skillbase_load($npc, 1);
			if(!empty($npc['acquired_list'])) {
				//第一次循环：根据acquired_list列表建立技能数组
				foreach($npc['acquired_list'] as $pk => $pv){//acquired_list列表是skillid => 0/1的形式
					if(!skill529_skill_filter($pk)) continue;//过滤技能id
					if(!empty($pv)) {
						//以pid_skillid为键名建立技能数组
						$spool[$npc['pid'].'_'.$pk] = Array();
					}
				}
				//第二次循环：根据parameter_list列表填充技能参数
				foreach($npc['parameter_list'] as $pk => $pv){//parameter_list列表是skillid_key => val的形式
					list($sid,$skey) = explode('_',$pk,2);
					$poolid = $npc['pid'].'_'.$sid;
					if(!isset($spool[$poolid])) continue;//如果第一步循环没有建立过这个技能数组就跳过
					$spool[$poolid][$skey] = $pv;
				}
			}
		}
		//gwrite_var('a.txt',$spool);
		return $spool;
	}
	
	//技能池过滤：除去不适合给玩家的技能，主要为隐藏技能、成就，以及个别技能（包括异常状态、10、11、12号技能，21号变身、81号换装、460号占位符、512号幻象技能）
	//若过滤，返回0；否则返回$skillid
	function skill529_skill_filter($skillid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$filter_id_arr = array_merge(range(1,9), Array(10,11,12,21,81,460,512));
		$filter_info_arr = Array('hidden', 'achievement', 'debuff');

		if(in_array($skillid, $filter_id_arr)) $skillid = 0;
		else{
			$const = constant('MOD_SKILL'.$skillid.'_INFO');
			foreach($filter_info_arr as $v) {
				if(strpos($const, $v)!==false) {
					$skillid = 0;
					break;
				}
			}
		}
		return $skillid;
	}
	
	//获得道具，会自动选一个空位放，没有就放0
	function skill529_get_item(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$ipool = skill529_get_itempool();
		if(!empty($ipool)){
			shuffle($ipool);
			$getitem = $ipool[0];
			$pos = 0;
			for($i=1;$i<=6;$i++){
				if(empty($pa['itms'.$i])){
					$pos = $i;
					break;
				}
			}
			$pa['itm'.$pos] = $getitem['itm'];
			$pa['itmk'.$pos] = $getitem['itmk'];
			$pa['itme'.$pos] = $getitem['itme'];
			$pa['itms'.$pos] = $getitem['itms'];
			$pa['itmsk'.$pos] = $getitem['itmsk'];
		}
	}
	
	//获取全部存活NPC的道具数据
	function skill529_get_itempool(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$query = "SELECT * FROM {$tablepre}players WHERE type > 0 AND hp > 0";
		$result = $db->query($query);
		$ipool = Array();
		while($r = $db->fetch_array($result)){
			foreach(Array('wep','arb','arh','ara','arf','art') as $p){
				if(!empty($r[$p]) && !empty($r[$p.'s'])){
					$ipool[] = Array(
						'itm' => $r[$p],
						'itmk' => $r[$p.'k'],
						'itme' => $r[$p.'e'],
						'itms' => $r[$p.'s'],
						'itmsk' => $r[$p.'sk'],
					);
				}
			}
			for($i=0;$i<=6;$i++){
				if(!empty($r['itm'.$i]) && !empty($r['itms'.$i])){
					$ipool[] = Array(
						'itm' => $r['itm'.$i],
						'itmk' => $r['itmk'.$i],
						'itme' => $r['itme'.$i],
						'itms' => $r['itms'.$i],
						'itmsk' => $r['itmsk'.$i],
					);
				}
			}
		}
		return $ipool;
	}
	
	function lost529(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked529(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
}

?>