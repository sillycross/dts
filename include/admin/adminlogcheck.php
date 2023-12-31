<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
include GAME_ROOT.'./templates/default/templates.lang.php';

$lang = array_merge($language, $lang);
$adminlogfile = GAME_ROOT.'./gamedata/adminlog_nf.php';
$adminlogdata = array();
if(file_exists($adminlogfile)) {
	foreach(openfile($adminlogfile) as $aval){
		$expl = explode(',',$aval);
		if(is_numeric($expl[0]))
			$adminlogdata[] = $expl;
	}
}
$showdata=array();
foreach($adminlogdata as $aval){
	list($t, $u, $o, $p1, $p2, $p3) = $aval;
	$t = gmstrftime("%Y-%m-%d  %X",$t);
	$show_o = $show_p = $show_gnum = '';
	if('infomng'==$o) {
		$show_o = '游戏自检';
	}elseif('killafker'==$o) {
		$show_o = '手动清挂机';
		$show_gnum = adminlog_parse_gnum($p1,$p2);
		$show_p = implode(' ',gdecode($p3,1));
	}elseif('roomclose'==$o) {
		$show_o = '关闭房间';
		$show_p = $p1.'号房间';
	}elseif('addgm'==$o || 'delgm'==$o || 'editgm'==$o) {
		if('addgm'==$o) $show_o = '添加权限';
		elseif('delgm'==$o) $show_o = '删除权限';
		elseif('editgm'==$o) $show_o = '修改权限';
		$p2=(int)$p2;
		$show_p = '账户 <span class="cyan b">'.$p1.'</span> → <span class="yellow b">'.$p2.'权限</span>';
	}elseif('editbanlist'==$o) {
		$show_o = '修改屏蔽词/IP';
		$nmlimit = gdecode($p1,1);$iplimit = gdecode($p2,1);
		$show_p = '用户名屏蔽：<br>'.htmlentities($nmlimit).'<br>IP屏蔽：<br>'.htmlentities($iplimit);
	}elseif('configmng'==$o || 'gamecfgmng'==$o || 'globalgamemng'==$o || 'systemmng'==$o){
		$show_o = $lang[$o];
//		if('configmng'==$o) $show_o = '底层参数设置';
//		elseif('globalgamemng'==$o) $show_o = '全局参数设置';
//		elseif('gamecfgmng'==$o) $show_o = '游戏参数设置';
		$edlist = gdecode($p1,1);
		foreach($edlist as $edk => $edv){
			if('adminmsg' == $edk || 'systemmsg' == $edk) $edv = '<a title="'.str_replace('"',"'",$edv).'">悬浮查看</a>';
			if(isset($lang[$edk])) $edk = $lang[$edk];
			if(is_array($edv)) {
				foreach($edv as $edvk => $edvv){
					if(isset($lang[$edvk])) $edvk = $lang[$edvk];
					if(strpos($edk, '密码')!==false) $edv = '****';
					$show_p .= $edvk.' → '.$edvv.'<br>';
				}
			}else{
				if(strpos($edk, '密码')!==false) $edv = '****';
				$show_p .= $edk.' → '.$edv.'<br>';
			}
		}
	}elseif(in_array($o, array('wthedit','hackedit','gsedit','gameover','addarea','gametypeset'))){
		if('wthedit'==$o) {
			$show_o = '修改天气';
			eval(import_module('weather'));
			$show_p = $wthinfo[$p3];
		}elseif('hackedit'==$o) {
			$show_o = '修改禁区状态';
			$show_p = $p3 ? '解除' : '未解除';
		}elseif('gsedit'==$o) {
			$show_o = '游戏状态设置';
			$show_p = $gstate[$p3];
		}elseif('gameover'==$o) {
			$show_o = '中止游戏';
		}elseif('addarea'==$o) {
			if('I'==$p3) $show_o = '立刻增加禁区';
			elseif('L'==$p3) $show_o = '60s后增加禁区';
		}elseif('gametypeset'==$o) {
			$show_o = '修改游戏类别';
			$show_p = $gtinfo[$p3];
		}
		$show_gnum = adminlog_parse_gnum($p1,$p2);
	}elseif(in_array($o, array('killpc', 'livepc', 'delpc', 'delcp', 'editpc', 'killnpc', 'livenpc', 'delnpc', 'delncp', 'editnpc'))){
		if(strpos($o, 'kill')===0) {
			$show_o = '杀死';
		}elseif(strpos($o, 'live')===0) {
			$show_o = '复活';
		}elseif(strpos($o, 'del')===0) {
			$show_o = '清除';
		}elseif(strpos($o, 'edit')===0) {
			$show_o = '修改';
		}
		if('n' == substr($o, strlen($o)-3, 1)) $show_o .= 'NPC';
		else $show_o .= '玩家';
		if('cp' == substr($o, strlen($o)-2)) $show_o .= '尸体';
		if(strpos($o, 'edit')===0){
			list($a1,$a2) = explode('_',$p1);
			$show_gnum = adminlog_parse_gnum($a1,$a2);
			$show_p = '<span class="yellow b">'.$p2.'</span> 修改内容：<br>';
			$diff = gdecode($p3,1);
			foreach($diff as $dk => $dv){
				if(isset($lang[$dk])) $dk = $lang[$dk];
				elseif('pls'==$dk) $dk = '当前位置';
				elseif('nskill'==$dk) $dk = '添加技能';
				elseif('nskilldel'==$dk) $dk = '删除技能';
				elseif('nskillpara'==$dk) $dk = '技能参数修改';
				elseif('nskillparadel'==$dk) $dk = '技能参数删除';
				$show_p .= $dk.' → '.$dv.'<br>';
			}
		}else{
			$show_gnum = adminlog_parse_gnum($p1,$p2);
			$show_p = '<span class="yellow b">'.$p3.'</span>';
		}
	}elseif('downloadurdata'==$o) {
		$show_o = '下载用户数据库';
	}elseif('uploadurdata'==$o) {
		$show_o = '上传并覆盖用户数据库';
	}elseif(in_array($o, array('banur','unbanur','delur','delur2','editur'))){
		if('banur'==$o) {
			$show_o = '封禁账户';
		}elseif('unbanur'==$o) {
			$show_o = '解封账户';
		}elseif('delur'==$o) {
			$show_o = '删除账户';
		}elseif('delur2'==$o) {
			$show_o = '批量删除账户';
		}elseif('editur'==$o) {
			$show_o = '修改账户数据';
		}
		$show_p = '<span class="yellow b">'.$p1.'</span>';
		if('editur'==$o) {
			$show_p .= ' 修改内容：<br>';
			$cont = gdecode($p2,1);
			foreach($cont as $ck => $cv){
				if(is_array($cv)) $cv = json_encode($cv);
				if(isset($lang[$ck])) $ck = $lang[$ck];
				if('gender'==$ck) $ck='默认性别';
				elseif('gold'==$ck) $ck='切糕';
//				elseif('motto'==$ck) $ck='口头禅';
//				elseif('lastword'==$ck) $ck='遗言';
				elseif('a_achievements'==$ck || 'cardlist'==$ck) {
					if('a_achievements'==$ck) $ck='成就';
					else $ck='卡片';
					$cv = '<a title="'.str_replace('"',"'",$cv).'">悬浮查看</a>';
				}
				$show_p .= $ck.' → '.$cv.'<br>';
			}
		}
	}elseif('sendmessageur' == $o){
		$show_o = '发送站内邮件';
		$show_p = '收件账户：<span class="yellow b">'.$p1.'</span>&nbsp;&nbsp;&nbsp;';
		if('Array' != $p2) {
			$show_p .= '邮件标题：'.gdecode($p2);
		}
	}else{
		$show_o = $o;
		$show_p = $p1.' '.$p2.' '.$p3;
	}
	$showdata[] = array($t, $u, $show_o, $show_p, $show_gnum);
}

function adminlog_parse_gnum($p1,$p2){
	return (!empty($p1) ? '房间区' : '').'第'.$p2.'局';
}
include template('admin_adminlogcheck');

?>