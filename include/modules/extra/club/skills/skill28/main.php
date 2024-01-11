<?php

namespace skill28
{
	$skill28stateinfo=Array(1=>'关闭', 2=>'开启');
	
	function init() 
	{
		define('MOD_SKILL28_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[28] = '毅重';
	}
	
	function acquire28(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(28,'choice','1',$pa);
	}
	
	function lost28(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(28,'choice',$pa);
	}
	
	function check_unlocked28(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=3;
	}
	
	function upgrade28()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(28) || !check_unlocked28($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val<1 || $val>2)
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(28,'choice',$val);
		if(1==$val) $log.='关闭了「毅重」状态。<br>';
		else $log.='开启了「毅重」状态。<br>';
	}
	
	function check_skill28_ex_array(&$pl, $arr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(28,$pl) && check_unlocked28($pl) && 2 == \skillbase\skill_getvalue(28,'choice',$pl)) {
			$arr = Array('A','a','h');
		}
		return $arr;
	}
	
	function get_ex_def_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		$ret = check_skill28_ex_array($pd, $ret);
		return $ret;
	}
	
	function get_ex_attack_array(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($pa, $pd, $active);
		$ret = check_skill28_ex_array($pa, $ret);
		return $ret;
	}
}

?>
