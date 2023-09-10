<?php

namespace radar
{
	global $radardata, $radar_tplist;
	
	function init()
	{
		eval(import_module('itemmain'));
		$iteminfo['ER'] = '探测仪器';
	}
	
	//使用雷达前的事件
	function pre_radar_event($radarsk = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return false;
	}
	
	//探测仪器的属性数字代表其类型
	//0:生命探测器，功能最少的探测器，只能看当前地图一般NPC
	//1:强化生命探测器，可以看当前和周围各1格地图，不过不打算引入了，广域已经泛滥了
	//2:广域生命探测器，可以看所有地图
	//3:高清生命探测器，可以看所有地图，并可以知道杂兵之外的NPC的名字
	//4:感应生命探测器，可以看所有地图，并可以知道电波幽灵、全息实体、DF、英灵殿NPC的位置和名字
	//5:避难所生命探测器，可以看所有地图，每次使用后15s自己在当前地图的先制率+3%
	
	//
	
	function use_radar($radarsk = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','map','radar','logger'));
		if(!$mode) {
			$log .= '仪器使用失败！<br>';
			return;
		}
		
		$existing_npctp = array();
		$radardata_raw = $radardata = array();
		//第一轮循环，得到原始的存活角色数据
		
		$result = $db->query("SELECT name,type,pls,hp FROM {$tablepre}players");
		while($cd = $db->fetch_array($result)) {
			$cdname = $cd['name']; $cdtype = $cd['type']; $cdpls = $cd['pls']; $cdhp = $cd['hp'];
			if(!isset($radardata_raw[$cdpls])) $radardata_raw[$cdpls] = array();
			if(!isset($radardata_raw[$cdpls][$cdtype])) $radardata_raw[$cdpls][$cdtype] = array();
			if(!isset($radardata_raw[$cdpls][$cdtype]['num'])) $radardata_raw[$cdpls][$cdtype]['num'] = 0;
			if(!isset($radardata_raw[$cdpls][$cdtype]['namelist'])) $radardata_raw[$cdpls][$cdtype]['namelist'] = array();
			if(!in_array($cdtype, $existing_npctp)) $existing_npctp[] = $cdtype;
			//通常状态下只有存活的才记录
			if($cdhp) {
				$radardata_raw[$cdpls][$cdtype]['num'] ++;
				if(in_array($cdtype, array(0, 2, 5, 7, 11, 14, 20, 21, 22, 45, 46))) $radardata_raw[$cdpls][$cdtype]['namelist'][] = $cdname;
			}
		}
		
		$radar_tplist = array_merge(Array(0), get_radar_npc_type_list($radarsk, $existing_npctp));
		
		//第二轮循环，形成显示用数据
		foreach($plsinfo as $plsi => $plsn) {
			$radardata[$plsi] = array();
			if(array_search($plsi,$arealist) <= $areanum && !$hack) {
				$radardata[$plsi] = 'x';//禁区，全部写红叉
			} elseif((!$radarsk && $plsi!=$pls) || (1==$radarsk && ($plsi < $pls - 1 || $plsi > $pls + 1))) {
				$radardata[$plsi] = '?';//探测不到，全部写问号
			} else {
				$radardata[$plsi] = array();
				//玩家是一定显示的，其他NPC则要根据死斗情况判定是否显示
				foreach($radar_tplist as $typei){
					if((!\gameflow_duel\is_gamestate_duel() || !$typei) && !empty($radardata_raw[$plsi][$typei]['num'])) {
						$radardata[$plsi][$typei]['num'] = $radardata_raw[$plsi][$typei]['num'];
						if(3 == $radarsk && !in_array($typei, array(6, 90))) $radardata[$plsi][$typei]['namelist'] = radar_parse_namelist($radardata_raw[$plsi][$typei]['namelist']);
						elseif(4 == $radarsk && in_array($typei, array(21,45,46))) $radardata[$plsi][$typei]['namelist'] = radar_parse_namelist($radardata_raw[$plsi][$typei]['namelist']);
					}else{
						$radardata[$plsi][$typei]['num'] = '-';
					}
				}
			}
		}
		
		$log .= '白色数字：该区域内的人数<br><span class="yellow b">黄色数字</span>：自己所在区域的人数<br><span class="red b">×</span>：禁区<br>';
		if($radarsk == 3 || $radarsk == 4) $log .= '鼠标悬停于带[ ]的数字可查看NPC名字列表。<br>';
		$log .= '<br>';
		//避难所探测器增加buff，不会显示特殊指令页面
		if(check_include_radar_cmdpage($radarsk)) {
			ob_start();
			include template(MOD_RADAR_RADARCMD);
			$cmd = ob_get_contents();
			ob_end_clean();
		}
		
		$main = MOD_RADAR_RADAR;
		return;
	}
	
	//判断是否显示特殊指令页面（就是有返回按钮的那个页面）
	function check_include_radar_cmdpage($radarsk = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return true;
	}
	
	//使用雷达完毕后的事件
	function post_radar_event($radarsk = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return false;
	}
	
	function radar_parse_namelist($arr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($arr)) return '';
		else return str_replace('"',"'",implode('<br>',$arr));
	}
	
	//根据雷达类型返回能显示的NPC类型
	function get_radar_npc_type_list($radarsk, $existing_npctp=array()){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//基本显示：杂兵、全息幻象、豆腐、猴子、幻影执行官、职人、女主
		$ret = Array(90,2,5,6,7,11,14);
		if(!empty($existing_npctp)){
			//如果幻影执行官没入场，不会显示执行官
			if(!in_array(7, $existing_npctp)) $ret = array_diff($ret, array(7));
			//感应探测器额外显示幽灵、实体、DF、英灵殿
			if(4==$radarsk) {
				foreach(Array(12,45,46,21) as $tv) {
					if(in_array($tv, $existing_npctp)) $ret[] = $tv;
				}
			}
		}
		return $ret;
	}
	
	//雷达道具消耗电力
	function item_radar_reduce(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		$itme--;//雷达以效果值判定电力
		$log .= "消耗了<span class=\"yellow b\">$itm</span>的电力。<br>";
		if ($itme <= 0) {
			$log .= $itm . '的电力用光了，请使用电池充电。<br>';
		}
		return true;
	}

	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'ER' ) === 0) {//雷达
			if ($itme > 0) {
				$log .= "使用了<span class=\"red b\">$itm</span>。<br>";
				pre_radar_event($itmsk);
				
				use_radar ( $itmsk );
				
				item_radar_reduce($theitem);
				
				post_radar_event($itmsk);
			} else {
				$itme = 0;
				$log .= $itm . '没有电了，请先充电。<br>';
			}
			return;
		}
		$chprocess($theitem);
	}
	
}

?>