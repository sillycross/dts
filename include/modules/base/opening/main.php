<?php

namespace opening
{
	function init() {}
	
	function opening_by_shootings_available()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','opening'));
		if($opening_by_shootings && in_array($gametype, $opening_by_shootings_gametype)) return true;
		return false;
	}
	
	//跳过开局剧情的指令
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		//只要不是自动刷新，就跳过
		if($hp > 0 && \skillbase\skill_query(1003) && !\skillbase\skill_getvalue(1003,'opening_skip')) {
			if ($command != 'enter') 
			{
				\skillbase\skill_setvalue(1003,'opening_skip',1);
			}elseif(opening_by_shootings_available()){
				eval(import_module('logger'));
				$log .= '<br><span class="yellow b">点击以下任意按钮皆可跳过开场剧情。</span><br>';
			}
		}
		
		$chprocess();
	}
	
//	function init_playerdata()
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$chprocess();
//		eval(import_module('sys','player','logger'));
//		if ($hp > 0 && opening_by_shootings_available() && \skillbase\skill_query(1003) && !\skillbase\skill_getvalue(1003,'opening_skip')) 
//		{
//			$log .= '<br><span class="yellow b">点击以下任意按钮皆可跳过开场剧情。</span><br>';
//		}
//	}
	
	function show_opening()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','opening','logger'));
		if($hp > 0 && opening_by_shootings_available() && \skillbase\skill_query(1003) && !\skillbase\skill_getvalue(1003,'opening_skip')) {
			$log .= ' ';
			$main = MOD_OPENING_STORYBOARD_CONTAINER;
//			ob_start();
//			include template(MOD_OPENING_CMD_SKIP_OP);
//			$cmd = ob_get_contents();
//			ob_end_clean();
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