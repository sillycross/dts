<?php

//记录得到的金钱数的技能

namespace skill1003
{
	$skill1003_o_money = 0;
	
	function init() 
	{
		define('MOD_SKILL1003_INFO','hidden;');
	}
	
	function acquire1003(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(1003,'money_got',0,$pa);
		\skillbase\skill_setvalue(1003,'qiegao_got',0,$pa);
	}
	
	function lost1003(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function pre_act(){//每次行动记录得到的金钱
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skill1003'));
		if(\skillbase\skill_query(1003)){
			$skill1003_o_money = $money;
		}
		$chprocess();
	}
	
	function post_act(){//每次行动记录得到的金钱
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','skill1003'));
		$chprocess();
		if(\skillbase\skill_query(1003) && $money > $skill1003_o_money){
			
			$money_got = $money - $skill1003_o_money;
			$money_got += \skillbase\skill_getvalue(1003,'money_got');	
			\skillbase\skill_setvalue(1003,'money_got',$money_got);	
		}
	}
	
	//战斗获得切糕改为先暂存在这个技能里，战斗结束才结算
	function battle_get_qiegao_update($qiegaogain,&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\skillbase\skill_query(1003, $pa)){
			$nowqiegao = \skillbase\skill_getvalue(1003,'qiegao_got', $pa);	
			\skillbase\skill_setvalue(1003,'qiegao_got',$nowqiegao + $qiegaogain, $pa);
		}else{
			$chprocess($qiegaogain,$pa);
		}
	}
	
	//战斗结束时的结算
	function gameover_get_gold_up($data, $winner = '',$winmode = 0)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($data, $winner, $winmode);
		$tmp_data = $data;
		list($tmp_data['acquired_list'], $tmp_data['parameter_list']) = \skillbase\skillbase_load_process($data['nskill'], $data['nskillpara']);
		if(\skillbase\skill_query(1003, $tmp_data)) {
			$ret += \skillbase\skill_getvalue(1003,'qiegao_got', $tmp_data);	
		}
		return $ret;
	}
}

?>