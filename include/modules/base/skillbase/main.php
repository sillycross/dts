<?php

namespace skillbase
{
	global $acquired_list;
	global $parameter_list;
	global $ppid;	//当前玩家pid
	$valid_skills = array();
	
	function init() 
	{
		global $ppid; $ppid = -1;
	}
	
	//判定指定编号的技能设定数据里是否包含指定的标签
	function check_skill_info($skillno, $str){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$str = str_replace(';','',$str).';';
		if(defined('MOD_SKILL'.$skillno.'_INFO') && strpos(constant('MOD_SKILL'.$skillno.'_INFO'), $str)!==false) return true;
		else return false;
	}

	//设置本模块当前玩家pid。在ppid=-1时也就是没有初始化时会被调用
	function skillbase_set_ppid()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$pid = get_var_in_module('pid', 'player');
		if (!empty($pid)) {
			eval(import_module('skillbase'));
			$ppid = $pid;
		}
	}
	
	//技能储存用base64转化函数
	function b64_conv_to_value($c)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ('a'<=$c && $c<='z') return ord($c)-ord('a');
		if ('A'<=$c && $c<='Z') return ord($c)-ord('A')+26;
		if ('0'<=$c && $c<='9') return ord($c)-ord('0')+52;
		if ($c=='+') return 62;
		if ($c=='-') return 63;
		throw new Exception('bad nskill value '.$c);
	}
	
	function value_conv_to_b64($c)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($c>=0)
		{
			if ($c<=25) return chr(ord('a')+$c);
			if ($c<=51) return chr(ord('A')+$c-26);
			if ($c<=61) return chr(ord('0')+$c-52);
			if ($c==62) return '+';
			if ($c==63) return '-';
		}
		throw new Exception('bad char in skillbase '.$c);
	}
	
	function parse_skill_parameter_data($nskparas)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$nskparas) return array();
		$para=explode(',',$nskparas);
		$cn=count($para);
		
		$para_list=Array();
		if ($cn%2!=1) throw new Exception('bad nskillpara value '.$nskparas);
		for ($i=0; $i<($cn-1)/2; $i++)
		{
			$para_list[base64_decode($para[$i*2])]=base64_decode($para[$i*2+1]);
		}
		return $para_list;
	}
	
	//载入指定角色的所有技能
	//传入的$pa是标准格式的玩家数组
	//把技能字符串转义为技能数组，并直接存入$pa
	//$dummy开启时认为这个$pa不是实际存在的玩家对象，不判定$pid也不触发onload_event
	function skillbase_load(&$pa, $dummy = false)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		list($pa['acquired_list'], $pa['parameter_list']) = skillbase_load_process($pa['nskill'], $pa['nskillpara']);
		
		if(empty($dummy)){
			if (!empty($pa['pid']) && $pa['pid']==$pid)
			{
				eval(import_module('skillbase'));
				$acquired_list = $pa['acquired_list'];
				$parameter_list = $pa['parameter_list'];
			}
			if(!empty($gamestate)) skill_onload_event($pa);//2024.02.14现在游戏结束时不会载入角色技能
		}
	}
	
	function skillbase_load_process($ss, $sps){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ac_list=Array();
		if (strlen($ss)%2!=0) throw new Exception('bad nskill value '.$ss);
		for ($i=0; $i<strlen($ss)/2; $i++)
		{
			$c=b64_conv_to_value($ss[$i*2])*64+b64_conv_to_value($ss[$i*2+1]);
			$ac_list[$c]=1;
		}
		$para_list = parse_skill_parameter_data($sps);
		return array($ac_list, $para_list);
	}
	
	//角色载入技能数据时进行的处理
	//这个skill_onload_event()执行顺序很早，而且底层对$acquired_list $parameter_list $sdata $pa等的引用关系的设计有点问题，导致在这里进行过于复杂的处理如选称号等，处理结果会被覆盖
	//涉及较复杂的判定建议延后处理，不要继承这个函数！
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//判定临时技能的失去
		check_tempskill_process($pa);
	}
	
	//格式化并储存技能参数，基本上只有player_save()调用
	//$in_proc代表进程执行中的额外储存
	function skillbase_save(&$pa, $in_proc = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		//如果是进程执行中的储存，不进行onsave_event的判断（目前调用这个函数的基本上是受伤和异常状态等临时技能）
		if(!$in_proc) skill_onsave_event($pa);
		
		eval(import_module('player','skillbase'));
		
		if ($pa['pid']==$pid)
		{
			$ac_list=$acquired_list;
			$para_list=$parameter_list;
		}
		else
		{
			$ac_list=$pa['acquired_list'];
			$para_list=$pa['parameter_list'];
		}
		
		list($ns, $pl) = skillbase_save_process($ac_list, $para_list);
		
		if ($pa['pid']==$pid)
		{
			$nskill=$ns;
			$nskillpara=$pl;
		}

		$pa['nskill']=$ns;
		$pa['nskillpara']=$pl;
	}
	
	function skillbase_save_process($sa, $spa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ns='';
		
		foreach ($sa as $skillkey => $skillvalue)
		{
			if ($skillvalue == 1)
			{
				$skillkey=(int)$skillkey;
				$x=$skillkey/64; $y=$skillkey%64; $x=(int)$x;
				$ns.=value_conv_to_b64($x).value_conv_to_b64($y);
			}
		}
		
		$pl='';
		foreach ($spa as $skillkey => $skillvalue)
		{
			$pl.=base64_encode($skillkey).','.base64_encode($skillvalue).',';
		}
		return array($ns, $pl);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function load_playerdata($pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pdata);
		if(isset($pdata['nskill'])) skillbase_load($pdata);
	}
	
	//对从数据库里读出来的raw数据的处理
	function playerdata_construct_process($data){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$data = $chprocess($data);
		skillbase_load($data);
		return $data;
	}
	
