<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

//这个文件只能调本地用户
global $userdb_forced_local;
$userdb_forced_local = 1;

$recover_dir = GAME_ROOT.'./gamedata/cache/user_backup';
$user_backup_list = array();
$user_backup_date_list = array();
if ($handle=opendir($recover_dir)) 
{
	while (($entry=readdir($handle))!==false) 
	{
		if ($entry!='.' && $entry!='..' && in_array(pathinfo($entry,PATHINFO_EXTENSION),array('dat','php'))) 
		{
			$user_backup_list[] = $entry;
			$user_backup_date_list[] = date("Y-m-d H:i:s", filemtime($recover_dir.'/'.$entry) + $now - time());
		}
	}
}

if(!empty($pagecmd) && $pagecmd == 'recover'){
	if(empty($recover_file) || !in_array($recover_file, $user_backup_list)){
		$cmd_info = "恢复参数错误";
	}else{
		$cont = file_get_contents($recover_dir.'/'.$recover_file);
		if(empty($cont)) $cmd_info = "文件格式错误";
		else{
			set_time_limit(0);
			$filepath = dir_init($recover_dir);
			$odbname = 'old_db_'.uniqid().'.php';
			$filepath .= '/';
			urlist_userdb_backup($filepath.$odbname);
			$cmd_info = '旧数据库已保存为"'.$odbname.'"';
			$db->query("TRUNCATE TABLE {$gtablepre}users");
			$cont = explode("\n", $cont);
			$cont_size = sizeof($cont);
			$cont_arr = array();
			$i = 0;
			foreach($cont as $ci => $cv){
				if(strpos($cv, '<?php')===0) continue;
				$cv = json_decode(trim($cv),1);
				if(isset($cv['username']) ) {
					$cont_arr[] = $cv;
					$i ++;
				}
				//一个记录可能有400个字符，达到1M时insert1次
				if(sizeof($cont_arr) >= 2500 || $ci >= $cont_size - 1) {
					insert_udata($cont_arr);
					$cont_arr = array();
				}
			}
			$cmd_info .= '<br>'.$i.'条记录已恢复，用户数据恢复成功';
		}
	}
	$urcmd = '';
}elseif(!empty($pagecmd) && $pagecmd == 'upload'){
	if(!isset($_FILES['uploadfile']) || empty($_FILES['uploadfile']['name'])) {
		$cmd_info = "不能上传空文件";
	}elseif($_FILES['uploadfile']['error'] > 0) {
		$cmd_info = '上传错误，编号：'.$_FILES['uploadfile']['error'];
	}else{
		$cont = file_get_contents($_FILES['uploadfile']['tmp_name']);
		$cont = explode("\r\n", $cont);
		$errno = 0;
		$cont_arr = array();
		foreach($cont as $cv){
			$cv = json_decode(trim($cv),1);
			if(!$cv || !isset($cv['username']) ) {
				if(!$cont_arr){
					$errno = 1;break;
				}
			}else{
				if($cv['username'] == $cuser && $cv['groupid'] < $udata['groupid']) {
					$errno = 2;break;
				}
				$cont_arr[] = $cv;
			}
		}
		if(1==$errno) {
			$cmd_info = '上传文件的格式错误';
		}elseif(2==$errno) {
			$cmd_info = '覆盖后将导致管理员信息不正确';
		}else{
			
			$filepath = dir_init($recover_dir);
			$odbname = 'old_db_'.uniqid().'.php';
			$filepath .= '/';
			urlist_userdb_backup($filepath.$odbname);
			$cmd_info = '旧数据库已保存为"'.$odbname.'"';
			set_time_limit(0);
			$db->query("TRUNCATE TABLE {$gtablepre}users");
			//这个就维持覆盖本地好了
			insert_udata($cont_arr, 1);
			adminlog('uploadurdata');
			$cmd_info .= '，用户数据覆盖成功';
			
		}
	}
	
	$urcmd = '';
}elseif(!empty($pagecmd) && $pagecmd == 'download'){
	$filepath = dir_init(GAME_ROOT.'./gamedata/cache/user_backup');
	$filepath .= '/';
	$sitename = explode('.',gurl());
	if(sizeof($sitename) <= 2) $sitename = $sitename[0];
	else $sitename = $sitename[1];
	$filename = 'userdb_'.$sitename.'_'.uniqid().'.php';
	urlist_userdb_backup($filepath.$filename);
	
	adminlog('downloadurdata');
	$file_size = filesize($filepath.$filename);
	ob_clean();
	header("Content-type: application/octet-stream");  
  header("Accept-Ranges: bytes");  
  header("Accept-Length: ".$file_size);  
  header("Content-Disposition: attachment; filename={$filename}");  
  $fp = fopen($filepath.$filename,"r");
  $buffer_size = 4096;
  $cur_pos = 0;
    
  while(!feof($fp))
  {
    $buffer = fread($fp,$buffer_size);
    echo $buffer;
    $cur_pos += $buffer_size;
  }
    
  die();
}
if(!isset($urcmd)){$urcmd = '';}
if($urcmd){
	if(!isset($start)){$start = 0;}
	if(!isset($pagemode)){$pagemode = '';}
	$start = getstart($start,$pagemode);
	if($pagecmd == 'check'){
		if(empty($urorder) || !in_array($urorder,Array('uid','groupid','lastgame'))){
			$urorder = 'uid';
		}
		$urorder2 = $urorder2 == 'ASC' ? 'ASC' : 'DESC';
		$urdata = fetch_udata('*', '1', "$urorder $urorder2, uid DESC LIMIT $start,$showlimit");
	}elseif($pagecmd == 'find'){
		if($checkmode == 'ip') {
			$urdata = fetch_udata('*', "ip LIKE '%{$checkinfo}%'", "uid DESC LIMIT $start,$showlimit");
		} else {
			$urdata = fetch_udata('*', "username LIKE '%{$checkinfo}%'", "uid DESC LIMIT $start,$showlimit");
		}
	}
	if(empty($urdata)) {
		$cmd_info = '没有符合条件的帐户！';
		$startno = $start + 1;
		$resultinfo = '位置：第'.$startno.'条记录';
	} else {
		foreach($urdata as &$ur) {
			if(!$ur['gender']) $ur['gender']='0';
			$ur['card_data'] = json_encode(gdecode($ur['card_data'],1));
			$ur['a_achievements'] = json_encode(\achievement_base\decode_achievements($ur));
		}
		unset($ur);
		$startno = $start + 1;
		$endno = $start + count($urdata);
		$resultinfo = '第'.$startno.'条-第'.$endno.'条记录';
	}
}
if($urcmd == 'ban' || $urcmd == 'unban' || $urcmd == 'del' || $urcmd == 'sendmessage') {
	$operlist = $gfaillist = $ffaillist = array();
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'user_'.$i})) {
			if(isset($urdata[$i]) && $urdata[$i]['uid'] == ${'user_'.$i} && ($urdata[$i]['groupid'] < $mygroup || $urcmd == 'sendmessage')){ //发邮件不需要权限更大
				$operlist[${'user_'.$i}] = $urdata[$i]['username'];
				if($urcmd == 'ban'){
					$urdata[$i]['groupid'] = 0;
				}elseif($urcmd == 'unban'){
					$urdata[$i]['groupid'] = 1;
				}elseif($urcmd == 'del'){
					unset($urdata[$i]);
				}
			}elseif(isset($urdata[$i]) && $urdata[$i]['uid'] == ${'user_'.$i}){
				$gfaillist[${'user_'.$i}] = $urdata[$i]['username'];
			}else{
				$ffaillist[] = ${'user_'.$i};
			}			
		}
	}
	if($operlist || $gfaillist || $ffaillist){
		$cmd_info = '';
		if($urcmd == 'sendmessage'){
			$operword = '发送邮件给';
		}elseif($urcmd == 'ban'){
			$operword = '封停';
			$cmdgroupid = 0;
		}elseif($urcmd == 'unban'){
			$operword = '解封';
			$cmdgroupid = 1;
		}elseif($urcmd == 'del'){
			$operword = '删除';
			$cmdgroupid = -1;
		}
		if($operlist){
			if($urcmd == 'sendmessage'){
				include_once './include/messages.func.php';
				foreach($operlist as $receiver){
					message_create($receiver, $stitle, $scontent, $senclosure, $from='sys');
				}
				$opernames = implode(',',($operlist));
				$cmd_info .= " 给帐户 $opernames 发送了邮件 。<br>";
				adminlog($urcmd.'ur',$opernames,gencode($stitle,$scontent,$senclosure));
			}else{
				$qrywhere = 'uid IN ('.implode(',',array_keys($operlist)).')';
				$opernames = implode(',',($operlist));
				if(-1 == $cmdgroupid) {
					delete_udata($qrywhere);
				}else{
					update_udata(array('groupid' => $cmdgroupid), $qrywhere);
				}
				//$db->query("$qryword WHERE uid IN $qrywhere");
				$cmd_info .= " 帐户 $opernames 被 $operword 。<br>";
				adminlog($urcmd.'ur',$opernames);
			}
		}
		if($gfaillist){
			$gfailnames = implode(',',($gfaillist));
			$cmd_info .= " 权限不够，无法 $operword 帐户 $gfailnames 。<br>";
		}
		if($ffaillist){
			$ffailnames = implode(',',($ffaillist));
			$cmd_info .= " UID为 $ffailnames 的帐户不在当前查询范围。<br>";
		}
	}else{
		$cmd_info = "指定的帐户超出查询范围或指令错误。";
	}
	$urcmd = 'list';
}elseif($urcmd == 'del2') {
	//这个就不更新了
	$result = $db->query("SELECT username,uid FROM {$gtablepre}users WHERE lastgame = 0 AND groupid<='$mygroup' LIMIT 1000");
	while($ddata = $db->fetch_array($result)){
		$n = $ddata['username'];$u = $ddata['uid'];
		adminlog('delur',$n);
		echo " 帐户 $n 被删除。<br>";
		$db->query("DELETE FROM {$gtablepre}users WHERE uid='$u'");
	}
}elseif(strpos($urcmd ,'edit')===0) {
	$uid = explode('_',$urcmd);
	$no = (int)$uid[1];
	$uid = (int)$uid[2];
	if(!$uid){
		$cmd_info = "帐户UID错误。";
	}elseif(!isset($urdata[$no]) || $urdata[$no]['uid'] != $uid){
		$cmd_info = "该帐户不存在或超出查询范围。";
	}elseif($urdata[$no]['groupid'] >= $mygroup && $urdata[$no]['username'] != $cuser){
		$cmd_info = "权限不够，不能修改此帐户信息！";
	}else{
		include_once './include/user.func.php';
		$log_old_data = $urdata[$no];
		$urdata[$no]['motto'] = $urmotto = astrfilter(${'motto_'.$no});
		$urdata[$no]['killmsg'] = $urkillmsg = astrfilter(${'killmsg_'.$no});
		$urdata[$no]['lastword'] = $urlastword = astrfilter(${'lastword_'.$no});
		$urdata[$no]['gold'] = $urgold = astrfilter(${'gold_'.$no});
		$urdata[$no]['icon'] = $uricon = (int)(${'icon_'.$no});
		$urdata[$no]['card_data'] = astrfilter(${'card_data_'.$no});
		$urdata[$no]['a_achievements'] = astrfilter(${'a_achievements_'.$no});
		
		$tmp_urcd = json_decode(htmlspecialchars_decode(${'card_data_'.$no}),1);
		//if($tmp_urcd) echo var_export($tmp_urcd,1);
		if($tmp_urcd) $ur_card_data = gencode($tmp_urcd);
		
		$tmp_urna = json_decode(htmlspecialchars_decode(${'a_achievements_'.$no}),1);		
		if($tmp_urna) $ur_achievements = \achievement_base\encode_achievements($tmp_urna);
		
		if(!in_array(${'gender_'.$no},array('0','m','f'))){
			$urdata[$no]['gender'] = $urgender = '0';
		}else{
			$urdata[$no]['gender'] = $urgender = ${'gender_'.$no};
		}
		
		$log_new_data = $urdata[$no];
		$log_new_data['card_data'] = htmlspecialchars_decode(${'card_data_'.$no});
		$log_new_data['a_achievements'] = htmlspecialchars_decode(${'a_achievements_'.$no});
		
		$cmd_info = '';
		$updarr = array(
			'motto' => $urmotto,
			'killmsg' => $urkillmsg,
			'lastword' => $urlastword,
			'icon' => $uricon,
			'gender' => $urgender,
			'gold' => $urgold,
//			'cardlist' => $urcardlist,
		);
		
		$extrasql='';
		if(!empty(${'pass_'.$no})){
			$urpass = create_storedpass($urdata[$no]['username'], create_cookiepass(${'pass_'.$no}));
			$updarr['password'] = $urpass;
			$updarr['alt_pswd'] = 1;
			$cmd_info = "修改了帐户 {$urdata[$no]['username']} 的密码！<br>";
		}
		if(empty($tmp_urcd)) {
			$cmd_info.="提交的卡片参数无效，已被忽略！<br>";
		}else{
			$updarr['card_data'] = $ur_card_data;
		}
		if(empty($tmp_urna)) {
			$cmd_info.="提交的成就参数无效，已被忽略！<br>";
		}else{
			$updarr['u_achievements'] = $ur_achievements;
		}
		update_udata($updarr, "uid='$uid'");
		$cmd_info .= "帐户 ".$urdata[$no]['username']." 的信息已修改！";
		
		//为了记录具体改了啥真是大费周章啊
		$log_diff_data = array_diff_assoc($log_new_data,$log_old_data);
		if(isset($log_diff_data['a_achievements'])) {
			$log_new_data['a_achievements'] = json_decode($log_new_data['a_achievements'],1);
			$log_old_data['a_achievements'] = json_decode($log_old_data['a_achievements'],1);
			if($log_new_data['a_achievements'][326] !== $log_old_data['a_achievements'][326])//特判
			{
				$log_diff326 = array_diff($log_new_data['a_achievements'][326], $log_old_data['a_achievements'][326]);
				$log_new_data['a_achievements'][326] = json_encode($log_diff326); 
				$log_old_data['a_achievements'][326] = '';
			}else{
				$log_old_data['a_achievements'][326] = $log_new_data['a_achievements'][326] = '';
			}
			$log_diff_data['a_achievements'] =array_diff_assoc($log_new_data['a_achievements'], $log_old_data['a_achievements']);
		}
		if(isset($log_diff_data['card_data'])) {
			$log_new_data['card_data'] = json_decode($log_new_data['card_data'],1);
			foreach($log_new_data['card_data'] as &$v) $v = json_encode($v);
			$log_old_data['card_data'] = json_decode($log_old_data['card_data'],1);
			foreach($log_old_data['card_data'] as &$v) $v = json_encode($v);
			$log_diff_data['card_data'] =array_diff_assoc($log_new_data['card_data'], $log_old_data['card_data']);
			//echo var_export($log_diff_data['card_data'],1).'<br>';
		}
		adminlog('editur',$urdata[$no]['username'],gencode($log_diff_data));
	}
	$urcmd = 'list';
}
include template('admin_urlist');

//直接读本地数据库比较快，也省内存
function urlist_userdb_backup($file){
	global $db, $gtablepre, $checkstr;
	$result = $db->query("SELECT * FROM {$gtablepre}users");
	writeover($file, $checkstr);
	while($r = $db->fetch_array($result)){
		writeover($file, json_encode($r, JSON_UNESCAPED_UNICODE)."\r\n", 'ab+');
	}
	return $file;
}
?>