<?php

namespace skill12
{
	function init() 
	{
		define('MOD_SKILL12_INFO','club;upgrade;locked;');
	}
	
	function acquire12(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost12(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked12(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade12()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(12))
		{
			$log.='你没有这个技能！<br>';
			return;
		}
		if ($skillpoint<1) 
		{
			$log.='技能点不足。<br>';
			return;
		}
		eval(import_module('wound'));
		$flag=false;
		$log.="消耗了<span class='lime'>1</span>点技能点，<br>";
		for ($i=0; $i<strlen($inf); $i++)
		{
			$log .= "{$infname[$inf[$i]]}状态解除了。<br>";
			$flag=true;
		}
		$inf = '';
		if(!$flag){
			$log .= '但是什么也没发生。<br>';
		}
		$skillpoint--; $inf='';
	}
	
}

?>
