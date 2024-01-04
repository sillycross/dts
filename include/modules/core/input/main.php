<?php

namespace input
{
	global $INPUT_VAR_LIST;
	
	function init() 
	{
		$INPUT_VAR_LIST=Array();
		
		global $___MOD_SRV;
		
		if (isset($_COOKIE))
		{
			$_COOKIE=gstrfilter($_COOKIE);
			foreach ($_COOKIE as $key => $value)
			{
				$key=(string)$key;
				if ($key!='' && (('a'<=$key[0] && $key[0]<='z') || ('A'<=$key[0] && $key[0]<='Z') || $key[0]=='_') && check_alnumudline($key))
				{
					global $$key;
					$$key=$value;
				}
			}
		}
			
		if (isset($_POST))
		{
			$_POST=gstrfilter($_POST);
			foreach ($_POST as $key => $value)
			{
				$key=(string)$key;
				if ($key!='' && (('a'<=$key[0] && $key[0]<='z') || ('A'<=$key[0] && $key[0]<='Z') || $key[0]=='_') && check_alnumudline($key))
				{
					global $$key;
					$$key=$value;
				}
			}
		}
		
		if (isset($_REQUEST))
		{
			$_REQUEST=gstrfilter($_REQUEST);
			foreach ($_REQUEST as $key => $value)
			{
				$key=(string)$key;
				if ($key!='' && (('a'<=$key[0] && $key[0]<='z') || ('A'<=$key[0] && $key[0]<='Z') || $key[0]=='_') && check_alnumudline($key))
				{
					global $$key;
					$$key=$value;
				}
			}
		}
	}
	
	//获得特定变量的数值，避免把input模块所有变量都导入进来
	//出于安全性考虑，以后需要用这个把所有import('input')的地方都替换掉
	//如果需要对返回值修改并能影响input模块的对应值，需要在使用时这么写：$command = &\input\get_var('command');
	//已经废弃，请用modules.func.php定义的get_var_input()函数
	function &get_var($varname){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = & get_var_in_module($varname, 'input');
		return $ret;
	}
}

?>