<?php

namespace npc
{
	function init() 
	{
		eval(import_module('player'));
		
		global $npc_typeinfo;
		$typeinfo+=$npc_typeinfo;
		
		global $npc_killmsginfo;
		$killmsginfo+=$npc_killmsginfo;
		
		global $npc_lwinfo;
		$lwinfo+=$npc_lwinfo;
	}
	
	function check_initnpcadd($typ)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		return 1;
	}
	
	//把rs_game里一些能复用的功能放进来
	function init_npcdata($npc, $plslist=array()){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys','map','player','npc','lvlctl'));
		//获得当前NPC能随机到的地图
		if(!$plslist) $plslist = \map\get_safe_plslist();
		//基本的一些数值
		$npc['endtime'] = $now;
		$npc['hp'] = $npc['mhp'];
		$npc['sp'] = $npc['msp'];
		$npc['ss'] = $npc['mss'];
		//经验值是刚好达到这个等级要求的
		$npc['exp'] = \lvlctl\calc_upexp($npc['lvl'] - 1);
		//熟练度，如果是整数则六系都是这个数值，如果是有键名的数组则直接merge，懒得作输入检查了，有问题请自行排查
		if(is_array($npc['skill'])) {$npc = array_merge($npc,$npc['skill']);}
		else { $npc['wp'] = $npc['wk'] = $npc['wg'] = $npc['wc'] = $npc['wd'] = $npc['wf'] = $npc['skill'];}						
		//性别，r为随机
		if($npc['gd'] == 'r'){$npc['gd'] = rand(0,1) ? 'm':'f';}
		//如果地点数据为随机，则根据输入的数组随机选地点
//		if($npc['pls'] == 99){
//			$plsnum = sizeof($plsinfo);
//			do{$rpls=rand(1,$plsnum-1);}while ($rpls==34);
//			$npc['pls'] = $rpls;
//		}
		if($npc['pls'] == 99){
			if(!empty($plslist)){
				shuffle($plslist);
				$npc['pls'] = $plslist[0];
			}else{
				$npc['pls'] = 0;
			}
		}	
		//npc初始状态默认为睡眠
		if(!isset($npc['state'])){$npc['state'] = 1;}
		//技能的获取
		init_npcdata_skills($npc);
		
		return $npc;
	}
	
	//非禁区域列表。
	//type=1:重要NPC（女主）额外回避雏菊、圣G、冰封
	function get_safe_plslist($no_dangerous_zone = true, $type = 0){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$ret = $chprocess($no_dangerous_zone, $type);
		if($no_dangerous_zone && 1 == $type)
			$ret = array_diff($ret, array(21,26,33));
		return $ret;
	}
	
	function init_npcdata_skills(&$npc)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		if (isset($npc['skills']) && is_array($npc['skills'])){
			$npc['pid'] = -2;//0和-1都会出问题
			$npc['skills']['460']='0';
			$npc['nskill'] = $npc['nskillpara'] = '';
			\skillbase\skillbase_load($npc, 1);
			foreach ($npc['skills'] as $key=>$value){
				if (defined('MOD_SKILL'.$key)){
					\skillbase\skill_acquire($key,$npc);
					if(is_array($value)){
						foreach($value as $vk => $vv){
							\skillbase\skill_setvalue($key,$vk,$vv,$npc);
						}
					}elseif ($value>0){
						\skillbase\skill_setvalue($key,'lvl',$value,$npc);
					}
				}	
			}
			
			\skillbase\skillbase_save($npc);
			unset($npc['pid']);
		}
	}
	
	function rs_game($xmode = 0) {
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		
		$chprocess($xmode);
		
		eval(import_module('sys','map','player','npc','lvlctl','skillbase'));
		if ($xmode & 8) {
			//echo " - NPC初始化 - ";
			$db->query("DELETE FROM {$tablepre}players WHERE type>0 ");
			//$plsnum = sizeof($plsinfo);
			$npcqry = '';
			$ninfo = get_npclist();
			//生成非禁区列表（不含英灵殿）
			$pls_available = \map\get_safe_plslist();
			//女主等重要NPC的特殊禁区列表
			$pls_available2 = \map\get_safe_plslist(1, 1);
			//外循环：type，编号可以不连续
			foreach ($ninfo as $i => $npcs){
				if(!empty($npcs)) {
					//检查当前模式允许不允许这个type的NPC加入
					if (!check_initnpcadd($i)) continue;
					//得到此type的NPC的加入列表
					$subnum = sizeof($npcs['sub']);
					$jarr = $jarr0 = array_keys($npcs['sub']);
					//定义数或者加入数目是0，不加入
					if (!$subnum || !$npcs['num']) $jarr=array();
					//定义数目大于加入数目，作随机选取
					elseif ($subnum > $npcs['num']) {
						shuffle($jarr);
						$jarr=array_slice($jarr,0,$npcs['num']);
					//定义数目小于加入数目，补足到加入数目
					}elseif ($subnum < $npcs['num']) {
						while(sizeof($jarr) < $npcs['num']) {
							$jarr = array_merge($jarr,$jarr0);
						}
					}
					sort($jarr);
					//内循环，每个加入数目的npc，编号可以不连续
					foreach($jarr as $j){
						//载入npc初始化参数，打个底以免漏变量
						$npc = array_merge($npcinit,$npcs);
						//载入npc个性化参数（sub）
						if(isset($npc['sub']) && is_array($npc['sub'])) $npc = array_merge($npc,$npc['sub'][$j]);
						//类型和编号，放进初始化函数有点蠢
						$npc['type'] = $i;
						$npc['sNo'] = $j;
						//选择所用地图列表
						$tmp_pls_available = 14 == $i ? $pls_available2 : $pls_available;
						//初始化函数
						$npc = init_npcdata($npc, $tmp_pls_available);
						//writeover('a.txt',json_encode($npc['nskillpara']));
//						$npc['endtime'] = $now;
//						$npc['hp'] = $npc['mhp'];
//						$npc['sp'] = $npc['msp'];
//						$npc['ss'] = $npc['mss'];
//						$npc['exp'] = \lvlctl\calc_upexp($npc['lvl'] - 1);
//						if(is_array($npc['skill'])) {$npc = $npc = array_merge($npc,$npc['skill']);}
//						else { $npc['wp'] = $npc['wk'] = $npc['wg'] = $npc['wc'] = $npc['wd'] = $npc['wf'] = $npc['skill'];}						
//						if($npc['gd'] == 'r'){$npc['gd'] = rand(0,1) ? 'm':'f';}
//						do{$rpls=rand(1,$plsnum-1);}
//						while ($rpls==34);
//						if($npc['pls'] == 99){$npc['pls'] = $rpls; }
//						$npc['state'] = 1;

						//按数据表字段进行格式化并insert
						$npc=\player\player_format_with_db_structure($npc);
						$db->array_insert("{$tablepre}players", $npc);
						
//						$npcqrylit = "(";
//						$npcqry = "(";
//						foreach ($npc as $key => $value)
//						{
//							if (in_array($key,$db_player_structure))
//							{
//								$npcqrylit .= $key.",";
//								$npcqry .= "'".$npc[$key]."',";
//							}
//						}
//						$npcqrylit=substr($npcqrylit,0,strlen($npcqrylit)-1).")";
//						$npcqry=substr($npcqry,0,strlen($npcqry)-1).")";
//						
//						$qry = "INSERT INTO {$tablepre}players ".$npcqrylit." VALUES ".$npcqry;
//						$db->query($qry);
//						unset($qry);
						
//						if (isset($npc['skills']) && is_array($npc['skills'])){
//							$npc['skills']['460']='0';
//							$qry="SELECT * FROM {$tablepre}players WHERE type>'0' ORDER BY pid DESC LIMIT 1";
//							$result=$db->query($qry);
//							$pr=$db->fetch_array($result);
//							$pp=\player\fetch_playerdata_by_pid($pr['pid']);
//							foreach ($npc['skills'] as $key=>$value){
//								if (defined('MOD_SKILL'.$key)){
//									\skillbase\skill_acquire($key,$pp);
//									if ($value>0){
//										\skillbase\skill_setvalue($key,'lvl',$value,$pp);
//									}
//								}	
//							}
//							\player\player_save($pp);
//						}
						//藏好自己，做好清理
						unset($npc);
					}
				}
			}
		}
	}
	
	function get_npclist(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','map','npc'));
		return $npcinfo;
	}
	
	//NPC回避禁区的处理
	function addarea_pc_process_single($sub, $atime){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($sub, $atime);
		eval(import_module('sys','map','npc'));
		$pid = $sub['pid'];
		$o_sub = $sub;
		$pls_available = \map\get_safe_plslist();//不能移动去的区域，如果不存在，NPC不移动
		$pls_available2 = \map\get_safe_plslist(1, 1);
		if($sub['type'] && !in_array($sub['type'],$killzone_resistant_typelist) && $pls_available){
			//选择所用的安全区列表
			$tmp_pls_available = 14 == $sub['type'] ? $pls_available2 : $pls_available;
			shuffle($tmp_pls_available);
			$sub['pls'] = $tmp_pls_available[0];
			$db->array_update("{$tablepre}players",$sub,"pid='$pid'",$o_sub);
			\player\post_pc_avoid_killarea($sub, $atime);
			//echo $sub['pid'].' ';
		}
	}
	
