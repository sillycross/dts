<?php

namespace achievement_base
{
	$ach_list = $ach_expired_list = Array();
	
	define('POSITIVE_PLAYER_DESC','与你IP不同且APM不少于10');
	function init() {
	}
	
	function ach_show_init(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('achievement_base'));
		$ach_list = $ach_expired_list = array();
		foreach($achtype as $ak => $av){
			//未开始直接不显示
			if(1 == check_achtype_available($ak)){
				$ach_list[$ak] = $av;
			}elseif(2 == check_achtype_available($ak)){//过期的另算
				$ach_expired_list[$ak] = $av;
			}
		}
//		foreach($bk_list as $bk => $bv){
//			$n_achtype[$bk] = $bv;
//		}
//		$achtype = $n_achtype;
	}
	
	//判定成就是否合法存在
	function check_ach_valid($achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return defined('MOD_SKILL'.$achid.'_INFO') && defined('MOD_SKILL'.$achid.'_ACHIEVEMENT_ID')	
			&& \skillbase\check_skill_info($achid, 'achievement') && !\skillbase\check_skill_info($achid, 'hidden');
	}
	
	//判定一个成就大类是否过期
	function check_achtype_available($achid){//0 未开始； 1 进行中； 2 过期
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','achievement_base'));
		$ret = 1;
		if(isset($ach_available_period[$achid])){
			list($achstart, $achend) = $ach_available_period[$achid];
			if(!empty($achstart) && $now < $achstart) $ret = 0;
			if(!empty($achend) && $now > $achend) $ret = 2;
		}
		return $ret;
	}
	
	//进入游戏时加载所有成就
	function post_enterbattlefield_events(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
		eval(import_module('sys','achievement_base'));
		$alist = array();
		foreach($achlist as $atk => $atv){
			if( 1 == check_achtype_available($atk))
				$alist = array_merge($alist, $atv);
		}
		foreach($alist as $av){
			//只有玩家可以获得成就技能
			if (!$pa['type']
			//非全局成就
				&& !\skillbase\check_skill_info($av, 'global')
			//确认允许完成成就的模式，未定义则用0键（只有正常游戏可以完成）
				&& ( ( !isset($ach_allow_mode[$av]) && in_array($gametype, $ach_allow_mode[0]) ) || ( isset($ach_allow_mode[$av]) && in_array($gametype,$ach_allow_mode[$av]) ) )
			//成就没有废弃（不能直接删，否则旧数据可能错位）
				&& !defined('MOD_SKILL'.$av.'_ABANDONED')
				&& !\skillbase\skill_query($av,$pa))
			\skillbase\skill_acquire($av,$pa);
		}
	}
	
	//传入成就数组，进行成就编码
	//如果$old_version=1则用旧版n_achievements方式，否则用新版方式
	function encode_achievements($aarr, $old_version=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$old_version){
			return gencode($aarr);
		}else{
			return encode_achievements_o($aarr);
		}
	}

	//传入users表的数组，进行成就解码
	//自动识别新旧成就，如果只有旧的则识别旧的，否则只识别新的 
	function decode_achievements($udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!empty($udata['u_achievements'])) {
			return gdecode($udata['u_achievements'], 1);
		}else{
			return decode_achievements_o($udata['n_achievements']);
		}
	}
	
	//成就编码（旧）
	function encode_achievements_o($aarr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('achievement_base'));
		$achdata=$aarr;
		$maxid=count($achdata)-2;
		$ret = '';
		foreach($achlist as $atvar){
			foreach ($atvar as $key){
				if (defined('MOD_SKILL'.$key.'_INFO') && defined('MOD_SKILL'.$key.'_ACHIEVEMENT_ID')){
					if ((\skillbase\check_skill_info($key, 'achievement'))&&(!\skillbase\check_skill_info($key, 'hidden')))
					{
						$id=(int)(constant('MOD_SKILL'.$key.'_ACHIEVEMENT_ID'));
						if ($id>$maxid) $maxid=$id;
						if (isset($achdata[$key])) $s=$achdata[$key];
						else $s='';
						//需要解码的意思
						$f=false;
						if (!\skillbase\check_skill_info($key, 'daily')) $f=true;
						if ($s!='VWXYZ') $f=true;
						if ($f){
							if($key==326){
								$v='';
								foreach($s as $sv){
									$v .= base64_encode_number($sv,3);
								}
							}else{
								$v=min((int)$s,(1<<30)-1);
								$v=base64_encode_number($v,5);
							}
						}
						$achdata[$key] = $achdata[$key]==='VWXYZ' ? 'VWXYZ' : $v;
					}
				}
			}
		}
		for ($i=0; $i<=$maxid; $i++)
			$ret.=$achdata[$i+300].';';

		return $ret;
	}
	
	//成就解码（旧）
	function decode_achievements_o($astr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('achievement_base'));
		$achdata=explode(';',$astr); 
		$ret = array();
		foreach($achlist as $atvar){
			foreach ($atvar as $key){
				if (defined('MOD_SKILL'.$key.'_INFO') && defined('MOD_SKILL'.$key.'_ACHIEVEMENT_ID')){
					if ((\skillbase\check_skill_info($key, 'achievement'))&&(!\skillbase\check_skill_info($key, 'hidden')))
					{
						$id=(int)(constant('MOD_SKILL'.$key.'_ACHIEVEMENT_ID'));
						if (isset($achdata[$id])) $s=(string)$achdata[$id];
						else $s='';
						//需要解码的意思
						$f=false;
						if (!\skillbase\check_skill_info($key, 'daily')) $f=true;
						if ($s&&$s!='VWXYZ') $f=true;
						$v=NULL;
						if ($f){
							if($key==326) $v=\skill326\cardlist_decode326($s);
							else $v=base64_decode_number($s);	
							$ret[$key] = $v;
						}
						
					}
				}
			}
		}
		return $ret;
	}
	
	//判定单个成就是否拥有（拥有，不是完成。主要是日常任务在用）
	function check_ach_got($key, $val)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret=false;
		if (!\skillbase\check_skill_info($key, 'daily')) $ret=true;
		elseif ( $val!==NULL && $val!=='VWXYZ' ) $ret=true;
		return $ret;
	}
	
	//更新单个玩家的成就记录
	//注意这个函数不负责写数据库，实际写数据库在gameover()判定的最后完成
	function update_achievements_by_udata(&$udata, &$pdata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$udata || !$pdata) return;
		//如果技能和成就没初始化，则初始化一次
		if(empty($pdata['acquired_list'])) \skillbase\skillbase_load($pdata);
		if(!is_array($udata['u_achievements'])) $udata['u_achievements'] = decode_achievements($udata);
		//每日任务是根据游戏结束时的用户数据判定的，也就是允许游戏结束前换每日任务
		foreach (\skillbase\get_acquired_skill_array($pdata) as $key)
		{
			if (check_ach_valid($key) && !\skillbase\check_skill_info($key, 'global'))//技能存在而且有效
			{
				$val = NULL;
				//无视没有获得的日常成就
				if (isset($udata['u_achievements'][$key])) $val = $udata['u_achievements'][$key];
				$vflag=check_ach_got($key, $val);
				if ($vflag){
					$func='\\skill'.$key.'\\finalize'.$key;
					if(function_exists($func)) $ret=$func($pdata,$val);//兼容性代码，如果存在旧式的结算函数，就按旧式结算函数算
					else $ret = ach_finalize($pdata, $udata, $val, $key);

					$udata['u_achievements'][$key]=$ret;
				}
			}
		}
	}
	
	//更新所有玩家的成就记录
	function update_achievements(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		//先获得当前局所有玩家的名称
//		$namelist = array();
//		$result = $db->query("SELECT name FROM {$tablepre}players WHERE type=0");
//		while ($pd=$db->fetch_array($result))
//		{
//			$namelist[] = $pd['name'];
//		}
//		$updatelist = array();
//		//然后一次性读用户记录，尽量减少在循环里读写数据库
//		if(!empty($namelist)){
//			$result = fetch_udata_multilist('*', array('username' => $namelist));
//			foreach($result as $udata){
//				$pdata = \player\fetch_playerdata($udata['username']);//这句理论上可以被玩家池加速
//				update_achievements_by_udata($udata, $pdata);
//				$updatelist[] = Array(
//					'username' => $udata['username'],
//					'u_achievements' => encode_achievements($udata['u_achievements'])
//				);
//			}
//		}
//		//一次性更新
//		$db->multi_update("{$gtablepre}users", $updatelist, 'username');
		
		foreach($gameover_ulist as &$udata){
			$pdata = $gameover_plist[$udata['username']];
			update_achievements_by_udata($udata, $pdata);
			$udata['u_achievements'] = encode_achievements($udata['u_achievements']);
		}
	}
	
	function post_gameover_events()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		update_achievements();
