<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();
//$db->query("ALTER TABLE {$gtablepre}rooms ADD `roomtype` tinyint unsigned NOT NULL DEFAULT 0");
ob_start();
print str_repeat(" ", 4096);
//载入bra.install.sql，将前缀改为alter_，并新建表
$install_db = file_get_contents('./install/bra.install.sql');
$install_db = str_replace('bra_', 'alter_', $install_db);
runquery($install_db);
output_t('Loading bra.install.sql...');

//获取表名内容备用
$alter_tables = array();
$result = $db->query("SHOW TABLES LIKE 'alter_%';");
while($rarr = $db->fetch_array($result)){
	$table = str_replace('alter_','',current($rarr));
	$alter_tables[] = $table;
	//winners表有个分身，要特判
	if($table == 'winners') $alter_tables[] = 'swinners';
}
output_t('Checking table names...');
foreach($alter_tables as $at){
	//获取现有表的记录
	//$db->select_db('acdts0');
	output_t('Start fetching db '.$gtablepre.$at);
	$result = $db->query("SELECT * FROM {$gtablepre}{$at} WHERE 1");
	$dir = './gamedata/tmp/backup';
	$file = $dir.'/'.$at.'.bak';
	if(!file_exists($dir)) mymkdir($dir);
	if(file_exists($fire)) unlink($file);
	$handle=fopen($file,'ab+');
	while($rarr = $db->fetch_array($result)){
		fwrite($handle,json_encode($rarr)."\n");
	}
	fclose($handle);
}

echo 'Done.';
function init_dbstuff(){
	include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
	$default_database = PHP_VERSION >= 7.0 ? 'mysqli' : 'mysql';
	$db_class_file = GAME_ROOT.'./include/db_'.$database.'.class.php';
	$db_default_class_file = GAME_ROOT.'./include/db_'.$default_database.'.class.php';
	if(file_exists($db_class_file)) include_once $db_class_file;
	elseif(file_exists($db_default_class_file)) include_once $db_default_class_file;
	else die('Cannot find db_class file!');
	$db = new dbstuff;
	$db->connect($dbhost, $dbuser, $dbpw, $dbname, $pconnect);
	return $db;
}

function check_authority()
{
	include GAME_ROOT.'./include/modules/core/sys/config/server.config.php';
	$_COOKIE=gstrfilter($_COOKIE);
	$cuser=$_COOKIE[$gtablepre.'user'];
	$cpass=$_COOKIE[$gtablepre.'pass'];
	$db = init_dbstuff();
	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$cuser'");
	if(!$db->num_rows($result)) { echo "<span><font color=\"red\">Cookie无效，请登录。</font></span><br>"; die(); }
	$udata = $db->fetch_array($result);
	if($udata['password'] != $cpass) { echo "<span><font color=\"red\">Cookie无效，请登录。</font></span><br>"; die(); }
	elseif(($udata['groupid'] < 9)&&($cuser!==$gamefounder)) { echo "<span><font color=\"red\">要求至少9权限。</font></span><br>"; die(); }
}

function output_t($str){
	echo $str.'<br>';
	ob_flush();
	flush();
}

function col_filter($objtable, $data){
	global $db;
	if(strpos($objtable,'swinners')!==false) $objtable = str_replace('swinners','winners',$objtable);
	$result = $db->query("DESCRIBE $objtable");
	$fields = array();
	while ($rarr = $db->fetch_array($result))
	{
		$fields[] = $rarr['Field'];
	}
	$del_fields = array();
	foreach(array_keys($data[0]) as $k0){
		if(!in_array($k0,$fields)) $del_fields[] = $k0;
	}
	foreach($data as &$v){
		foreach($del_fields as $dv){
			if(isset($v[$dv])) unset($v[$dv]);
		}
	}
	return $data;
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
				//$name = preg_replace("/CREATE TABLE `*([a-z0-9_]+)`*\s*\(.*/is", "\\1", $query);
				//echo $lang['create_table'].' '.$name.' ... <font color="#0000EE">'.$lang['succeed'].'</font><br>';
				$db->query(createtable($query, $dbcharset));
			} else {
				$db->query($query);
			}
		}
	}
}

function createtable($sql, $dbcharset) {
	$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql)." ENGINE=$type DEFAULT CHARSET=$dbcharset";
}