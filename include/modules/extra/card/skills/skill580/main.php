<?php

namespace skill580
{
	$skill580_itmlist = array('被遗忘的伞', '☆被遗忘的伞☆', '★被遗忘的伞★');
	$skill580_act_rate = 30;
	
	function init() 
	{
		define('MOD_SKILL580_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[580] = '怪伞';
	}
	
	function acquire580(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost580(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked580(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function meetman($sid)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','skill580'));
		\player\update_sdata();	
		$edata = \player\fetch_playerdata_by_pid($sid);
		
		if (\skillbase\skill_query(580,$edata) && check_unlocked580($edata) && empty(\skillbase\skill_getvalue(1003,'sk580_sid')))
		{
			eval(import_module('sys'));
			$dice = rand(0,99);
			if ($dice < $skill580_act_rate)
			{
				\skillbase\skill_setvalue(1003,'sk580_sid',$sid);
				\itemmain\discover_item();
				return;
			}
		}
		$chprocess($sid);
		\skillbase\skill_delvalue(1003,'sk580_sid');
	}
	
	//给当前行动玩家的itm0赋值，并假造一个发现道具的界面
	function skill580_discover_umbrella()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
	}
	
	//用一个道具替换物品
	function discover_item()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain'));
		if (!empty(\skillbase\skill_getvalue(1003,'sk580_sid')))
		{
			eval(import_module('skill580'));
			$key = rand(0,2);
			$itm = array(
				'iid' => 0, 
				'itm' => $skill580_itmlist[$key],
				'itmk' => 'X',
				'itme' => 1,
				'itms' => 1,
				'itmsk' => ''
			);
			$itm0 = $itmk0 = $itmsk0 = '';
			$itme0 = $itms0 = 0;
			
			\itemmain\focus_item($itm);
			return true;
		}
		return $chprocess();
	}
	
	//如果拾取，改为遭遇敌人
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$sid = \skillbase\skill_getvalue(1003,'sk580_sid');
		if (!empty($sid))
		{
			eval(import_module('input'));
			if (($mode == 'itemmain' && $command == 'itemget') || ($mode == 'command' && $command == 'itm0'))
			{
				eval(import_module('player'));
				if(!empty($itms0)) {
					$itm0 = $itmk0 = $itmsk0 = '';
					$itme0 = $itms0 = 0;
				}
				\metman\meetman($sid);
				return;
			}
			else if ($mode == 'itemmain' && $command == 'dropitm0')
			{
				eval(import_module('player','logger'));
				$log .= "<span class=\"yellow b\">{$itm0}</span>发出唉的一声，然后不见了。<br>";
				$itm0 = $itmk0 = $itmsk0 = '';
				$itme0 = $itms0 = 0;
				\skillbase\skill_delvalue(1003,'sk580_sid',$sdata);
				return;
			}
		}
		$chprocess();
	}
	
	//必定被先手
	function check_enemy_meet_active(&$ldata,&$edata)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!empty(\skillbase\skill_getvalue(1003,'sk580_sid'))) return 0;
		else return $chprocess($ldata,$edata);
	}
}

?>