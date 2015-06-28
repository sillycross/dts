<?php

namespace metman
{
	////////// MODULE HEADER START ///////////////
	$___MODULE_dependency = 'sys player explore logger';
	$___MODULE_dependency_optional = 'itemmain';	//这是为了在判断explore时候先判metman后判itemfind
	$___MODULE_conflict = '';
	$___MODULE_codelist = 'main.php config/metman.config.php';
	$___MODULE_templatelist = 'meetman meetman_cmd';
	////////// MODULE HEADER END /////////////////
	require __INIT_MODULE__(__NAMESPACE__,__DIR__);
}

?>
