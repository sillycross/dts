<?php

namespace skill544
{
	function init() 
	{
		define('MOD_SKILL544_INFO','card;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[544] = '基础';
	}
	
	function acquire544(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(544,'lvl','0',$pa);
		\skillbase\skill_setvalue(544,'activated','0',$pa);
	}
	
	function lost544(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(544,'lvl',$pa);
	}
	
	function check_unlocked544(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}

	//入场后调整gamevars
	function post_load_profile_event(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (\skillbase\skill_query(544) && empty(\skillbase\skill_getvalue(544,'activated')))
		{
			eval(import_module('sys'));
			$gamevars['skill544_activated'] = 1;
			save_gameinfo();
			\skillbase\skill_setvalue(544,'activated','1');
		}
		$chprocess();
	}

	function get_club_choice_array()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','clubbase'));
		if (!empty($gamevars['skill544_activated'])) {
			$max_club_choice_num = Array(4,0);
		}
		return $chprocess();
	}

	//调整特殊称号的概率
	function club_choice_probability_process($clublist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if (!empty($gamevars['skill544_activated'])) {
			foreach($clublist as $i => &$v) {
				if(!empty($v['type'])) $v['probability'] = 0;
			}
			return $clublist;
		}else return $chprocess($clublist);
	}
}

?>