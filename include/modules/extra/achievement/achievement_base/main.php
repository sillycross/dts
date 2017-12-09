<?php

namespace achievement_base
{
	
	function init() {
	}
	
	function ach_init(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('achievement_base'));
		$n_achtype = $bk_list = array();
		foreach($achtype as $ak => $av){
			//未开始直接不显示
			if(1 == check_achtype_available($ak)){
				$n_achtype[$ak] = $av;
			}elseif(2 == check_achtype_available($ak)){//过期的放后面
				$bk_list[$ak] = $av;
			}
		}
		foreach($bk_list as $bk => $bv){
			$n_achtype[$bk] = $bv;
		}
		$achtype = $n_achtype;
	}
	
	function skill_onload_event(&$pa)//技能模块载入时直接加载所有成就
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','achievement_base'));
		$alist = array();
		foreach($achlist as $atk => $atv){
			if( 1 == check_achtype_available($atk))
				$alist = array_merge($alist, $atv);
		}
		foreach($alist as $av){
			//只有玩家可以获得成就技能
			if (!$pa['type']
			//确认允许完成成就的模式，未定义则用0键（只有正常游戏可以完成）
				&& ( ( !isset($ach_allow_mode[$av]) && in_array($gametype, $ach_allow_mode[0]) ) || ( isset($ach_allow_mode[$av]) && in_array($gametype,$ach_allow_mode[$av]) ) )
				&& !\skillbase\skill_query($av,$pa))
			\skillbase\skill_acquire($av,$pa);
		}
		$chprocess($pa);
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
						if ($s!=''&&$s!='VWXYZ') $f=true;
						if ($f){
							if($key==326) $v=\skill326\cardlist_decode326($s);
							else $v=base64_decode_number($s);	
							$ret[$key] = $v;
						}else{
							$ret[$key] = $s;
						}
					}
				}
			}
		}
		return $ret;
	}
	
	//更新单个玩家的成就记录
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
			if (defined('MOD_SKILL'.$key.'_INFO') && defined('MOD_SKILL'.$key.'_ACHIEVEMENT_ID')
			&& \skillbase\check_skill_info($key, 'achievement') && 1 == check_achtype_available($key))//技能存在而且有效
			{
				$val = 0;
				//无视没有获得的日常成就
				if (isset($udata['u_achievements'][$key])) $val = $udata['u_achievements'][$key];
				$vflag=false;
				if (!\skillbase\check_skill_info($key, 'daily')) $vflag=true;
				if ( $val!=='VWXYZ' ) $vflag=true;
				if ($vflag){
					//临时措施
					if($key!=326){
						$val=min((int)$val,(1<<30)-1);
						$val=base64_encode_number($val,5);
					}
					//上面这个措施需要回头弄掉，太蠢
					
					$func='\\skill'.$key.'\\finalize'.$key;
					$ret=$func($pdata,$val);
					//临时措施
					if($key!=326){
						$ret=base64_decode_number($ret);
					}
					//上面这个措施需要回头弄掉，太蠢
					
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
		$namelist = array();
		$result = $db->query("SELECT name FROM {$tablepre}players WHERE type=0");
		while ($pd=$db->fetch_array($result))
		{
			$namelist[] = $pd['name'];
		}
		$updatelist = array();
		//然后一次性读用户记录，尽量减少在循环里读写数据库
		if(!empty($namelist)){
			$wherecause = "('".implode("','",$namelist)."')";
			$result = $db->query("SELECT * FROM {$tablepre}users WHERE username IN $wherecause");
			while ($udata=$db->fetch_array($result))
			{
				$pdata = \player\fetch_playerdata($udata['username']);//这句理论上可以被玩家池加速
				update_achievements_by_udata($udata, $pdata);
				$updatelist[] = Array(
					'username' => $udata['username'],
					'u_achievements' => encode_achievements($udata['u_achievements'])
				);
			}
		}
		//一次性更新
		$db->multi_update("{$gtablepre}users", $updatelist, 'username');
	}
	
	function post_gameover_events()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		update_achievements();
		$chprocess();
	}
	
