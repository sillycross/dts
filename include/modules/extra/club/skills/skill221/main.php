<?php

namespace skill221
{
	function init() 
	{
		define('MOD_SKILL221_INFO','club;');
		eval(import_module('clubbase'));
		$clubskillname[221] = '衰弱';
	}
	
	function acquire221(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost221(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked221(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return $pa['lvl']>=7;
	}
	
	function get_skill221_lasttime(&$pa,&$pd,&$active){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 10;
	}

	function strike_finish(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['is_hit'] && \skillbase\skill_query(221,$pa))
		{
			eval(import_module('logger','skill221','skill600','sys'));
			$var_221=get_skill221_lasttime($pa,$pd,$active);//持续时间
			if (!\skillbase\skill_query(600,$pd)){
				\skillbase\skill_acquire(600,$pd);
				$var_221_2=$now;
			}else{
				$var_221_2=\skillbase\skill_getvalue(600,'end',$pd);
				//if ($var_221_2<$now) $var_221_2=$now;
				$var_221_2=$now;//现在被黑衣连续命中不会叠加衰弱时间
			}
			\skillbase\skill_setvalue(600,'start',$var_221_2,$pd);
			\skillbase\skill_setvalue(600,'end',$var_221_2+$var_221,$pd);
		}
		$chprocess($pa,$pd,$active);
	}
}

?>
