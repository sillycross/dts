<?php

namespace activity_ranking
{
	global $activity_ranking_available;
	$activity_ranking_available = array();
	
	function init() 
	{
	}
	
	//检查是否存在对应的表，如果不存在则创建
	function prepare_aranking_table($activity_name)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$activity_name) return NULL;
		eval(import_module('sys', 'activity_ranking'));
		if(!in_array($activity_name, $activity_ranking_available)) {
			$result = $db->query("SHOW TABLES LIKE '{$gtablepre}activity_ranking_{$activity_name}';");
			if (!$db->num_rows($result))
			{
				$sql = file_get_contents(GAME_ROOT.'./gamedata/sql/activity_ranking.sql');
				$sql = str_replace("\r", "\n", str_replace(' bra_', ' '.$gtablepre, $sql));
				$sql = str_replace('activity_ranking', 'activity_ranking_'.$activity_name, $sql);
				$db->queries($sql);
			}
			$activity_ranking_available[] = $activity_name;
		}
		return true;
	}
	
	function aranking_send($acmd, $apara1='', $apara2='')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$aurl = str_replace('userdb','aranking',$userdb_remote_storage);
		if('save_ulist_aranking' == $acmd) {
			$apara2 = gencode($apara2);
		}
		$context = array(
			'asign' => $userdb_remote_storage_sign,
			'apass' => timestamp_salt($userdb_remote_storage_pass),
			'acmd' => $acmd,
			'apara1' => $apara1,
			'apara2' => $apara2,
		);
		for($i=0;$i<$userdb_remote_reconnect_times;$i++) {
			$ret_raw = curl_post($aurl, $context);
			$ret = gdecode($ret_raw,1);
			if(NULL!==$ret || strpos($ret_raw, 'Error')===0) break;
		}
		if(NULL===$ret || ('load_aranking' == $acmd && !is_array($ret))) {
			return array('error' => '连接远程数据库失败'.$ret_raw);
		}
		else {
			return $ret;
		}
	}
	
	//载入成就排行，排序直接按数组的
	function load_aranking($activity_name, $limit=10)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		global $userdb_forced_local;
		//有远程数据库储存时，读远程数据库
		if($userdb_remote_storage && !$userdb_forced_local) {
			return aranking_send('load_aranking', $activity_name, $limit);
		}
		//没有远程数据库时读本地
		if(!prepare_aranking_table($activity_name)) return NULL;
		eval(import_module('sys'));
		if($limit <= 0) $limit = 1;
		$limit = (int) $limit;
		$result = $db->query("SELECT * FROM {$gtablepre}activity_ranking_{$activity_name} ORDER BY score1 DESC, score2 DESC LIMIT {$limit}");
		$adata = array();
		while($ad = $db->fetch_array($result)) {
			$adata[] = $ad;
		}
		return $adata;
	}
	
	//保存多个用户成就排行
	//建议在成就结算后，用专用函数调用此函数，不需要二次判定是最高值还是积累值
	function save_ulist_aranking($activity_name, $ulist)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		global $userdb_forced_local;
		//有远程数据库储存时，写远程数据库
		if($userdb_remote_storage && !$userdb_forced_local) {
			return aranking_send('save_ulist_aranking', $activity_name, $ulist);
		}
		//没有远程数据库时写本地
		if(!prepare_aranking_table($activity_name)) return NULL;		
		$upd_arr = array();
		foreach($ulist as $uv){
			if(isset($uv['username'])) {
				$arr = array(
					'username' => $uv['username'],
					'atime' => $now,
					'score1' => $uv['score1']
				);
				if(isset($uv['score2'])) $arr['score2'] = $uv['score2'];
				$upd_arr[] = $arr;
			}
		}
		$db->array_insert("{$gtablepre}activity_ranking_{$activity_name}", $upd_arr, 1, 'username');
	}
}
?>