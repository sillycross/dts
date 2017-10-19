<?php

namespace pose
{
	////////// MODULE HEADER START ///////////////
	$___MODULE_dependency = 'sys player logger';
	$___MODULE_dependency_optional = 'trap metman weapon battle itemmain enemy rest edible';
	$___MODULE_conflict = '';
	$___MODULE_codelist = 'main.php config/pose.config.php';
	$___MODULE_templatelist = 'enemy_pose pose_profile pose_profile_cmd';
	////////// MODULE HEADER END /////////////////
	require __INIT_MODULE__(__NAMESPACE__,__DIR__);
}

?>
