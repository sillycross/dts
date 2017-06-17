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
//创建history表 shistory表
$db->query("DROP TABLE IF EXISTS {$gtablepre}history");
$db->query("DROP TABLE IF EXISTS {$gtablepre}shistory");
$db->query("CREATE TABLE {$gtablepre}history LIKE alter_history");
$db->query("CREATE TABLE {$gtablepre}shistory LIKE alter_history");
output_t('History tables created.');
//删掉alter_开头的临时表
$result = $db->query("SHOW TABLES LIKE 'alter_%';");
while($rarr = $db->fetch_array($result)){
	$table = current($rarr);
	$db->query("DROP TABLE IF EXISTS $table");
}
//以下正式开始处理数据
$process = 0;//进度指示物，防止程序意外退出。
foreach (array('winners', 'swinners') as $htablename){
	$result = $db->query("SELECT * FROM {$gtablepre}{$htablename} WHERE 1 ORDER BY gid;");
	while($hdata = $db->fetch_array($result)){
		$process = $hdata['gid'];
		output_t('Game '.$process.' in '.$htablename.' starting processing.');
		$nhdata = array(
			'gid' => $hdata['gid'],
			'wmode' => $hdata['wmode'],
			'winner' => $hdata['name'],
			'motto' => $hdata['motto'],
			'gametype' => $hdata['gametype'],
			'vnum' => $hdata['vnum'],
			'gtime' => $hdata['gtime'],
			'gstime' => $hdata['gstime'],
			'getime' => $hdata['getime'],
			'winnum' => $hdata['winnum'],
			'hdmg' => $hdata['hdmg'],
			'hdp' => $hdata['hdp'],
			'hkill' => $hdata['hkill'],
			'hkp' => $hdata['hkp']
		);
		$winnerpdata = array();
		if(!empty($hdata['namelist'])) {
			$namelist = explode(',',$hdata['namelist']);
			foreach($namelist as $nv){
				$winnerpdata[] = array('name' => $nv);
			}
		}elseif($hdata['name']){
			$unsetlist = array_keys($nhdata);
			$unsetlist = array_merge($unsetlist, array('namelist', 'weplist', 'iconlist', 'gdlist', 'pass'));
			unset($unsetlist['winner']);
			foreach($unsetlist as $uv) unset($hdata[$uv]);
			$winnerpdata[] = $hdata;
		}
		if($winnerpdata) $nhdata['winnerpdata'] = gencode($winnerpdata);
		
		output_t('Game '.$process.' in '.$htablename.' data transferred.');
		
		$validlist = array();
		$hbakprefix = $htablename == 'winners' ? '' : 's.';
		$file = './gamedata/bak/'.$hbakprefix.$process.'_newsinfo.html';
		if(file_exists($file)){
			$hcont = file_get_contents($file);
			//$nhdata['hnews'] = gencode($hcont);
			$hcontarr = explode('<li',$hcont);
			foreach($hcontarr as $hcv){
				if(strpos($hcv, '(男生') !== false || strpos($hcv, '(女生') !== false) {
					$hcva = explode(' ',explode('(',$hcv)[0]);
					$hcva = str_replace('管理员-','',$hcva[sizeof($hcva)-1]);
					$validlist[] = $hcva;
				}
			}
		}
		if(!empty($validlist)) $nhdata['validlist'] = implode('&',$validlist);
		$db->array_insert($gtablepre.str_replace('winners','history',$htablename),$nhdata);
		writeover('process.txt',$htablename.$process);
		output_t('Game '.$process.' in '.$htablename.' processed.');
	}
}

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