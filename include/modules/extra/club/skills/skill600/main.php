<?php

namespace skill600
{

	function init() 
	{
		define('MOD_SKILL600_INFO','hidden;debuff;');
	}
	
	function acquire600(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill600'));
	}
	
	function lost600(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function chginf($infpos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','sys','player','skill600'));
		if ((\skillbase\skill_query(600))&&(check_skill600_state()==1)) 
		{
			$log .= '你现在不能处理伤口或异常状态！';
			$mode = 'command';
			return;
		}
		$chprocess($infpos);
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','skill600'));
		if ((strpos ( $theitem['itmk'], 'C' ) === 0)&&(\skillbase\skill_query(600))&&(check_skill600_state()==1)) 
		{
			$log .= '你喝了一小口药剂，感觉自己根本就喝不下去！';
			$mode = 'command';
			return;
		}
		$chprocess($theitem);
	}
	
	function upgrade12()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','skill600'));
		if ((\skillbase\skill_query(600))&&(check_skill600_state()==1))
		{
			$log.='你现在不能处理伤口或异常状态！<br>';
			$mode = 'command';
			return;
		}
		$chprocess();
	}
	
	function check_skill600_state(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','skill600'));
		if (!\skillbase\skill_query(600)) return 0;
		$e=\skillbase\skill_getvalue(600,'end');
		if ($now<$e) return 1;
		return 0;
	}
	
	function bufficons_list()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		\player\update_sdata();
		if (\skillbase\skill_query(600,$sdata))
		{
			eval(import_module('skill600','skillbase'));
			$skill600_start = (int)\skillbase\skill_getvalue(600,'start'); 
			$skill600_end = (int)\skillbase\skill_getvalue(600,'end'); 
			$z=Array(
				'disappear' => 1,
				'clickable' => 0,
				'hint' => '状态「衰弱」<br>无法解除异常状态或包扎伤口',
			);
			if ($now<$skill600_end)
			{
				$z['style']=1;
				$z['totsec']=$skill600_end-$skill600_start;
				$z['nowsec']=$now-$skill600_start;
			}
			else 
			{
				$z['style']=4;
			}
			\bufficons\bufficon_show('img/skill600.gif',$z);
		}
		$chprocess();
	}
}

?>
