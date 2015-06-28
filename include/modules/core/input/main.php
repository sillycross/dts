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
}

?>
