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
	
	function skill_onload_event(&$pa)//因为lvl是在加载技能后写入的，判定获得道具和技能的效果得后置到onload事件
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(529,$pa) && empty(\skillbase\skill_getvalue(529,'activated',$pa)))
		{
			eval(import_module('input'));
			//玩家第一次进入游戏的界面是不会player_save的，要正常执行，应该在玩家自动刷新界面时生成
			if(!$pa['type'] && !empty($command)){
				//lvl=1，获得时自动获取场上NPC的一件道具
				if(1 == \skillbase\skill_getvalue(529,'lvl',$pa)){
					skill529_get_item($pa);
				//lvl=2，获得时自动复制场上NPC的一个技能
				}elseif(2 == \skillbase\skill_getvalue(529,'lvl',$pa)){
					skill529_get_skill($pa);
				}
				\skillbase\skill_setvalue(529,'activated',1,$pa);
				//\skillbase\skill_lost(529, $pa);
			}
		}
		return $chprocess($pa);
	}
	
	function skill529_get_skill(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$spool = skill529_get_skillpool();
		//gwrite_var('a.txt', $spool);
		if(!empty($spool)){
			$spool_keys = Array_keys($spool);
			do{
				shuffle($spool_keys);
				list($nid, $getskillid) = explode('_',$spool_keys[0]);
			} while(strpos(constant('MOD_SKILL'.$getskillid.'_INFO'),'hidden;')!==false);//获得的技能不能带有hidden标签
			
			\skillbase\skill_acquire($getskillid,$pa);
			
			$getskillval = $spool[$nid.'_'.$getskillid];
			//gwrite_var('b.txt', $getskillval);
			foreach($getskillval as $gk => $gv){
				\skillbase\skill_setvalue($getskillid, $gk, $gv, $pa);//获得对应技能
			}
		}
	}
	
	//获取全部存活NPC的技能编号数据
	//返回$spool[$nid_$skillid][$skillval_key] = $skillval_value
	function skill529_get_skillpool(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$query = "SELECT * FROM {$tablepre}players WHERE type > 0 AND hp > 0";
		$result = $db->query($query);
		$spool = Array();
		while($npc = $db->fetch_array($result)){
			\skillbase\skillbase_load($npc, 1);
			if(!empty($npc['acquired_list'])) {
				
				foreach($npc['parameter_list'] as $pk => $pv){
					list($sid,$skey) = explode('_',$pk);
					if(!skill529_skill_filter($sid)) continue;//过滤技能id
					$poolid = $npc['pid'].'_'.$sid;//以pid_skillid为键名储存技能参数
					if(!isset($spool[$poolid])) $spool[$poolid] = Array();
					$spool[$poolid][$skey] = $pv;
				}
			}
		}
		return $spool;
	}
	
	//技能池过滤：除去不适合给玩家的技能，主要包括81号换装、460号占位符、512号幻象技能
	//若过滤，返回0；否则返回$skillid
	function skill529_skill_filter($skillid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$filter_arr = Array(81,460,512);

		if(in_array($skillid, $filter_arr)) $skillid = 0;
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