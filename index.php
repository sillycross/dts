<?php

define('CURSCRIPT', 'index');

require './include/common.inc.php';

$timing = 0;
if($gamestate > 10) {
	$timing = $now - $starttime;
} else {
	if($starttime > $now) {
		$timing = $starttime - $now;
	} else {
		$timing = 0;
	}
}
$adminmsg = file_get_contents('./gamedata/adminmsg.htm') ;
$systemmsg = file_get_contents('./gamedata/systemmsg.htm') ;
include template('index');

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
 <head>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
try {
var pageTracker = _gat._getTracker("UA-9744745-2");
pageTracker._trackPageview();
} catch(err) {}</script>
 </head>
</html>