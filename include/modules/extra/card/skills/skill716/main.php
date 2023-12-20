<?php

namespace skill716
{
	function init() 
	{
		define('MOD_SKILL716_INFO','card;active;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[716] = '占卜';
	}
	
	function acquire716(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost716(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		
		if ($mode == 'special' && $command == 'skill716_special') 
		{
			$subcmd = get_var_in_module('subcmd','input');
			if ($subcmd == 'castsk716_1') cast_skill716(1);
			if ($subcmd == 'castsk716_2') cast_skill716(2);
			return;
		}
		$chprocess();
	}

	function cast_skill716($choice)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger'));
		if (!\skillbase\skill_query(716)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if ($choice == 1)
		{
			$log .= '你掷出了一枚硬币。<br>';
			if (rand(0,1)) $log .= '<span class="yellow b">是正面！说不定要走好运了？</span><br>';
			else $log .= '<span class="yellow b">是背面……应该没事吧？</span><br>';
		}
		elseif ($choice == 2)
		{
			$dice = rand(1,6);
			$log .= '你丢了一枚骰子。<br>';
			if ($dice == 1) $log .= '<span class="yellow b">是1……应该不会有啥事吧？</span><br>';
			elseif ($dice == 2) $log .= '<span class="yellow b">是2……可能还行？</span><br>';
			elseif ($dice == 3) $log .= '<span class="yellow b">是3……还算凑合吧！</span><br>';
			elseif ($dice == 4) $log .= '<span class="yellow b">是4……还算不错？</span><br>';
			elseif ($dice == 5) $log .= '<span class="yellow b">是5！看来今天运气还行？</span><br>';
			else $log .= '<span class="yellow b">是6！看来今天要撞大运了！</span><br>';
		}
		else
		{
			$log .= '参数不合法。<br>';
			return;
		}
		$mode = 'command';
		return;
	}
	
}

?>