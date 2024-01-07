<?php

namespace skill557
{
	function init() 
	{
		define('MOD_SKILL557_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[557] = '马脚';
	}
	
	function acquire557(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost557(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));
		if (\skillbase\skill_query(557) && strpos ( $theitem['itmk'], 'DF' ) === 0) 
		{			
			$skill557_choice = get_var_input('skill557_choice');
			//如果没有提供目标参数，认为是从命令菜单直接点击的装备按钮
			if (empty($skill557_choice))
			{
				ob_start();
				include template(MOD_SKILL557_CASTSK557);
				$cmd = ob_get_contents();
				ob_end_clean();	
				return;
			}
			else
			{
				//这里进行前置的过滤
				if ('ara' != $skill557_choice && 'arf' != $skill557_choice)
				{
					$log.='参数不合法。<br>';
					$mode = 'command';
					return;
				}
				\armor\use_armor($theitem, $skill557_choice);
				return;
			}
		}
		$chprocess($theitem);
	}
}

?>