//	function fetch_playerdata($Pname, $Ptype = 0, $ignore_pool = 0)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$pa=$chprocess($Pname, $Ptype, $ignore_pool);
//		skillbase_load($pa);
//		return $pa;
//	}
//	
//	function fetch_playerdata_by_pid($pid)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$pa=$chprocess($pid);
//		skillbase_load($pa);
//		return $pa;
//	}
	
	//角色数据储存之前，格式化技能字段数据
	function player_save($data, $in_proc = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		skillbase_save($data, $in_proc);
		$chprocess($data, $in_proc);
	}
	
	//获得技能
	//$skillid为技能编号，$pa为传入的角色数据（如果留空则会调用$sdata即当前玩家）
	//$no_cover如果为真则在重复获得技能时不会再次执行acquirexxx()（一般用来获得一些参数）
	function skill_acquire($skillid, &$pa = NULL, $no_cover = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skillbase'));
		$skillid=(int)$skillid;
		$already = 1;
		if ($pa == NULL || (!empty($pa['pid']) && $pa['pid']==$pid))//当前玩家
		{
			if ($pa == NULL) {
				\player\update_sdata();
				$pa=$sdata;//这句要不要加引用呢，原本是没加的，需要找个时间排查一下加了会带来什么
			}
			if(empty($acquired_list[$skillid])) {
				$already = 0;
				$acquired_list[$skillid]=1;
			}			
		}
		else
		{
			if(empty($pa['acquired_list'][$skillid])) {
				$already = 0;
				$pa['acquired_list'][$skillid]=1;
			}
		}
		$func='skill'.$skillid.'\\acquire'.$skillid;
		//称号技能重复获得时不会再次触发acquirexxx()
		if (defined('MOD_SKILL'.$skillid) && !($already && $no_cover)) $func($pa);
		//每次获得技能时把临时参数删掉，避免获得了非临时技能被临时技能删掉
		skill_delvalue($skillid, 'tsk_expire', $pa);
	}
	
	//失去技能
	//$skillid为技能编号，$pa为传入的角色数据（如果留空则会调用$sdata即当前玩家）
	//会触发对应技能的lostxxx()函数
	function skill_lost($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skillbase'));
		$skillid=(int)$skillid;
		$changed = 0;
		if ($pa == NULL || $pa['pid']==$pid)
		{
			if ($pa == NULL) {
				\player\update_sdata();
				$pa=$sdata;
			}
			if(!empty($acquired_list[$skillid])){
				$changed = 1;
				$acquired_list[$skillid]=0;
			}
		}
		else
		{
			if(!empty($pa['acquired_list'][$skillid])){
				$changed = 1;
				$pa['acquired_list'][$skillid]=0;
			}
		}
		if($changed) {
			$func='skill'.$skillid.'\\lost'.$skillid;
			if (defined('MOD_SKILL'.$skillid)) $func($pa);
		}
	}
	
	//判定角色是否拥有指定编号的技能
	//$skillid为技能编号，$pa为传入的角色数据（如果留空则会用$acquired_list判定当前玩家的技能情况）
	function skill_query($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player', 'skillbase'));
		$skillid=(int)$skillid;
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || (!empty($pa['pid']) && $pa['pid']==$ppid)) return !empty($acquired_list[$skillid]) && skill_enabled($skillid, $sdata);
		return !empty($pa['acquired_list'][$skillid]) && skill_enabled($skillid, $pa);
	}
	
	//判定角色指定编号的技能是否解锁
	//$skillid为技能编号，$pa为传入的角色数据。注意这里$pa不能留空，代码就是这样写的，很蛋疼。
	//“禁用无效”>“禁用”
	//所以要禁用请继承skill_enabled_core()，要保证启用请继承skill_enabled()
	function skill_enabled($skillid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return skill_enabled_core($skillid, $pa);
	}
	
	//判定技能是否解锁的核心函数
	function skill_enabled_core($skillid, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return true;
	}

	//判定角色是否拥有包含指定标签的技能
	//$str为需查询的标签，$pa为传入的角色数据（如果留空则会用$acquired_list判定当前玩家的技能情况）
	function check_have_skill_info($str, &$pa = NULL) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || (!empty($pa['pid']) && $pa['pid']==$ppid)) 
		{
			$check_skill_list = $acquired_list;
		}
		else
		{
			$check_skill_list = $pa['acquired_list'];
		}
		$ret = 0;
		foreach($check_skill_list as $skillno => $v) {
			if(check_skill_info($skillno, $str)){
				$ret = 1;
				break;
			}
		}
		return $ret;
	}
	
	function get_acquired_skill_array(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || (!empty($pa['pid']) && $pa['pid']==$ppid))
		{
			$ret=Array();
			foreach($acquired_list as $key=>$value) if ($value==1) array_push($ret,$key);
			return $ret;
		}
		else
		{
			$ret=Array();
			foreach($pa['acquired_list'] as $key=>$value) if ($value==1) array_push($ret,$key);
			return $ret;
		}
	}
	
	function skill_setvalue($skillid, $skillkey, $skillvalue, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillkey=$skillid.'_'.$skillkey;
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || (!empty($pa['pid']) && $pa['pid']==$ppid)) 
		{
			$parameter_list[$skillkey]=$skillvalue;
		}
		else
		{
			$pa['parameter_list'][$skillkey]=$skillvalue;
		}
	}
	
	function skill_getvalue($skillid, $skillkey, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillkey=$skillid.'_'.$skillkey;
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || (!empty($pa['pid']) && $pa['pid']==$ppid)) 
		{
			if (isset($parameter_list[$skillkey])) return $parameter_list[$skillkey]; else return NULL;
		}
		else
		{
			if (isset($pa['parameter_list'][$skillkey])) return $pa['parameter_list'][$skillkey]; else return NULL;
		}
	}
	
	//这个函数与上面那个区别在于，这个是直接从nskillpara串中读出某个元素的值，不需要初始化player结构
	function skill_getvalue_direct($skillid, $skillkey, $paradata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skillkey=$skillid.'_'.$skillkey;
		$para_list=parse_skill_parameter_data($paradata);
		if (isset($para_list[$skillkey])) return $para_list[$skillkey]; else return NULL;
	}
	
	function skill_delvalue($skillid, $skillkey, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillkey=$skillid.'_'.$skillkey;
		if ($ppid==-1) skillbase_set_ppid();
		if ($pa == NULL || $pa['pid']==$ppid) 
		{
			unset($parameter_list[$skillkey]);
		}
		else
		{
			unset($pa['parameter_list'][$skillkey]);
		}
	}
	
	//玩家加入战场时处理所有的技能，要求传入的$pa里有skills元素
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skillbase'));
		$ret = $chprocess($pa);
		
		//为了灵活性，直接处理所有技能，在固定称号的时候记得要写入skills不然进游戏就没技能了
		if (!empty($pa['skills'])){
			foreach ($pa['skills'] as $key=>$value){
				if (defined('MOD_SKILL'.$key)){
					skill_acquire($key,$pa);
					if(is_array($value)){
						foreach($value as $vk => $vv){
							skill_setvalue($key,$vk,$vv,$pa);
						}
					}elseif ($value>0){
						skill_setvalue($key,'lvl',$value,$pa);
					}
				}	
			}
		}
		//如果是篝火挑战者，或者别的会换卡的卡，在这里把$card换回原卡
		//这样成就按篝火判定，但是技能是换到的卡的技能
		if(!empty($pa['o_card'])) {
			$pa['card'] = $pa['o_card'];
		}
		//追加模式入场技能
		if(!empty($valid_skills[$gametype])){
			foreach($valid_skills[$gametype] as $vsv){
				if(defined('MOD_SKILL'.$vsv))
					\skillbase\skill_acquire($vsv,$pa);
			}
		}
		return $ret;
	}
	
	function check_tempskill_process(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$arr = get_acquired_skill_array($pa);
		foreach ($arr as $key)
		{
			if (defined('MOD_SKILL'.$key.'_INFO') && (strpos(constant('MOD_SKILL'.$key.'_INFO'),'club;')!==false || strpos(constant('MOD_SKILL'.$key.'_INFO'),'card;')!==false))
			{
				check_skill_tempskill($key, $pa);
			}
		}
	}
	
	function check_skill_tempskill($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$tsk_expire = skill_getvalue($skillid, 'tsk_expire', $pa);
		if (skill_query($skillid, $pa) && !empty($tsk_expire))
		{
			if ($now > $tsk_expire)
			{
				skill_lost($skillid, $pa);
				skill_delvalue($skillid, 'tsk_expire', $pa);
			}
		}
	}

}

?>