//		logmicrotime('成就处理');
		$chprocess();
	}
	
	//返回合法的成就数组（显示用）
	function get_valid_achievements($u_achievements)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//先载入玩家数据库成就数据
		//$u_achievements = decode_achievements($udata);
		
		//然后按成就设定数据的顺序生成一个$v_achievements并返回
		eval(import_module('achievement_base'));
		$v_achievements = array();
		foreach ($achlist as $tval){
			foreach ($tval as $key){
				//成就有定义且合法
				if (check_ach_valid($key)){
					if(isset($u_achievements[$key])) $v_achievements[$key] = $u_achievements[$key];
					elseif(!in_array($key, $achlist[20])) $v_achievements[$key] = 0;
					else $v_achievements[$key] = NULL;
				}
			}
		}
		
		return $v_achievements;
	}
	
	//返回类别为$at的全部成就窗格（只能用这个词形容了）显示数据形成的数组。排版放html里搞
	function show_achievements_single_type($aarr, $at)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$aarr) return;
		eval(import_module('achievement_base'));
		$showarr = array();
		//日常成就独有的显示顺序
		if(20 == $at) {
			$showlist = array();
			foreach($daily_type as $dtv){
				foreach  ($dtv as $dv){
					$showlist[] = $dv;
				}
			}
		}
		else $showlist = $achlist[$at];
		foreach ($showlist as $key){
			if(isset($aarr[$key])){
				$val = $aarr[$key];
				//不显示没有获得的日常成就
				$showflag=check_ach_got($key, $val);
				if($showflag) {
					//利用缓冲区挨个输出各成就窗格
					$func='\\skill'.$key.'\\show_achievement'.$key;
					ob_start();
					if(function_exists($func)) $func($val);//兼容性代码，如果存在旧式的显示函数，就按旧式函数显示
					else show_achievement_single($val, $key);
					$showarr[] = ob_get_contents();
					ob_end_clean();
				}
			}
		}
		
		while (sizeof($showarr) % $ach_show_num_per_row != 0){//不足3个的分类补位
			$showarr[] = '';
		}
		return $showarr;
	}
	
	function refresh_daily_quest(&$udata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$refdaily_flag = false;
		eval(import_module('sys','achievement_base'));
		if(($now-$udata['cd_a1']) >= $daily_intv){
			refresh_daily_quest_core($udata);
			$refdaily_flag = true;
			$udata['cd_a1']=$now;
		}
		return $refdaily_flag;
	}
	
	function refresh_daily_quest_core(&$udata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','achievement_base'));
		
		$daily_got = array();
		foreach($daily_type as $dtv){
			$i = $flag = 0;
			do{
				$dtv0 = array_randompick($dtv);
				if(check_ach_valid($dtv0)) {
					$daily_got[] = $dtv0;
					$flag = 1;
				}
				$i ++;
			} while(!$flag && $i < 10);
		}
		
		foreach ($achlist[20] as $key){
			if (in_array($key,$daily_got)){
				$udata['u_achievements'][$key]=0;
			}else{
				$udata['u_achievements'][$key]='VWXYZ';
			}
		}
		$ud_str = encode_achievements($udata['u_achievements']);
		update_udata_by_username(array('u_achievements' => $ud_str, 'cd_a1' => $now), $udata['username']);
	}
	
	//获取当前等级的成就名称
	//如果是隐藏成就会自动解码
	//$tp=1会连续输出所有完成的同编号成就名并用空格隔开
	function show_ach_title($achid, $achlv, $tp=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill'.$achid));
		$ret = '';
		if(!empty(${'ach'.$achid.'_name'})) {
			if(\skillbase\check_skill_info($achid, 'secret;')) $secret_flag = 1;
			foreach(${'ach'.$achid.'_name'} as $lv => $n){
				if(!empty($secret_flag)) $n = achievement_secret_decode($n);
				if($lv <= $achlv) {
					if($tp) $ret .= $n.' ';
					else $ret = $n;
				}
			}
		}
		$ret = str_replace('"',"'",$ret);
		if(!$ret) $ret = 'MISSING';
		return $ret;		
	}
	
	//获取成就获得方式等的悬浮提示
	function show_ach_title_2($achid, $achlv)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','achievement_base'));
		$ret1 = '';
		$allow_mode = $ach_allow_mode[0];
		if(isset($ach_allow_mode[$achid])) $allow_mode = $ach_allow_mode[$achid];
		foreach($allow_mode as $am){
			$ret1 .= $gtinfo[$am].' ';
		}
		$ret1 = str_replace('"',"'",substr($ret1,0,-1));
		$ret1 = preg_replace('/<font class=\'.+?\'>/s', '', str_replace('</font>','',$ret1));//去除颜色
		$ret1 = '只能在'.$ret1.'中完成';
		$ret2 = show_ach_title($achid, $achlv-1, 1);
		if('MISSING' == $ret2) $ret2 = '';
		else $ret2 = '<br>已完成：<span class=\'evergreen b\'>'.$ret2.'</span>';
		return $ret1.$ret2;
	}
	
	//用于其他模块继承
	function show_ach_title_3($achid, $adata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return NULL;
	}
	
	function get_daily_type($achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = NULL;
		if(check_ach_valid($achid) && \skillbase\check_skill_info($achid, 'daily')){
			$ret = (int)(constant('DAILY_TYPE'.$achid));
		}
		return $ret;
	}
	
	function ach_finalize_process(&$pa, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $data;
	}
	
	//用于生成一条成就奖励站内信
	function ach_create_prize_message($pa, $achid, $c, $getqiegao=0, $getcard=0, $getcardblink=0, $ext='', $getkarma=0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if(!$pa || !$achid) return;
		if (isset($pa['username'])) $n=$pa['username'];
		else $n=$pa['name'];
		
		eval(import_module('sys','skill'.$achid));
		$achtitle = ${'ach'.$achid.'_name'}[$c];
		$achtitle = str_replace('<br>', '', achievement_secret_decode($achtitle));
		$pt = '祝贺你在'.($room_prefix ? '房间' : '').'第'.$gamenum.'局获得了成就<span class="yellow b">'.$achtitle.'</span>！'.$ext;
		if($getqiegao || $getcard) $pt .= '查收本消息即可获取奖励。';
		if($getcard) {
			$pt .= '如果已有奖励卡片则会转化为切糕。';
			if (!empty($getcardblink) && in_array((int)$getcardblink, array(10,20))) $blink = (int)$getcardblink;
			else $blink = \cardbase\get_card_calc_blink($getcard, $pa);
		}
		
		$prizecode='';
		if($getqiegao) $prizecode .= 'getqiegao_'.$getqiegao.';';
		if($getcard) {
			$prizecode .= 'getcard_'.$getcard.';';
			if($blink) $prizecode .= 'getcardblink_'.$blink.';';
		}
		if($getkarma) $prizecode .= 'getkarma_'.$getkarma.';';
		include_once './include/messages.func.php';
		message_create(
			$n,
			'成就奖励',
			$pt,
			$prizecode
		);		
	}
	
	//成就通用结算函数，需要成就模块里至少定义$achXXX_threshold
	//$data是既有进度，新进度怎么判定请继承ach_finalize_process()自定义
	function ach_finalize(&$pa, &$ud, $data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!$data)					
			$x=get_achievement_default_var($achid);						
		else $x=$data;	
		$achid = (int)$achid;
		
		eval(import_module('sys', 'skill'.$achid));
		
		$ox=$x;
		$x=ach_finalize_process($pa, $x, $achid);
		if($x == $ox) return $x;//任何成就只要没变化就不继续判定
		//if(empty(${'ach'.$achid.'_threshold'})) return;//没有定义阈值则直接返回
		$threshold = ${'ach'.$achid.'_threshold'};
		
		$qiegao_flag = $card_flag = 0;
		if(!empty(${'ach'.$achid.'_qiegao_prize'})){
			$qiegao_flag = 1;
			$qiegao_prize = ${'ach'.$achid.'_qiegao_prize'};
		}
		//writeover('a.txt',var_export($qiegao_prize,1)."\r\n",'ab+');
		if(!empty(${'ach'.$achid.'_card_prize'})){
			$card_flag = 1;
			$card_prize = ${'ach'.$achid.'_card_prize'};
			if(!empty(${'ach'.$achid.'_card_prize_blink'})) $card_prize_blink = ${'ach'.$achid.'_card_prize_blink'};
		}
		$qiegao_up = 0;
		foreach($threshold as $tk => $tv){
			if(!empty($tv) && ach_finalize_check_progress($pa, $tv, $x, $achid)){
				if(ach_finalize_check_progress($pa, $tv, $ox, $achid)) continue; 
				else{
					$getqiegao=$getcard=0;
					if($qiegao_flag && !empty($qiegao_prize[$tk])) {
						//$qiegao_up += $qiegao_prize[$tk];
						$getqiegao = $qiegao_prize[$tk];
					}
					$getcardblink = 0;
					if($card_flag && !empty($card_prize[$tk])) {
						$getcard = $card_prize[$tk];
						if(is_array($getcard)) {
							$cardlist_got = \cardbase\get_cardlist_energy_from_udata($ud)[0];
							$getcard = array_diff($getcard, $cardlist_got);//优先获得没有拿到过的卡
							if(empty($getcard)) $getcard = $card_prize[$tk];//如果这个卡集全部获得了，那么随机一个
							$getcard = array_randompick($getcard);
						}
						//\cardbase\get_card($card_got,$pa);
						if(isset($card_prize_blink) && isset($card_prize_blink[$tk])) $getcardblink = $card_prize_blink[$tk];
					}
					ach_create_prize_message($pa, $achid, $tk, $getqiegao, $getcard, $getcardblink);
				}
			}
		}
		//if(!empty($qiegao_up)) \cardbase\get_qiegao($qiegao_up, $pa);

		return $x;
	}
	
	//判定数据与阈值的关系，默认是大于等于阈值算达成
	function ach_finalize_check_progress(&$pa, $t, $data, $achid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $data >= $t;
	}
	
	//成就默认值
	function get_achievement_default_var($achid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0;
	}
	
	//成就进度值处理
	function parse_achievement_progress_var($achid, $x){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $x;
	}
	
	//隐藏成就文本解密函数，很粗糙，就是单纯检测前缀然后gdecode
	function achievement_secret_decode($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(substr($str,0,10)!='<:secret:>') return $str;
		return gdecode(substr($str,10),1);
	}
	
	//成就通用显示函数，需要成就模块里至少定义$achXXX_threshold
	function show_achievement_single($data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill'.$achid));
		$unit = ${'ach'.$achid.'_unit'};
		$proc_words = ${'ach'.$achid.'_proc_words'};
		$proc_words2 = !empty(${'ach'.$achid.'_proc_words2'}) ? ${'ach'.$achid.'_proc_words2'} : '';
		$c = 0; $top_flag = 0;
		$p = get_achievement_default_var($achid);
		if ($data) $p=$data;
		$ach_threshold = ${'ach'.$achid.'_threshold'};
		if(!\skillbase\check_skill_info($achid, 'global')){//非全局成就
			foreach($ach_threshold as $tk => $tv){
				if(!empty($tv) && ach_finalize_check_progress($pa, $tv, $p, $achid)) {
					$c = $tk;
				}elseif(empty($tv)){
					$top_flag = 1;
					break;
				}else break;
			}
			$cu = $c;
			//这个部分顶级和非顶级之间的关系做得有点烂……没办法，历史原因顶级变成了999，只能舍近求远
			if(!empty($ach_threshold[$c+1])) $cu = $c + 1;//用于显示下一级名称、阈值和奖励的，0级是1，1级是2，顶级维持顶级
			$p = parse_achievement_progress_var($achid, $p);
		}else{//全局成就
			$c = $top_flag = 0;$cu = 1;$p = '';
			eval(import_module('sys'));
			list($lversion, $lnum) = ach_global_ach_last_acomplish($data, $achid);
			if($lversion && (-1 != gversion_compare($lversion, $gameversion) || $lnum == ach_global_ach_finalize_save_getnum($data, $achid))) {
				$c = $cu = 1;
				$top_flag = 1;
			}elseif($lversion){
				$c = $cu = 1;
			}
			if($lversion) {
				$acmp_list = implode('<br>',array_keys($data));
				$p = '<span title="已完成版本：<br>'.$acmp_list.'">'.$lversion.'</span>';
			}
		}		
		
		$stitle = \achievement_base\show_ach_title($achid, $cu);//成就名
		$atitle = \achievement_base\show_ach_title_2($achid, $c+1);//成就悬浮提示
		$ptitle = \achievement_base\show_ach_title_3($achid, $data);//其他一些需要提示的东西
		$dailytype = \skillbase\check_skill_info($achid, 'daily') ? \achievement_base\get_daily_type($achid) : 0;
		$prize_desc = show_prize_single($cu, $achid);
		$ach_desc = show_achievement_single_desc($cu, $achid, $ach_threshold[$cu]);
		
		if(!$c) {
			$ach_state_desc = '<span class="red b">[未完成]</span>';
		}elseif(!$top_flag) {
			$ach_state_desc = '<span class="cyan b">[进行中]</span>';
		}else {
			$ach_state_desc = '<span class="lime b">[完成]</span>';
		}
		$ach_icon = show_achievement_icon($achid, $c, $top_flag);
		
		if(!file_exists(GAME_ROOT."/img/ach/{$ach_icon}")) {
			if(!$c) $ach_icon = 'N.gif';
			elseif($top_flag) $ach_icon = 'DA.gif';
			else $ach_icon = 'D.gif';
		}
		include template('MOD_ACHIEVEMENT_BASE_COMMON_DESC');
	}
	
	function show_achievement_icon($achid, $c, $top_flag)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$c) {
			$ach_iconid = 'n';
		}elseif(!$top_flag) {
			$ach_iconid = $c;
		}else {
			$ach_iconid = $c;
		}
		$ach_icon = get_daily_type($achid) ? 'daily'.get_daily_type($achid).'_'.$ach_iconid : 'a'.$achid.'_'.$ach_iconid;
		$ach_icon .= '.png';
		return $ach_icon;
	}
	
	//获得当前级别的成就描述。如果是隐藏成就会自动解码
	function show_achievement_single_desc($data, $achid, $tval)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill'.$achid));
		if(empty(${'ach'.$achid.'_desc'})) return '';
		else $ach_desc = ${'ach'.$achid.'_desc'};
		if(\skillbase\check_skill_info($achid, 'secret;')) $secret_flag = 1;
		foreach($ach_desc as $dk => $dv){
			if($data >= $dk) {
				if(!empty($secret_flag)) $ret = achievement_secret_decode($dv);
				else $ret = $dv;
			}
		}
		$ret = str_replace('<:threshold:>', $tval, $ret);
		return $ret;
	}
	
	function show_prize_single($cn, $achid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill'.$achid));
		$threshold = ${'ach'.$achid.'_threshold'};
		$qiegao_prize = !empty(${'ach'.$achid.'_qiegao_prize'}) ? ${'ach'.$achid.'_qiegao_prize'} : NULL;
		$card_prize = !empty(${'ach'.$achid.'_card_prize'}) ? ${'ach'.$achid.'_card_prize'} : NULL;
		$card_prize_desc = !empty(${'ach'.$achid.'_card_prize_desc'}) ? ${'ach'.$achid.'_card_prize_desc'} : NULL;
		$card_prize_blink = !empty(${'ach'.$achid.'_card_prize_blink'}) ? ${'ach'.$achid.'_card_prize_blink'} : NULL;
		$ret = '';
		//切糕显示
		if(!empty($qiegao_prize)) {
			foreach($qiegao_prize as $lv => $n){
				if($lv <= $cn) {
					$qp = $n;
				}
			}
			$ret .= '<font color="olive">切糕'.$qp.'</font> ';
		}
		//自定义显示
		if(!empty(${'ach'.$achid.'_unique_prize_desc'})) {
			$ret .= ${'ach'.$achid.'_unique_prize_desc'};
		}else{
			//卡片显示
			if(!empty($card_prize)) {
				if(!empty($card_prize_desc)) {
					$ret .= $card_prize_desc;
				}else{
					$cp = 0;
					foreach($card_prize as $lv => $n){
						if($lv == $cn) {
							$cp = $n;
						}
					}
					if(is_array($cp)) {
						$ret1 = '';
						foreach($cp as $card) {
							$ret1 .= show_prize_single_card($card);
							if(isset($card_prize_blink) && isset($card_prize_blink[$cn]))
							{
								$blink = (int)$card_prize_blink[$cn];
								if ($blink == 10) $ret1 .= '<span class="L5">（闪烁）</span>';
								if ($blink == 20) $ret1 .= '<span class="L5">（镜碎）</span>';
							}
							if(count($cp) > 1) $ret1 .= '|';
						}
						if(substr($ret1,strlen($ret1)-1) === '|') $ret1 = substr($ret1,0,-1);
						$ret1 = str_replace('|','、',$ret1);
						$ret .= '<span class="evergreen b" title="'.str_replace('"',"'",$ret1).'">卡集(悬浮查看)中随机卡片1张</span>';
					}elseif($cp){
						$card = (int)$cp;
						$ret .= '&nbsp;'.show_prize_single_card($card);
						if(isset($card_prize_blink) && isset($card_prize_blink[$cn]))
						{
							$blink = (int)$card_prize_blink[$cn];
							if ($blink == 10) $ret .= '<span class="L5">（闪烁）</span>';
							if ($blink == 20) $ret .= '<span class="L5">（镜碎）</span>';
						}
					}
				}
			}
		}
		if(empty($ret)) $ret = '无奖励';
		return $ret;
	}
	
	function show_prize_single_card($card)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase'));
		return '<span class="'.$card_rarecolor[$cards[$card]['rare']].'">'.$cards[$card]['name'].'</span>';
	}
	
	//判定是不是活跃玩家的通用函数
	function ach_check_positive_player($pl, $pe)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		list($vapm, $aapm) = \apm\calc_apm($pe);

		if($pe['type']) return false;//排除NPC
		if($pl['ip'] == $pe['ip'] || $vapm < 10) return false;//排除双方IP相同，或者对方VAPM<10
