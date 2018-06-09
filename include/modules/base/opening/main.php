<?php

namespace opening
{
	function init() {}
	
	function in_game_opening_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','opening'));
		if($in_game_opening && in_array($gametype, $in_game_opening_gametype)) return true;
		return false;
	}
	
	//跳过开局剧情的指令
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		
		if ($command == 'skip_op') 
		{
			\skillbase\skill_setvalue(1003,'opening_skip',1);
			return;
		}
		$chprocess();
	}
	
	function show_opening()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','opening','logger'));
		if(in_game_opening_available()){
			if(\skillbase\skill_query(1003) && !\skillbase\skill_getvalue(1003,'opening_skip')) {
				$log .= ' ';
				$main = MOD_OPENING_STORYBOARD_CONTAINER;
				ob_start();
				include template(MOD_OPENING_CMD_SKIP_OP);
				$cmd = ob_get_contents();
				ob_end_clean();
			}
		}
	}
	
	//如果未跳过开局剧情则显示之
	function prepare_initial_response_content()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		show_opening();
	}
	
	function prepare_response_content()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		show_opening();
	}
}

?>