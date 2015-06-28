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
		$result = $db->query("SELECT * FROM {$tablepre}users ORDER BY $urorder $urorder2, uid DESC LIMIT $start,$showlimit");	
	}elseif($pagecmd == 'find'){
		if($checkmode == 'ip') {
			$result = $db->query("SELECT * FROM {$tablepre}users WHERE ip LIKE '%{$checkinfo}%' ORDER BY uid DESC LIMIT $start,$showlimit");
		} else {
			$result = $db->query("SELECT * FROM {$tablepre}users WHERE username LIKE '%{$checkinfo}%' ORDER BY uid DESC LIMIT $start,$showlimit");
		}
	}
	if(!$db->num_rows($result)) {
		$cmd_info = '没有符合条件的帐户！';
		$startno = $start + 1;
		$resultinfo = '位置：第'.$startno.'条记录';
	} else {
		while($ur = $db->fetch_array($result)) {
			if(!$ur['gender']){$ur['gender']='0';}
			$urdata[] = $ur;			
		}
		$startno = $start + 1;
		$endno = $start + count($urdata);
		$resultinfo = '第'.$startno.'条-第'.$endno.'条记录';
	}
}
if($urcmd == 'ban' || $urcmd == 'unban' || $urcmd == 'del') {
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
//				adminlog('banur',$urdata[$i]['username']);
			}elseif(isset($urdata[$i]) && $urdata[$i]['uid'] == ${'user_'.$i}){
				$gfaillist[${'user_'.$i}] = $urdata[$i]['username'];
			}else{
				$ffaillist[] = ${'user_'.$i};
			}			
		}
	}
	if($operlist || $gfaillist || $ffaillist){
		$cmd_info = '';
		if($urcmd == 'ban'){
			$operword = '封停';
			$qryword = "UPDATE {$tablepre}users SET groupid='0' ";
		}elseif($urcmd == 'unban'){
			$operword = '解封';
			$qryword = "UPDATE {$tablepre}users SET groupid='1' ";
		}elseif($urcmd == 'del'){
			$operword = '删除';
			$qryword = "DELETE FROM {$tablepre}users ";
		}
		if($operlist){
			$qrywhere = '('.implode(',',array_keys($operlist)).')';
			$opernames = implode(',',($operlist));
			$db->query("$qryword WHERE uid IN $qrywhere");
			$cmd_info .= " 帐户 $opernames 被 $operword 。<br>";
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
}  elseif($urcmd == 'del2') {
	$result = $db->query("SELECT username,uid FROM {$tablepre}users WHERE lastgame = 0 AND groupid<='$mygroup' LIMIT 1000");
	while($ddata = $db->fetch_array($result)){
		$n = $ddata['username'];$u = $ddata['uid'];
		adminlog('delur',$n);
		echo " 帐户 $n 被删除。<br>";
		$db->query("DELETE FROM {$tablepre}users WHERE uid='$u'");
	}
}elseif(strpos($urcmd ,'edit')===0) {
	$uid = explode('_',$urcmd);
	$no = (int)$uid[1];
	$uid = (int)$uid[2];
	if(!$uid){
		$cmd_info = "帐户UID错误。";
	}elseif(!isset($urdata[$no]) || $urdata[$no]['uid'] != $uid){
		$cmd_info = "该帐户不存在或超出查询范围。";
	}elseif($urdata[$no]['groupid'] >= $mygroup){
		$cmd_info = "权限不够，不能修改此帐户信息！";
	}else{
		$urdata[$no]['motto'] = $urmotto = astrfilter(${'motto_'.$no});
		$urdata[$no]['killmsg'] = $urkillmsg = astrfilter(${'killmsg_'.$no});
		$urdata[$no]['lastword'] = $urlastword = astrfilter(${'lastword_'.$no});
		$urdata[$no]['icon'] = $uricon = (int)(${'icon_'.$no});
		if(!in_array(${'gender_'.$no},array('0','m','f'))){
			$urdata[$no]['gender'] = $urgender = '0';
		}else{
			$urdata[$no]['gender'] = $urgender = ${'gender_'.$no};
		}
		if(!empty(${'pass_'.$no})){
			$urpass = md5(${'pass_'.$no});
			$db->query("UPDATE {$tablepre}users SET motto='$urmotto',killmsg='$urkillmsg',lastword='$urlastword',icon='$uricon',gender='$urgender',password='$urpass' WHERE uid='$uid'");
			$cmd_info = "帐户 ".$urdata[$no]['username']." 的密码及其他信息已修改！";
		}else{
			$db->query("UPDATE {$tablepre}users SET motto='$urmotto',killmsg='$urkillmsg',lastword='$urlastword',icon='$uricon',gender='$urgender' WHERE uid='$uid'");
			$cmd_info = "帐户 ".$urdata[$no]['username']." 的信息已修改！";
		}		
	}
	$urcmd = 'list';
}
include template('admin_urlist');



function urlist($htm,$cmd='',$start=0) {
}

?>

