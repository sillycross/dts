<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}

$gmdata = fetch_udata('uid,username,groupid', 'groupid > 1', 'groupid DESC', 2);
$cmd_info = '';
if($command == 'add') {
	$addgroup = intval($addgroup);
	if(!$addname) {
		$cmd_info =  '必须填写GM账号';
	} elseif ($addgroup < 2 || $addgroup >= $mygroup || $addgroup > 10) {
		$cmd_info =  '权限设置错误！';
	} else {
		$newgm = fetch_udata_by_username($addname, 'uid,username,groupid');
		if(empty($result)) { 
			$cmd_info =  '此账号不存在。'; 
		} else {
			if($newgm['groupid'] >1){
				$cmd_info =  '此账号已经是管理员！'; 
			}else{
				update_udata_by_username(array('groupid' => $addgroup), $addname);
				adminlog('addgm',$addname,$addgroup);
				$cmd_info =  "管理员 {$addname} 添加成功，权限等级：{$addgroup}";
				$newgm['groupid'] = $addgroup;
				$gmdata[$newgm['uid']] = $newgm;
			}				
		}
	}
	$command = 'gmlist';
} elseif($command == 'del') {
	$adminuid = intval($adminuid);
	if(isset($gmdata[$adminuid])) {
		$uid = $gmdata[$adminuid]['uid'];
		if($gmdata[$adminuid]['groupid'] >= $mygroup){
			$cmd_info = "权限不够，不能删除管理员 {$gmdata[$adminuid]['username']}！";
		} else {
			update_udata(array('groupid' => 1), "uid='$uid'");
			adminlog('delgm',$gmdata[$adminuid]['username']);
			$cmd_info =  "管理员 {$gmdata[$adminuid]['username']} 的管理权限被删除！";
			unset($gmdata[$adminuid]);
		}
	}else{
		$cmd_info =  "请输入正确的数据！";
	}
	$command = 'gmlist';
} elseif($command == 'edit') {
	$adminuid = intval($adminuid);
	$editgroup = intval($_POST[$adminuid.'_group']);
	if(isset($gmdata[$adminuid])) {
		if ( $editgroup < 2 || $editgroup >= $mygroup || $editgroup > 10) {
			$cmd_info =  '权限设置错误！';
		} elseif($gmdata[$adminuid]['groupid'] >= $mygroup) {
			$cmd_info =  "权限不够，不能编辑管理员 {$editname} ！<br>";
		} else {
			update_udata(array('groupid' => $editgroup), "uid='$adminuid'");
			adminlog('editgm',$gmdata[$adminuid]['username'],$editgroup);
			$cmd_info =  "管理员 {$gmdata[$adminuid]['username']} 权限修改成功，权限等级：{$editgroup}";
			$gmdata[$adminuid]['groupid'] = $editgroup;
		}
	}else{
		$cmd_info =  "请输入正确的数据！";
	}
	$command = 'gmlist';
}
include template('admin_gmlist');
?>