//	function add_new_killarea($where,$atime)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		
//		eval(import_module('sys','map','npc'));
//		$plsnum = sizeof($plsinfo) - 1;
//		if ($areanum >= sizeof($plsinfo) - 1) return $chprocess($where);
//		$query = $db->query("SELECT * FROM {$tablepre}players WHERE pls={$where} AND type>0 AND hp>0");
//		while($sub = $db->fetch_array($query)) 
//		{
//			$pid = $sub['pid'];
//			if (!in_array($sub['type'],$killzone_resistant_typelist))
//			{
//				$pls = $arealist[rand($areanum+1,$plsnum)];
//				if ($areanum+1 < $plsnum)
//				{
//					while ($pls==34) {$pls = $arealist[rand($areanum+1,$plsnum)];}
//				}
//				$db->query("UPDATE {$tablepre}players SET pls='$pls' WHERE pid=$pid");
//			}
//		}
//		$chprocess($where,$atime);
//	}
	
	function get_player_killmsg(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('player'));
		if ($pdata['type']>0)
		{
			if (isset($killmsginfo[$pdata['type']])){
				if(is_array($killmsginfo[$pdata['type']])) $kilmsg = $killmsginfo[$pdata['type']][$pdata['name']];
				else $kilmsg = $killmsginfo[$pdata['type']];
			}else  $kilmsg = '';
			return $kilmsg;
		}
		else  return $chprocess($pdata);
	}
	
	function get_player_lastword(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if ($pdata['type']>0)
		{
			eval(import_module('player','npc'));
			if($pdata['hp'] > 0){
				if(is_array ( $npc_revive_info [$pdata['type']] )){
					if (isset($npc_revive_info[$pdata['type']][$pdata['name']]))
						return $npc_revive_info[$pdata['type']][$pdata['name']];
				}else {
					if (isset($npc_revive_info[$pdata['type']]))
						return $npc_revive_info[$pdata['type']];
				}
			}
			if (is_array ( $lwinfo [$pdata['type']] )) {
				if (isset($lwinfo[$pdata['type']][$pdata['name']]))
					$lstwd = $lwinfo[$pdata['type']][$pdata['name']];
				else  $lstwd = '';
			} else {
				if (isset($lwinfo[$pdata['type']]))
					$lstwd = $lwinfo[$pdata['type']];
				else  $lstwd = '';
			}
			return $lstwd;
		}
		else  return $chprocess($pdata);
	}
	
}

?>