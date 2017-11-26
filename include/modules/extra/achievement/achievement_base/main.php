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
	
	//成就编码
	function encode_achievements_o($aarr){
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
	
	//成就解码
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
	
	function post_gameover_events()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys'));
		$result = $db->query("SELECT name,pid FROM {$tablepre}players WHERE type=0");
		while ($udata=$db->fetch_array($result))
		{
			$edata=\player\fetch_playerdata_by_pid($udata['pid']);
			if ($edata===NULL) continue;
			$res = $db->query("SELECT n_achievements FROM {$gtablepre}users WHERE username='{$udata['name']}'");
			if (!$db->num_rows($res)) continue;
			$zz=$db->fetch_array($res); $ach=$zz['n_achievements'];
			$achdata=explode(';',$ach);
			$maxid=count($achdata)-2;
			foreach (\skillbase\get_acquired_skill_array($edata) as $key) //也就是说，允许先进游戏后换每日任务，甚至可以先清场，结束前换每日任务
				if (defined('MOD_SKILL'.$key.'_INFO') && defined('MOD_SKILL'.$key.'_ACHIEVEMENT_ID') && 1 == check_achtype_available($key))
					if (\skillbase\check_skill_info($key, 'achievement'))
					{
						$id=((int)(constant('MOD_SKILL'.$key.'_ACHIEVEMENT_ID')));
						if ($id>$maxid) $maxid=$id;
						if (isset($achdata[$id])) $s=((string)$achdata[$id]); else $s='';
						$f=false;
						if (!\skillbase\check_skill_info($key, 'daily')) $f=true;
						if (($s!='')&&($s!='VWXYZ')) $f=true;
						if ($f){
							$func='\\skill'.$key.'\\finalize'.$key;
							$achdata[$id]=$func($edata,$s);
						}
					}
			
			$nachdata='';
			for ($i=0; $i<=$maxid; $i++)
				$nachdata.=$achdata[$i].';';
			
			$db->query("UPDATE {$gtablepre}users SET n_achievements = '$nachdata' WHERE username='{$udata['name']}'");	
		}
		
		$chprocess();
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
