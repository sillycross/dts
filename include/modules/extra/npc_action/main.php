<?php

namespace npc_action
{
	$npc_action_tmp_paras = Array();
	
	function init() {}
	
	//确定npc_action是否开启，本模块用$npc_action_gametype判定游戏模式
	function is_npc_action_allowed(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','npc_action'));
		$ret = in_array($gametype, $npc_action_gametype);
		return $ret;
	}
	
	//获得需要npc_action的名字清单，目前直接调用config
	function get_npc_action_list(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('npc_action'));
		$ret = array_keys($npc_action_data);
		return $ret;
	}
	
	//NPC行动主函数
	//没有传参（函数自行获取数据）和返回值，内部有模块是否开启的判定，从外部直接调用就好
	//暂时的考虑是在玩家行动结束时也就是post_action()的时候调用。嘛这样考虑的话最好用player表的fetch函数
	function npc_action_main(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//前置处理
		if(!is_npc_action_allowed()) 
			return;
		
		//如果$gamevars没有记录npc_action_list，则把$npc_action_names写入
		$needupdate_gameinfo = 0;
		eval(import_module('sys'));
		//此时有可能游戏结束或者什么的，作为保险再判定一次$gamestate
		if($gamestate < 20)
			return;
		
		if(!isset($gamevars['npc_action_list'])) {
			$npc_action_list = get_npc_action_list();
			if(empty($npc_action_list)) 
				return;
				
			//暂时不save，本函数后续处理可能还会修改到gamevars，最后一起save
			$needupdate_gameinfo = 1;
		}
		
		//如果$gamevars有记录npc_action_list，则直接使用，避免反复判断因为游戏模式等原因而不可能入场的NPC，提高效率
		else{
			$npc_action_list = $gamevars['npc_action_list'];
		}
		//判定config里规定的NPC是否存在
		$npc_action_pdata_list = npc_action_checknpc($npc_action_list);
		if(empty($npc_action_pdata_list)) 
			return;
			
		//如果存在列表同$gamevars记录的不同，修改$gamevars。注意这样会导致evonpc需要额外把NPC名字写入$gamevars
		foreach($npc_action_list as $nk => $nv) {
			if(empty($npc_action_pdata_list[$nv])) {
				unset($npc_action_list[$nk]);
				$needupdate_gameinfo = 1;
			}
		}
		
		//更新gamevars
		if($needupdate_gameinfo) {
			$gamevars['npc_action_list'] = $npc_action_list;
			save_gameinfo();
		}
		
		//判定NPC存活
		foreach($npc_action_pdata_list as $nk => $nv) {
			if($nv['hp'] <= 0) {
				unset($npc_action_pdata_list[$nk]);
			}
		}
		
		if(empty($npc_action_pdata_list)) 
			return;
			
		//至少有1个相关NPC存活则进入以下判定
		
		//预留$gamevars临时覆盖config的接口
		eval(import_module('npc_action'));
		if(!empty($gamevars['npc_action_extradata'])) {
			$npc_action_data = array_merge($npc_action_data, $gamevars['npc_action_extradata']);
		}
		
		$needupdate_players = Array();
		foreach($npc_action_pdata_list as $nk => $nv) {
			$nv = npc_action_single($nv);//这个函数返回要更新的$npc标准格式数组，如果不用更新则返回NULL
			if(!empty($nv)) {
				$needupdate_players[$nk] = $nv;
			}
		}
		
		if(empty($needupdate_players)) {
			return;
		}else{//第二轮更新gamevars，这里是统一更新NPC上次行动时间
			save_gameinfo();
		}
		
		//一次性更新player表
		foreach($needupdate_players as $nv) {
			\player\player_save($nv);
			//echo '更新'.$nv['name'];
		}
	}
	
	//单个NPC的行动的判定
	//输入从player表获得的npc标准格式。如果有提供$act，则根据其来行动，如果没有则自行选择行动
	//如果有修改，返回$npc；如果没有修改返回NULL
	//会修改$gamevars变量的$last_npc_action数组
	function npc_action_single($npc, $act = '') {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = NULL;
		
		//首先判定是否行动
		$act_flag = 0;
		eval(import_module('sys','npc_action'));
		
		$last_act = $starttime;
		if(!empty($gamevars['last_npc_action'][$npc['name']])) {
			$last_act = $gamevars['last_npc_action'][$npc['name']];
		}
		$setting = $npc_action_data[$npc['name']];
		if($now - $last_act > $npc_action_min_intv) {//间隔时间大于NPC最小间隔才会行动
			if(!empty($setting['intv'])) {
				$intv = $setting['intv'];
				list($devi1, $devi2) = $setting['devi'];
				if($devi1 > 0) $devi1 = 0-$devi1;
				$range1 = $intv + $devi1; $range2 = $intv + $devi2;
				if($now - $last_act >= $range2) {//间隔时间比NPC行动间隔加上正偏差还要大，则必定行动
					$act_flag = 1;
				}else{
					$rand = rand($range1, $range2);
					if($now - $last_act >= $rand) {//间隔时间介于正负偏差之间，则随机判定行动，间隔越久概率越大
						$act_flag = 1;
					}
				}
			}
		}
		if(!$act_flag)
			return $ret;
		//echo '经过时间：'.($now - $last_act).'秒，行动。';
		
		//如果没有给出$act，根据config进行行动选择
		if(empty($act)) {
			//第一轮，筛选出符合条件的行动，并计算概率分母。注意提前批行动是单独计算的
			$rate_total = $rate_total_early = 0;
			$action_list = $action_list_early = Array();
			foreach($setting['actions'] as $ak => $av) {
				if(!empty($setting['setting'][$ak])) {
					$allow_flag = 1;
					$setting_act = $setting['setting'][$ak];
					//判定是否符合清醒条件
					if(!empty($setting_act['need_awake']) && in_array($npc['state'], array(1,2,3))) {
						$allow_flag = 0;
					}
					//判定是否符合怒气条件
					if(isset($setting_act['need_rage_GE']) && $npc['rage'] < $setting_act['need_rage_GE']) {
						$allow_flag = 0;
					}
					if(isset($setting_act['need_rage_LE']) && $npc['rage'] > $setting_act['need_rage_LE']) {
						$allow_flag = 0;
					}
					if($allow_flag) {
						if(!empty($setting_act['early_action'])) {//提前批行动单独计算概率
							$rate_total_early += $av;
							$action_list_early[$ak] = $av;
						}else{
							$rate_total += $av;
							$action_list[$ak] = $av;
						}
					}
				}
			}
			
			//第二轮，从符合条件行动中选出执行哪个行动，如果多个行动则按概率和概率分母的比例加权随机
			if(!empty($action_list_early)) {//先计算是否有提前批行动
				$act_early = get_npc_action_list_chosen ($action_list_early, $rate_total_early);
			}
			if(!empty($action_list)) {
				$act = get_npc_action_list_chosen ($action_list, $rate_total);
			}
		}	
		
		if(!empty($act_early) && !empty($setting['setting'][$act_early])) {//先执行提前批行动
			$setting_act_early = $setting['setting'][$act_early];
			$ret = npc_action_single_core($npc, $act_early, $setting_act_early);
			if(!empty($ret)) $early_flag = 1;
		}
		
		if(empty($ret) && !empty($act) && !empty($setting['setting'][$act])) {//提前批无结果，再正常执行
			$setting_act = $setting['setting'][$act];
			$ret = npc_action_single_core($npc, $act, $setting_act);
		}		
		
		if(!empty($ret)) {//如果返回值有值，则进行后续行动处理
			if(!empty($early_flag)) {//返回的是提前批行动，把$act和$setting_act改为对应的值
				$act = $act_early;
				$setting_act = $setting_act_early;
			}
			//记录NPC行动时间。save_gameinfo()在外部执行。
			if(empty($gamevars['last_npc_action'])) {
				$gamevars['last_npc_action'] = Array();
			}
			//如果行动后会改变怒气，在这里处理
			if(!empty($setting_act['rage_change_after_action'])) {
				$ret['rage'] += $setting_act['rage_change_after_action'];
				//这里就简单把怒气范围设为0-100吧，不考虑突破上限
				if($ret['rage'] < 0) $ret['rage'] = 0;
				elseif($ret['rage'] > 100) $ret['rage'] = 100;
			}
			//判定是否进行addchat
			if(!empty($setting_act['addchat'])) {
				$addchat_txt = array_randompick($setting_act['addchat_txt']);
				for($c=1;$c<10;$c++) {
					if(!empty($npc_action_tmp_paras['para'.$c])) {
						$addchat_txt = str_replace('<:para'.$c.':>', $npc_action_tmp_paras['para'.$c], $addchat_txt);
					}
				}
				\sys\addchat(6, $addchat_txt, $npc['name']);
			}
			$gamevars['last_npc_action'][$npc['name']] = $now;
			
			//单个NPC行动更新的后续处理
			$ret = post_npc_action_single($ret);
			//清除临时变量
			$npc_action_tmp_paras = Array();
		}
		
		return $ret;
	}
	
	//单个NPC的单个行动的具体执行，不进行任何判定，可能作为行动组合的其中一部分多次调用自身
	//传入的setting参数来自config，但也可被临时修改，函数中不再取config中的数据
	function npc_action_single_core($npc, $act, $setting_act)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		$npc_action_tmp_paras = & get_var_in_module('npc_action_tmp_paras', 'npc_action');

		//涉及移动的NPC动作
		if(in_array($act, Array('move','chase','evade'))) {
			eval(import_module('map'));
			$moveto = $npc['pls'];//保底赋值，防止飞到无月去
			$o_pls = $npc['pls'];//记录原地点
			$safe_plslist = array_keys($plsinfo);
			//可用移动目的地计算。在禁区数较多时会造成NPC行动减缓
			if(!empty($setting_act['avoid_forbidden'])) {
				$safe_plslist = \npc\get_safe_plslist($setting_act['avoid_dangerous']);
				if(empty($safe_plslist)) //如果所有的可用移动目的地都是禁区，则不移动
					return;
			}
			
			//移动目的地计算，根据moveto_list设置，可以是指定地点或者随机移动。
			if('move' == $act) {//单纯移动
				//如果moveto_list键值有多个元素，随机取一个
				$moveto_list = $setting_act['moveto_list'];
				if(!is_array($moveto_list)) 
					$moveto_list = Array($moveto_list);
				$moveto_list = array_intersect($moveto_list, array_merge($safe_plslist, Array(99)));
				//移除npc所处位置
				$moveto_list = array_diff($moveto_list, Array($npc['pls']));
				
				if(empty($moveto_list)) //如果所有的目的地都是禁区，则不移动
					return;
				
				$moveto = array_randompick($moveto_list);

				if(99 == $moveto) {//99号代表随机一个可用地点
					$moveto_list = array_diff($safe_plslist, Array($npc['pls']));
					if(empty($moveto_list)) //如果所有的目的地都是禁区，则不移动
						return;
					$moveto = array_randompick($moveto_list);
				}
			}elseif('chase' == $act || 'evade' == $act) {//追杀或躲避特定角色
				//如果object键值有多个元素，随机取一个
				if(empty($setting_act['object'])) {
					$chase_object = Array('R');
				}else{
					$chase_object = $setting_act['object'];
					if(!is_array($chase_object)) 
						$chase_object = Array($chase_object);
				}
				if(empty($chase_object)) 
					return;
				$chase_object = array_randompick($chase_object);
				//根据object条件判定实际是哪个角色，因为这里只取部分字段，直接读数据库罢
				$cond = '';
				if('R' == $chase_object) {
					$cond = "type=0 AND hp>0";
				}elseif('T' == $chase_object) {
					$cond = "type=0 AND hp>0 ORDER BY killnum DESC, npckillnum DESC, lvl DESC";
				}elseif('B' == $chase_object) {
					$cond = "type=0 AND hp>0 ORDER BY killnum ASC, npckillnum ASC, lvl ASC";
				}elseif('P' == substr($chase_object,0,1)){
					$pname = explode(':', $chase_object)[1];
					$cond = "name='$pname' AND type=0 AND hp>0";
				}elseif('N' == substr($chase_object,0,1)){
					$pname = explode(':', $chase_object)[1];
					$cond = "name='$pname' AND type>0 AND hp>0";
				}
				if(empty($cond)) 
					return;
				$result = $db->query("SELECT name, pid, pls FROM {$tablepre}players WHERE ".$cond);
				$cdatas = Array();
				while($cdata = $db->fetch_array($result)) {
					if(in_array($cdata['pls'], $safe_plslist)) {//只获取在安全区的对象
						$cdatas[] = $cdata;
					}
				}
				if(empty($cdatas)) //没有可用目标则跳过本次行动
					return;
				$cdata = array_randompick($cdatas);
				if('evade' == $act) {//躲避
					if($npc['pls'] != $cdata['pls']) //执行到这里如果本来就不在相关地图，直接返回
						return;
					$npc['pls'] = $cdata['pls'];
					//调用自己，执行一次随机移动
					$setting_act['moveto_list'] = Array(99);
					$ret0 = npc_action_single_core($npc, 'move', $setting_act);
					if(empty($ret0)) 
						return;
					$moveto = $ret0['pls'];
				}else{//追杀
					$moveto = $cdata['pls'];
				}
				
				//给addchat的参数赋值
				$npc_action_tmp_paras['para3'] = $cdata['name'];
			}

			//随机到的目的地与NPC当前位置不同，才执行行动。如果不是，就跳过本次行动
			if($moveto == $npc['pls']) {
				return;
			}
			//真正给返回值赋值
			$npc['pls'] = $moveto;
			//给addchat的参数赋值
			$npc_action_tmp_paras['para1'] = $plsinfo[$o_pls]; $npc_action_tmp_paras['para2'] = $plsinfo[$moveto];
		}
		return $npc;
	}
	
	//单个NPC行动更新的后续处理。本模块不进行任何操作，其他模块可重载本函数
	//注意传参本身已经是npc_action_single()的返回值
	//返回值同npc_action_single()的返回值的格式相同，所以如果这里返回空值就会阻止NPC行动
	function post_npc_action_single($npc) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $npc;
	}
	
	//从形如array('move' => 100)这样的加权概率数组中随机选出一个行动（键名）
	//如果有给出$rate_total会直接用（之前代码是那么写的，省点运算量）如果没有则会重新算一遍
	function get_npc_action_list_chosen($action_list, $rate_total=0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($rate_total)) {
			$rate_total = 0;
			foreach($action_list as $ak => $av) {
				$rate_total += (int)$av;
			}
		}
		if(empty($rate_total))
			return;
		if(count($action_list)>1){
			$rand = rand(0,99);
			foreach($action_list as $ak => $av) {
				if($rand < $av/$rate_total*100) {
					$act = $ak;
					break;
				}
			}
		}elseif($action_list > 0){
			$act = array_keys($action_list)[0];
		}
		return $act;
	}
	
	//判断给定名字的NPC是否存在
	//传参$narr为数组，元素为NPC名字，返回值为数组，键名为NPC名字，键值为二级数组，二级键名为pid，二级键值为fetch到的值（目前为*）。尽量不要对存在同名NPC的角色定行动方针
	function npc_action_checknpc($narr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($narr)) return;
		eval(import_module('sys'));
		
		$narr = array_unique($narr);
		$narr_str = "'".implode("','", $narr)."'";
		$ret = Array();
		
		//考虑到npc_action是在post_act()执行，玩家池里常是有数据的，用player模块的fetch函数能节省一点数据开支
		//这里分两步，第一步用sql获得符合条件NPC的pid，第二步用fetch获得完整的信息
		$result = $db->query("SELECT pid FROM {$tablepre}players WHERE type>0 AND name IN (".$narr_str.")");
		if(!$db->num_rows($result))
			return $ret;
		
		$pids = Array();
		while($npcd = $db->fetch_array($result)){
			$pids[] = $npcd['pid'];
		}
		
		foreach($pids as $p) {
			$npcd = \player\fetch_playerdata_by_pid($p);
			$ret[$npcd['name']] = $npcd;
		}
		
		return $ret;
	}
	
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		npc_action_main();
	}
}
?>