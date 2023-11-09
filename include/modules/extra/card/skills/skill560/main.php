<?php

namespace skill560
{
	$ragecost = 50;
	//$skill560_gain = 200;
	$skill560_size = 5;
	
	function init() 
	{
		define('MOD_SKILL560_INFO','card;battle;');
		eval(import_module('clubbase'));
		$clubskillname[560] = '鬼叫';
	}
	
	function acquire560(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(560,'affected_list','',$pa);
	}
	
	function lost560(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(560,'affected_list',$pa);
	}
	
	function check_unlocked560(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_rage_cost560(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill560'));
		return $ragecost;
	}

	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['bskill'] != 560) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(560,$pa) || !check_unlocked560($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill']=0;
		}
		else
		{
			$rcost = get_rage_cost560($pa);
			if ( !\clubbase\check_battle_skill_unactivatable($pa,$pd,560) )
			{
				eval(import_module('logger'));
				$log .= \battle\battlelog_parser($pa, $pd, $active, "<span class=\"lime b\"><:pa_name:>对<:pd_name:>发动了技能「鬼叫」！</span><br>");
				$pa['rage'] -= $rcost;
				addlist560($pd['pid'], $pa);
				addnews ( 0, 'bskill560', $pa['name'], $pd['name'] );
				$temp_log = $log;
			}
			else
			{
				if ($active)
				{
					eval(import_module('logger'));
					$log.='怒气不足或其他原因不能发动。<br>';
				}
				$pa['bskill'] = 0;
			}
		}
		$chprocess($pa, $pd, $active);
		if (isset($temp_log)) $log = $temp_log;
	}
	
	//把id加入列表
	function addlist560($id, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill560'));	
		$affected_list = getlist560($pa);
		if(!in_array($id, $affected_list)) $affected_list[] = $id;
		//如果超过容量，去掉最老的一个记录
		if(sizeof($affected_list) > $skill560_size) array_shift($affected_list);
		\skillbase\skill_setvalue(560,'affected_list',encode560($affected_list),$pa);
	}
	
	//判定id是否在列表中
	function inlist560($id, &$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$affected_list = getlist560($pa);
		if(in_array($id, $affected_list)) return true;
		return false;
	}
	
	//获得记录的列表（数组）
	function getlist560(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return decode560(\skillbase\skill_getvalue(560,'affected_list',$pa));
	}
	
	//把列表中的id转化为玩家名用于显示，用空格隔开。在这里会自动去掉已经死掉的角色。
	function showlist560(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$affected_list = getlist560($pa);
		if(empty($affected_list)) return '';
		$wherelist = '('.implode(',', $affected_list).')';
		eval(import_module('sys'));
		$result = $db->query("SELECT name, hp, pid FROM {$tablepre}players WHERE pid IN $wherelist");
		$checked_affected_list = $showlist = array();
		while($v = $db->fetch_array($result)){
			if($v['hp'] > 0) {
				$checked_affected_list[] = $v['pid'];
				$showlist[] = $v['name'];
			}
		}
		if(sizeof($checked_affected_list) != sizeof($affected_list)) 
			\skillbase\skill_setvalue(560,'affected_list',encode560($checked_affected_list),$pa);
		return implode(' ', $showlist);
	}
	
	//id数组转字符串记录
	function encode560($arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return implode('_', $arr);
	}
	
	//id字符串记录转数组
	function decode560($str)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($str)) return Array();
		return explode('_', $str);
	}

	function strike(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if ($pa['bskill'] == 560) {
			eval(import_module('logger','player'));
			$log .= "<span class=\"yellow b\">你放弃了攻击，但你喊出的凄婉的鬼叫声已经深深地铭刻进了对方的心灵！</span><br>";
			$pd['skill560_flag']=1;
			$pa['is_hit']=0;
		}
		else $chprocess($pa, $pd, $active);
	}

	function check_can_counter(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!empty($pa['skill560_flag'])) return 1;
		return $chprocess($pa, $pd, $active);
	}

	function player_attack_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if (!($pa['is_counter'] && \skillbase\skill_query(560,$pd) && !empty($pa['skill560_flag']))) $chprocess($pa, $pd, $active);
	}
	
	//对记录过的角色的先攻率+15%
	function calculate_active_obbs(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess($ldata,$edata);
		eval(import_module('logger'));
		if (\skillbase\skill_query(560,$ldata) && check_unlocked560($ldata) && inlist560($edata['pid'], $ldata)) {
			$r += 15;
		}
		if (\skillbase\skill_query(560,$edata) && check_unlocked560($edata) && inlist560($ldata['pid'], $edata)) {
			$r -= 15;
		}
		return $r;
	}
	
	function check_battle_skill_unactivatable(&$ldata,&$edata,$skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata,$edata,$skillno);
		if(560 == $skillno && 0 == $ret){
			if(inlist560($edata['pid'], $ldata)) $ret = 9;//如果已经标记了对方，不能发动这个战斗技
		}
		return $ret;
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if($news == 'bskill560')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「鬼叫」</span></span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
//	function strike_finish(&$pa, &$pd, $active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;		
//		eval(import_module('player'));
//		if (\skillbase\skill_query(560,$pd) && !empty($pa['skill560_flag'])) {
//			if (isset($pd['inf'])) {
//				eval(import_module('logger'));
//				if (strlen($pd['inf']) > 0) {
//					eval(import_module('skill560'));
//					$gain = min(strlen($pd['inf']) * $skill560_gain, $pa['money']);
//					if (1-$active) {
//						if ($gain > 0) $log.="<span class=\"yellow b\">你靠着出色的演技，向对方索取了{$gain}元！</span><br>";
//						else $log.="<span class=\"yellow b\">你的演技相当不错，但对方穷得连一个子儿也掏不出来了！</span><br>";
//					}
//					else {
//						if ($gain > 0) $log.="<span class=\"yellow b\">{$pd['name']}靠着出色的演技，向你索取了{$gain}元！</span><br>";
//						else $log.="<span class=\"yellow b\">对方的演技相当不错，但你穷得连一个子儿也掏不出来了！</span><br>";
//					}
//					$pd['money'] += $gain;
//					$pa['money'] -= $gain;
//				}
//				else {
//					if (1-$active) $log.="<span class=\"yellow b\">你没事人一样的演技没能得到对方的认可！</span><br>";
//					else $log.="<span class=\"yellow b\">对方没事人一样的演技没能得到你的认可！</span><br>";
//				}
//			}
//		}
//		$chprocess($pa, $pd, $active);
//	}
//
//	function player_kill_enemy(&$pa,&$pd,$active)
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('player'));		
//		if (\skillbase\skill_query(560,$pd) && !empty($pa['skill560_flag'])) {
//			if ($pd['hp'] <= 0) {
//				eval(import_module('logger'));
//				if (1-$active) $log .= "<span class=\"red b\">哎呀……看来你今天撞了大运！</span><br>";
//				else $log .= "<span class=\"red b\">哎呀……看来对方今天撞了大运！</span><br>";
//			}
//		}
//		$chprocess($pa, $pd, $active);
//	}

}

?>