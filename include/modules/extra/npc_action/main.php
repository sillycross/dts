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
	//暂时的考虑是在玩家行动结束时也就是post_action()的时候调用
	function npc_action_main(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//前置处理
		if(!is_npc_action_allowed()) 
			return;
		
		//如果$gamevars没有记录npc_action_list，则把$npc_action_names写入
		$needupdate_gameinfo = 0;
		eval(import_module('sys', 'npc_action'));
		//此时有可能游戏结束或者什么的，作为保险再判定一次$gamestate
		if($gamestate < 20)
			return;
		
		if(!isset($gamevars['npc_action_list'])) {
			$npc_action_list = get_npc_action_list();
			if(empty($npc_action_list)) 
				return;
			if(!empty($gamevars['timestopped']))//NPC不准在停止的时间里行动
				return;
			//暂时不save，本函数后续处理可能还会修改到gamevars，最后一起save
			$needupdate_gameinfo = 1;
		}		
		//如果$gamevars有记录npc_action_list，则直接使用，避免反复判断因为游戏模式等原因而不可能入场的NPC，提高效率
		else{
			$npc_action_list = $gamevars['npc_action_list'];
		}
		//判定NPC是否存在
		$npc_action_pid_list = npc_action_checknpc($npc_action_list);
		//var_dump(array_keys($npc_action_pid_list));
			
		//如果存在列表同$gamevars记录的不同，修改$gamevars。注意这样会导致evonpc需要额外把NPC名字写入$gamevars
		foreach($npc_action_list as $nk => $nv) {
			if(empty($npc_action_pid_list[$nv])) {
				unset($npc_action_list[$nk]);
				$needupdate_gameinfo = 1;
			}
		}
		
		//更新gamevars
		if($needupdate_gameinfo) {
			$gamevars['npc_action_list'] = $npc_action_list;
			save_gameinfo();
		}

		$needupdate_gameinfo = 0;
		
		if(empty($npc_action_list)) 
			return;

		//判定本轮是否满足NPC行动的时间条件，前置到这里判断有助于减少数据库开销
		$npc_action_list_nowact = $npc_action_list;
		foreach($npc_action_list_nowact as $nk => $nv) {
			$last_act = $starttime;
			$act_flag = 0;
			if(!empty($gamevars['last_npc_action'][$nv])) {
				$last_act = $gamevars['last_npc_action'][$nv];
			}
			$setting = $npc_action_data[$nv];
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
			if(!$act_flag) {//不行动，则从$npc_action_list_nowact里删去，并且同时更新$npc_action_pid_list
				unset($npc_action_list_nowact[$nk]);
				unset($npc_action_pid_list[$nv]);
			}
		}
		//echo 'npc_loading: '.var_export($npc_action_pid_list,1);
		//根据$npc_action_list_nowact拉取所有NPC数据
		$npc_action_pdata_list = npc_action_loadnpc($npc_action_pid_list);
		//判定NPC存活
		foreach($npc_action_pdata_list as $nk => $nv) {
			if($nv['hp'] <= 0) {
				unset($npc_action_pdata_list[$nk]);
				$gamevars['last_npc_action'][$nv['name']] = $now;//如果NPC已死，记录死亡时间，下次间隔后再判定行动，避免反复判定占用资源
				$needupdate_gameinfo = 1;
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
		
		if(empty($needupdate_gameinfo) && empty($needupdate_players)) {
			return;
		}else{//第二轮更新gamevars，这里是统一更新NPC上次行动时间
			save_gameinfo();
		}
		//echo 'npc_update: '.var_export(array_keys($needupdate_players),1);
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
		
		eval(import_module('sys','npc_action'));
		
		$setting = $npc_action_data[$npc['name']];
		
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
						//echo $npc['name'].'-'.$npc['type'].":".$npc['rage'].' ';
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
		$npc['chatflag'] = 0;//NPC发送聊天记录的标记
		
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
			$npc['successflag'] = 0;
			if(!empty($early_flag)) {//返回的是提前批行动，把$act和$setting_act改为对应的值
				$act = $act_early;
				$setting_act = $setting_act_early;
			}
			//记录NPC行动时间。save_gameinfo()在外部执行。这里只要有返回$npc，无论是否成功都执行
			if(empty($gamevars['last_npc_action'])) {
				$gamevars['last_npc_action'] = Array();
			}
			//如果行动后会改变怒气，在这里处理
			if(!empty($setting_act['rage_change_after_action']) && !empty($ret['successflag'])) {
				$ret['rage'] += $setting_act['rage_change_after_action'];
				//这里就简单把怒气范围设为0-100吧，不考虑突破上限
				if($ret['rage'] < 0) $ret['rage'] = 0;
				elseif($ret['rage'] > 100) $ret['rage'] = 100;
			}
			//判定是否进行addchat
			if(!empty($setting_act['addchat']) && !empty($ret['chatflag'])) {
				$addchat_txt = array_randompick($setting_act['addchat_txt']);
				for($c=1;$c<10;$c++) {
					if(!empty($npc_action_tmp_paras['para'.$c])) {
						$addchat_txt = str_replace('<:para'.$c.':>', $npc_action_tmp_paras['para'.$c], $addchat_txt);
					}
				}
				\sys\addchat(6, $addchat_txt, $npc['name']);
			}
			//更新上次NPC行动时间
			$gamevars['last_npc_action'][$npc['name']] = $now;
			if(!empty($npc['guard_time'])) {
				$gamevars['last_npc_action'][$npc['name']] -= $npc['guard_time'];
				unset($npc['guard_time']);
			}
			
			//单个NPC行动更新的后续处理
			$ret = post_npc_action_single($ret);
			//清除临时变量
			$npc_action_tmp_paras = Array();
			unset($ret['chatflag'], $npc['successflag']);
		}
		
		return $ret;
	}
	
	//单个NPC的单个行动的具体执行，不进行任何判定，可能作为行动组合的其中一部分多次调用自身
	//传入的setting参数来自config，但也可被临时修改，函数中不再取config中的数据
	function npc_action_single_core($npc, $act, $setting_act)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','npc_action'));
		
		$npc_action_tmp_paras = & get_var_in_module('npc_action_tmp_paras', 'npc_action');
		
		//警觉，什么都不做，但是修改endtime，使NPC下一次行动时间提前若干秒。也可以作为空技能
		if('guard' == $act) {
			$npc['guart_time'] = !empty($setting_act['guard_time']) ? $setting_act['guard_time'] : 0;
			$npc['successflag'] = 1;
		}
		//涉及移动的NPC动作
		elseif(in_array($act, Array('move','chase','evade'))) {
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
				}elseif('W' == $chase_object) {//上一次与自己作战的玩家
					//$wid = \skillbase\skill_getvalue(1007,'last_enemy',$npc);
					$wid = $npc['bid'];
					if(!empty($wid)) $cond = "pid='$wid' AND type=0 AND hp>0";
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
					if($npc['pls'] != $cdata['pls']) //执行到这里如果当前玩家本来就不在NPC的位置，直接返回
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
			$npc['chatflag'] = $npc['successflag'] = 1;
		} 
		//偷袭，就算玩家没有进行行动，也有概率袭击当前玩家，这个时候必定先制玩家
		elseif('ambush' == $act) {
			//当前是否为玩家指令，如果不是，或者当前玩家指令不合适，则返回
			if(empty($mode) || empty($command) || in_array($command, Array('move', 'search', 'npc_action', 'back', 'menu', 'enter')))
				return;
			//如果sdata不存在或者一些不可能是玩家指令的情况，则返回
			eval(import_module('player'));
			if(empty($sdata) || empty($hp)) 
				return;
			//不在当前地点，返回
			if($npc['pls'] != $pls)
				return;
			//判定当前玩家是否符合config条件，不符合则返回
			//如果object键值有多个元素，随机取一个
			//可能以后可以改成有更丰富的交并集关系
			if(empty($setting_act['object'])) {
				$ambush_object = Array('R');
			}else{
				$ambush_object = $setting_act['object'];
				if(!is_array($ambush_object)) 
					$ambush_object = Array($ambush_object);
			}
			if(empty($ambush_object)) 
				return;
			$ambush_object = array_randompick($ambush_object);
			if('A' == $ambush_object) {
				//必定通过，但是占个位置
			}elseif('S' == $ambush_object){
				//只偷袭强者，这里判定标准是击杀过玩家或者击杀NPC数>=10
				if($killnum < 0 && $npckillnum < 10)
					return;
			}elseif('L' == $ambush_object){
				//只偷袭弱者，这里判定标准是没有杀过玩家且击杀NPC数<10
				if($killnum > 0 || $npckillnum >= 10)
					return;
			}elseif('P' == substr($ambush_object,0,1)){
				//只袭击指定名字的玩家
				$pname = explode(':', $ambush_object)[1];
				if($pname != $name)
					return;
			}elseif('W' == $ambush_object) {
				//只袭击上一次与自己作战的玩家
				$wid = $npc['bid'];
				if($pid != $wid)
					return;
			}
			//从这里开始，就算没有成功袭击，也会判定为执行过了行动
			//计算遇敌率
			//这里调用另一个进程载入NPC的数据，并由NPC对玩家执行一次发现判定，这样导致的BIG最少
			$responce = curl_post(
				url_dir().'command.php',
				array(
					'sign' => $userdb_remote_storage_sign,
					'room_id'=> $room_id,
					'mode'=>'bot',
					'command'=>'npc_action',
					'nid' => $npc['pid'],
					'npc_action' => 'calc_findenemy',
					'object' => $pid
				)
			);
			$responce = (int)$responce;
			//成功发现当前玩家
			if($responce) {
				//计算先制率，这里直接让当前玩家执行就可以了
				if(!empty($setting_act['ambush_findrate_buff'])) {
					$npc['npc_action_buff'] = $setting_act['ambush_findrate_buff'];
				}
				$active = \enemy\check_enemy_meet_active($sdata,$npc);
				unset($npc['npc_action_buff']);
				//成功先制当前玩家
				if($active) {
					//调用战斗函数
					$log .= '<br><span class="yellow b">什么，旁边的草丛里竟然传来声音……？</span><br>';
					\enemy\battle_wrapper($npc,$sdata,0);
					//消除当前玩家的指令避免后续判定，也就是认为玩家的指令被NPC打断。这里其实已经执行完行动了……
					$mode = $command = '';
					//给addchat的参数赋值
					$npc_action_tmp_paras['para3'] = $name;
					$npc['chatflag'] = $npc['successflag'] = 1;
				}
			}
		}
		if(empty($npc['successflag'])) {//没有顺利执行
			if(!empty($setting_act['action_if_fail']) && !empty($npc_action_data[$npc['name']]['setting'][$setting_act['action_if_fail']])) {//如果规定了失败动作，执行一次
				npc_action_single_core($npc, $setting_act['action_if_fail'], $npc_action_data[$npc['name']]['setting'][$setting_act['action_if_fail']]);
			}
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
	
	//判断给定名字的NPC是否存在。如果config里给定了type，则还会额外判定type是否相同
	//现在只返回键名为npc名字，键值为pid的数组
	function npc_action_checknpc($narr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($narr)) return;
		eval(import_module('sys', 'npc_action'));
		
		$narr = array_unique($narr);
		$narr_str = "'".implode("','", $narr)."'";
		$ret = Array();
		
		
		$result = $db->query("SELECT pid,name,type FROM {$tablepre}players WHERE type>0 AND name IN (".$narr_str.")");
		if(!$db->num_rows($result))
			return $ret;
		
		while($npcd = $db->fetch_array($result)){
			if(empty($npc_action_data[$npcd['name']]['type']) || $npcd['type'] == $npc_action_data[$npcd['name']]['type'])
			{
				$ret[$npcd['name']] = $npcd['pid'];
			}
		}
		return $ret;
	}
	
	//拉取给定pid的NPC
	//考虑到npc_action是在post_act()执行，玩家池里常是有数据的，用player模块的fetch函数能节省一点数据开支
	//传参$npid_arr为数组，键名随意，键值为NPC的pid
	//返回值为数组，键名为NPC名字，键值为二级数组，为fetch到的值（目前为*）。尽量不要对存在同名NPC的角色定行动方针
	function npc_action_loadnpc($npid_arr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = Array();
		
		if(empty($npid_arr)) return $ret;
		
		foreach($npid_arr as $p) {
			//echo 'fetch: '.$p.' ';
			$npcd = \player\fetch_playerdata_by_pid($p);
			$ret[$npcd['name']] = $npcd;
		}
		return $ret;
	}
	
	//NPC行动时额外的先制率
	function calculate_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','searchmemory'));
		$ret = $chprocess($ldata,$edata);
		if(!empty($edata['npc_action_buff'])) {
			//注意这里正负号是相反的
			$ldata['active_words'] = \attack\add_format(-$edata['npc_action_buff'], $ldata['active_words'],0);
			$ret -= $edata['npc_action_buff'];
		}
		return $ret;
	}
	
	//执行主函数
	function post_act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		npc_action_main();
	}
	
	//从command.php调用，执行一些需要切换到npc数据的操作。注意执行这个函数的时候还没有载入当前玩家
	//直接返回需要返回的值（好像有点绕）。这里指的是不需要echo之类的操作显示出来
	function bot_action()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//前置判定
		eval(import_module('sys'));
		
		if('bot' != $mode && 'npc_action' != $command)
			return;
		$room_id = get_var_in_module('room_id', 'input');
		$room_prefix = room_id2prefix($room_id);
		$tablepre = room_get_tablepre();
		\sys\load_gameinfo();
		//gwrite_var('b.txt', $tablepre.' '.$gamestate);
		if($gamestate < 20 || !is_npc_action_allowed())
			return;
		$nid = get_var_in_module('nid', 'input');
		$npc_action = get_var_in_module('npc_action', 'input');
		$object = get_var_in_module('object', 'input');
		
		//把NPC当做玩家载入
		\player\load_playerdata(\player\fetch_playerdata_by_pid((int)$nid, 0, 1));
		if('calc_findenemy' == $npc_action) {
			$edata = \player\fetch_playerdata_by_pid((int)$object, 0, 1);
			
			$ret = \metman\check_alive_discover($edata);
		}
		return $ret;
	}
	
	//NPC进化变身时判定并追加npc_action参数
	function evonpc($xtype,$xname)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($xtype,$xname);
		if(!empty($ret)){
			eval(import_module('sys','npc_action'));
			if(!empty($npc_action_data[$ret['name']]) && (empty($npc_action_data[$ret['name']]['type']) || $npc_action_data[$ret['name']]['type'] == $xtype)){
				if(!in_array($ret['name'], $gamevars['npc_action_list'])) {
					$gamevars['npc_action_list'][] = $ret['name'];
					save_gameinfo();
				}
			}
		}
		return $ret;
	}	
}
?>