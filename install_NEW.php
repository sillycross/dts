<?php
error_reporting(E_ERROR | E_PARSE);
//set_magic_quotes_runtime(0);
//ini_set('date.timezone','Asia/Shanghai');

define('IN_GAME', TRUE);
define('GAME_ROOT', '');

if(PHP_VERSION < '7.0.0') {
	exit('PHP version must >= 7.0.0!');
}

$action = $_POST['action'] ? $_POST['action'] : $_GET['action'];
$language = $_POST['language'] ? $_POST['language'] : $_GET['language'];

@set_time_limit(1000);
$server_config =  './include/modules/core/sys/config/server.config.php';
$server_config_sample =  './include/modules/core/sys/config/server.config.sample.php';
if (!file_exists($server_config)){
	if(!file_exists($server_config_sample))	exit('"server.config.sample.php" doesn\'t exist.');
	else {
		if(!copy($server_config_sample,$server_config)) exit('Cannot create "server.config.php".');
	}
}

$modulemng_config =  './include/modulemng/modulemng.config.php';
$modulemng_config_sample =  './include/modulemng/modulemng.config.sample.php';
if (!file_exists($modulemng_config)){
	if(!file_exists($modulemng_config_sample))	exit('"modulemng.config.sample.php" doesn\'t exist.');
	else {
		if(!copy($modulemng_config_sample,$modulemng_config)) exit('Cannot create "modulemng.config.php".');
	}
}

$system_config =  './include/modules/core/sys/config/system.config.php';
$system_config_sample =  './include/modules/core/sys/config/system.config.sample.php';
if (!file_exists($system_config)){
	if(!file_exists($system_config_sample))	exit('"system.config.sample.php" doesn\'t exist.');
	else {
		if(!copy($system_config_sample,$system_config)) exit('Cannot create "system.config.php".');
	}
}

$game_config =  './include/modules/core/sys/config/game.config.php';
$game_config_sample =  './include/modules/core/sys/config/game.config.sample.php';
if (!file_exists($game_config)){
	if(!file_exists($game_config_sample))	exit('"game.config.sample.php" doesn\'t exist.');
	else {
		if(!copy($game_config_sample,$game_config)) exit('Cannot create "game.config.php".');
	}
}

//$game_config = './include/modules/core/sys/config/game.config.php';
//if(!file_exists($game_config))	exit('"game.config.php" doesn\'t exist.');

$mdcontents = file_get_contents($modulemng_config);
$mdcontents = preg_replace("/[$]___MOD_CODE_ADV1\s*\=\s*-?[0-9]+;/is", "\$___MOD_CODE_ADV1 = 0;", $mdcontents);
$mdcontents = preg_replace("/[$]___MOD_CODE_ADV2\s*\=\s*-?[0-9]+;/is", "\$___MOD_CODE_ADV2 = 0;", $mdcontents);
$mdcontents = preg_replace("/[$]___MOD_CODE_ADV3\s*\=\s*-?[0-9]+;/is", "\$___MOD_CODE_ADV3 = 0;", $mdcontents);
$mdcontents = preg_replace("/[$]___MOD_SRV\s*\=\s*-?[0-9]+;/is", "\$___MOD_SRV = 0;", $mdcontents);
file_put_contents($modulemng_config,$mdcontents);

@include $server_config;

switch($language) {
	case 'simplified_chinese_gbk':
		$dbcharset = $charset = 'gbk';
		break;
	case 'simplified_chinese_utf8':
		$dbcharset = 'utf8';
		$charset = 'utf-8';
		break;
	case 'traditional_chinese_big5':
		$dbcharset = $charset = 'big5';
		break;
	case 'traditional_chinese_utf8':
		$dbcharset = 'utf8';
		$charset = 'utf-8';
		break;
	case 'english':
		$dbcharset = 'utf8';
		$charset = 'utf-8';
		break;
	default:
		$language = '';
		$dbcharset = 'utf8';
		$charset = 'utf-8';
}

if($language) {

	$languagefile = './install/'.$language.'.lang.php';
	$sql_install_file = './install/bra.install.sql';
	
	if(!is_readable($languagefile) || !is_readable($sql_install_file)) {
		exit('Please upload ./install and all its files completely.');
	}
	
	$sql_files = './gamedata/sql';
	if(!is_readable($sql_files)) {
		exit('Please upload ./gamedata/sql and all its files completely.');
	}

	require_once $languagefile;

	$lockfile = './gamedata/install.lock';
	if(file_exists($lockfile)) {
		exit($lang['lock_exists']);
	}

	//$fp = fopen($sql_install_file, 'rb');
	//$sql = fread($fp, 2048000);
	//fclose($fp);
	$sql = file_get_contents($sql_install_file);
	$sql .= "\n".file_get_contents($sql_files.'/players.sql');
	$sql .= "\n".file_get_contents($sql_files.'/shopitem.sql');
	$sql .= "\n".file_get_contents($sql_files.'/reset.sql');
}

header('Content-Type: text/html; charset='.$charset);
$version = '3.0.0';

