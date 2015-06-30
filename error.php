<?php

define('NO_MOD_LOAD', TRUE);	
define('NO_SYS_UPDATE', TRUE);
define('TPLDIR','./templates/default');
define('TEMPLATEID','1');

require './include/common.inc.php';
		
$message = $_POST['errormsg'];

include template('error');

?>

