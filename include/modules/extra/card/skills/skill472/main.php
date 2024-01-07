<?php

namespace skill472
{
	function init() 
	{
		define('MOD_SKILL472_INFO','card;upgrade;');
	}
	
	function acquire472(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(472,'l','0',$pa);
	}
	
	function lost472(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked472(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function sklearn_checker472($a='', $b='')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($a=='caller_id') return 472;
		if ($a=='show_cost') return 0;
		if ($a=='is_learnable') 
		{
			$val=constant('MOD_SKILL'.$b.'_INFO');
			if ((int)$b==257) return 0;
			if (strpos($val,'battle;')===false) return 1;
			return 0;
		}
		if ($a=='now_learnable') return 1;
	}
	
	function upgrade472()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill472','player','logger'));
		$skillpara1=(int)get_var_input('skillpara1');
		if (!\skillbase\skill_query(472) || !check_unlocked472($sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (!\sklearn_util\sklearn_basecheck($skillpara1) || !sklearn_checker472('is_learnable',$skillpara1)) 
		{
			$log.='你不可以学习这个技能！<br>';
			return;
		}
		if (!sklearn_checker472('now_learnable',$skillpara1))
		{
			$log.='现在尚没有足够资源学习这个技能！<br>';
			return;
		}
		if (\skillbase\skill_query($skillpara1))
		{
			$log.='你已经拥有这个技能了！<br>';
			return;
		}
		if (((int)\skillbase\skill_getvalue(472,'l'))!=0)
			\sklearn_util\sklearn_skill_lost((int)\skillbase\skill_getvalue(472,'l'));

		\skillbase\skill_setvalue(472,'l',$skillpara1);
		\skillbase\skill_acquire($skillpara1);
		$log.='学习成功。<br>';
	}
}

?>