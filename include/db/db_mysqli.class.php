<?php

if (! defined ( 'IN_GAME' )) { exit ( 'Access Denied' ); }

class dbstuff {
//	var $querynum = 0;
//	var $selectnum = 0;
//	var $insertnum = 0;
//	var $updatenum = 0;
//	var $deletenum = 0;
	public $con = NULL;
	
	function connect($dbhost, $dbuser, $dbpw, $dbname = '', $pconnect = 0) {
		if(!function_exists('mysqli_connect')) exit('未安装mysqli扩展！');
		
		$this -> con = mysqli_connect ( $dbhost, $dbuser, $dbpw, $dbname );
		if (mysqli_connect_errno())
		$this->halt ( 'Can not connect to MySQL server' );
	
	
		global $charset, $dbcharset;
		
		if (! $dbcharset && in_array ( strtolower ( $charset ), array ('gbk', 'big5', 'utf-8' ) )) {
			$dbcharset = str_replace ( '-', '', $charset );
		}		
		if ($dbcharset) {
			mysqli_query ( $this->con, "SET character_set_connection=$dbcharset, character_set_results=$dbcharset, character_set_client=$dbcharset" );
		}
		
		mysqli_query ( $this->con, "SET sql_mode=''" );
		if (mysqli_connect_errno())
		$this->halt ();
	}
	
	function select_db($dbname) {
		return mysqli_select_db ( $this->con, $dbname );
	}
	
	function fetch_array($query, $result_type = MYSQLI_ASSOC) {
		return mysqli_fetch_array ( $query, $result_type );
	}
	
	function query($sql, $type = '') {
		//mysqli不存在unbuffered指令，游戏也从来没用到过这个参数
		//$func = $type == 'UNBUFFERED' && function_exists ( 'mysqli_unbuffered_query' ) ? 'mysqli_unbuffered_query' : 'mysqli_query';
		$result = mysqli_query ( $this -> con, $sql );
		if (! $result && $type != 'SILENT') {
			$this->halt ( 'MySQL Query Error', $sql );
		}
//		$this->querynum ++;
//		if(strpos($sql,'SELECT')===0){$this->selectnum ++;}
//		elseif(strpos($sql,'INSERT')===0){$this->insertnum ++;}
//		elseif(strpos($sql,'UPDATE')===0){$this->updatenum ++;}
//		elseif(strpos($sql,'DELETE')===0){$this->deletenum ++;}
//		if(strpos($sql,'UPDATE')===0){
//			ob_start();
//			var_dump(debug_backtrace());
//			$a = ob_get_contents();
//			ob_end_clean();
//			file_put_contents('a.txt',$a."\r\n",FILE_APPEND);
//		}
		return $result;
	}
	
	function queries ($queries, $ignore_result = true) {
//	  foreach (preg_split ("/[;]+/", trim($queries)) as $query_split) {
//	  	$query = '';
//	  	foreach (preg_split ("/[\n]+/", trim($query_split)) as $query_row){
//	  		if (!empty($query_row) && substr($query_row,0,2) != '--' && substr($query_row,0,1) != '#') {
//	  			$query .= $query_row;
//				}
//	  	}
//	  	if(substr($query, 0, 12) == 'CREATE TABLE') {
//				$this->query($this->create_table($query));
//			} elseif (!empty($query)) {
//				$this->query($query);
//			}
//	  }
	  mysqli_multi_query($this->con,$this->parse_create_table($queries));
	  while ($ignore_result && $this->more_results()) { 
	    if ($this->next_result() === false) { 
	      $this->halt ( 'Some error uccurred in multi_querying.' );
	      break; 
	    } 
		} 
	  $result = $this->store_result();
	  return $result;
	}
	
	function parse_create_table($sql) {//修改了替换逻辑，不会有什么区别的
		global $dbcharset;
		$sql = preg_replace("/ENGINE\s*=\s*([a-z]+)/i", "ENGINE=$1 DEFAULT CHARSET=".$dbcharset, $sql);
		return $sql;
//		$type = strtoupper(preg_replace("/\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*;/isU", "\\2", $sql));
//		$type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
//		return preg_replace("/\s*(CREATE TABLE\s+.+\s+\(.+?\)).*;$/isU", "\\1", $sql)." ENGINE=$type DEFAULT CHARSET=$dbcharset;";
	}
	
	function array_insert($dbname, $data){ //根据$data的键和键值插入数据
		$query = "INSERT INTO {$dbname} ";
		$fieldlist = $valuelist = '';
		foreach ($data as $key => $value) {
			$fieldlist .= "{$key},";
			$valuelist .= "'{$value}',";
		}
		if(!empty($fieldlist) && !empty($valuelist)){
			$query .= '(' . substr($fieldlist, 0, -1) . ') VALUES (' . substr($valuelist, 0, -1) .')';
		}
		$this->query ($query);
		return $query;
	}
	
	function array_update($dbname, &$data, $where){ //根据$data的键和键值更新数据
		$query = "UPDATE {$dbname} SET ";
		foreach ($data as $key => $value) {
			$query .= "{$key} = '{$value}',";
		}
		$query = substr($query, 0, -1) . " WHERE {$where}";
		$this->query ($query);
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
	
	function affected_rows() {
		return mysqli_affected_rows ($this -> con);
	}
	
	function error() {
		return mysqli_error ($this -> con);
	}
	
	function errno() {
		return intval ( mysqli_errno ($this -> con) );
	}
	
	function result($query, $row) {
		mysqli_data_seek($query, $row);
		return mysqli_fetch_array ( $query, MYSQLI_NUM )[0];
	}
	
	function data_seek($query, $row) {
		return mysqli_data_seek ( $query, $row );
	}
	
	function num_rows($query) {
		$query = mysqli_num_rows ( $query );
		return $query;
	}
	
	function num_fields($query) {
		return mysqli_num_fields ( $query );
	}
	
	function next_result(){
		return mysqli_next_result ( $this->con );
	}
	
	function more_results() {
		return mysqli_more_results ( $this->con );
	}
	
	function store_result() {
		return mysqli_store_result ( $this->con );
	}
	
	function free_result($query) {
		return mysqli_free_result ( $query );
	}
	
	
	
	function insert_id() {
		$id = mysqli_insert_id ($this -> con);
		return $id;
	}
	
	function fetch_row($query) {
		$query = mysqli_fetch_row ( $query );
		return $query;
	}
	
	function fetch_fields($query) {
		return mysqli_fetch_field ( $query );
	}
	
	function version() {
		return mysqli_get_server_info ($this->con);
	}
	
	function close() {
		return mysqli_close ($this->con);
	}
	
	function halt($message = '', $sql = '') {
		echo '数据库错误。请联系管理员。<br><br>';
		$dberror = $this->errno().' '.$this->error();
		echo '错误信息：'.$dberror.'<br><br>';
		echo '以下是stack dump<br>';
		var_export(debug_backtrace());
		die();
		require_once GAME_ROOT . './include/db/db_mysqli_error.inc.php';
	}
}

?>