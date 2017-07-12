<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE);

define('IN_GAME', TRUE);
define('GAME_ROOT', '');
$server_config =  './include/modules/core/sys/config/server.config.php';
include $server_config;

include './include/global.func.php';
check_authority();
$db = init_dbstuff();

$result = $db->query("SELECT * FROM {$gtablepre}users WHERE alt_pswd=0");
while($u = $db->fetch_array($result)){
	$un = $u['username'];
  $up = md5($un.$u['password']);
  $db->query("UPDATE {$gtablepre}users SET password='$up',alt_pswd=1 WHERE username='$un'");
  echo "processing user $un ...<br>";
}
echo "done.";

function output_t($str){
	echo $str.'<br>';
	ob_flush();
	flush();
}

function col_filter($objtable, $data){
	global $db,$del_fields;
	if(strpos($objtable,'swinners')!==false) $objtable = str_replace('swinners','winners',$objtable);
	if(!isset($del_fields[$objtable])){
		$del_fields[$objtable] = array();
		$result = $db->query("DESCRIBE $objtable");
		$fields = array();
		while ($rarr = $db->fetch_array($result))
		{
			$fields[] = $rarr['Field'];
		}
		//$del_fields = array();
		foreach(array_keys($data) as $k0){
			if(!in_array($k0,$fields)) $del_fields[$objtable][] = $k0;
		}
	}
	foreach($del_fields[$objtable] as $dv){
		if(isset($data[$dv])) unset($data[$dv]);
		if($dv == 'roomid') $data[$dv] = 0;
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
	$type = in_array($type, array('MYISAM','INNODB', 'HEAP')) ? $type : 'MYISAM';
	return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql)." ENGINE=$type DEFAULT CHARSET=$dbcharset";
}