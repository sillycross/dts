<?php

namespace skill601
{

	function init() 
	{
		define('MOD_SKILL601_INFO','hidden;debuff;');
	}
	
	function acquire601(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill601'));
	}
	
	function lost601(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function get_move_coldtime(&$dest){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(601))&&(check_skill601_state()==1)) return $chprocess($dest)*1.8;
		return $chprocess($dest);
	}
	
	function get_search_coldtime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(601))&&(check_skill601_state()==1)) return $chprocess()*1.8;
		return $chprocess();
	}
	
	function get_itemuse_coldtime(&$item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ((\skillbase\skill_query(601))&&(check_skill601_state()==1)) return $chprocess($item)*3;
		return $chprocess($item);
	}
	
	function check_skill601_state(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill601'));
		if (!\skillbase\skill_query(601)) return 0;
		$e=\skillbase\skill_getvalue(601,'end');
		if ($now<$e) return 1;
		return 0;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(601,$sdata))
		{
			eval(import_module('skill601','skillbase'));
			$skill601_start = (int)\skillbase\skill_getvalue(601,'start'); 
			$skill601_end = (int)\skillbase\skill_getvalue(601,'end'); 
			$z=Array(
				'disappear' => 1,
				'clickable' => 0,
				'hint' => '状态「EMP」<br>行动冷却时间延长',
			);
			if ($now<$skill601_end)
			{
				$z['style']=1;
				$z['totsec']=$skill601_end-$skill601_start;
				$z['nowsec']=$now-$skill601_start;
			}
			else 
			{
				$z['style']=4;
			}
			\bufficons\bufficon_show('img/skill601.gif',$z);
		}
		$chprocess();
	}
}

?>
