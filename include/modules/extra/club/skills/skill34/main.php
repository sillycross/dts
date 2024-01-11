<?php

namespace skill34
{
	function init() 
	{
		define('MOD_SKILL34_INFO','club;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[34] = '百战';
	}
	
	function acquire34(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(34,'choice','',$pa);
	}
	
	function lost34(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(34,'choice',$pa);
	}
	
	function get_unlock34_progress(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$sum = 0;
		eval(import_module('skill32'));
		$clv = (int)\skillbase\skill_getvalue(32,'lvl',$pa);
		for ($i=0; $i<$clv; $i++) $sum+=$upgradecost[$i];
		eval(import_module('skill35'));
		$clv = (int)\skillbase\skill_getvalue(35,'lvl',$pa);
		for ($i=0; $i<$clv; $i++) $sum+=$upgradecost[$i];
		return $sum;
	}
	
	function check_unlocked34(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		//return (get_unlock34_progress($pa)>=15);
		return $pa['lvl']>=18;
	}
	
	function get_avaliable_attr()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('ex_phy_def'));
		$arr = array_values($def_kind);
		eval(import_module('ex_dmg_def'));
		$arr = array_merge($arr,array_values($def_kind));
		$arr[] = 'R';
		$arr = array_unique($arr);
		return $arr;
	}
	
	function upgrade34()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(34) || !check_unlocked34($sdata))
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		$val = get_var_input('skillpara1');
		if (!in_array($val,get_avaliable_attr()))
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(34,'choice',$val);
		$log.='设置成功。<br>';
	}
	
	function get_ex_def_array_core(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\skillbase\skill_query(34,$pd) || !check_unlocked34($pd)) return $chprocess($pa, $pd, $active);
		$choice = \skillbase\skill_getvalue(34,'choice',$pd);
		$ret = $chprocess($pa, $pd, $active);
		if ($choice!='') array_push($ret,$choice);
		return $ret;
	}
}

?>
