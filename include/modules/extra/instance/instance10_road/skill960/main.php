<?php

namespace skill960
{
	function init() 
	{
		define('MOD_SKILL960_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[960] = '任务';
	}
	
	function acquire960(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(960,'taskarr','',$pa);
		\skillbase\skill_setvalue(960,'taskprog','',$pa);
		\skillbase\skill_setvalue(960,'invscore','0',$pa);
	}
	
	function lost960(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(960,'taskarr',$pa);
		\skillbase\skill_delvalue(960,'taskprog',$pa);
		\skillbase\skill_delvalue(960,'invscore',$pa);
	}
	
	function check_unlocked960(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//获取任务列表
	function get_taskarr(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (empty($pa))
		{
			eval(import_module('player'));
			$pa = $sdata;
		}
		$taskarr = \skillbase\skill_getvalue(960,'taskarr',$pa);
		if ('' === $taskarr) $taskarr = array();
		else $taskarr = explode('_',$taskarr);
		return $taskarr;
	}
	
	//获取任务完成度
	function get_taskprog(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (empty($pa))
		{
			eval(import_module('player'));
			$pa = $sdata;
		}
		$taskprog = \skillbase\skill_getvalue(960,'taskprog',$pa);
		if ('' === $taskprog) $taskprog = array();
		else $taskprog = explode('_',$taskprog);
		return $taskprog;
	}
	
	//更新任务完成度
	function update_taskprog(&$pa=NULL, $taskid, $newprog)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (empty($pa))
		{
			eval(import_module('player'));
			$pa = $sdata;
		}
		$taskarr = get_taskarr($pa);
		$taskprog = get_taskprog($pa);
		$i = array_search($taskid, $taskarr);
		if ($i !== false)
		{
			if (is_string($newprog) && ($newprog[0] === '+')) $taskprog[$i] += (int)substr($newprog, 1);
			else $taskprog[$i] = $newprog;
			$taskprog = implode('_',$taskprog);
			\skillbase\skill_setvalue(960,'taskprog',$taskprog,$pa);
		}
	}
	
	//添加任务
	function add_task(&$pa, $taskid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$taskarr = get_taskarr($pa);
		$taskprog = get_taskprog($pa);
		if (!in_array($taskid, $taskarr))
		{
			$taskarr[] = $taskid;
			$taskarr = implode('_',$taskarr);
			\skillbase\skill_setvalue(960,'taskarr',$taskarr,$pa);
			$taskprog[] = '0';
			$taskprog = implode('_',$taskprog);
			\skillbase\skill_setvalue(960,'taskprog',$taskprog,$pa);
		}
	}
	
	//删除任务
	function remove_task(&$pa, $taskid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$taskarr = get_taskarr($pa);
		$taskprog = get_taskprog($pa);
		
		$i = array_search($taskid, $taskarr);
		if ($i !== false)
		{
			unset($taskarr[$i]);
			$taskarr = array_values($taskarr);
			$taskarr = implode('_',$taskarr);
			\skillbase\skill_setvalue(960,'taskarr',$taskarr,$pa);
			unset($taskprog[$i]);
			$taskprog = array_values($taskprog);
			$taskprog = implode('_',$taskprog);
			\skillbase\skill_setvalue(960,'taskprog',$taskprog,$pa);
		}
	}
	
	//提交任务
	function submit_task(&$pa, $taskid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$taskarr = get_taskarr($pa);
		$taskprog = get_taskprog($pa);
		
		eval(import_module('skill960','logger'));
		
		if (!isset($tasks_info[$taskid]))
		{
			$log .= '输入参数错误。<br>';
			return;
		}
		else
		{
			$i = array_search($taskid, $taskarr);
			if ($i !== false) 
			{
				$flag = 0;
				if (($tasks_info[$taskid]['tasktype'] === 'battle_kill') || ($tasks_info[$taskid]['tasktype'] === 'itemuse'))
				{
					if (isset($tasks_info[$taskid]['taskreq']['num']) && ((int)$taskprog[$i] >= $tasks_info[$taskid]['taskreq']['num']))
					{
						$log .= '你完成了这个任务！<br>';
						get_task_reward($pa, $taskid);
						remove_task($pa, $taskid);
						$flag = 1;
					}
				}
				elseif ($tasks_info[$taskid]['tasktype'] === 'item_search')
				{
					$flag = 1;
					if (isset($tasks_info[$taskid]['taskreq']['num']))
					{
						$icount = submit_task_item($pa, $taskid, (int)$taskprog[$i]);
						if ($icount >= $tasks_info[$taskid]['taskreq']['num'])
						{
							$log .= '你完成了这个任务！<br>';
							get_task_reward($pa, $taskid);
							remove_task($pa, $taskid);
						}
						else update_taskprog($pa, $taskid, $icount);
					}
				}
				elseif ($tasks_info[$taskid]['tasktype'] === 'special')
				{
					$skillid = $tasks_info[$taskid]['taskreq']['skillid'];
					$skilllvl = $tasks_info[$taskid]['taskreq']['lvl'];
					if (!empty($skillid) && \skillbase\skill_query($skillid, $pa) && !empty($skilllvl) && ((int)\skillbase\skill_getvalue($skillid,'lvl',$pa) >= $skilllvl))
					{
						$log .= '你完成了这个任务！<br>';
						get_task_reward($pa, $taskid);
						remove_task($pa, $taskid);
						skill_lost($skillid, $pa);
						$flag = 1;
					}
				}
				if (!$flag)
				{
					$log .= '你尚未完成这个任务。<br>';
					return;
				}
			}
			else
			{
				$log .= '你没有这个任务。<br>';
				return;
			}
		}
	}
	
	//提交任务道具
	function submit_task_item(&$pa, $taskid, $icount)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill960','logger'));
		if (isset($tasks_info[$taskid]['taskreq']))
		{
			if (isset($tasks_info[$taskid]['taskreq']['num'])) $itmnum = $tasks_info[$taskid]['taskreq']['num'];
			else $itmnum = 1;
			$flag = 0;
			for ($i=1;$i<=6;$i++)
			{
				$theitem = array('itm'=>$pa['itm'.$i], 'itmk'=>$pa['itmk'.$i], 'itme'=>$pa['itme'.$i], 'itms'=>$pa['itms'.$i], 'itmsk'=>$pa['itmsk'.$i]);
				if (check_item_taskreq($theitem, $tasks_info[$taskid]['taskreq']))
				{
					$log .= "你提交了<span class=\"yellow b\">{$pa['itm'.$i]}</span>。<br>";
					$pa['itm'.$i] = $pa['itmk'.$i] = $pa['itmsk'.$i] = '';
					$pa['itme'.$i] = $pa['itms'.$i] =0;
					$icount += 1;
					$flag = 1;
					if ($icount >= $itmnum) break;
				}
			}
		}
		if (!$flag) $log .= "你没有任务所需的道具。<br>";
		return $icount;
	}
	
