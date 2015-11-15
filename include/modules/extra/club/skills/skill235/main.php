<?php

namespace skill235
{
	function init() 
	{
		define('MOD_SKILL235_INFO','club;active;locked;');
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
	
	function skill_onload_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
	}
	
	function skill_onsave_event(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess($pa);
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
			if ($skillpoint>0){
				$skillpoint--;
				\radar\newradar(2);
			}else{
				$log .= '<span class="red">你的技能点不足，不能发动技能！</span><br />';
				$mode = 'command';
				return;
			}
		}else{
			$log .= '<span class="red">你没有这个技能！</span><br />';
			$mode = 'command';
			return;
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','input'));
	
		if ($mode == 'special' && $command == 'skill235_special' && $subcmd=='wscan') 
		{
			wscan();
			return;
		}
			
		$chprocess();
	}
	
}

?>
