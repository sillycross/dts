<?php

namespace skill575
{
	$ragecost = 50;
	
	//对应recipe的id为键值+100
	$skill575_recipe_list = array
	(
		0 => '新难题「英灵殿的一块天花板」',
		1 => '新难题「常温超导材料」',
		2 => '新难题「变色月壤」',
		3 => '新难题「基岩」',
		4 => '新难题「黄鸡的皮衣」',
		5 => '新难题「混沌法球」',
		6 => '新难题「宏电子」',
		7 => '新难题「星见炮」',
		8 => '新难题「AC大逃杀振兴计划」',
	);
	
	function init() 
	{
		define('MOD_SKILL575_INFO','card;battle;');
		eval(import_module('clubbase'));
		$clubskillname[575] = '难题';
	}
	
	function acquire575(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(575, 'tpidlist', '', $pa);
	}
	
	function lost575(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(575, 'tpidlist', $pa);
	}
	
	function check_unlocked575(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl'] >= 15;
	}
	
	function get_rage_cost575(&$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill575'));
		return $ragecost;
	}
	
	function sk575_add_tpid(&$pa, $tpid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$tpidlist = \skillbase\skill_getvalue(575,'tpidlist',$pa);
		if ('' === $tpidlist) $ls = array();
		else $ls = explode('_',$tpidlist);
		if (!in_array($tpid, $ls))
		{
			$ls[] = $tpid;
			$tpidlist = implode('_',$ls);
			\skillbase\skill_setvalue(575,'tpidlist',$tpidlist,$pa);
		}
	}
	
	function sk575_check_tpid(&$pa, $tpid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$tpidlist = \skillbase\skill_getvalue(575,'tpidlist',$pa);
		if ('' === $tpidlist) $ls = array();
		else $ls = explode('_',$tpidlist);
		if (in_array($tpid, $ls)) return true;
		return false;
	}
	
	function check_battle_skill_unactivatable(&$ldata, &$edata, $skillno)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($ldata, $edata, $skillno);
		if (575 == $skillno && 0 == $ret)
		{
			if ($edata['type'] > 0) $ret = 8;
			if (sk575_check_tpid($pa, $edata['pid'])) $ret = 6;
		}
		return $ret;
	}

	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//如果攻击者有难题技能，并且受攻击者有寻宝技能
		if (\skillbase\skill_query(575,$pa) && check_unlocked575($pa) && \skillbase\skill_query(576,$pd)) $pd['skill575_flag'] = 1;

		if ($pa['bskill'] != 575) return $chprocess($pa, $pd, $active);
		if (!\skillbase\skill_query(575,$pa) || !check_unlocked575($pa))
		{
			eval(import_module('logger'));
			$log .= '你尚未解锁这个技能！';
			$pa['bskill'] = 0;
		}
		else if (0 == $pd['type'] && !sk575_check_tpid($pa, $pd['pid']))
		{
			$rcost = get_rage_cost575($pa);
			if ($pa['rage'] >= $rcost)
			{
				eval(import_module('skill575','logger'));
				if ($active)
					$log .= "<span class=\"lime b\">你给{$pd['name']}留下了一道「难题」，名为</span>";
				else  $log .= "<span class=\"lime b\">{$pa['name']}给你留下了一道「难题」，名为</span>";
				$pa['rage'] -= $rcost;
				addnews ( 0, 'bskill575', $pa['name'], $pd['name'] );
				sk575_add_tpid($pa, $pd['pid']);
				sk575_give_request($pa, $pd);
			}
		}
		else
		{
			if ($active)
			{
				eval(import_module('logger'));
				$log .= '怒气不足或其他原因不能发动。<br>';
			}
			$pa['bskill'] = 0;
		}
		$chprocess($pa, $pd, $active);
	}
	
	function sk575_give_request(&$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill575','logger'));
		//随机选择一道难题
		$recipekey = rand(0, count($skill575_recipe_list)-1);
		//配方id
		$recipeid = $recipekey + 100;
		$recipename = $skill575_recipe_list[$recipekey];
		$log .= "<span class=\"yellow b\">$recipename</span><span class=\"lime b\">！</span><br>";
		//给对手衍生技能
		\skillbase\skill_acquire(576,$pd);
		\skillbase\skill_setvalue(576,'recipeid',$recipeid,$pd);
		\skillbase\skill_setvalue(576,'mpid',$pa['pid'],$pd);
		
		$theitem = array('itm' => $recipename, 'itmk' => 'R', 'itme' => 1,'itms' => 1,'itmsk' => $recipeid);
		\searchmemory\searchmemory_senditem($theitem, $pa, $pd);
	}
	
	function searchmemory_senditem(&$theitem, &$pa, &$pd)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		$flag = 0;
		for ($i=1; $i<=6; $i++)
		{
			//如果对方背包有空位，则在空位塞道具
			if (!$pd['itms'.$i])
			{
				$pd['itm'.$i] = $itm;
				$pd['itmk'.$i] = $itmk;
				$pd['itme'.$i] = $itme;
				$pd['itms'.$i] = $itms;
				$pd['itmsk'.$i] = $itmsk;
				$flag = 1;
				break;
			}
		}
		//没有空位，则放进视野
		if (0 == $flag)
		{
			if (\searchmemory\searchmemory_available())
			{
				eval(import_module('sys','player'));
				$dropid = \itemmain\itemdrop_query($itm, $itmk, $itme, $itms, $itmsk, $pd['pls']);
				$amarr = array('iid' => $dropid, 'itm' => $itm, 'pls' => $pd['pls'], 'unseen' => 0);
				\skill1006\add_beacon($amarr, $pd);//2023.11.18改用临时视野
				\player\player_save($pd);
			}
		}
		$itm = ''; $itmk = '';
		$itme = 0; $itms = 0; $itmsk = '';
		$chprocess($theitem, $pa, $pd);
	}
	
	//不会被还在做题的玩家先手
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(575,$ldata) && check_unlocked575($ldata) && \skillbase\skill_query(576,$data))
			return 1;
		else  return $chprocess($ldata,$edata);
	}
	
	function skill_enabled_core($skillid, &$pa = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skillbase'));
		$skillid = (int)$skillid;
		if ($pa!=NULL && !empty($pa['skill575_flag']))
		{
			//所有非称号特性技能失效
			if (!\skillbase\check_skill_info($skillid,'achievement') && !\skillbase\check_skill_info($skillid,'feature') && !\skillbase\check_skill_info($skillid,'hidden'))
				return 0;
		}
		return $chprocess($skillid,$pa);
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'bskill575') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}对{$b}发动了技能<span class=\"yellow b\">「难题」</span></span></li>";
		
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}

}

?>