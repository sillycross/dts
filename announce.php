<?php

define('CURSCRIPT', 'announce');
require './include/common.inc.php';
include './include/roommng.config.php';

eval(import_module('cardbase'));

include template('announce');

?>

