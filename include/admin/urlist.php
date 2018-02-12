<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
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
		$result = $db->query("SELECT * FROM {$gtablepre}users ORDER BY $urorder $urorder2, uid DESC LIMIT $start,$showlimit");	
	}elseif($pagecmd == 'find'){
		if($checkmode == 'ip') {
			$result = $db->query("SELECT * FROM {$gtablepre}users WHERE ip LIKE '%{$checkinfo}%' ORDER BY uid DESC LIMIT $start,$showlimit");
		} else {
			$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username LIKE '%{$checkinfo}%' ORDER BY uid DESC LIMIT $start,$showlimit");
		}
	}
	if(!$db->num_rows($result)) {
		$cmd_info = '没有符合条件的帐户！';
		$startno = $start + 1;
		$resultinfo = '位置：第'.$startno.'条记录';
	} else {
		while($ur = $db->fetch_array($result)) {
			if(!$ur['gender']){$ur['gender']='0';}
			$ur['a_achievements'] = json_encode(\achievement_base\decode_achievements($ur));
			$urdata[] = $ur;
		}
		$startno = $start + 1;
		$endno = $start + count($urdata);
		$resultinfo = '第'.$startno.'条-第'.$endno.'条记录';
	}
}
if($urcmd == 'ban' || $urcmd == 'unban' || $urcmd == 'del' || $urcmd == 'sendmessage') {
	$operlist = $gfaillist = $ffaillist = array();
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'user_'.$i})) {
			if(isset($urdata[$i]) && $urdata[$i]['uid'] == ${'user_'.$i} && $urdata[$i]['groupid'] < $mygroup){
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
			$qryword = "UPDATE {$gtablepre}users SET groupid='0' ";
		}elseif($urcmd == 'unban'){
			$operword = '解封';
			$qryword = "UPDATE {$gtablepre}users SET groupid='1' ";
		}elseif($urcmd == 'del'){
			$operword = '删除';
			$qryword = "DELETE FROM {$gtablepre}users ";
		}
		if($operlist){
			if($urcmd == 'sendmessage'){
				include_once './include/messages.func.php';
				foreach($operlist as $receiver){
					message_create($receiver, $stitle, $scontent, $senclosure, $from='sys');
				}
				$opernames = implode(',',($operlist));
				$cmd_info .= " 给帐户 $opernames 发送了邮件 。<br>";
				adminlog($urcmd.'ur',$opernames,array($stitle,$scontent,$senclosure));
			}else{
				$qrywhere = '('.implode(',',array_keys($operlist)).')';
				$opernames = implode(',',($operlist));
				$db->query("$qryword WHERE uid IN $qrywhere");
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
		$urdata[$no]['cardlist'] = $urcardlist = astrfilter(${'cardlist_'.$no});
		$urdata[$no]['a_achievements'] = astrfilter(${'a_achievements_'.$no});
		
		$tmp_urna = json_decode(htmlspecialchars_decode(${'a_achievements_'.$no}),1);
		
		if($tmp_urna) $ur_achievements = \achievement_base\encode_achievements($tmp_urna);
		
		if(!in_array(${'gender_'.$no},array('0','m','f'))){
			$urdata[$no]['gender'] = $urgender = '0';
		}else{
			$urdata[$no]['gender'] = $urgender = ${'gender_'.$no};
		}
		
		$log_new_data = $urdata[$no];
		$log_new_data['a_achievements'] = htmlspecialchars_decode(${'a_achievements_'.$no});
		$cmd_info = '';
		$extrasql='';
		if(!empty(${'pass_'.$no})){
			$urpass = create_storedpass($urdata[$no]['username'], create_cookiepass(${'pass_'.$no}));
			$extrasql.=",password='$urpass'";
			$cmd_info = "修改了帐户 {$urdata[$no]['username']} 的密码！<br>";
		}
		if(empty($tmp_urna)) {
			$cmd_info.="提交的成就参数无效，已被忽略！<br>";
		}else{
			$extrasql.=",u_achievements='$ur_achievements'";
		}
		
		$db->query("UPDATE {$gtablepre}users SET motto='$urmotto',killmsg='$urkillmsg',lastword='$urlastword',icon='$uricon',gender='$urgender',gold='$urgold',cardlist='$urcardlist'{$extrasql} WHERE uid='$uid'");
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
		adminlog('editur',$urdata[$no]['username'],gencode($log_diff_data));
	}
	$urcmd = 'list';
}
include template('admin_urlist');

?>