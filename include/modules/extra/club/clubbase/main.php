<?php

namespace clubbase
{
	function init() {}
	
	//获得内定称号，$pa为NULL时代表当前玩家
	//注意这个函数不检查玩家是否可以获得这个称号
	function club_acquire($clubid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('clubbase'));
		
		if ($pa == NULL)
		{
			eval(import_module('player'));
			$club = $clubid;
		}
		else  $pa['club'] = $clubid;
		
		foreach ($clublist[$clubid]['skills'] as $key){
			if (defined('MOD_SKILL'.$key)){
				\skillbase\skill_acquire($key,$pa,1);//现在称号技能在重复获得时不会覆盖原有技能的数据
			}
		}
	}
	
	//因为某些原因失去内定称号，$pa为NULL时代表当前玩家
	function club_lost(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('clubbase','logger'));
		
		foreach (\skillbase\get_acquired_skill_array($pa) as $skillid) 
			if (\skillbase\check_skill_info($skillid,'club'))
				\skillbase\skill_lost($skillid,$pa);

		if ($pa == NULL)
		{
			$club = 0;
		}
		else  $pa['club'] = 0;
	}
	
	function club_randseed_calc($modval, $baseval, $curgid, $curuid, $curpid, $sttime, $vatime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$s=((string)$baseval).((string)$curgid).((string)$baseval).((string)$curuid).((string)$curpid).((string)$sttime).((string)$baseval).((string)$vatime).((string)$baseval);
		$s=md5($s);
		$hashval=0;
		for ($i=0; $i<strlen($s); $i++) $hashval=($hashval*269+ord($s[$i]))%$modval;
		return $hashval;
	}
	
	function club_choice_probability_process($clublist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $clublist;
	}
	
	function get_club_choice_array()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','clubbase'));
		
		//取消了费时的数据库查询，反正现在这样也预测不了了
		$curgid=$gamenum;
		$curuid=233;
		$curpid=$pid+3;
		
		$mod_value = 5000077;
		$base_value = 6397;
		
		$sttime = $starttime;
		$vatime = 233;
		
		$clublist = club_choice_probability_process($clublist);
		$ret = Array();
		for ($clubtype = 0; $clubtype <= 1; $clubtype++)
		{
			$clubcnt = 0; $club_waitlist = Array(); $p_sum = 0;
			foreach ($clublist as $key => $value) 
				if ($value['type']==$clubtype)
				{ 
					$clubcnt ++; 
					array_push($club_waitlist, Array('id' => $key, 'pr' => $value['probability']));
					$p_sum += $value['probability'];
				}
			
			for ($k=0; $k<min($max_club_choice_num[$clubtype],$clubcnt); $k++)
			{
				if ($p_sum == 0) break;
				$base_value = ($base_value + club_randseed_calc($mod_value, $base_value, $curgid, $curuid, $curpid, $sttime, $vatime)) % $mod_value;
				$dice = club_randseed_calc($mod_value, $base_value, $curgid, $curuid, $curpid, $sttime, $vatime);
				$dice = $dice % $p_sum + 1;
				for ($i=0; $i<count($club_waitlist); $i++)
				{
					if ($dice <= $club_waitlist[$i]['pr'])
					{
						array_push($ret,$club_waitlist[$i]['id']);
						$p_sum -= $club_waitlist[$i]['pr'];
						$club_waitlist[$i]['pr']=0;
						break;
					}
					else  $dice -= $club_waitlist[$i]['pr'];
				}
			}
		}
		//排序，先一般，后特殊
		$ret1 = $ret2 = Array();
		foreach($ret as $v) {
			if(9 == $v) $v = 5.5;//排序时超能排在拆弹后面
			if(!$clublist[$v]['type']) $ret1[] = $v;
			else $ret2[] = $v;
		}
		sort($ret1); sort($ret2);
		$ret = Array_merge(Array(0), $ret1, $ret2);
		foreach($ret as &$v) {
			if(5.5 == $v) $v = 9;//换回来
		}
		return $ret;
	}
	
	//玩家选择称号技能的指令处理
	function player_selectclub($id)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','clubbase'));
		if ($club!=0) return 1;
		if ($id==0) return 2;
		if ($id<0) return 3;
		$ret = get_club_choice_array();
		if ($id>=count($ret)) return 3;
		club_acquire($ret[$id]);
		return 0;
	}

	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		if($mode == 'special' && strpos($command,'clubsel') === 0) 
		{
			$clubchosen = (int)substr($command,7);
			$retval = player_selectclub($clubchosen);
			if ($retval==0)
				$log.="称号选择成功。<br>";
			else if ($retval==1)
				$log.="称号选择失败，称号一旦被选择便无法更改。<br>";
			else if ($retval==2)
				$log.="未选择称号。<br>";
			else  $log.="称号选择非法！<br>";
			$mode = 'command';
			return;
		}
		
		if ($mode == 'special' && $command == 'viewskills') 
		{
			$mode = MOD_CLUBBASE_SKILLPAGE;
			return;
		}
		$subcmd = get_var_input('subcmd');
		if ($mode == 'special' && substr($command,0,5) == 'skill' && substr($command,-8)=='_special' && ($subcmd=='upgrade' || $subcmd=='activate')) 
		{
			$id=substr($command,5,-8); $id=(int)$id;
			
			if (\skillbase\check_skill_info($id,'upgrade') && \skillbase\skill_query($id))
			{
				$func='skill'.$id.'\\'.$subcmd.$id;
				$func();
			}
			else
			{
				$log.='你不能发动这个技能。<br>';
			}
			if ($subcmd=='activate')
				$mode = 'command';
			else  $mode = MOD_CLUBBASE_SKILLPAGE;
			return;
		}
			
		$chprocess();
	}
	
	//NPC获得称号和技能，是对NPC原始数据进行处理
	//继承NPC模块的函数
	function init_npcdata($npc, $plslist=array()){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$npc = $chprocess($npc, $plslist);
		init_npcdata_skills($npc);
		return $npc;
	}
	
	function init_npcdata_skills(&$npc)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		if (!empty($npc['club']) || (!empty($npc['skills']) && is_array($npc['skills']))){
			$npc['pid'] = -2;//0和-1都会出问题
			
			$npc['nskill'] = $npc['nskillpara'] = '';
			\skillbase\skillbase_load($npc, 1);
			
			//NPC先获得称号技能
			check_npc_clubskill_load($npc);
			
			if(!empty($npc['skills']) && is_array($npc['skills'])){
				$npc['skills']['460']='0';
				//再获得特有技能
				init_npcdata_skills_get_custom($npc);
			}			
			
			\skillbase\skillbase_save($npc);
			unset($npc['pid']);
		}
	}
	
	function init_npcdata_skills_get_custom(&$npc){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
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
	}
	
	function skill_query_unlocked($id,$who=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$id=(int)$id;
		
		$func = 'skill'.$id.'\\check_unlocked'.$id;
		if(!$who) {
			eval(import_module('player'));
			$who = $sdata;
		}
		return $func($who);
	}
	
	//判定某个战斗技是否能够显示在战斗界面上
	//这个函数应该是“与”的关系
	function check_battle_skill_available(&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return true;
	}
	
	//检查战斗技能是否不可启用
	//0:可释放
	//1:武器系别不正确，需要定义$wepk_req变量，其中是$iteminfo代码（WC WG等）
	//2:攻击方式不正确，需要定义$wep_skillkind_req变量，其中是熟练度代码（wc wg等）
	//3:怒气不足，需要定义get_rage_costXXX(&$pa)函数
	//4:正在CD，需要定义check_skillXXX_state(&$pa)函数
	//5:次数用尽
	//6以后自定义
	//6:神击特殊提示
	//7:浴血特殊提示
	//8:不能对NPC发动（追猎，其他技能要使用需要自行判定）
	//9:已经标记了该NPC（鬼叫，其他技能要使用需要自行判定）
	function check_battle_skill_unactivatable(&$ldata,&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill'.$skillno));
		$ret = 0;
		$rage_func = '\\skill'.$skillno.'\\get_rage_cost'.$skillno;
		$state_func = '\\skill'.$skillno.'\\check_skill'.$skillno.'_state';
		$remain_func = '\\skill'.$skillno.'\\get_remaintime'.$skillno;
		if(!empty($wepk_req) && substr($ldata['wepk'],0,2) != $wepk_req) $ret = 1;
		elseif(!empty($wep_skillkind_req) && \weapon\get_skillkind($ldata,$edata,1) != $wep_skillkind_req) $ret = 2;
		elseif(function_exists($rage_func) && $ldata['rage'] < $rage_func($ldata)) $ret = 3;
		elseif(function_exists($state_func) && 2 == $state_func($ldata)) $ret = 4;
		elseif(function_exists($remain_func) && $remain_func($ldata) <= 0) $ret = 5;
		//6以后请自定义
		return $ret;
	}
	
	//返回不可用的提示，如果返回空则为可用