//	function post_gameover_events()
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		
//		eval(import_module('sys'));
//		$result = $db->query("SELECT name,pid FROM {$tablepre}players WHERE type=0");
//		while ($udata=$db->fetch_array($result))
//		{
//			$edata=\player\fetch_playerdata_by_pid($udata['pid']);
//			if ($edata===NULL) continue;
//			$res = $db->query("SELECT n_achievements FROM {$gtablepre}users WHERE username='{$udata['name']}'");
//			if (!$db->num_rows($res)) continue;
//			$zz=$db->fetch_array($res); $ach=$zz['n_achievements'];
//			$achdata=explode(';',$ach);
//			$maxid=count($achdata)-2;
//			foreach (\skillbase\get_acquired_skill_array($edata) as $key) //也就是说，允许先进游戏后换每日任务，甚至可以先清场，结束前换每日任务
//				if (defined('MOD_SKILL'.$key.'_INFO') && defined('MOD_SKILL'.$key.'_ACHIEVEMENT_ID') && 1 == check_achtype_available($key))
//					if (\skillbase\check_skill_info($key, 'achievement'))
//					{
//						$id=((int)(constant('MOD_SKILL'.$key.'_ACHIEVEMENT_ID')));
//						if ($id>$maxid) $maxid=$id;
//						if (isset($achdata[$id])) $s=((string)$achdata[$id]); else $s='';
//						$f=false;
//						if (!\skillbase\check_skill_info($key, 'daily')) $f=true;
//						if (($s!='')&&($s!='VWXYZ')) $f=true;
//						if ($f){
//							$func='\\skill'.$key.'\\finalize'.$key;
//							$achdata[$id]=$func($edata,$s);
//						}
//					}
//			
//			$nachdata='';
//			for ($i=0; $i<=$maxid; $i++)
//				$nachdata.=$achdata[$i].';';
//			
//			$db->query("UPDATE {$gtablepre}users SET n_achievements = '$nachdata' WHERE username='{$udata['name']}'");	
//		}
//		
//		$chprocess();
//	}
	
	//返回合法的成就数组
	function get_valid_achievements($udata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//先载入玩家数据库成就数据
		$u_achievements = decode_achievements($udata);
		//然后按成就设定数据的顺序生成一个$v_achievements并返回
		eval(import_module('achievement_base'));
		$v_achievements = array();
		foreach ($achlist as $tval){
			foreach ($tval as $key){
				//成就有定义且合法
				if (defined('MOD_SKILL'.$key.'_INFO') && defined('MOD_SKILL'.$key.'_ACHIEVEMENT_ID') 
				&& \skillbase\check_skill_info($key, 'achievement') && !\skillbase\check_skill_info($key, 'hidden')){
					if(isset($u_achievements[$key]))
						$v_achievements[] = $u_achievements;
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
		foreach ($achlist[$at] as $key){
			if(isset($aarr[$key])){
				$val = $aarr[$key];
				//不显示没有获得的日常成就
				$showflag=false;
				if (!\skillbase\check_skill_info($key, 'daily')) $showflag=true;
				elseif ( $val!=='VWXYZ' ) $showflag=true;
				if($showflag) {
					//临时措施
					if($key!=326){
						$val=min((int)$val,(1<<30)-1);
						$val=base64_encode_number($val,5);
					}
					//上面这个措施需要回头弄掉，太蠢
					
					//利用缓冲区挨个输出各成就窗格
					$func='\\skill'.$key.'\\show_achievement'.$key;
					ob_start();
					$func($val);
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
	
	function show_achievements($un,$at)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','achievement_base'));
		$res = $db->query("SELECT n_achievements FROM {$gtablepre}users WHERE username='$un'");
		if (!$db->num_rows($res)) return;
		$zz=$db->fetch_array($res); $ach=$zz['n_achievements']; 
		$achdata=explode(';',$ach); 
		$c=0;
		foreach ($achlist[$at] as $key)
			if (defined('MOD_SKILL'.$key.'_INFO') && defined('MOD_SKILL'.$key.'_ACHIEVEMENT_ID'))
				if ((\skillbase\check_skill_info($key, 'achievement'))&&(!\skillbase\check_skill_info($key, 'hidden')))
				{
					$id=((int)(constant('MOD_SKILL'.$key.'_ACHIEVEMENT_ID')));
					if (isset($achdata[$id])) $s=((string)$achdata[$id]); else $s='';
					$f=false;
					if (!\skillbase\check_skill_info($key, 'daily')) $f=true;
					if (($s!='')&&($s!='VWXYZ')) $f=true;
					if ($f){
						$func='\\skill'.$key.'\\show_achievement'.$key;
						$c++;
						if ($c%3==1) echo "<tr>";
						echo '<td width="300" align="left" valign="top">';
						$func($s);
						echo "</td>";
						if ($c%3==0) echo "</tr>";
					}
				}
		while ($c<3){//不足3个的分类补位
			$c++;
			echo '<td width="300" align="left" valign="top" style="border-style:none">';
			echo "</td>";
			if ($c%3==0) echo "</tr>";
		}
		if ($c%3!=0) echo "</tr>";
	}
	
	function refresh_daily_quest(&$udata){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$refdaily_flag = false;
		eval(import_module('sys','achievement_base'));
		if(($now-$udata['cd_a1']) >= $daily_intv){
			\achievement_base\get_daily_quest($udata['username']);
			$refdaily_flag = true;
			$udata['cd_a1']=$now;
		}
		return $refdaily_flag;
	}

	function get_daily_quest($un){
	
		if (eval(__MAGIC__)) return $___RET_VALUE;
	
		eval(import_module('sys','achievement_base'));
		$res = $db->query("SELECT n_achievements FROM {$gtablepre}users WHERE username='$un'");
		if (!$db->num_rows($res)) return;
		$zz=$db->fetch_array($res); $ach=$zz['n_achievements']; 
		$achdata=explode(';',$ach); 
		$maxid=count($achdata)-2;
		$ta=$achlist[20];
		shuffle($ta);
		$ta=array_slice($ta,0,3);
		foreach ($achlist[20] as $key){
			$id=((int)(constant('MOD_SKILL'.$key.'_ACHIEVEMENT_ID')));
			if (isset($achdata[$id])) $s=((string)$achdata[$id]); else $s='';
			if ($id>$maxid) $maxid=$id;
			if (in_array($key,$ta)){
				$achdata[$id]='aaaaa';
			}else{
				$achdata[$id]='VWXYZ';
			}
		}
		$nachdata='';
		for ($i=0; $i<=$maxid; $i++)
			$nachdata.=$achdata[$i].';';
		$db->query("UPDATE {$gtablepre}users SET n_achievements = '$nachdata',cd_a1 = '$now' WHERE username='$un'");
	}
	
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
}

?>
