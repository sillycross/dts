<?php

define('CURSCRIPT', 'user_profile');

require './include/common.inc.php';

if ($cuser=='') { gexit('请登录'); die(); }

\achievement_base\show_achievements();

?>
