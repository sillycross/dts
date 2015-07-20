<?php

namespace miracletrap
{
	////////// MODULE HEADER START ///////////////
	$___MODULE_dependency = 'sys player trap';
	$___MODULE_dependency_optional = 'tactic ex_attr_trap';	//由于奇迹陷阱应当无视一切减伤，任何减低陷阱伤害的模块都应当放在这里并接管
	$___MODULE_conflict = '';
	$___MODULE_codelist = 'main.php';
	$___MODULE_templatelist = '';
	////////// MODULE HEADER END /////////////////
	require __INIT_MODULE__(__NAMESPACE__,__DIR__);
}

?>
