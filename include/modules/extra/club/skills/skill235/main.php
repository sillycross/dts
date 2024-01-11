<?php

namespace skill235
{
	function init() 
	{
		define('MOD_SKILL235_INFO','club;active;');
		eval(import_module('clubbase'));
		$clubskillname[235] = '探测';
	}
	
	function acquire235(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost235(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked235(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
		
	function wscan(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','skill235'));
		if(\skillbase\skill_query(235)){
			$lv=(int)\skillbase\skill_getvalue(235,'lvl');
			if ($skillpoint>0 || $lv==1){
				if ($lv!=1) $skillpoint--;
				\radar\use_radar(2);
			}else{
				$log .= '<span class="red b">你的技能点不足，不能发动技能！</span><br />';
				$mode = 'command';
				return;
			}
		}else{
			$log .= '<span class="red b">你没有这个技能！</span><br />';
			$mode = 'command';
			return;
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill235_special' && get_var_input('subcmd')=='wscan') 
		{
			wscan();
			return;
		}
			
		$chprocess();
	}
	
}

?>