?>
<html>
<head>
<title>Bra Installation Wizard</title>
<style>
A:visited	{COLOR: #3A4273; TEXT-DECORATION: none}
A:link		{COLOR: #3A4273; TEXT-DECORATION: none}
A:hover		{COLOR: #3A4273; TEXT-DECORATION: underline}
body,table,td	{COLOR: #3A4273; FONT-FAMILY: Tahoma, Verdana, Arial; FONT-SIZE: 12px; LINE-HEIGHT: 20px; scrollbar-base-color: #E3E3EA; scrollbar-arrow-color: #5C5C8D}
input		{COLOR: #085878; FONT-FAMILY: Tahoma, Verdana, Arial; FONT-SIZE: 12px; background-color: #3A4273; color: #FFFFFF; scrollbar-base-color: #E3E3EA; scrollbar-arrow-color: #5C5C8D}
.install	{FONT-FAMILY: Arial, Verdana; FONT-SIZE: 20px; FONT-WEIGHT: bold; COLOR: #000000}
</style>
</head>
<?php

if(!in_array($language, array('simplified_chinese_gbk', 'simplified_chinese_utf8', 'traditional_chinese_big5', 'traditional_chinese_utf8', 'english'))) {

?>
<body bgcolor="#FFFFFF">
<table cellpadding="0" cellspacing="0" border="0" width="100%" height="100%" align="center">
<tr><td valign="middle" align="center">

<table cellpadding="0" cellspacing="0" border="0" align="center">
  <tr align="center" valign="middle">
    <td bgcolor="#000000">
    <table cellpadding="10" cellspacing="1" border="0" width="500" height="100%" align="center">
    <tr>
      <td valign="middle" align="center" bgcolor="#EBEBEB">
        <!-- final utf8/orig <br><b>Bra Installation Wizard</b><br><br>Please choose your prefered language<br><br><center><a href="?language=simplified_chinese_gbk">[&#31616;&#20307;&#20013;&#25991; GBK]</a> &nbsp; <a href="?language=simplified_chinese_utf8">[&#31616;&#20307;&#20013;&#25991; UTF-8]</a><br><a href="?language=traditional_chinese_big5">[&#32321;&#39636;&#20013;&#25991; BIG5]</a> &nbsp; <a href="?language=traditional_chinese_utf8">[&#32321;&#39636;&#20013;&#25991; UTF-8]</a><br><a href="?language=english">[English]</a><br><br> -->
        <br><b>Bra Installation Wizard</b><br><br>Please choose your prefered language<br><br><center> &nbsp; <a href="?language=simplified_chinese_utf8">[&#31616;&#20307;&#20013;&#25991;]</a><br> &nbsp; <a href="?language=traditional_chinese_utf8">[&#32321;&#39636;&#20013;&#25991;]</a><br><br>
      </td>
    </tr>
    </table>
    </td>
  </tr>
</table>

</td></td></table>
</body>
</html>
<?php

	exit();

} else {
?>
<body bgcolor="#3A4273" text="#000000">
<table width="95%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF" align="center">
  <tr>
    <td>
      <table width="98%" border="0" cellspacing="0" cellpadding="0" align="center">
        <tr>
          <td class="install" height="30" valign="bottom"><font color="#FF0000">&gt;&gt;</font>
            <?php echo $lang['install_wizard']; ?></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
            <b><?php echo $lang['welcome']; ?></b>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
<?php
}
if(!$action) {

	$discuz_license = str_replace('  ', '&nbsp; ', $lang['license']);

?>
        <tr>
          <td><b><?php echo $lang['current_process']; ?> </b><font color="#0000EE"><?php echo $lang['show_license']; ?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['agreement']; ?></font></b></td>
        </tr>
        <tr>
          <td><br>
            <table width="90%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr>
                <td bgcolor="#E3E3EA">
                  <table width="99%" cellspacing="1" border="0" align="center">
                    <tr>
                      <td>
                        <?php echo $discuz_license; ?>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
          </td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?php echo $language; ?>">
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="<?php echo $lang['agreement_yes']; ?>" style="height: 25">&nbsp;
              <input type="button" name="exit" value="<?php echo $lang['agreement_no']; ?>" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?php
} elseif($action == 'config') {

	$exist_error = FALSE;
	$write_error = FALSE;
	if(file_exists($server_config)) {
		$fileexists = result(1, 0);
	} else {
		$fileexists = result(0, 0);
		$exist_error = TRUE;
		$config_info = $lang['config_nonexistence'];
	}
	if(is_writeable($server_config)) {
		$filewriteable = result(1, 0);
		$config_info = $lang['config_comment'];
	} else {
		$filewriteable = result(0, 0);
		$write_error = TRUE;
		$config_info = $lang['config_unwriteable'];
	}

?>
        <tr>
          <td><b><?php echo $lang['current_process']; ?> </b><font color="#0000EE"><?php echo $lang['configure']; ?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['check_config']; ?></font></b></td>
        </tr>
        <tr>
          <td>config.inc.php <?php echo $lang['check_existence']; ?> <?php echo $fileexists; ?></td>
        </tr>
        <tr>
          <td>config.inc.php <?php echo $lang['check_writeable']; ?> <?php echo $filewriteable; ?></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['edit_config']; ?></font></b></td>
        </tr>
        <tr>
          <td align="center"><br><?php echo $config_info; ?></td>
        </tr>
<?php

	if(!$exist_error) {

		if(!$write_error) {

			$dbhost = 'localhost';
			$dbuser = 'dbuser';
			$dbpw = 'dbpw';
			$dbname = 'dbname';
			$tablepre = 'bra_';
			$authkey = 'bra';
			$moveut = 0;
			$gamefounder = 'admin';

			@include $server_config;
			$tablepre = $gtablepre;
			$now = time();
			list($nowsec,$nowmin,$nowhour,$nowday,$nowmonth,$nowyear,$nowwday,$nowyday,$nowisdst) = localtime($now);
			$nowmonth++;
			$nowyear += 1900;


?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?php echo $language; ?>">
              <table width="650" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr bgcolor="#3A4273">
                  <td align="center" width="20%" style="color: #FFFFFF"><?php echo $lang['variable']; ?></td>
                  <td align="center" width="40%" style="color: #FFFFFF"><?php echo $lang['value']; ?></td>
                  <td align="center" width="40%" style="color: #FFFFFF"><?php echo $lang['comment']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['gamefounder']; ?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="gamefounder" value="<?php echo $gamefounder; ?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['gamefounder_comment']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;<?php echo $lang['dbhost']; ?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbhost" value="<?php echo $dbhost; ?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['dbhost_comment']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['dbuser']; ?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="dbuser" value="<?php echo $dbuser; ?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['dbuser_comment']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['dbpw']; ?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="password" name="dbpw" value="<?php echo $dbpw; ?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['dbpw_comment']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['moveut']; ?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="moveut" value="<?php echo $moveut; ?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['moveut_comment']; ?><br><?php echo $nowyear; ?><?php echo $lang['year']; ?><?php echo $nowmonth; ?><?php echo $lang['month']; ?><?php echo $nowday; ?><?php echo $lang['day']; ?><?php echo $nowhour; ?><?php echo $lang['hour']; ?><?php echo $nowmin; ?><?php echo $lang['min']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA" style="color: #FF0000">&nbsp;<?php echo $lang['tablepre']; ?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="tablepre" value="<?php echo $tablepre; ?>" size="30" onClick="javascript: alert('<?php echo $lang['install_note']; ?>:\n\n<?php echo $lang['tablepre_prompt']; ?>');"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['tablepre_comment']; ?></td>
                </tr>
				<!--
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['authkey']; ?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="authkey" value="<?php echo $authkey; ?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['authkey_comment']; ?></td>
                </tr>
				-->
				<input type="hidden" name="authkey" value="<?php echo $authkey; ?>"> 
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['bbsurl']; ?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="bbsurl" value="<?php echo $bbsurl; ?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['bbsurl_comment']; ?></td>
                </tr>
                <tr>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['gameurl']; ?></td>
                  <td bgcolor="#EEEEF6" align="center"><input type="text" name="gameurl" value="<?php echo $gameurl; ?>" size="30"></td>
                  <td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['gameurl_comment']; ?></td>
                </tr>
              </table>
              <br>
              <input type="hidden" name="action" value="dbselect">
              <input type="hidden" name="saveconfig" value="1">
              <input type="submit" name="submit" value="<?php echo $lang['save_config']; ?>" style="height: 25">
              <input type="button" name="exit" value="<?php echo $lang['exit']; ?>" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?php

		} else {

			@include $server_config;
			$now = time();
			list($nowsec,$nowmin,$nowhour,$nowday,$nowmonth,$nowyear,$nowwday,$nowyday,$nowisdst) = localtime($now);
			$nowmonth++;
			$nowyear += 1900;

?>
        <tr>
          <td>
            <br>
            <table width="60%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center" style="color: #FFFFFF"><?php echo $lang['variable']; ?></td>
                <td align="center" style="color: #FFFFFF"><?php echo $lang['value']; ?></td>
                <td align="center" style="color: #FFFFFF"><?php echo $lang['comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$gamefounder</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $gamefounder; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['gamefounder_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbhost</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbhost; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['dbhost_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbuser</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbuser; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['dbuser_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbpw</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbpw; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['dbpw_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$dbname</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbname; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['dbname_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$moveut</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $moveut; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['moveut_comment']; ?><br><?php echo $nowyear; ?><?php echo $lang['year']; ?><?php echo $nowmonth; ?><?php echo $lang['month']; ?><?php echo $nowday; ?><?php echo $lang['day']; ?><?php echo $nowhour; ?><?php echo $lang['hour']; ?><?php echo $nowmin; ?><?php echo $lang['min']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$tablepre</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $tablepre; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['tablepre_comment']; ?></td>
              </tr>
			  <!--
              <tr>
                <td bgcolor="#E3E3EA" align="center">$authkey</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $authkey; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['authkey_comment']; ?></td>
              </tr>
			  -->
			<input type="hidden" name="authkey" value="<?php echo $authkey; ?>"> 
              <tr>
                <td bgcolor="#E3E3EA" align="center">$bbsurl</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $bbsurl; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['bbsurl_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">$gameurl</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $gameurl; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['gameurl_comment']; ?></td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td align="center">
            <form method="post" action="?language=<?php echo $language; ?>">
              <input type="hidden" name="action" value="environment">
              <input type="submit" name="submit" value="<?php echo $lang['confirm_config']; ?>" style="height: 25">
              <input type="button" name="exit" value="<?php echo $lang['refresh_config']; ?>" style="height: 25" onclick="javascript: window.location=('?language=<?php echo $language; ?>&action=config');">
            </form>
          </td>
        </tr>
<?php

		}

	} else {

?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?php echo $language; ?>">
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="<?php echo $lang['recheck_config']; ?>" style="height: 25">
              <input type="button" name="exit" value="<?php echo $lang['exit']; ?>" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?php
	}
} elseif($action == 'dbselect') {

	$exist_error = FALSE;
	$write_error = FALSE;
	if(file_exists($server_config)) {
		$fileexists = result(1, 0);
	} else {
		$fileexists = result(0, 0);
		$exist_error = TRUE;
		$config_info = $lang['config_nonexistence'];
	}
	if(is_writeable($server_config)) {
		$filewriteable = result(1, 0);
		$config_info = $lang['choice_or_new_db'];
	} else {
		$filewriteable = result(0, 0);
		$write_error = TRUE;
		$config_info = $lang['config_unwriteable'];
	}
?>
        <tr>
          <td><b><?php echo $lang['current_process']; ?> </b><font color="#0000EE"><?php echo $lang['game_db_conf']; ?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['check_config']; ?></font></b></td>
        </tr>
        <tr>
          <td>config.inc.php <?php echo $lang['check_existence']; ?> <?php echo $fileexists; ?></td>
        </tr>
        <tr>
          <td>config.inc.php <?php echo $lang['check_writeable']; ?> <?php echo $filewriteable; ?></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"><?php echo $lang['show_and_edit_db_conf']; ?></font></b></td>
        </tr>
        <tr>
          <td align="center"><br><?php echo $config_info; ?></td>
        </tr>
         <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?php echo $language; ?>">
            <table width="40%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center" colspan="3" style="color: #FFFFFF"><?php echo $lang['db_set']; ?></td>
              </tr>
<?php
	if(!$exist_error) {
		
	

		if(!$write_error) {
			if(function_exists('mysql_connect')) $default_database = 'mysql';
			elseif(function_exists('mysqli_connect')) $default_database = 'mysqli';
			if(!$database) $database = $default_database;
			if($database == 'mysql' && !function_exists('mysql_connect')) $database = $default_database;

			if($_POST['saveconfig'] && is_writeable($server_config)) {
				$gamefounder = setconfig($_POST['gamefounder']);
				$dbhost = setconfig($_POST['dbhost']);
				$dbuser = setconfig($_POST['dbuser']);
				$dbpw = setconfig($_POST['dbpw']);
				$tablepre = setconfig($_POST['tablepre']);
				$authkey = setconfig($_POST['authkey']);
				$bbsurl = setconfig($_POST['bbsurl']);
				$gameurl = setconfig($_POST['gameurl']);
				$moveut = (int)$_POST['moveut'];

				$svcontents = file_get_contents($server_config);

				$svcontents = preg_replace("/[$]gamefounder\s*\=\s*[\"'].*?[\"'];/is", "\$gamefounder = '$gamefounder';", $svcontents);
				$svcontents = preg_replace("/[$]dbhost\s*\=\s*[\"'].*?[\"'];/is", "\$dbhost = '$dbhost';", $svcontents);
				$svcontents = preg_replace("/[$]dbuser\s*\=\s*[\"'].*?[\"'];/is", "\$dbuser = '$dbuser';", $svcontents);
				$svcontents = preg_replace("/[$]dbpw\s*\=\s*[\"'].*?[\"'];/is", "\$dbpw = '$dbpw';", $svcontents);
				$svcontents = preg_replace("/[$]gtablepre\s*\=\s*[\"'].*?[\"'];/is", "\$gtablepre = '$tablepre';", $svcontents);
				$svcontents = preg_replace("/[$]authkey\s*\=\s*[\"'].*?[\"'];/is", "\$authkey = '$authkey';", $svcontents);
				$svcontents = preg_replace("/[$]database\s*\=\s*[\"'].*?[\"'];/is", "\$database = '$database';", $svcontents);
				$svcontents = preg_replace("/[$]bbsurl\s*\=\s*[\"'].*?[\"'];/is", "\$bbsurl = '$bbsurl';", $svcontents);
				$svcontents = preg_replace("/[$]gameurl\s*\=\s*[\"'].*?[\"'];/is", "\$gameurl = '$gameurl';", $svcontents);
				$svcontents = preg_replace("/[$]moveut\s*\=\s*-?[0-9]+;/is", "\$moveut = $moveut;", $svcontents);

				file_put_contents($server_config,$svcontents);
			}

			include $server_config;
			//if($database == 'mysql') $database = PHP_VERSION >= 7.0 ? 'mysqli': $database;
			include './include/db/db_'.$database.'.class.php';
			$db = new dbstuff;
			$db->connect($dbhost, $dbuser, $dbpw);

			$query = $db->query("CREATE DATABASE bra_temp", 'SILENT');
			if($db->error()) {
				$createerror = TRUE;
			} else {
				$query = $db->query("DROP DATABASE bra_temp", 'SILENT');
				$createerror = FALSE;
			}

			$query = $db->query("SHOW DATABASES", 'SILENT');

			$option = '';
			if($query) {
				while($database = $db->fetch_array($query)) {
					if($database['Database'] != 'mysql') {
						$option .= '<option value="'.$database['Database'].'"' .($dbname == $database['Database'] ? ' selected' : '') . '>'.$database['Database']."</option>";
					}
				}
			}
			if(!empty($option)) {
?>
              <tr>
              	<td bgcolor="#EEEEF6">&nbsp;
                  <input name="type" type="radio" value="2" checked style="background-color:#EEEEF6">
        	  <?php echo $lang['db_use_existence']; ?>:
                </td>
                <td bgcolor="#EEEEF6">&nbsp;
                  <select name="dbnameselect" style="width:200px"><?php echo $option; ?></select>
                </td>
              </tr>

<?php
			}
			if(!$createerror) {
?>
              <tr>
                <td bgcolor="#EEEEF6">&nbsp;
                  <input name="type" type="radio" value="1" style="background-color:#EEEEF6"<?php echo ((empty($option)) ? ' checked' : ''); ?>>
                  <?php echo $lang['db_create_new']; ?>:
                </td>
                <td bgcolor="#EEEEF6">&nbsp;
                  <input type="text" name="dbname" value="<?php echo $dbname; ?>" style="width:200px">
                </td>
              </tr>
<?php
			}
			if($createerror && empty($option)) {
?>
              <tr>
                <td bgcolor="#EEEEF6">&nbsp;
                  <?php echo $lang['choice_one_db']; ?>:
                </td>
                <td bgcolor="#EEEEF6">&nbsp;
                  <input type="text" name="dbname" value="<?php echo $dbname; ?>" style="width:200px">
                </td>
              </tr>
<?php
			}
?>
            </table>
           </td>
         </tr>
<?php
		} else {
				@include $server_config;
?>
              <tr>
        	<td bgcolor="#EEEEF6">&nbsp;
                  <?php echo $lang['db']; ?>:
                </td>
                <td bgcolor="#EEEEF6">&nbsp;
                  <input type="hidden" name="dbname" value="<?php echo $dbname; ?>"><?php echo $dbname; ?>
                </td>
              </tr>
            </table>
           </td>
         </tr>
<?php
		}
?>
         <tr>
	   <td align="center">
	     <br>
	     <input type="hidden" name="action" value="environment">
	     <input type="hidden" name="saveconfig" value="1">
	     <input type="submit" name="submit" value="<?php echo $lang['save_config']; ?>" style="height: 25">
	     <input type="button" name="exit" value="<?php echo $lang['exit']; ?>" style="height: 25" onclick="javascript: window.close();">
	   </td>
	 </tr>
	 </form>
<?php
	}
	if($exist_error) {
?>
        <tr>
          <td align="center">
            <br>
            <form method="post" action="?language=<?php echo $language; ?>">
              <input type="hidden" name="action" value="config">
              <input type="submit" name="submit" value="<?php echo $lang['recheck_config']; ?>" style="height: 25">
              <input type="button" name="exit" value="<?php echo $lang['exit']; ?>" style="height: 25" onclick="javascript: window.close();">
            </form>
          </td>
        </tr>
<?php

	}
} elseif($action == 'environment') {

	if($_POST['saveconfig'] && is_writeable($server_config)) {

		$dbname = ($_POST['type'] == 1) ? $_POST['dbname'] : $_POST['dbnameselect'];
		$dbname = setconfig($dbname);

		$fp = fopen($server_config, 'r');
		$configfile = fread($fp, filesize($server_config));
		fclose($fp);

		$configfile = preg_replace("/[$]dbname\s*\=\s*[\"'].*?[\"'];/is", "\$dbname = '$dbname';", $configfile);

		$fp = fopen($server_config, 'w');
		fwrite($fp, trim($configfile));
		fclose($fp);

	}

	include $server_config;
	include './include/db/db_'.$database.'.class.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw);

	$msg = '';
	$quit = FALSE;

	$curr_os = PHP_OS;

	$curr_php_version = PHP_VERSION;
	if($curr_php_version < '5.5.0') {
		$msg .= "<font color=\"#FF0000\">$lang[php_version_low]</font>\t";
		$quit = TRUE;
	}

//	if(@ini_get(file_uploads)) {
//		$max_size = @ini_get(upload_max_filesize);
//		$curr_upload_status = $lang['attach_enabled'].$max_size;
//		$msg .= $lang['attach_enabled_info'].$max_size."\t";
//	} else {
//		$curr_upload_status = $lang['attach_disabled'];
//		$msg .= "<font color=\"#FF0000\">$lang[attach_disabled_info]</font>\t";
//	}

	$query = $db->query("SELECT VERSION()");
	$curr_mysql_version = $db->result($query, 0);
	$version_unit = (int)explode('.',$curr_mysql_version)[0];
	if($version_unit < 5) {
		$msg .= "<font color=\"#FF0000\">$lang[mysql_version_low]</font>\t";
		$quit = TRUE;
	}

	$curr_disk_space = intval(diskfreespace('.') / (1024 * 1024)).'M';

	if(dir_writeable('./templates')) {
		$curr_tpl_writeable = $lang['writeable'];
	} else {
		$curr_tpl_writeable = $lang['unwriteable'];
		$msg .= "<font color=\"#FF0000\">$lang[unwriteable_template]</font>\t";
	}

	if(dir_writeable('./gamedata')) {
		$curr_data_writeable = $lang['writeable'];
	} else {
		$curr_data_writeable = $lang['unwriteable'];
		$msg .= "<font color=\"#FF0000\">$lang[unwriteable_gamedata]</font>\t";
	}

	if(strstr($tablepre, '.')) {
		$msg .= "<font color=\"#FF0000\">$lang[tablepre_invalid]</font>\t";
		$quit = TRUE;
	}

	$db->select_db($dbname);
	if($db->error()) {
		if($db->version() > '4.1') {
			$db->query("CREATE DATABASE IF NOT EXISTS $dbname DEFAULT CHARACTER SET $dbcharset");
		} else {
			$db->query("CREATE DATABASE IF NOT EXISTS $dbname");
		}
		if($db->error()) {
			$msg .= "<font color=\"#FF0000\">$lang[db_invalid]</font>\t";
			$quit = TRUE;
		} else {
			$db->select_db($dbname);
			$msg .= "$lang[db_auto_created]\t";
		}
	}

	$query = $db->query("CREATE TABLE bra_test (test TINYINT (3) UNSIGNED)", 'SILENT');
	if($db->error()) {
		$dbpriv_createtable = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	} else {
		$dbpriv_createtable = $lang['yes'];
	}
	$query = $db->query("INSERT INTO bra_test (test) VALUES (1)", 'SILENT');
	if($db->error()) {
		$dbpriv_insert = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	} else {
		$dbpriv_insert = $lang['yes'];
	}
	$query = $db->query("SELECT * FROM bra_test", 'SILENT');
	if($db->error()) {
		$dbpriv_select = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	} else {
		$dbpriv_select = $lang['yes'];
	}
	$query = $db->query("UPDATE bra_test SET test='2' WHERE test='1'", 'SILENT');
	if($db->error()) {
		$dbpriv_update = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	} else {
		$dbpriv_update = $lang['yes'];
	}
	$query = $db->query("DELETE FROM bra_test WHERE test='2'", 'SILENT');
	if($db->error()) {
		$dbpriv_delete = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	} else {
		$dbpriv_delete = $lang['yes'];
	}
	$query = $db->query("DROP TABLE bra_test", 'SILENT');
	if($db->error()) {
		$dbpriv_droptable = '<font color="#FF0000">'.$lang['no'].'</font>';
		$quit = TRUE;
	} else {
		$dbpriv_droptable = $lang['yes'];
	}

	$query = $db->query("SELECT COUNT(*) FROM $tablepre"."users", 'SILENT');
	if(!$db->error()) {
		$msg .= "<font color=\"#FF0000\">$lang[db_not_null]</font>\t";
		$alert = " onSubmit=\"return confirm('$lang[db_drop_table_confirm]');\"";
	} else {
		$alert = '';
	}

	if($quit) {
		$msg .= "<font color=\"#FF0000\">$lang[install_abort]</font>";
	} else {
		$msg .= $lang['install_process'];
	}
?>
        <tr>
          <td><b><?php echo $lang['current_process']; ?> </b><font color="#0000EE"><?php echo $lang['check_env']; ?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['check_user_and_pass']; ?></font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="50%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center" style="color: #FFFFFF"><?php echo $lang['permission']; ?></td>
                <td align="center" style="color: #FFFFFF"><?php echo $lang['status']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">CREATE TABLE</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbpriv_createtable; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">INSERT</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbpriv_insert; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">SELECT</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbpriv_select; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">UPDATE</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbpriv_update; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">DELETE</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbpriv_delete; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">DROP TABLE</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $dbpriv_droptable; ?></td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['compare_env']; ?></font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <table width="80%" cellspacing="1" bgcolor="#000000" border="0" align="center">
              <tr bgcolor="#3A4273">
                <td align="center"></td>
                <td align="center" style="color: #FFFFFF"><?php echo $lang['env_required']; ?></td>
                <td align="center" style="color: #FFFFFF"><?php echo $lang['env_best']; ?></td>
                <td align="center" style="color: #FFFFFF"><?php echo $lang['env_current']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['env_os']; ?></td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $lang['unlimited']; ?></td>
                <td bgcolor="#E3E3EA" align="center">UNIX/Linux/FreeBSD</td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $curr_os; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['env_php']; ?></td>
                <td bgcolor="#EEEEF6" align="center">5.5.0+</td>
                <td bgcolor="#E3E3EA" align="center">7.0.0+</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $curr_php_version; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['env_mysql']; ?></td>
                <td bgcolor="#EEEEF6" align="center">MySQL 5.0+</td>
                <td bgcolor="#E3E3EA" align="center">MySQL 5.7+</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo ($version_unit >= 10 && $default_database == 'mysql') ? 'mariaDB '.$curr_mysql_version : $database.' '.$curr_mysql_version; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['env_diskspace']; ?></td>
                <td bgcolor="#EEEEF6" align="center">10M+</td>
                <td bgcolor="#E3E3EA" align="center">50M+</td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $curr_disk_space; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./templates <?php echo $lang['env_dir_writeable']; ?></td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $lang['unlimited']; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['writeable']; ?></td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $curr_tpl_writeable; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" align="center">./gamedata <?php echo $lang['env_dir_writeable']; ?></td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $lang['unlimited']; ?></td>
                <td bgcolor="#E3E3EA" align="center"><?php echo $lang['writeable']; ?></td>
                <td bgcolor="#EEEEF6" align="center"><?php echo $curr_data_writeable; ?></td>
              </tr>
            </table>
            <br>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['confirm_preparation']; ?></font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol><?php echo $lang['preparation']; ?></ol>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['install_note']; ?></font></b></td>
        </tr>
        <tr>
          <td>
            <br>
            <ol>
<?php

	foreach(explode("\t", $msg) as $message) {
		echo "              <li>$message</li>";
	}
	echo"            </ol>\n";

	if($quit) {

?>
            <center>
            <input type="button" name="refresh" value="<?php echo $lang['recheck_config']; ?>" style="height: 25" onclick="javascript: window.location=('?language=<?php echo $language; ?>&action=environment');">&nbsp;
            <input type="button" name="exit" value="<?php echo $lang['exit']; ?>" style="height: 25" onclick="javascript: window.close();">
            </center>
<?php

	} else {
		include $server_config;
		include $system_config;
		$now = time() + $moveut*3600;
		list($nowsec,$nowmin,$nowhour,$nowday,$nowmonth,$nowyear,$nowwday,$nowyday,$nowisdst) = localtime($now);
		$nowmonth++;
		$nowyear += 1900;
		if(file_exists('./gamedata/adminmsg.htm')) $adminmsg = file_get_contents('./gamedata/adminmsg.htm') ;
		else $adminmsg = '';

?>
        <form method="post" action="?language=<?php echo $language; ?>" <?php echo $alert; ?>>

        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['add_admin']; ?></font></b></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <table width="650" cellspacing="1" bgcolor="#000000" border="0" align="center">
                <tr bgcolor="#3A4273">
                  <td align="center" width="20%" style="color: #FFFFFF"><?php echo $lang['variable']; ?></td>
                  <td align="center" width="30%" style="color: #FFFFFF"><?php echo $lang['value']; ?></td>
                  <td align="center" width="50%" style="color: #FFFFFF"><?php echo $lang['comment']; ?></td>
                </tr>
              <tr>
                <td bgcolor="#E3E3EA" width="20%">&nbsp;<?php echo $lang['username']; ?></td>
                <td bgcolor="#EEEEF6" width="30%"><input type="text" name="username" value="admin" size="30"></td>
				<td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['username_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" width="20%">&nbsp;<?php echo $lang['brpswd']; ?></td>
                <td bgcolor="#EEEEF6" width="30%"><input type="password" name="brpswd" size="30"></td>
				<td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['brpswd_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" width="20%">&nbsp;<?php echo $lang['adminmsg']; ?></td>
                <td bgcolor="#EEEEF6" width="30%"><textarea cols="30" rows="4" style="overflow:auto" name="adminmsg"><?php echo $adminmsg; ?></textarea></td>
				<td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['adminmsg_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" width="20%">&nbsp;<?php echo $lang['startmode']; ?></td>
                <td bgcolor="#EEEEF6" width="30%"><input type="radio" name="startmode" value="1" <?php if(1==$startmode) echo 'checked'; ?>><?php echo $lang['startmode_1']; ?><input type="radio" name="startmode" value="2"  <?php if(2==$startmode) echo 'checked'; ?>><?php echo $lang['startmode_2']; ?><input type="radio" name="startmode" value="3"  <?php if(3==$startmode) echo 'checked'; ?>><?php echo $lang['startmode_3']; ?><input type="radio" name="startmode" value="0"  <?php if(0==$startmode) echo 'checked'; ?>><?php echo $lang['startmode_0']; ?></td>
				<td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['startmode_comment']; ?></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" width="20%">&nbsp;<?php echo $lang['starttime']; ?></td>
                <td bgcolor="#EEEEF6" colspan="2"><input type="radio" name="starttime" value="<?php echo $now; ?>" checked><?php echo $lang['starttime_1']; ?><input type="radio" name="starttime" value="0"><?php echo $lang['starttime_0']; ?><input type="text" name="setyear" size="4" value="<?php echo $nowyear; ?>"><?php echo $lang['year']; ?><input type="text" name="setmonth" size="2" value="<?php echo $nowmonth; ?>"><?php echo $lang['month']; ?><input type="text" name="setday" size="2" value="<?php echo $nowday; ?>"><?php echo $lang['day']; ?><input type="text" name="sethour" size="2" value="<?php echo $nowhour; ?>"><?php echo $lang['hour']; ?><input type="hidden" name="startmin" size="2" value="<?php echo $startmin; ?>"></td>
              </tr>
              <tr>
                <td bgcolor="#E3E3EA" width="20%">&nbsp;<?php echo $lang['iplimit']; ?></td>
                <td bgcolor="#EEEEF6" width="30%"><input type="text" name="iplimit" value="<?php echo $iplimit; ?>" size="30"></td>
				<td bgcolor="#E3E3EA">&nbsp;<?php echo $lang['iplimit_comment']; ?></td>
              </tr>
            </table>
            <br>
            <input type="hidden" name="action" value="install">
            <input type="submit" name="submit" value="<?php echo $lang['start_install']; ?>" style="height: 25" >&nbsp;
            <input type="button" name="exit" value="<?php echo $lang['exit']; ?>" style="height: 25" onclick="javascript: window.close();">
          </td>
        </tr>

        </form>
<?php

	}
} elseif($action == 'install') {
	

	$username = $_POST['username'];
	$brpswd = $_POST['brpswd']

?>
        <tr>
          <td><b><?php echo $lang['current_process']; ?> </b><font color="#0000EE"> <?php echo $lang['installing']; ?></font></td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['check_admin']; ?></font></b></td>
        </tr>
        <tr>
          <td><?php echo $lang['check_admin_validity']; ?>
<?php

	$msg = '';
	if(!$username) {
		$msg = $lang['admin_username_invalid'];
	}
	if(!$brpswd) {
		$msg = $lang['admin_password_invalid'];
	}
	else {
	$brpswd=md5($username.md5($brpswd));
	}
  
	if($msg) {

?>
            ... <font color="#FF0000"><?php echo $lang['fail_reason']; ?> <?php echo $msg; ?></font></td>
        </tr>
        <tr>
          <td align="center">
            <br>
            <input type="button" name="back" value="<?php echo $lang['go_back']; ?>" onclick="javascript: history.go(-1);">
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
            <b style="font-size: 11px">Powered by <a href="http://loongyou.com" target="_blank"><?php echo $lang['gamename']; ?> <?php echo $version; ?></a> , &nbsp; Copyright &copy; <a href="http://www.loongyou.com" target=\"_blank\">loongyou.com</a>, 2006-2007</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
</body>
</html>

<?php

		exit();
	} else {
		echo result(1, 0)."</td>\n";
		echo"        </tr>\n";
	}

?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td><b><font color="#FF0000">&gt;</font><font color="#000000"> <?php echo $lang['select_db']; ?></font></b></td>
        </tr>
<?php
	include $server_config;

	if(empty($dbcharset) && ($charset == 'gbk' || $charset == 'big5')) {
		$dbcharset = $charset;
	}

	include './include/db/db_'.$database.'.class.php';
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	$db->select_db($dbname);


	echo"        <tr>\n";
	echo"          <td>$lang[select_db] $dbname ".result(1, 0)."</td>\n";
	echo"        </tr>\n";
	echo"        <tr>\n";
	echo"          <td>\n";
	echo"            <hr noshade align=\"center\" width=\"100%\" size=\"1\">\n";
	echo"          </td>\n";
	echo"        </tr>\n";
	echo"        <tr>\n";
	echo"          <td><b><font color=\"#FF0000\">&gt;</font><font color=\"#000000\"> $lang[create_table]</font></b></td>\n";
	echo"        </tr>\n";
	echo"        <tr>\n";
	echo"          <td>\n";
	
	$extrasql = "INSERT INTO bra_users (username,`password`,groupid) VALUES ('$username','$brpswd','9');";
	$starttime = (int)$_POST['starttime'];
	$startmin = (int)$_POST['startmin'];
	if(!$starttime) {
		$nowtime = time()+$moveut*3600;
		$settime = mktime((int)$_POST['sethour'],(int)$_POST['startmin'],0,(int)$_POST['setmonth'],(int)$_POST['setday'],(int)$_POST['setyear']);
		$starttime = $nowtime > $settime ? $nowtime : $settime;
	}
	$extrasql .= "\nINSERT INTO bra_game (gamenum,starttime) VALUES (0,'$starttime');";

	runquery($sql);
	runquery($extrasql);

loginit('adminlog');
loginit('newsinfo');

dir_clear('./gamedata/bak');
dir_clear('./gamedata/cache');
dir_clear('./gamedata/javascript');
dir_clear('./gamedata/remote_replays');
dir_clear('./gamedata/replays');
dir_clear('./gamedata/templates');

dir_clear('./gamedata/tmp/log');
dir_clear('./gamedata/tmp/news');
dir_clear('./gamedata/tmp/replay');
dir_clear('./gamedata/tmp/response');
dir_clear('./gamedata/tmp/rooms');
dir_clear('./gamedata/tmp/server');

echo $lang['init_game'];


$adminmsg = setconfig($_POST['adminmsg']);
$startmode = (int)$_POST['startmode'];
$iplimit = (int)$_POST['iplimit'];

if($startmode == 1) {
	$starthour = 10;
} elseif($startmode == 2) {
	$starthour = 1;
} else {
	$starthour = 0;
}


$gcontents = file_get_contents($game_config);

//$gcontents = preg_replace("/[$]adminmsg\s*\=\s*[\"'].*?[\"'];/is", "\$adminmsg = '$adminmsg';", $gcontents);
$gcontents = preg_replace("/[$]startmode\s*\=\s*[0-9]+;/is", "\$startmode = $startmode;", $gcontents);
$gcontents = preg_replace("/[$]starthour\s*\=\s*[0-9]+;/is", "\$starthour = $starthour;", $gcontents);
$gcontents = preg_replace("/[$]iplimit\s*\=\s*[0-9]+;/is", "\$iplimit = $iplimit;", $gcontents);

file_put_contents($game_config,$gcontents);
file_put_contents('./gamedata/adminmsg.htm',$adminmsg);

result();
//echo $lang['new_game'];
//
//result();

touch(GAME_ROOT.$lockfile);
?>
          </td>
        </tr>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
            <font color="#FF0000"><b><?php echo $lang['install_succeed']; ?></font><br>
            <?php echo $lang['username']; ?></b> <?php echo $username; ?><br><br>
            <a href="<?php echo $gameurl; ?>" target="_blank"><?php echo $lang['goto_game']; ?></a>
          </td>
        </tr>
<?php

}

?>
        <tr>
          <td>
            <hr noshade align="center" width="100%" size="1">
          </td>
        </tr>
        <tr>
          <td align="center">
             <b style="font-size: 11px">Powered by <a href="http://loongyou.com" target="_blank"><?php echo $lang['gamename']; ?> <?php echo $version; ?></a> , &nbsp; Copyright &copy; <a href="http://www.loongyou.com" target=\"_blank\">loongyou.com</a>, 2006-2007</b>
          </td>
        </tr>
      </table>
    </td>
  </tr>
</table>
<br>
</body>
</html>
<?php

function loginit($logfile) {
	global $lang;

	echo $lang['init_log'].' '.$logfile;
	$fp = @fopen('./gamedata/'.$logfile.'.php', 'w');
	@fwrite($fp, "<? if(!defined(\"IN_GAME\")) exit(\"Access Denied\"); ?>\n");
	@fclose($fp);
	result();
}

function runquery($sql) {
	global $lang, $dbcharset, $gtablepre, $db;

	$sql = str_replace("\r", "\n", str_replace('bra_', $gtablepre, $sql));
	$ret = array();
	$num = 0;
	foreach(explode(";\n", trim($sql)) as $query) {
		$queries = explode("\n", trim($query));
		foreach($queries as $query) {
			$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
		}
		$num++;
	}
	unset($sql);

	foreach($ret as $query) {
		$query = trim($query);
		if($query) {
			if(substr($query, 0, 12) == 'CREATE TABLE') {
				$name = preg_replace("/CREATE TABLE `*([a-z0-9_]+)`*\s*\(.*/is", "\\1", $query);
				echo $lang['create_table'].' '.$name.' ... <font color="#0000EE">'.$lang['succeed'].'</font><br>';
				$db->query(createtable($query, $dbcharset));
			} else {
				$db->query($query);
			}
		}
	}
}

function result($result = 1, $output = 1) {
	global $lang;

	if($result) {
		$text = '... <font color="#0000EE">'.$lang['succeed'].'</font><br>';
		if(!$output) {
			return $text;
		}
		echo $text;
	} else {
		$text = '... <font color="#FF0000">'.$lang['fail'].'</font><br>';
		if(!$output) {
			return $text;
		}
		echo $text;
	}
}

function dir_writeable($dir) {
	if(!is_dir($dir)) {
		@mkdir($dir, 0777);
	}
	if(is_dir($dir)) {
		if($fp = @fopen("$dir/test.test", 'w')) {
			@fclose($fp);
			@unlink("$dir/test.test");
			$writeable = 1;
		} else {
			$writeable = 0;
		}
	}
	return $writeable;
}

function dir_clear($dir) {
	global $lang;

	echo $lang['clear_dir'].' '.$dir;
	while(!($directory = @dir($dir))){
		@mkdir($dir, 0777);
	}
	while($entry = $directory->read()) {
		$filename = $dir.'/'.$entry;
		if(is_file($filename) && $entry != '.gitignore') {
			@unlink($filename);
		}
	}
	$directory->close();
	result();
}

function random($length) {
	$hash = '';
	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
	$max = strlen($chars) - 1;
	mt_srand((double)microtime() * 1000000);
	for($i = 0; $i < $length; $i++) {
		$hash .= $chars[mt_rand(0, $max)];
	}
	return $hash;
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM','INNODB', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql)." ENGINE=$type DEFAULT CHARSET=$dbcharset";
}

function setconfig($string) {
	if(function_exists('get_magic_quotes_gpc') && !get_magic_quotes_gpc()) {
		$string = str_replace('\'', '\\\'', $string);
	} else {
		$string = str_replace('\"', '"', $string);
	}
	return $string;
}

?>