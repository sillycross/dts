<?php

namespace skill276
{
	function init() 
	{
		define('MOD_SKILL276_INFO','club;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[276] = '瞎选';
	}
	
	function acquire276(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(276,'activated',0,$pa);
	}
	
	function lost276(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked276(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//开局随机选择一个称号
	function post_load_profile_event()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		if (!$club && \skillbase\skill_query(276) && empty(\skillbase\skill_getvalue(276,'activated'))) {
			//玩家第一次进入游戏的界面是不会player_save的，要正常执行，应该在玩家自动刷新界面时生成
			if(!empty($command)){
				eval(import_module('clubbase'));
				$sl = array();
				foreach($clublist as $key=>$val){
					if (!empty($val['probability'])) {
						for($i=0;$i<$val['probability'];$i++){//按概率个数塞称号，这样随机称号的概率会和称号设定的概率保持一致
							$sl[] = $key;
						}
					}
				}
				
				\clubbase\club_acquire(array_randompick($sl));
				\skillbase\skill_setvalue(276,'activated',1);
			}
		}
		$chprocess();
	}
	
}

?>