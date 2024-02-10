<?php

namespace skill738
{
	function init()
	{
		define('MOD_SKILL738_INFO','card;upgrade;');
		eval(import_module('clubbase'));
		$clubskillname[738] = '换挡';
	}
	
	function acquire738(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(738,'choice','2',$pa);
	}
	
	function lost738(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(738,'choice',$pa);
	}
	
	function check_unlocked738(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function upgrade738()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		if (!\skillbase\skill_query(738, $sdata))
		{
			$log .= '你没有这个技能！<br>';
			return;
		}
		$val = (int)get_var_input('skillpara1');
		if ($val < 1 || $val > 3)
		{
			$log .= '参数不合法。<br>';
			return;
		}
		\skillbase\skill_setvalue(738,'choice',$val);
		$log .= '设置成功。<br>';
	}
	
	function skill738_get_sp_cost_change(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if ($pa['club'] == 17) return 0;
		$r = 0;
		$skill738_choice = (int)\skillbase\skill_getvalue(738,'choice',$pa);
		if ($skill738_choice == 1)
		{
			$r = -floor(max($pa['msp']-400, 0) / 50);
		}
		elseif ($skill738_choice == 3)
		{
			$r = floor($pa['msp'] / 50);
		}
		return $r;
	}
	
	function calculate_move_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess();
		eval(import_module('player'));
		if (\skillbase\skill_query(738, $sdata)) 
		{
			$r = max(1, $r + skill738_get_sp_cost_change($sdata));
		}
		return $r;
	}
	
	function calculate_search_sp_cost()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$r = $chprocess();
		eval(import_module('player'));
		if (\skillbase\skill_query(738, $sdata)) 
		{
			$r = max(1, $r + skill738_get_sp_cost_change($sdata));
		}
		return $r;
	}
	
	function get_move_coldtime(&$dest){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(738, $sdata))
		{
			$spcostup = skill738_get_sp_cost_change($sdata);
			if ($spcostup > 0)
			{
				$cddownrate = max(0.1, 1-$spcostup/100);
				return $chprocess($dest)*$cddownrate;
			}
		}
		return $chprocess($dest);
	}
	
	function get_search_coldtime(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(738, $sdata))
		{
			$spcostup = skill738_get_sp_cost_change($sdata);
			if ($spcostup > 0)
			{
				$cddownrate = max(0.1, 1-$spcostup/100);
				return $chprocess()*$cddownrate;
			}
		}
		return $chprocess();
	}
	
	function get_itemuse_coldtime(&$item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(738, $sdata))
		{
			$spcostup = skill738_get_sp_cost_change($sdata);
			if ($spcostup > 0)
			{
				$cddownrate = max(0.1, 1-$spcostup/100);
				return $chprocess($item)*$cddownrate;
			}
		}
		return $chprocess($item);
	}
	
}

?>