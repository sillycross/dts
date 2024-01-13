<?php

namespace skill55
{
	//变更技能需要的金钱
	$skill55_need = 50;
	
	function init() 
	{
		define('MOD_SKILL55_INFO','club;upgrade;locked;');
	}
	
	function acquire55(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(55,'l','0',$pa);
	}
	
	function lost55(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked55(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function sklearn_checker55($a='', $b='')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($a=='caller_id') return 55;
		if ($a=='show_cost') return 0;
		if ($a=='is_learnable') 
		{
			if ((int)$b==257) return 0;
			return 1;
		}
		if ($a=='now_learnable') 
		{
			if (((int)\skillbase\skill_getvalue(55,'l'))==0) return 1;
			eval(import_module('skill55','player'));
			if ($money>=$skill55_need) return 1; else return 0;
		}
	}
	
	function upgrade55()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill55','player','logger'));
		$skillpara1 = (int)get_var_input('skillpara1');
		if (!\skillbase\skill_query(55) || !check_unlocked55($sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (!\sklearn_util\sklearn_basecheck($skillpara1) || !sklearn_checker55('is_learnable',$skillpara1)) 
		{
			$log.='你不可以学习这个技能！<br>';
			return;
		}
		if (!sklearn_checker55('now_learnable',$skillpara1))
		{
			$log.='现在尚没有足够资源学习这个技能！<br>';
			return;
		}
		if (\skillbase\skill_query($skillpara1))
		{
			$log.='你已经拥有这个技能了！<br>';
			return;
		}
		if (((int)\skillbase\skill_getvalue(55,'l'))!=0)
		{
			\skillbase\skill_lost((int)\skillbase\skill_getvalue(55,'l'));
			$money-=$skill55_need;
		}
		
		\skillbase\skill_setvalue(55,'l',$skillpara1);
		\skillbase\skill_acquire($skillpara1);
		$log.='学习成功。<br>';
	}
}

?>