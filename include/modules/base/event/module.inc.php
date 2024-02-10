<?php

namespace event
{
	////////// MODULE HEADER START ///////////////
	$___MODULE_dependency = 'sys player map logger wound gameflow_duel';
	$___MODULE_dependency_optional = 'trap metman itemmain enemy corpse ex_dmg_att skill1003';	//探索时event优先级最高
	$___MODULE_conflict = '';
	$___MODULE_codelist = 'main.php config/event.config.php';
	$___MODULE_templatelist = '';
	////////// MODULE HEADER END /////////////////
	require __INIT_MODULE__(__NAMESPACE__,__DIR__);
}

?>