	//获取任务奖励
	function get_task_reward(&$pa, $taskid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill960','logger'));
		if (!isset($tasks_info[$taskid]['reward'])) return;
		foreach ($tasks_info[$taskid]['reward'] as $k => $v)
		{
			if ($k === 'money')
			{
				$pa['money'] += $v;
				$log .= "<span class=\"yellow b\">你获得了{$v}元金钱。</span><br>";
			}
			elseif ($k === 'exp')
			{		
				$log .= "<span class=\"yellow b\">你获得了{$v}点经验值。</span><br>";
				\lvlctl\getexp($v, $pa);
			}
			elseif ($k === 'invscore')
			{		
				$log .= "<span class=\"yellow b\">你获得了{$v}点调查度。</span><br>";
				$invscore = (int)\skillbase\skill_getvalue(960,'invscore',$pa);
				$invscore += $v;
				\skillbase\skill_setvalue(960,'invscore',$invscore,$pa);
			}
			elseif ($k === 'item')
			{
				if (\skillbase\skill_query(952,$pa))
				{
					foreach ($v as $theitem)
					{
						\skill952\skill952_sendin_core($theitem, $pa);
						$log .= "你获得了<span class=\"yellow b\">{$theitem['itm']}</span>。<br>";
					}
					$log .= "道具被送到了你的奖励箱中。<br>";
				}
				elseif (\searchmemory\searchmemory_available())
				{
					foreach ($v as $theitem)
					{
						$dropid = \itemmain\itemdrop_query($theitem['itm'], $theitem['itmk'], $theitem['itme'], $theitem['itms'], $theitem['itmsk'], $pa['pls']);
						$amarr = array('iid' => $dropid, 'itm' => $theitem['itm'], 'pls' => $pa['pls'], 'unseen' => 0);
						\skill1006\add_beacon($amarr, $pa);
						\player\player_save($pa);
						$log .= "<span class=\"yellow b\">{$theitem['itm']}</span>掉到了你的身旁。<br>";
					}
					$log .= "<span class=\"yellow b\">究竟是从哪儿送来的？</span><br>";
				}
			}
		}
	}
	
