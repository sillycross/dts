<?php

namespace skill704
{
	$skill704_message = array(
		0 => '咕咕咕，恭喜发财，新年快乐，咕咕咕，大鹌鹑。',
		1 => '咕咕咕，咕咕，我祝你事事顺利，教你做个文明人！',
		2 => '咕咕咕，咕咕，大鹌鹑',
		3 => '咕咕，咕咕咕，我祝你新年快乐',
		4 => '咕咕，咕咕咕，我祝你身体健康',
		5 => '咕咕，咕咕咕，我祝你财源滚滚',
		6 => '咕咕，咕咕咕，我祝你步步高升',
		7 => '咕咕，咕咕咕，我祝你学业有成',
		8 => '咕咕，我咕你买了个表的',
		9 => '咕咕，再见，咕咕，傻逼！',
	);
	
	function init() 
	{
		define('MOD_SKILL704_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[704] = '凶兽';
	}
	
	function acquire704(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost704(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function send_battle_msg(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger'));
		if(\skillbase\skill_query(704,$pa) && empty(get_var_in_module('message', 'input')))
		{
			//不是每次都会叫
			if (rand(0,99) < 60)
			{
				eval(import_module('skill704'));
				$mkey = rand(0, count($skill704_message)-1);
				$message = $skill704_message[$mkey];
				$log .= "<span class=\"lime b\">你向{$pd['name']}喊道：“{$message}”</span><br>";
				$pd['battle_msg'] = "<span class=\"lime b\">{$pa['name']}向你喊道：“{$message}”</span><br><br>";
				\sys\addchat(6, "{$pa['name']}高喊着“{$message}”杀向了{$pd['name']}");
			}
		}
		else $chprocess($pa, $pd, $active);
	}

}

?>