<?php

namespace item_randskills
{
	$rs_allowclublist = array(1,2,3,4,5,6,7,8,9,10,11,13,14,18,19,20,21,24,25,26);
	//禁了保镖、家教、学习，以及衍生技和加血攻防治疗
	$rs_banlist = array(10,11,12,19,20,24,31,41,42,55,56,59,72,79,98,224,231,106);
	//某些技能附带另外的技能，很搞
	$rs_feature = array
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
	
	//此处S,A,B,C,X表示技能稀有度
	$rs_cardskills = array
	(
		'S' => array(405, 406, 415, 434, 500, 515, 516, 518, 539, 591, 719),
		'A' => array(407, 409, 410, 437, 439, 440, 446, 458, 472, 486, 496, 502, 517, 527, 560, 595, 597, 710),
		'B' => array(416, 420, 429, 443, 447, 453, 454, 464, 465, 467, 473, 534, 556, 567, 590, 598, 705),
		'C' => array(422, 428, 442, 448, 449, 450, 452, 457, 463, 470, 471, 479, 489, 557, 570, 582),
		'X' => array(469, 474, 478, 483, 494, 511, 571, 579, 702, 704, 707, 708, 722),
	);
	
	function init()
	{
		eval(import_module('itemmain'));
		$iteminfo['SC'] = '技能核心';
		$itemspkinfo['^scls'] = '备选技能';//不显示
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if (strpos($k,'SC01')===0)
		{
			$ret .= '使用后可以在三个未拥有的随机称号技能中选择一个习得';
		}
		elseif (strpos($k,'SC02')===0)
		{
			$ret .= '使用后可以随机习得一个未拥有的称号技能';
		}
		elseif (strpos($k,'SCC1')===0)
		{
			$ret .= '使用后可以在三个C级技能中选择一个习得';
		}
		elseif (strpos($k,'SCC2')===0)
		{
			$ret .= '使用后可以随机习得一个C级技能';
		}
		elseif (strpos($k,'SCB1')===0)
		{
			$ret .= '使用后可以在三个B级技能中选择一个习得';
		}
		elseif (strpos($k,'SCB2')===0)
		{
			$ret .= '使用后可以随机习得一个B级技能';
		}
		elseif (strpos($k,'SCA1')===0)
		{
			$ret .= '使用后可以在三个A级技能中选择一个习得';
		}
		elseif (strpos($k,'SCA2')===0)
		{
			$ret .= '使用后可以随机习得一个A级技能';
		}
		elseif (strpos($k,'SCS1')===0)
		{
			$ret .= '使用后可以在三个S级技能中选择一个习得';
		}
		elseif (strpos($k,'SCS2')===0)
		{
			$ret .= '使用后可以随机习得一个S级技能';
		}
		elseif (strpos($k,'SCX2')===0)
		{
			$ret .= '使用后可以随机习得一个奇怪的技能';
		}
		elseif(strpos($k,'Y')===0 || strpos($k,'Z')===0)
		{
			if ($n == '四面骰'){
				$ret .= '使用后可以将自己的所有技能替换为随机称号技能';
			}
		}
		return $ret;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0) 
		{
			if ($itm == '四面骰') {
				eval(import_module('clubbase'));
				$log.="你使用了<span class=\"yellow b\">{$itm}</span>。<br>";
				
				$acquired_skills = \skillbase\get_acquired_skill_array($sdata);
				$count = 0;
				//计算技能数，并失去所有非hidden技能
				if (!empty($acquired_skills))
				{
					foreach ($acquired_skills as $skillid)
					{
						if (isset($clubskillname[$skillid]) && (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;') === false))
						{
							\skillbase\skill_lost($skillid, $sdata);
							$count += 1;
						}
					}
				}
				//得到新的技能
				if ($count > 0)
				{
					get_rand_clubskill($sdata, $count);
					$log .= "随着骰子的转动，你感到自己的身体变得焕然一新！<br>";
				}
				else
				{
					$log .= "但是，这个骰子对你好像没什么用的样子……<br>";
				}
				\itemmain\itms_reduce($theitem);
				return;
			}	
		}
		elseif (strpos($itmk, 'SC') === 0)
		{
			eval(import_module('item_randskills'));
			$log .= "你使用了<span class=\"yellow b\">{$itm}</span>。<br>";
			if ($itmk[3] === '1')
			{
				$sclist = get_skcore_skilllist($itmk, $itmsk);
				$skcore_choice = get_var_input('skcore_choice');
				if (empty($skcore_choice))
				{
					ob_start();
					include template(MOD_ITEM_RANDSKILLS_USE_SKCORE);
					$cmd = ob_get_contents();
					ob_end_clean();	
					return;
				}
				else
				{
					if (!in_array((int)$skcore_choice, array(1,2,3)))
					{
						$log .= '参数不合法。<br>';
						$mode = 'command';
						return;
					}
					else
					{
						eval(import_module('clubbase','item_randskills'));
						$skillid = $sclist[(int)$skcore_choice-1];
						\skillbase\skill_acquire($skillid, $sdata);
						if (array_key_exists($skillid, $rs_feature))
						{
							foreach ($rs_feature[$skillid] as $extra_skillid) \skillbase\skill_acquire($extra_skillid, $pa);
						}
						$log .= "你习得了技能<span class=\"yellow b\">「{$clubskillname[$skillid]}」</span>！<br>";
						use_skcore_success($sdata);
					}
				}
				\itemmain\itms_reduce($theitem);
				return;
			}
			elseif ($itmk[3] === '2')
			{
				if ($itmk[2] === '0') $rs_skills = get_rand_clubskill($sdata, 1);
				elseif (in_array($itmk[2], array('S','A','B','C','X')))
				{
					$ls_skills = array_diff($rs_cardskills[$itmk[2]], \skillbase\get_acquired_skill_array($sdata));		
					if (empty($ls_skills)) $rs_skills = array();
					else
					{
						$skillid = array_randompick($ls_skills, 1);
						\skillbase\skill_acquire($skillid, $sdata);
						$rs_skills = [$skillid];
					}
				}
				if (!empty($rs_skills))
				{
					eval(import_module('clubbase'));
					$log .= "你习得了技能<span class=\"yellow b\">「{$clubskillname[$rs_skills[0]]}」</span>！<br>";
					use_skcore_success($sdata);
				}
				else
				{
					$log .= "但是好像什么也没有发生。<br>";
				}
				\itemmain\itms_reduce($theitem);
				return;
			}
		}
		
		$chprocess($theitem);
	}
	
	//用于在使用技能核心后添加判定的接口
	function use_skcore_success(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}	
	
	function get_skcore_skilllist($itmk, &$itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (!\itemmain\check_in_itmsk('^scls', $itmsk)) 
		{
			$sclist = add_skcore_choices($itmk, $itmsk);
		}
		else
		{
			$sclist = \itemmain\check_in_itmsk('^scls', $itmsk);
			$sclist = \attrbase\base64_decode_comp_itmsk($sclist);
			$sclist = explode(',',$sclist);
			$acquired_skills = \skillbase\get_acquired_skill_array($sdata);
			$flag = 0;
			foreach ($sclist as $skillid)
			{
				if (in_array($skillid, $acquired_skills))
				{
					$flag = 1;
					break;
				}
			}
			if ($flag) $sclist = add_skcore_choices($itmk, $itmsk);
		}
		return $sclist;
	}
	
	function add_skcore_choices($itmk, &$itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if ($itmk[2] === '0')
		{
			$ls_skills = get_skilllist();
		}
		elseif(in_array($itmk[2], array('S','A','B','C','X')))
		{
			eval(import_module('item_randskills'));
			$ls_skills = $rs_cardskills[$itmk[2]];
		}
		else return array();
		$ls_skills = array_diff($ls_skills, \skillbase\get_acquired_skill_array($sdata));
		$itmsk = \itemmain\replace_in_itmsk('^scls','',$itmsk);
		if (!empty($ls_skills))
		{
			$sclist = array_randompick($ls_skills, 3);
			$scstr = implode(',',$sclist);
			$itmsk .= '^scls_'.\attrbase\base64_encode_comp_itmsk($scstr).'1';
			return $sclist;
		}
		else return array();
	}
	
	function get_rand_clubskill(&$pa, $count, $sktype='all')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('item_randskills'));
		$ls_skills = get_skilllist($sktype);
		//排除已学会的技能
		$ls_skills = array_diff($ls_skills, \skillbase\get_acquired_skill_array($pa));
		
