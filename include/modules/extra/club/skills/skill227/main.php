<?php

namespace skill227
{
	function init() 
	{
		define('MOD_SKILL227_INFO','club;upgrade;locked;');
	}
	
	function acquire227(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(227,'l','0',$pa);
	}
	
	function lost227(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked227(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=15;
	}
	
	function sklearn_checker227($a='', $b='')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($a=='caller_id') return 227;
		if ($a=='show_cost') return 0;
		if ($a=='is_learnable') 
		{
			$val=constant('MOD_SKILL'.$b.'_INFO');
			//不能学战斗技
			if (strpos($val,'battle;')===false) return 1;
			return 0;
		}
		if ($a=='now_learnable') return 1;
	}
	
	function upgrade227()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		$skillpara1=(int)get_var_input('skillpara1');
		if (!\skillbase\skill_query(227) || !check_unlocked227($sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		if (!\sklearn_util\sklearn_basecheck($skillpara1) || !sklearn_checker227('is_learnable',$skillpara1)) 
		{
			$log.='你不可以学习这个技能！<br>';
			return;
		}
		if (!sklearn_checker227('now_learnable',$skillpara1))
		{
			$log.='现在尚没有足够资源学习这个技能！<br>';
			return;
		}
		if (\skillbase\skill_query($skillpara1))
		{
			$log.='你已经拥有这个技能了！<br>';
			return;
		}
		if (((int)\skillbase\skill_getvalue(227,'l'))!=0)
		{
			$log.='你已经学习过一个技能了！<br>';
			return;
		}
		\skillbase\skill_setvalue(227,'l',$skillpara1);
		\skillbase\skill_acquire($skillpara1);
		$log.='学习成功。<br>';
	}
}

?>