//	function get_battle_skill_unactivatable_words(&$edata,$skillno)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('player','itemmain','weapon','skill'.$skillno));
//		$ret = '';
//		$skill_state = check_battle_skill_unactivatable($sdata,$edata,$skillno);
//		
//		if(1 == $skill_state) $ret = '武器不适用，需持<span class="yellow b">'.$iteminfo[$wepk_req].'</span>';
//		elseif(2 == $skill_state) $ret = '武器不适用，需持<span class="yellow b">'.$skilltypeinfo[$wep_skillkind_req].'系武器</span>';
//		elseif(3 == $skill_state) {
//			$rage_func = '\\skill'.$skillno.'\\get_rage_cost'.$skillno;
//			$ret = '怒气不足，需要<span class="red b">'.($rage_func($sdata)).'</span>点怒气';
//		}elseif(4 == $skill_state) $ret = '技能尚在冷却中';
//		return $ret;
//	}
	
	//获得包含所有战斗技能的数组
	function get_battle_skill_entry_array(&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('clubbase','player'));
		$blist = array();
		//第一轮循环，获得所有技能，称号技能优先
		$alist = array();
		if (isset($clublist[$club])){
			$alist = $clublist[$club]['skills'];
		}
		foreach (\skillbase\get_acquired_skill_array() as $v) {
			if(!in_array($v, $alist)) $alist[] = $v;
		}
		//第二轮循环，对所有技能进行判断，提取战斗技能
		//非隐藏技能则会额外判定是否解锁
		foreach ($alist as $key) {
			if (!\skillbase\check_skill_info($key, 'achievement') && \skillbase\check_skill_info($key, 'battle') 
			 && \skillbase\skill_query($key) && check_battle_skill_available($edata,$key))
			{
				$flag = 0;
				if (\skillbase\check_skill_info($key, 'hidden')) $flag = 1;
				if (!$flag) 
				{
					$func = 'skill'.$key.'\\check_unlocked'.$key;
					if ($func($sdata)) $flag = 1;
				}
				if ($flag)
				{
					$blist[] = $key;
				}
			}
		}
		return $blist;
	}
	
	
	//生成战斗技能按钮
	function get_battle_skill_entry(&$edata,$which)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','clubbase','player'));
		//一次性获得所有战斗技能，然后根据$which的值来生成页面
		//会占用$uip['blist']
		if(!isset($uip['blist'])) {
			$uip['blist'] = get_battle_skill_entry_array($edata);
		}
		$key = $uip['blist'][$which];
		ob_start();
		include template(MOD_CLUBBASE_BATTLECMD_COMMON);
		$default = ob_get_contents();
		ob_end_clean();
		//兼容旧模式，请勿在battlecmd_common.htm里写任何非空格的意外输出，注释也不行
		if (empty(trim($default))) include template(constant('MOD_SKILL'.$key.'_BATTLECMD'));
		else echo $default;
	}
					
	function get_profile_skill_buttons()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','clubbase'));
		$___TEMP_inclist = Array();
		if ($club!=0)
			foreach ($clublist[$club]['skills'] as $key) 
				if (defined('MOD_SKILL'.$key.'_INFO') && \skillbase\check_skill_info($key, 'club') && \skillbase\check_skill_info($key, 'active') && \skillbase\skill_query($key)) 
				{ 
					$flag = 0; 
					if (\skillbase\check_skill_info($key, 'hidden')) $flag = 1; 
					if (!$flag) 
					{ 
						$func = 'skill'.$key.'\\check_unlocked'.$key; 
						if ($func($sdata)) $flag = 1; 
					} 
					if ($flag) 
						array_push($___TEMP_inclist,template(constant('MOD_SKILL'.$key.'_PROFILECMD')));
				}
		foreach (\skillbase\get_acquired_skill_array() as $key) 
			if ($club==0 || !in_array($key,$clublist[$club]['skills']))
				if (defined('MOD_SKILL'.$key.'_INFO') && !\skillbase\check_skill_info($key, 'achievement') && \skillbase\check_skill_info($key, 'active')) 
				{ 
					$flag = 0; 
					if (\skillbase\check_skill_info($key, 'hidden')) $flag = 1; 
					if (!$flag) 
					{ 
						$func = 'skill'.$key.'\\check_unlocked'.$key; 
						if ($func($sdata)) $flag = 1; 
					} 
					if ($flag) 
						array_push($___TEMP_inclist,template(constant('MOD_SKILL'.$key.'_PROFILECMD'))); 
				}
		
		foreach ($___TEMP_inclist as $___TEMP_template_name) include $___TEMP_template_name;
		
	}
	
	//生成技能表页面
	//为了调整显示顺序，不是按获得顺序直接显示的
	function get_skillpage()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','clubbase'));
		startmicrotime();
		$checked_list = $___TEMP_inclist = Array();
		//第一顺位，生命、攻防、治疗三大件，固定顺序（肌肉和根性的同名技能视为生命和攻防，先写死在这里吧）
		foreach(Array(10, 29, 11, 39, 12) as $key) {
			if (defined('MOD_SKILL'.$key.'_INFO') && \skillbase\skill_query($key)) {
				array_push($___TEMP_inclist,template(constant('MOD_SKILL'.$key.'_DESC')));
				$checked_list[] = $key;
			}
		}
		//第二顺位，本系的其他技能
		if($club) {
			$clubskill_list = $clublist[$club]['skills'];
			foreach ($clubskill_list as $key) {
				if (defined('MOD_SKILL'.$key.'_INFO') && !in_array($key, $checked_list) && \skillbase\check_skill_info($key, 'club') && !\skillbase\check_skill_info($key, 'hidden') && \skillbase\skill_query($key)) {
					array_push($___TEMP_inclist,template(constant('MOD_SKILL'.$key.'_DESC')));
					$checked_list[] = $key;
				}
			}
		}
		//第三顺位，其他技能
		foreach (\skillbase\get_acquired_skill_array() as $key) {
			if (defined('MOD_SKILL'.$key.'_INFO') && !in_array($key, $checked_list) && !\skillbase\check_skill_info($key, 'achievement') && !\skillbase\check_skill_info($key, 'hidden')) {
				array_push($___TEMP_inclist,template(constant('MOD_SKILL'.$key.'_DESC'))); 
				$checked_list[] = $key;
			}
		}
		foreach ($___TEMP_inclist as $___TEMP_template_name) {
			include $___TEMP_template_name;
		}
