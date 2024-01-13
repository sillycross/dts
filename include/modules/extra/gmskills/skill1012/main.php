<?php

namespace skill1012
{
	function init() 
	{
		define('MOD_SKILL1012_INFO','active;unique;');
		eval(import_module('clubbase'));
		$clubskillname[1012] = '寻真';
	}
	
	function acquire1012(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost1012(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked1012(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill1012_sub_page()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		include template(MOD_SKILL1012_SUB_PAGE);
		$cmd=ob_get_contents();
		ob_clean();
	}
	
	function skill1012_show_var($show_namespace, $show_varname)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$show_namespace = str_replace('$', '', $show_namespace);
		$show_varname = str_replace('$', '', $show_varname);
		if(empty($show_varname)) {
			$log .= '变量名错误！<br>';
			$mode = 'command';$command = '';
			return;
		}
		if(!empty($show_namespace)) {
			eval(import_module($show_namespace));
			$log .= $show_namespace . '命名空间下的';
		}
		if(!isset(${$show_varname})) $log .= '变量<span class="yellow b">$'.$show_varname.'</span>没有定义。<br>';
		else $log .= '变量<span class="yellow b">$'.$show_varname.'</span>的值是：'. str_replace('style="color: #000000"', '', highlight_string(var_export(${$show_varname}, 1), 1)).'。<br>';
		return;
		
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill1012_special') 
		{
			if (!\skillbase\skill_query(1012)) 
			{
				$log.='你没有这个技能。';
				$mode = 'command';$command = '';
				return;
			}
			$subcmd = get_var_input('subcmd');
			if(!isset($subcmd)){
				$mode = 'command';$command = '';
				return;
			}elseif($subcmd == 'sub_page') {
				skill1012_sub_page();
				return;
			}elseif($subcmd == 'show_var'){
				$show_ns = get_var_input('show_ns');
				$show_vn = get_var_input('show_vn');
				skill1012_show_var($show_ns, $show_vn);
				return;
			}else{
				$log .= '命令参数错误。';
				$mode = 'command';$command = '';
				return;
			}
		}
		$chprocess();
	}
	
}

?>