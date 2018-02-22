<?php

define('CURSCRIPT', 'kujilist');

require './include/common.inc.php';
require './include/user.func.php';

if(!$cuser||!$cpass) { gexit($_ERROR['no_login'],__file__,__line__); }

$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
if(!$db->num_rows($result)) { gexit($_ERROR['login_check'],__file__,__line__); }
$udata = $db->fetch_array($result);
include_once './include/user.func.php';
if(!pass_compare($udata['username'], $cpass, $udata['password'])) { gexit($_ERROR['wrong_pw'], __file__, __line__); }
if($udata['groupid'] <= 0) { gexit($_ERROR['user_ban'], __file__, __line__); }

extract($udata);
$cg=$udata['gold'];

eval(import_module('kujibase'));
	
$kreq=$kujicost;

include template('kujilist');

?> 