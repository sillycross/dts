<?php

namespace trap
{
	////////// MODULE HEADER START ///////////////
	$___MODULE_dependency = 'sys map itemmain logger player lvlctl';
	$___MODULE_dependency_optional = 'explore metman npc';	//metman的可选依赖是为了保证在explore时先判trap再判metman
	$___MODULE_conflict = '';
	$___MODULE_codelist = 'main.php config/trap.config.php';
	$___MODULE_templatelist = '';
	////////// MODULE HEADER END /////////////////
	require __INIT_MODULE__(__NAMESPACE__,__DIR__);
}

?>