	//击杀任务计算计数
	function player_kill_enemy(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		if (\skillbase\skill_query(960,$pa) && check_unlocked960($pa))
		{
			$taskarr = get_taskarr($pa);
			$taskprog = get_taskprog($pa);
			eval(import_module('skill960'));
			foreach ($taskarr as $taskid)
			{
				if (($tasks_info[$taskid]['tasktype'] === 'battle_kill') && check_npc_taskreq($pd, $tasks_info[$taskid]['taskreq']))
				{
					update_taskprog($pa, $taskid, '+1');
				}
			}
		}
		$chprocess($pa,$pd,$active);
	}
	
	//判断击杀的NPC是否满足任务要求
	function check_npc_taskreq($pd, $req)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!$pd['type']) return false;
		if (isset($req['name']) && ($pd['name'] !== $req['name'])) return false;
		if (isset($req['type']) && ((int)$pd['type'] !== $req['type'])) return false;
		if (isset($req['lvl']) && ((int)$pd['lvl'] < $req['lvl'])) return false;
		if (isset($req['wepk']) && (strpos($pd['wepk'], $req['wepk']) !== 0)) return false;
		return true;
	}
	
	//使用道具任务
	function itemuse($theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(960,$sdata) && check_unlocked960($sdata))
		{
			$taskarr = get_taskarr($sdata);
			$taskprog = get_taskprog($sdata);
			eval(import_module('skill960'));
			foreach ($taskarr as $taskid)
			{
				if (($tasks_info[$taskid]['tasktype'] === 'item_use') && check_item_taskreq($theitem, $tasks_info[$taskid]['taskreq']))
				{
					update_taskprog($sdata, $taskid, '+1');
				}
			}
		}
		$chprocess($theitem);
	}
	
	//判断道具是否满足任务要求
	function check_item_taskreq($theitem, $req)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (isset($req['itm']))
		{
			if (isset($req['itm_match']) && ($req['itm_match'] == 1))
			{
				foreach ($req['itm'] as $v)
				{
					$flag = 0;
					if (strpos($theitem['itm'], $v) !== false)
					{
						$flag = 1;
						break;
					}
					if (!$flag) return false;
				}
			}
			elseif (!in_array($theitem['itm'], $req['itm'])) return false;
		}
		if (isset($req['itmk']))
		{
			foreach ($req['itmk'] as $v)
			{
				$flag = 0;
				if (strpos($theitem['itmk'], $v) === 0)
				{
					$flag = 1;
					break;
				}
				if (!$flag) return false;
			}
		}
		return true;
	}
	
	//获取随机任务
	function get_rand_task(&$pa, $rank, $num)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$taskarr = get_taskarr($pa);
		eval(import_module('skill960','logger'));
		if (!isset($tasks_index[$rank])) return;
		$tasklist = array_diff($tasks_index[$rank], $taskarr);
		$newtasks = array_randompick($tasklist, $num);
		$log .= "<span class=\"yellow b\">你获得了新的任务！</span><br>";
		if (is_array($newtasks))
		{
			foreach ($newtasks as $taskid)
			{
				add_task($pa, $taskid);
			}
		}
		else add_task($pa, $newtasks);
	}
	
	//显示任务文字说明
	function show_task_tip($taskid, $prog, $show_reward = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill960'));
		$task_tip = '';
		if (isset($tasks_info[$taskid]['name']))
		{
			$task_tip .= "<span class=\"yellow b\">【{$tasks_info[$taskid]['name']}】</span>";
		}
		if (isset($tasks_info[$taskid]['taskreq']['num'])) $task_tip .= "（<span class=\"yellow b\">{$prog} / {$tasks_info[$taskid]['taskreq']['num']}</span>）";
		$task_tip .= '：<br>';
		if ($tasks_info[$taskid]['tasktype'] === 'battle_kill')
		{
			$task_tip .= '战斗击杀';
			if (isset($tasks_info[$taskid]['taskreq']['num'])) $task_tip .= "<span class=\"yellow b\">{$tasks_info[$taskid]['taskreq']['num']}</span>个";			
			if (isset($tasks_info[$taskid]['taskreq']['type']))
			{
				eval(import_module('npc'));
				$task_tip .= "类别为<span class=\"yellow b\">{$npc_typeinfo[$tasks_info[$taskid]['taskreq']['type']]}</span>的";
			}
			if (isset($tasks_info[$taskid]['taskreq']['wepk']))
			{
				eval(import_module('itemmain'));
				$task_tip .= "使用武器为<span class=\"yellow b\">{$iteminfo[$tasks_info[$taskid]['taskreq']['wepk']]}</span>的";
			}
			if (isset($tasks_info[$taskid]['taskreq']['lvl'])) $task_tip .= "等级至少为<span class=\"yellow b\">{$tasks_info[$taskid]['taskreq']['lvl']}</span>的";
			if (isset($tasks_info[$taskid]['taskreq']['name'])) $task_tip .= "名称为“<span class=\"yellow b\">{$tasks_info[$taskid]['taskreq']['name']}”</span>的";
			$task_tip .= 'NPC';
		}
		elseif (($tasks_info[$taskid]['tasktype'] === 'item_search') || ($tasks_info[$taskid]['tasktype'] === 'item_use'))
		{
			if ($tasks_info[$taskid]['tasktype'] === 'item_search') $task_tip .= "提交";
			else $task_tip .= "使用";
			if (isset($tasks_info[$taskid]['taskreq']['itm']))
			{
				if (isset($tasks_info[$taskid]['taskreq']['itm_match']) && ($tasks_info[$taskid]['taskreq']['itm_match'] == 1)) $task_tip .= "名称包含";
				else $task_tip .= "名称为";
				$itm_ls = $tasks_info[$taskid]['taskreq']['itm'];
				$c = count($itm_ls);
				if ($c == 1) $task_tip .= $itm_ls[0];
				elseif ($c == 2) $task_tip .= implode('或', $itm_ls);
				else $task_tip = implode('、', array_slice($itm_ls, 0, -1)).'或'.end($itm_ls);
				$task_tip .= "的";
			}
			if (isset($tasks_info[$taskid]['taskreq']['itmk']))
			{
				$itmk_ls = $tasks_info[$taskid]['taskreq']['itmk'];
				$c = count($itmk_ls);
				$itmk_info_ls = array();
				foreach ($tasks_info[$taskid]['taskreq']['itmk'] as $v)
				{
					eval(import_module('itemmain'));
					$itmk_info_ls[] = $iteminfo[$v];
				}
				if ($c == 1) $task_tip .= $itmk_info_ls[0];
				elseif ($c == 2) $task_tip .= implode('或', $itmk_info_ls);
				else $task_tip = implode('、', array_slice($itmk_info_ls, 0, -1)) . '或' . end($itmk_info_ls);
			}
			else $task_tip .= "道具";
			if (isset($tasks_info[$taskid]['taskreq']['num'])) $task_tip .= "<span class=\"yellow b\">{$tasks_info[$taskid]['taskreq']['num']}</span>次";
		}
		elseif ($tasks_info[$taskid]['tasktype'] === 'special')
		{
			if (isset($tasks_info[$taskid]['taskreq']['skillid']))
			{
				eval(import_module('clubbase'));
				$task_tip .= "完成任务<span class=\"yellow b\">「{$clubskillname[$tasks_info[$taskid]['taskreq']['skillid']]}」</span>";
			}
		}
		$task_tip .= '<br>';
		if ($show_reward && isset($tasks_info[$taskid]['reward']))
		{
			$task_tip .= "任务奖励：";
			foreach ($tasks_info[$taskid]['reward'] as $k => $v)
			{
				if ($k === 'money')
				{
					$task_tip .= "金钱<span class=\"yellow b\">$v</span>元 ";
				}
				elseif ($k === 'exp')
				{
					$task_tip .= "经验值<span class=\"yellow b\">$v</span>点 ";
				}
				elseif ($k === 'invscore')
				{
					$task_tip .= "调查度<span class=\"yellow b\">$v</span>点 ";
				}
				elseif ($k === 'item')
				{
					foreach ($v as $theitem)
					{
						$itemarr = array_values($theitem);
						$task_tip .= "<span class=\"yellow b\">".\itemmix\parse_itemmix_resultshow($itemarr)."</span> ";
					}
				}
			}
		}
		return $task_tip;
	}
	
	//查看任务
	function cast_skill960()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(960)) 
		{
			$log .= '你没有这个技能。';
			return;
		}
		$flag = 0;
		$subcmd = get_var_input('subcmd');
		$taskid_submit = get_var_input('taskid_submit');
		if(!empty($taskid_submit))
		{
			submit_task($sdata, $taskid_submit);
			$flag = 1;
		}
		if(!$flag && 'show' != $subcmd) {
			$log.='参数不合法。<br>';
		}
		if(empty($itms0)) {//为了防止卡死，手里是空的才显示界面
			ob_start();
			include template(MOD_SKILL960_CASTSK960);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
	
		if ($mode == 'special' && $command == 'skill960_special') 
		{
			cast_skill960();
			return;
		}
			
		$chprocess();
	}
	
}

?>