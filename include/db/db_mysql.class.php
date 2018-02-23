<?php

if (! defined ( 'IN_GAME' )) {
	exit ( 'Access Denied' );
}

class dbstuff {
	var $querynum = 0;
	var $selectnum = 0;
	var $insertnum = 0;
	var $updatenum = 0;
	var $deletenum = 0;
	
	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0) {
		if ($pconnect) {
			if (! mysql_pconnect ( $dbhost, $dbuser, $dbpw )) {
				$this->halt ( 'Can not connect to MySQL server' );
			}
		} else {
			if (! mysql_connect ( $dbhost, $dbuser, $dbpw )) {
				$this->halt ( 'Can not connect to MySQL server' );
			}
		}
		
		if ($this->version () > '4.1') {
			global $charset, $dbcharset;
			if (! $dbcharset && in_array ( strtolower ( $charset ), array ('gbk', 'big5', 'utf-8' ) )) {
				$dbcharset = str_replace ( '-', '', $charset );
			}
			
			if ($dbcharset) {
				mysql_query ( "SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=$dbcharset" );
			}
			
			if ($this->version () > '5.0.1') {
				mysql_query ( "SET sql_mode=''" );
			}
		}
		
		if ($dbname) {
			mysql_select_db ( $dbname );
		}
	
	}
	
	function select_db($dbname) {
		return mysql_select_db ( $dbname );
	}
	
	function fetch_array($query, $result_type = MYSQL_ASSOC) {
		return mysql_fetch_array ( $query, $result_type );
	}
	
	function query($sql, $type = '') {
		$func = $type == 'UNBUFFERED' && function_exists ( 'mysql_unbuffered_query' ) ? 'mysql_unbuffered_query' : 'mysql_query';
		if (! ($query = $func ( $sql )) && $type != 'SILENT') {
			$this->halt ( 'MySQL Query Error', $sql );
		}
		$this->querynum ++;
		if(strpos($sql,'SELECT')===0){$this->selectnum ++;}
		elseif(strpos($sql,'INSERT')===0){$this->insertnum ++;}
		elseif(strpos($sql,'UPDATE')===0){
			$this->updatenum ++;
			if(strpos($sql, 'users') !==false && strpos($sql, 'room') !==false) {
				$bk = debug_backtrace();
				global $now;
				writeover('tmp_roomid_log_2.txt', $sql.' from line '.$bk[1]['line']." at file ".$bk[1]['file'].' at '.$now."\r\n",'ab+');
			}
		}
		elseif(strpos($sql,'DELETE')===0){$this->deletenum ++;}
		return $query;
	}
	
	function queries ($queries) {
	  foreach (preg_split ("/[;]+/", trim($queries)) as $query_split) {
	  	$query = '';
	  	foreach (preg_split ("/[\n]+/", trim($query_split)) as $query_row){
	  		if (!empty($query_row) && substr($query_row,0,2) != '--' && substr($query_row,0,1) != '#') {
	  			$query .= $query_row;
				}
	  	}
	  	if(substr($query, 0, 12) == 'CREATE TABLE') {
				$this->query($this->create_table($query));
			} elseif (!empty($query)) {
				$this->query($query);
			}
	  }
	  return;
	}
	
	function create_table($sql) {
		global $dbcharset;
		$type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
		$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
		return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
			(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
	}
	
//	function Aselect($dbname, $where = Array(), $fields = Array(), $limit = '') {
//		if (! empty ( $dbname )) {
//			$dbname_string = mysql_real_escape_string($dbname);
//		}else{
//			return false;
//		}
//		
//		if (! empty ( $fields )) {
//			$fields_string = '';
//			foreach($fields as $val){
//				$val = mysql_real_escape_string($val);
//				$fields_string .= "{$val},";
//			}
//			$fields_string = substr($fields_string,0,-1);
//		}else{
//			$fields_string = '*';
//		}
//		
//		if (! empty ( $where )) {
//			$where_string = '';
//			foreach($where as $val){
//				if(is_array($val) && isset($val[0]) && isset($val[1]) && isset($val[2])){
//					$val[0] = mysql_real_escape_string($val[0]);
//					$val[1] = mysql_real_escape_string($val[1]);
//					$val[2] = mysql_real_escape_string($val[2]);
//					$where_string .= $val[0].$val[1]."'".$val[2]."' AND ";
//				}				
//			}
//			if(!empty($where_string)){
//				$where_string = 'WHERE '.substr($where_string,0,-5);
//			}
//		}else{
//			$where_string = '';
//		}
//		
//		if (! empty ( $limit )) {
//			$limit_string = 'LIMIT '.mysql_real_escape_string($limit);
//		}else{
//			$limit_string = '';
//		}
//		
//		$query = "SELECT {$fields_string} FROM {$dbname_string} {$where_string} {$limit_string}";
//		
//		//return $query;
//		return $this->query ($query);
//	}
	
//	function delete($dbname, $where){
//		$query = "DELETE FROM {$dbname} WHERE {$where}";
//		return $this->query ($query);
//	}
	
	//根据$data的键和键值插入数据。多数据插入是直接按字段先后顺序排的，请保证输入数据字段顺序完全一致！
	function array_insert($dbname, $data){
		$tp = 1;//单记录插入
		if(is_array(array_values($data)[0])) $tp = 2;//多记录插入 
		$query = "INSERT INTO {$dbname} ";
		$fieldlist = $valuelist = '';
		if(2!=$tp){//单记录插入
			if(!$data) return;
			foreach ($data as $key => $value) {
				$fieldlist .= "{$key},";
				$valuelist .= "'{$value}',";
			}
			if(!empty($fieldlist) && !empty($valuelist)){
				$query .= '(' . substr($fieldlist, 0, -1) . ') VALUES (' . substr($valuelist, 0, -1) .')';
			}
		}else{//多记录插入
			foreach (array_keys(array_values($data)[0]) as $key) {
				$fieldlist .= "{$key},";
			}
			foreach ($data as $dv){
				if(!$dv) continue;
				$valuelist .= "(";
				foreach ($dv as $value) {
					$valuelist .= "'{$value}',";
				}
				$valuelist = substr($valuelist, 0, -1).'),';
			}
			if(!empty($valuelist)) {
				$query .= '(' . substr($fieldlist, 0, -1) . ') VALUES '.substr($valuelist, 0, -1);
			}
		}
		
		if(!empty($query)) $this->query ($query);
		return $query;
	}
	
	function array_update($dbname, $data, $where, $o_data=NULL){ //根据$data的键和键值更新数据
		$query = '';
		foreach ($data as $key => $value) {
			if(!is_array($o_data) || !isset($o_data[$key]) || $value !== $o_data[$key])
				$query .= "{$key} = '{$value}',";
		}
		if(!empty($query)){
			$query = "UPDATE {$dbname} SET ".substr($query, 0, -1) . " WHERE {$where}";
			$this->query ($query);
		}
		return $query;
	}
	
	function multi_update($dbname, $data, $confield, $singleqry = ''){
		$fields = $range = Array();
		foreach($data as $rval){
			$con = $rval[$confield];
			$range[] = "'$con'";
			foreach($rval as $fkey => $fval){
				if($fkey != $confield){
					if(isset(${$fkey.'qry'})){
						${$fkey.'qry'} .= "WHEN '$con' THEN '$fval' ";
					}else{
						$fields[] = $fkey;
						${$fkey.'qry'} = "(CASE $confield WHEN '$con' THEN '$fval' ";
					}
				}				
			}
		}
		$query = '';
		foreach($fields as $val){
			if(!empty(${$val.'qry'})){
				${$val.'qry'} .= "END) ";
				$query .= "$val = ${$val.'qry'},";
			}
		}
		if(!empty($query)){
			if($singleqry){$singleqry = ','.$singleqry;}
			$query = "UPDATE {$dbname} SET ".substr($query,0,-1)."$singleqry WHERE $confield IN (".implode(',',$range).")";
			$this->query ($query);
		}
		return $query;
	}
	
/*	function select_fetch_array($dbname, $fields = '*', $where = '', $limit = '') { //返回二维数组
		$query = "SELECT {$fields} FROM {$dbname} ";
		if (! empty ( $where )) {
			$query .= "WHERE {$where} ";
		}
		if (! empty ( $limit )) {
			$query .= "LIMIT {$limit}";
		}
		$result = $this->query ($query);
		while($data = $this->fetch_array($result)){
			
		}
	}*/
	
	function affected_rows() {
		return mysql_affected_rows ();
	}
	
	function error() {
		return mysql_error ();
	}
	
	function errno() {
		return intval ( mysql_errno () );
	}
	
	function result($query, $row) {
		$query = mysql_result ( $query, $row );
		return $query;
	}
	
	function data_seek($query, $row) {
		return mysql_data_seek ( $query, $row );
	}
	function num_rows($query) {
		$query = mysql_num_rows ( $query );
		return $query;
	}
	
	function num_fields($query) {
		return mysql_num_fields ( $query );
	}
	
	function free_result($query) {
		return mysql_free_result ( $query );
	}
	
	function insert_id() {
		$id = mysql_insert_id ();
		return $id;
	}
	
	function fetch_row($query) {
		$query = mysql_fetch_row ( $query );
		return $query;
	}
	
	function fetch_fields($query) {
		return mysql_fetch_field ( $query );
	}
	
	function version() {
		return mysql_get_server_info ();
	}
	
	function close() {
		return mysql_close ();
	}
	
	function halt($message = '', $sql = '') {
		header('Content-Type: text/HTML; charset=utf-8');
		echo '数据库错误。请联系管理员。<br><br>';
		$dberror = $this->errno().' '.$this->error();
		echo '错误信息：'.$dberror.'<br><br>';
		echo '以下是stack dump<br>';
		var_export(debug_backtrace());
		die();
		require_once GAME_ROOT . './include/db/db_mysql_error.inc.php';
	}
}

/* End of file db_mysql.class.php */
/* Location: /include/db/db_mysql.class.php */