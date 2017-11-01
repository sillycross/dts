<?
if(!defined('IN_GAME')) {
	exit('Access Denied');
}

$timestamp = time();
$errmsg = '';

$dberror = $this->error();
$dberrno = $this->errno();

if($dberrno == 1114) {

?>
<html>
<head>
<title>Max Onlines Reached</title>
</head>
<body bgcolor="#FFFFFF">
<table cellpadding="0" cellspacing="0" border="0" width="600" align="center" height="85%">
  <tr align="center" valign="middle">
    <td>
    <table cellpadding="10" cellspacing="0" border="0" width="80%" align="center" style="font-family: Verdana, Tahoma; color: #666666; font-size: 9px">
    <tr>
      <td valign="middle" align="center" bgcolor="#EBEBEB">
        <br><b style="font-size: 10px">Forum onlines reached the upper limit</b>
        <br><br><br>Sorry, the number of online visitors has reached the upper limit.
        <br>Please wait for someone else going offline or visit us in idle hours.
        <br><br>
      </td>
    </tr>
    </table>
    </td>
  </tr>
</table>
</body>
</html>
<?

	function_exists('dexit') ? dexit() : exit();

} else {

	if($message) {
		$errmsg = "<b>BRA info</b>: $message\n\n";
	}
	if(isset($GLOBALS['_DSESSION']['bra_user'])) {
		$errmsg .= "<b>User</b>: ".htmlspecialchars($GLOBALS['_DSESSION']['bra_user'])."\n";
	}
	$errmsg .= "<b>Time</b>: ".gmdate("Y-n-j g:ia", $timestamp + ($GLOBALS['timeoffset'] * 3600))."\n";
	$errmsg .= "<b>Script</b>: ".$GLOBALS['PHP_SELF']."\n\n";
	if($sql) {
		$errmsg .= "<b>SQL</b>: ".htmlspecialchars($sql)."\n";
	}
	$errmsg .= "<b>Error</b>:  $dberror\n";
	$errmsg .= "<b>Errno.</b>:  $dberrno";

	echo "</table></table></table></table></table>\n";
	echo "<p style=\"font-family: Verdana, Tahoma; font-size: 11px; background: #FFFFFF;\">";
	echo nl2br($errmsg);
	if($GLOBALS['adminemail']) {
		$errlog = array();
		if(@$fp = fopen(GAME_ROOT.'./gamedata/dberror.log', 'r')) {
			while((!feof($fp)) && count($errlog) < 20) {
				$log = explode("\t", fgets($fp, 50));
				if($timestamp - $log[0] < 86400) {
					$errlog[$log[0]] = $log[1];
				}
			}
			fclose($fp);
		}

		if(!in_array($dberrno, $errlog)) {
			$errlog[$timestamp] = $dberrno;
			@$fp = fopen(GAME_ROOT.'./gamedata/dberror.log', 'w');
			@flock($fp, 2);
			foreach(array_unique($errlog) as $dateline => $errno) {
				@fwrite($fp, "$dateline\t$errno");
			}
			@fclose($fp);
			if(function_exists('errorlog')) {
				errorlog('MySQL', basename($GLOBALS['_SERVER']['PHP_SELF'])." : $dberror - ".cutstr($sql, 120), 0);
			}
			
			if($GLOBALS['dbreport']) {
				echo "<br><br>An error report has been dispatched to our administrator.";
				@sendmail($GLOBALS['adminemail'], '[bra!] MySQL Error Report',
						"There seems to have been a problem with the database of your bra game\n\n".
						strip_tags($errmsg)."\n\n".
						"Please check-up your MySQL server and forum scripts, similar errors will not be reported again in recent 24 hours\n".
						"If you have troubles in solving this problem, please visit loongyou Community http://www.loongyou.com");
			}

		} else {
			echo '<br><br>Similar error report has beed dispatched to administrator before.';
		}

	}
	echo '</p>';

	function_exists('gexit') ? gexit() : exit();

}

/* End of file db_mysqli_error.inc.php */
/* Location: /include/db/db_mysqli_error.inc.php */