//		logmicrotime('完成处理');
	}
	
	//载入玩家发动的攻击技能
	function load_user_battleskill_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;

		$pdata['bskill'] = get_var_input('bskill');
		$pdata['bskillpara'] = get_var_input('bskillpara');
	}
	
	function load_user_combat_command(&$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		load_user_battleskill_command($pdata);
		$chprocess($pdata);
	}
	
	function check_npc_clubskill_load(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('clubbase'));
		
		if (!isset($clublist[$pa['club']]) || !isset($clublist[$pa['club']]['skills']) || count($clublist[$pa['club']]['skills'])==0)
			return;
			
		if (!\skillbase\skill_query($clublist[$pa['club']]['skills'][0],$pa))
		{
			club_acquire($pa['club'],$pa);
		}
	}
			
	//让NPC获取称号技能
	//现在移动到init_npcdata()时自动完成
//	function battle_prepare(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('clubbase'));
//		if ($pa['type'] && $pa['club']) check_npc_clubskill_load($pa);
//		if ($pd['type'] && $pd['club']) check_npc_clubskill_load($pd);
//		$chprocess($pa, $pd, $active);
//	}
	
	//显示NPC技能页，目前会显示能生效的称号技能，但是屏蔽生命、攻防、治愈、所有战斗技和不能生效的称号技能
	function get_npcskillpage($pn)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skillbase','clubbase'));
		$___TEMP_inclist = Array();
		//传入npc数组
		$who = $pn;
		foreach (\skillbase\get_acquired_skill_array($pn) as $key) {
			//第一层，屏蔽成就、战斗技、主动技、限制技、隐藏技能、称号特性（部分特性即天赋加熟练、亡灵复活、全息幻象叫本体特性、空投机器人的两个特性等除外）
			if (defined('MOD_SKILL'.$key.'_INFO') && !\skillbase\check_skill_info($key, 'achievement') && !\skillbase\check_skill_info($key, 'battle') 
				&& !\skillbase\check_skill_info($key, 'active') && !\skillbase\check_skill_info($key, 'limited') 
				&& !\skillbase\check_skill_info($key, 'hidden') && (!\skillbase\check_skill_info($key, 'feature') || in_array($key, array(58, 70, 512, 803, 804)))) 
			{
				//第二层，屏蔽未解锁的技能、需要升级但是0级的技能，以及10、11、12、233、252号技能（生命、攻防、治愈、网瘾、天眼）
				$check_unlocked_func = 'skill'.$key.'\\check_unlocked'.$key;
				if(!in_array($key, Array(10,11,12,233,252)) && (!\skillbase\check_skill_info($key, 'upgrade') || !empty(\skillbase\skill_getvalue($key, 'lvl', $pn))) 
					&& function_exists($check_unlocked_func) && $check_unlocked_func($pn)){
					array_push($___TEMP_inclist,template(constant('MOD_SKILL'.$key.'_DESC'))); 
				}
			}
		}
		sort($___TEMP_inclist);
		//file_put_contents('a.txt', var_export($pn,1));
		//为了显示正确的技能等级，在显示前移花接木一下
		//$sdata的替换，这一步只是换容器，不会影响传引用
		//技能页面如果用的是$who而不是$sdata则原则上不会有这个问题的，但是……
		$o_sdata = $sdata;
		$sdata = $pn;
		//$parameter_list的替换
		$o_parameter_list = $parameter_list;
		$parameter_list = $pn['parameter_list'];
		//熟练度的生成，这里主要是方便帮助列表的显示
		if(isset($sdata['skill'])) {
			$wsarr = Array('wp','wk','wg','wc','wd','wf');
			foreach($wsarr as $wsv) {
				if(is_array($sdata['skill'])) $sdata[$wsv] = $sdata['skill'][$wsv];
				else $sdata[$wsv] = $sdata['skill'];
			}
		}		
		foreach ($___TEMP_inclist as $___TEMP_template_name) include $___TEMP_template_name;
		//切换回去
		$parameter_list = $o_parameter_list;
		$sdata = $o_sdata;
	}
	
	//-1 技能不存在 0 解锁 1 等级不够 2 存在互斥技能且尚未选择 4 互斥技能解锁
	function skill_check_unlocked_state($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!defined('MOD_SKILL'.$skillid.'_INFO')) return -1;
		if(!$pa) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		eval(import_module('skill'.$skillid));
		$ret = 0;
		if(isset(${'unlock_lvl'.$skillid}) && $pa['lvl'] < ${'unlock_lvl'.$skillid}) $ret += 1;
		if(isset(${'alternate_skillno'.$skillid}) && \skillbase\skill_query(${'alternate_skillno'.$skillid}, $pa)){
			if(\skillbase\skill_getvalue($skillid,'unlocked',$pa)==0 ) $ret += 2;
			if(\skillbase\skill_getvalue(${'alternate_skillno'.$skillid},'unlocked',$pa)>0) $ret += 4;
		}
		return $ret;
	}
}

?>
