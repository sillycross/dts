<?php

namespace tactic
{
	////////// MODULE HEADER START ///////////////
	$___MODULE_dependency = 'sys player logger';
	$___MODULE_dependency_optional = 'metman battle weapon trap enemy';
	$___MODULE_conflict = '';
	$___MODULE_codelist = 'main.php config/tactic.config.php';
	$___MODULE_templatelist = 'enemy_tactic tactic_profile tactic_profile_cmd';
	////////// MODULE HEADER END /////////////////
	require __INIT_MODULE__(__NAMESPACE__,__DIR__);
}

?>