		if (empty($ls_skills)) return array();
		$rs_skills = array_randompick($ls_skills, $count);
		if (!is_array($rs_skills)) $rs_skills = [$rs_skills];
		foreach ($rs_skills as $skillid)
		{
			\skillbase\skill_acquire($skillid, $pa);
			//部分技能实际由几个组成的处理
			if (array_key_exists($skillid, $rs_feature))
			{
				foreach ($rs_feature[$skillid] as $extra_skillid) \skillbase\skill_acquire($extra_skillid, $pa);
			}
		}
		return $rs_skills;
	}
	
	function get_skilllist($sktype='all')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;		
		eval(import_module('clubbase','item_randskills'));
		$ls_skills = array();
		foreach ($clublist as $club => $arr)
		{
			if (in_array($club, $rs_allowclublist))
			{
				foreach ($arr['skills'] as $skillid) 
				{
					if (get_if_skilltype($skillid, $sktype)) $ls_skills[] = $skillid;
				}
			}
		}	
		//排除被ban的技能
		$ls_skills = array_diff($ls_skills, $rs_banlist);
		return $ls_skills;
	}
	
	function get_if_skilltype($skillid, $sktype='all')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'hidden;')!==false) return false;
		//任意技能
		if ('all' === $sktype) return true;
		//称号特性
		elseif ('feature' === $sktype)
		{
			if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')!==false) return true;
		}
		//战斗技（非称号特性）
		elseif ('battle' === $sktype)
		{
			if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')===false && strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'battle;')!==false) return true;
		}
		//升级技（非称号特性）
		elseif ('upgrade' === $sktype)
		{
			if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')===false && strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'upgrade;')!==false) return true;
		}
		//非战斗技（非称号特性）
		elseif ('nonbattle' === $sktype)
		{
			if (strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'feature;')===false && strpos(constant('MOD_SKILL'.$skillid.'_INFO'),'battle;')===false) return true;
		}
		return false;
	}

	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if ($ret) {
			if (strpos($cinfo[0], '^scls') === 0) return false;
		}
		return $ret;
	}

}

?>