//		$skill1003_got = \skillbase\skill_getvalue(1003,'money_got', $pe);	
//		if($skill1003_got < 1000) return false;
		return true;
		//return !$pl['type'] && $pl['lvl'] >= 7 && $pl['money'] >= 1000;
	}
	
	//全局成就的判定，依据是版本号
	//如果版本号跟当前版本一致，则直接跳过判定（已完成）
	//也就是说并不储存“上一次完成”的信息，需要注意和一般成就的不同
	function ach_global_ach_check(&$ud)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','achievement_base'));
		$flag = 0;
		foreach($achlist[5] as $ai){
			if (\skillbase\check_skill_info($ai, 'global')){
				if(empty($ud['u_achievements'][$ai])) $ud['u_achievements'][$ai] = array();
				list($lversion, $lnum) = ach_global_ach_last_acomplish($ud['u_achievements'][$ai], $ai);
				//重新判定条件：完成版本号、完成时数目与当前都不同
				if(!$lversion || (-1 == gversion_compare($lversion, $gameversion) && $lnum != ach_global_ach_finalize_save_getnum($ud['u_achievements'][$ai], $ai))) {
					$flag = $flag || ach_global_ach_check_single($ud, $ai);
				}
			}
		}
		
		//如果成就有修改则写一次
		if($flag) {
			$ud_str = encode_achievements($ud['u_achievements']);
			update_udata_by_username(array('u_achievements' => $ud_str), $ud['username']);
		}
	}
	
	function ach_global_ach_check_single(&$ud, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (ach_global_ach_check_progress($ud, $achid)) {
			ach_global_ach_finalize($ud, $achid);
			return 1;
		}
		return 0;
	}
	
	//全局成就是否满足条件
	//而是新完成还是已完成，是在ach_global_ach_check()根据版本号判定的
	function ach_global_ach_check_progress(&$ud, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return false;
	}
	
	//全局成就结算
	function ach_global_ach_finalize(&$ud, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys', 'skill'.$achid));
		list($lversion,$lnum) = ach_global_ach_last_acomplish($ud['u_achievements'][$achid], $achid);
		ach_global_ach_finalize_save($ud, $achid);
		$getqiegao = $getcard = $getkarma = 0;
		if(!$lversion){//第一次完成
			if(!empty(${'ach'.$achid.'_qiegao_prize'})) $getqiegao = ${'ach'.$achid.'_qiegao_prize'}[1];
			if(!empty(${'ach'.$achid.'_card_prize'})) $getcard = ${'ach'.$achid.'_card_prize'}[1];
			if(!empty(${'ach'.$achid.'_karma_prize'})) $getkarma = ${'ach'.$achid.'_karma_prize'}[1];
		}else{//非第一次完成
			if(!empty(${'ach'.$achid.'_qiegao_prize'})) $getqiegao = ${'ach'.$achid.'_qiegao_prize'}[2];
			if(!empty(${'ach'.$achid.'_card_prize'})) $getcard = ${'ach'.$achid.'_card_prize'}[2];
			if(!empty(${'ach'.$achid.'_karma_prize'})) $getkarma = ${'ach'.$achid.'_karma_prize'}[2];
		}
		
		$achtitle = \achievement_base\show_ach_title($achid, 1);
		$pt = '祝贺你'.($lversion ? '再次' : '').'获得了成就<span class="yellow b">'.$achtitle.'</span>！';
		if($getqiegao || $getcard || $getkarma) $pt .= '查收本消息即可获取奖励。';
		if($getcard) $pt .= '如果已有奖励卡片则会转化为切糕。';
		include_once './include/messages.func.php';
		message_create(
			$ud['username'],
			'全局成就奖励',
			$pt,
			($getqiegao ? 'getqiegao_'.$getqiegao : '').';'.($getcard ? 'getcard_'.$getcard : '').';'.($getkarma ? 'getkarma_'.$getkarma : '')
		);
	}
	
	//全局成就储存，同时储存版本号和完成时的数目
	function ach_global_ach_finalize_save(&$ud, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\check_skill_info($achid, 'global')) return;
		eval(import_module('sys'));
		$num = ach_global_ach_finalize_save_getnum($ud['u_achievements'][$achid], $achid);
		if(!isset($ud['u_achievements'][$achid]) || !is_array($ud['u_achievements'][$achid])) {
			$ud['u_achievements'][$achid] = array($gameversion => $num);
		}else {
			$ud['u_achievements'][$achid][$gameversion] = $num;
		}
	}
	
	function ach_global_ach_finalize_save_getnum($data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 0;
	}
	
	//全局成就读取最新获得的版本号和数目
	function ach_global_ach_last_acomplish($data, $achid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!is_array($data)) return '';
		$tmp_version = ''; $tmp_num = 0;
		foreach ($data as $ak => $av){
			if(!$tmp_version || 1 == gversion_compare($ak, $tmp_version)) {
				$tmp_version = $ak;
				$tmp_num = $av;
			}
		}
		return array($tmp_version, $tmp_num);
	}
}

?>