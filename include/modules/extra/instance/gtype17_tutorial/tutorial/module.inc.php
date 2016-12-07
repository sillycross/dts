<?php

namespace tutorial
{
	////////// MODULE HEADER START ///////////////
	$___MODULE_dependency = 'sys player map logger clubbase itemmain itemshop metman enemy addnpc explore skill1000 battle weapon edible wound corpse ex_dmg_def attack';
	$___MODULE_dependency_optional = 'weather';
	$___MODULE_conflict = '';
	$___MODULE_codelist = 'main.php tutorial.config.php tutorialnpc.config.php';
	$___MODULE_templatelist = 'tutorial tutorial_itemfind tutorial_battlecmd tutorial_battleresult tutorial_corpse tutorial_shop tutorial_sp_shop';
	////////// MODULE HEADER END /////////////////
	require __INIT_MODULE__(__NAMESPACE__,__DIR__);
}

?>