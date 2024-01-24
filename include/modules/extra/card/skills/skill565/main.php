<?php

namespace skill565
{
	$sk565_allowclublist = array(1,2,3,4,5,6,7,8,9,10,11,13,14,18,19,20,21,24,25,26);
	//禁了保镖、家教、学习和灵感，以及衍生技；加血攻防治疗也禁了，保险起见
	$sk565_banlist = array(10,11,12,19,20,24,31,41,42,55,56,59,72,79,98,224,231,242,106);
	//某些技能附带另外的技能，很搞
	$sk565_feature = array
	(
		//拆弹称号特性
		17 => array(19,20),
		//宝骑称号特性
		23 => array(24),
		//根性称号特性
		29 => array(31),
		//肌肉称号特性
		39 => array(79),
		//亡灵称号特性
		58 => array(24,59),
		//黑衣称号特性
		219 => array(224),
		//锡安称号特性
		230 => array(231),
		//妙手称号特性
		97 => array(98),
		//宝骑技能吸光
		272 => array(106),
	);
	
	function init() 
	{	
		define('MOD_SKILL565_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[565] = '神抽';
	}
	
	function acquire565(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//获得一个称号特性、两个战斗技、两个升级技（非称号特性）、两个非战斗技能（非称号特性）
		get_rand_skill($pa, 'feature', 1);
		get_rand_skill($pa, 'battle', 2);
		get_rand_skill($pa, 'upgrade', 2);
		get_rand_skill($pa, 'nonbattle', 2);
		\skillbase\skill_lost(565,$pa);
	}
	
	function lost565(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function get_rand_skill(&$pa, $sktype, $count)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill565'));
		$ls_skills = get_skill_list($sktype);		
		//排除已学会的技能
		$ls_skills = array_diff($ls_skills, \skillbase\get_acquired_skill_array($pa));
		
		$randkeys = array_rand($ls_skills, $count);
		if (!is_array($randkeys)) $randkeys = [$randkeys];
		foreach ($randkeys as $key)
		{
			$skillid = $ls_skills[$key];
			\skillbase\skill_acquire($skillid, $pa);
			//部分技能实际由几个组成的处理
			if (array_key_exists($skillid, $sk565_feature))
			{
				foreach ($sk565_feature[$skillid] as $extra_skillid) \skillbase\skill_acquire($extra_skillid, $pa);
			}
		}		
	}
		
	function get_skill_list($sktype)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('clubbase','skill565'));
		$ls_skills = array();
		foreach ($clublist as $club => $arr)
		{
			if (in_array($club, $sk565_allowclublist))
			{
				foreach ($arr['skills'] as $skillid) 
				{
					if (get_if_skill_type($skillid, $sktype)) $ls_skills[] = $skillid;
				}
			}
		}	
		//排除被ban的技能
		$ls_skills = array_diff($ls_skills, $sk565_banlist);
		return $ls_skills;
	}
	
	function get_if_skill_type($skillid, $sktype)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;')!==false) return false;
		//称号特性
		if ('feature' === $sktype)
		{
			if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')!==false) return true;
		}
		//战斗技（非称号特性）
		else if ('battle' === $sktype)
		{
			if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')===false && strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'battle;')!==false) return true;
		}
		//升级技（非称号特性）
		else if ('upgrade' === $sktype)
		{
			if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')===false && strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'upgrade;')!==false) return true;
		}
		//非战斗技（非称号特性）
		else if ('nonbattle' === $sktype)
		{
			if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')===false && strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'battle;')===false) return true;
		}
		return false;
	}
	
}

?>