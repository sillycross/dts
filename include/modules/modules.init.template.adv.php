<?php

namespace _____TEMPLATE_MODULE_NAME_____
{
	if (!defined('IN_GAME')) exit('Access Denied');
	foreach(explode(' ',$___MODULE_codelist) as $___TEMP_key) if ($___TEMP_key!="") include GAME_ROOT._____TEMPLATE_MODULE_PATH_____.$___TEMP_key; 
	
	_____TEMPLATE_PRESET_CODE_____
	
	if (!$___TEMP_DRY_RUN) init();
	
	_____TEMPLATE_ADV_CODE_____
	
}

?>