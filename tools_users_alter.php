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
$result = $db->query("SHOW TABLES LIKE 'alter_%users';");
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
	$db->query("DROP TABLE IF EXISTS {$gtablepre}{$at}_clone");
	if($at == 'swinners'){//winners表有个分身，要特判
		$db->query("CREATE TABLE {$gtablepre}{$at}_clone LIKE alter_winners");
	}else{
		$db->query("CREATE TABLE {$gtablepre}{$at}_clone LIKE alter_{$at}");
	}
	$rarr_a = array();
	while($rarr = $db->fetch_array($result)){
		$rarr_a = col_filter("alter_{$at}", $rarr);
		if(!empty($rarr_a) && (!isset($rarr_a['uid']) || $rarr_a['uid']>0)){
		$db->array_insert("{$gtablepre}{$at}_clone", $rarr_a);
		}
	}
	//$db->select_db('acdts');
	//output_t('Start cloning database structure of '.$gtablepre.$at);
	
	//重头戏，挨个insert，因为怕query语句超长所以不能拼接成一句insert
}

//结束，删除原表，做好清理
output_t('All finished. Now delete the original databases and change names');
foreach($alter_tables as $at){
	$db->query("DROP TABLE IF EXISTS {$gtablepre}{$at}");
	$db->query("RENAME TABLE {$gtablepre}{$at}_clone TO {$gtablepre}{$at}");
}
$result = $db->query("SHOW TABLES LIKE 'alter_%';");
while($rarr = $db->fetch_array($result)){
	$table = current($rarr);
	$db->query("DROP TABLE IF EXISTS $table");
}
//$result = $db->query("DESCRIBE {$gtablepre}players");
echo 'Done.';


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