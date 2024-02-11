<?php

namespace event
{
	////////// MODULE HEADER START ///////////////
	$___MODULE_dependency = 'sys player map logger wound gameflow_duel itemmain';
	$___MODULE_dependency_optional = 'trap metman enemy corpse';	//探索时event优先级最高
	$___MODULE_conflict = '';
	$___MODULE_codelist = 'main.php config/event.config.php';
	$___MODULE_templatelist = '';
	////////// MODULE HEADER END /////////////////
	require __INIT_MODULE__(__NAMESPACE__,__DIR__);
}

?>