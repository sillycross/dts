<?php

namespace attack
{
	//打击准备
	function strike_prepare(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//打击进行
	function strike(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//打击结束
	function strike_finish(&$pa,&$pd,$active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	//攻击进行
	function attack(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		strike_prepare($pa,$pd,$active);
		strike($pa,$pd,$active);
		strike_finish($pa,$pd,$active);
	}
}

?>
