<?php

namespace radar
{
	global $radardata, $radar_tplist;
	
	$radar_fetch_type = Array(//从数据库拉取时，记录具体名字的角色type
		0,//玩家
		2,//全息幻象
		5,//杏仁豆腐
		7,//幻影执行官
		11,//真职人
		12,//DF
		14,//数据碎片，即女主
		20,//英雄
		21,//武神
		22,//天神
		45,//电波幽灵
		46//全息实体
	);
	
	$radar_disp_npc_type = Array(//可显示的NPCtype，键名对应计数属性
		0 => Array(//基本显示90,2,5,6,7,11,14
			90,//杂兵
			2,//全息幻象
			5,//杏仁豆腐
			6,//黑幕
			7,//幻影执行官
			11,//真职人
			14,//数据碎片，即女主
		),
		16 => Array(//感应生命探测器，能知道电波幽灵、全息实体、DF、英灵殿武神NPC的位置和名字
			12,//DF
			45,//电波幽灵
			46,//全息实体
			21,//武神
		),
	);
	$radar_disp_recordname_npc_type = Array(//显示具体名字的角色type，键名对应计数属性
		8 => Array(//高清生命探测器，能知道全息、豆腐、黑幕、执行官、真职人、女主的名字
			2,//全息幻象
			5,//杏仁豆腐
			6,//黑幕
			7,//幻影执行官
			11,//真职人
			14,//数据碎片，即女主
		),
		16 => Array(//感应生命探测器，能知道电波幽灵、全息实体、DF、英灵殿武神NPC的位置和名字
			12,//DF
			45,//电波幽灵
			46,//全息实体
			21,//武神
		),
	);
	
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
	
	//判定探测器等级
	function check_radar_digit($radarsk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$radar_digit = 0;
		if(empty($radarsk)) return $radar_digit;
		if(is_numeric($radarsk)) $radar_digit = radar_itmsk_to_digit((int)$radarsk);
		elseif(defined('MOD_EX_ATTR_DIGIT')){
			$radar_digit = (int)\itemmain\check_in_itmsk('^rdsk', $radarsk, 1);
		}
		return $radar_digit;
	}
	
	//原雷达数字属性转计数属性
	function radar_itmsk_to_digit($radarsk) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = 0;
		if(1 == $radarsk) $ret = 1;
		elseif(2 == $radarsk) $ret = 2;
		elseif(3 == $radarsk) $ret = 2+8;
		elseif(4 == $radarsk) $ret = 2+16;
		elseif(5 == $radarsk) $ret = 2+32;
		return $ret;
	}
	
	//探测仪器的属性数字代表其类型
	//0:生命探测器，功能最少的探测器，只能看当前地图一般NPC
	//1:强化生命探测器，可以看当前和周围各1格地图，不过不打算引入了，广域已经泛滥了
	//2:广域生命探测器，可以看所有地图
	//3:高清生命探测器，可以看所有地图，并可以知道杂兵之外的NPC的名字，对应计数属性是2+8
	//4:感应生命探测器，可以看所有地图，并可以知道电波幽灵、全息实体、DF、英灵殿NPC的位置和名字，对应计数属性是2+16
	//5:避难所生命探测器，可以看所有地图，每次使用后15s自己在当前地图的先制率+3%，对应计数属性是2+32
	
	//如果开启了模块ex_attr_digit也就是计数属性，那么根据按位运算的计数属性来判定。不过为了兼容，代码上会保留数字
	//0:生命探测器，功能最少的探测器，只能看当前地图一般NPC
	//1:强化，可以看当前和周围各1格地图，但实际并不存在这个道具
	//2:广域，可以看所有地图
	//4:占位符
	//8:高清，可以知道杂兵之外的NPC的名字
	//16:感应，可以知道电波幽灵、全息实体、DF、英灵殿NPC的位置和名字
	//32:避难所功能，每次使用后15s自己在当前地图的先制率+3%
	
	
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
		//第一轮循环，得到原始的存活角色数据。从数据库是全部拉取的，在代码层进行筛选
		
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
				if(in_array($cdtype, $radar_fetch_type)) $radardata_raw[$cdpls][$cdtype]['namelist'][] = $cdname;
			}
		}
		
		$radar_tplist = array_merge(Array(0), get_radar_npc_type_list($radarsk, $existing_npctp));
		
		//探测器等级的判定
		$radar_digit = check_radar_digit($radarsk);
		
		//第二轮循环，形成显示用数据
		foreach($plsinfo as $plsi => $plsn) {
			$radardata[$plsi] = array();
			if(array_search($plsi,$arealist) <= $areanum && !$hack) {
				$radardata[$plsi] = 'x';//禁区，全部写红叉
			} elseif((!($radar_digit & 1) && !($radar_digit & 2) && $plsi!=$pls) || (($radar_digit & 1) && !($radar_digit & 2) && ($plsi < $pls - 1 || $plsi > $pls + 1))) {
				$radardata[$plsi] = '?';//探测不到，全部写问号
			} else {
				$radardata[$plsi] = array();
				//玩家是一定显示的，其他NPC则要根据死斗情况判定是否显示
				foreach($radar_tplist as $typei){
					if((!\gameflow_duel\is_gamestate_duel() || !$typei) && !empty($radardata_raw[$plsi][$typei]['num'])) {
						$radardata[$plsi][$typei]['num'] = $radardata_raw[$plsi][$typei]['num'];
						//显示具体名字
						if(($radar_digit & 8) && in_array($typei, $radar_disp_recordname_npc_type[8])) $radardata[$plsi][$typei]['namelist'] = radar_parse_namelist($radardata_raw[$plsi][$typei]['namelist']);
						elseif(($radar_digit & 16) && in_array($typei, $radar_disp_recordname_npc_type[16])) $radardata[$plsi][$typei]['namelist'] = radar_parse_namelist($radardata_raw[$plsi][$typei]['namelist']);
					}else{
						$radardata[$plsi][$typei]['num'] = '-';
					}
				}
			}
		}
		
		$log .= '白色数字：该区域内的人数<br><span class="yellow b">黄色数字</span>：自己所在区域的人数<br><span class="red b">×</span>：禁区<br>';
		if(($radar_digit & 8) || ($radar_digit & 16)) $log .= '鼠标悬停于带[ ]的数字可查看NPC名字列表。<br>';
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
		eval(import_module('sys','radar'));
		
		//探测器等级的判定
		$radar_digit = check_radar_digit($radarsk);
		
		//基本显示：杂兵、全息幻象、豆腐、猴子、幻影执行官、职人、女主
		$ret = $radar_disp_npc_type[0];//Array(90,2,5,6,7,11,14);
		if(!empty($existing_npctp)){
			//如果幻影执行官没入场，不会显示执行官
			if(!in_array(7, $existing_npctp)) $ret = array_diff($ret, array(7));
			//感应探测器额外显示幽灵、实体、DF、英灵殿
			if($radar_digit & 16) {
				foreach($radar_disp_npc_type[16] as $tv) {
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