<?php

namespace skill735
{
	$skill735_mixinfo = array
	(
		0 => array(
			array('class' => 'card', 'stuff' => array('面饼','矿泉水','打火机'),'result' => array('煮好的泡面','HB',75,10,)),
			array('class' => 'card', 'stuff' => array('面饼','矿泉水','打火机','调料包'),'result' => array('煮好的泡面','HB',150,20,)),
			array('class' => 'card', 'stuff' => array('面饼','矿泉水','打火机','蔬菜包'),'result' => array('煮好的泡面','HB',150,20,)),
			array('class' => 'card', 'stuff' => array('面饼','矿泉水','打火机','调料包','蔬菜包'),'result' => array('煮好的泡面','HB',300,20,)),
		),
		1 => array(
			array('class' => 'card', 'stuff' => array('面饼','矿泉水','打火机'),'result' => array('煮好的泡面','PB',75,10,)),
			array('class' => 'card', 'stuff' => array('面饼','矿泉水','打火机','调料包'),'result' => array('煮好的泡面','PB',150,20,)),
			array('class' => 'card', 'stuff' => array('面饼','矿泉水','打火机','蔬菜包'),'result' => array('煮好的泡面','PB',150,20,)),
			array('class' => 'card', 'stuff' => array('面饼','矿泉水','打火机','调料包','蔬菜包'),'result' => array('煮好的泡面','PB',300,20,)),
		)//被G污染了
	);
	
	function init()
	{
		define('MOD_SKILL735_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[735] = '随面';
	}
	
	function acquire735(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost735(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked735(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function get_mixinfo()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		eval(import_module('player'));
		if (\skillbase\skill_query(735,$sdata) && check_unlocked735())
		{
			eval(import_module('skill735'));
			if (\skillbase\skill_getvalue(735,'lvl',$sdata)) $ret = array_merge($ret, $skill735_mixinfo[1]);
			else $ret = array_merge($ret, $skill735_mixinfo[0]);
		}
		return $ret;
	}
	
}

?>