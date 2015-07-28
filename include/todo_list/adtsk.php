<?php


if(!defined('IN_GAME')) {
	exit('Access Denied');
}

function getword(){
	global $db,$tablepre,$name,$motto,$lastword,$killmsg;
	
	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$name'");
	$userinfo = $db->fetch_array($result);
	$motto = $userinfo['motto'];
	$lastword = $userinfo['lastword'];
	$killmsg = $userinfo['killmsg'];
	
}

function chgword($nmotto,$nlastword,$nkillmsg) {
	global $db,$tablepre,$name,$log;
	
	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$name'");
	$userinfo = $db->fetch_array($result);

//	foreach ( Array('<','>',';',',','\\\'','\\"') as $value ) {
//		if(strpos($nmotto,$value)!==false){
//			$nmotto = str_replace ( $value, '', $nmotto );
//		}
//		if(strpos($nlastword,$value)!==false){
//			$nlastword = str_replace ( $value, '', $nlastword );
//		}
//		if(strpos($nkillmsg,$value)!==false){
//			$nkillmsg = str_replace ( $value, '', $nkillmsg );
//		}
//	}

	
	if($nmotto != $userinfo['motto']) {
		$log .= $nmotto == '' ? '口头禅已清空。' : '口头禅变更为<span class="yellow">'.$nmotto.'</span>。<br>';
	}
	if($nlastword != $userinfo['lastword']) {
		$log .= $nlastword == '' ? '遗言已清空。' : '遗言变更为<span class="yellow">'.$nlastword.'</span>。<br>';
	}
	if($nkillmsg != $userinfo['killmsg']) {
		$log .= $nkillmsg == '' ? '杀人留言已清空。' : '杀人留言变更为<span class="yellow">'.$nkillmsg.'</span>。<br>';
	}

	$db->query("UPDATE {$gtablepre}users SET motto='$nmotto', lastword='$nlastword', killmsg='$nkillmsg' WHERE username='$name'");
	
	$mode = 'command';
	return;
}

function chgpassword($oldpswd,$newpswd,$newpswd2){
	global $db,$tablepre,$name,$log;
	
	if (!$oldpswd || !$newpswd || !$newpswd2){
		$log .= '放弃了修改密码。<br />';
		$mode = 'command';
		return;
	} elseif ($newpswd !== $newpswd2) {
		$log .= '<span class="red">两次输入的新密码不一致。</span><br />';
		$mode = 'command';
		return;
	}
	
	$oldpswd = md5($oldpswd);$newpswd = md5($newpswd);
	
	$result = $db->query("SELECT * FROM {$gtablepre}users WHERE username='$name'");
	$userinfo = $db->fetch_array($result);
	
	if($oldpswd == $userinfo['password']){
		$db->query("UPDATE {$gtablepre}users SET `password` ='$newpswd' WHERE username='$name'");
		$log .= '<span class="yellow">密码已修改！</span><br />';
		
		//include_once GAME_ROOT.'./include/global.func.php';
		
		gsetcookie('pass',$newpswd);
		$mode = 'command';
		return;
	}else{
		$log .= '<span class="red">原密码输入错误！</span><br />';
		$mode = 'command';
		return;
	}
}

function adtsk(){
	global $log,$mode,$club,$wep,$wepk,$wepe,$weps,$wepsk;
	if($wepk == 'WN' || !$wepe || !$weps){
		$log .= '<span class="red">你没有装备武器，无法改造！</span><br />';
		$mode = 'command';
		return;
	}
	if (strpos($wepsk,'j')!==false){
				$log.='多重武器不能改造。<br>';
				$mode='command';
				return;
			}
	if($club == 7){//电脑社，电气改造
		$position = 0;
		foreach(Array(1,2,3,4,5,6) as $imn){
			global ${'itm'.$imn},${'itmk'.$imn},${'itme'.$imn},${'itms'.$imn},${'itmsk'.$imn};
			if(strpos(${'itmk'.$imn},'B')===0 && ${'itme'.$imn} > 0 ){
				$position = $imn;
				break;
			}
		}
		if($position){
			if(strpos($wepsk,'e')!==false){
				$log .= '<span class="red">武器已经带有电击属性，不用改造！</span><br />';
				$mode = 'command';
				return;
			}elseif(strlen($wepsk)>=5){
				$log .= '<span class="red">武器属性数目达到上限，无法改造！</span><br />';
				$mode = 'command';
				return;
			}
			
			
			${'itms'.$position}-=1;
			$itm = ${'itm'.$position};
			$log .= "<span class=\"yellow\">用{$itm}改造了{$wep}，{$wep}增加了电击属性！</span><br />";
			$wep = '电气'.$wep;
			$wepsk .= 'e';
			if(${'itms'.$position} == 0){
				$log .= "<span class=\"red\">$itm</span>用光了。<br />";
				${'itm'.$position} = ${'itmk'.$position} = ${'itmsk'.$position} = '';
				${'itme'.$position} =${'itms'.$position} =0;				
			}
			$mode = 'command';
			return;
		}else{
			$log .= '<span class="red">你没有电池，无法改造武器！</span><br />';
			$mode = 'command';
			return;
		}
	}elseif($club == 8){//带毒改造
		$position = 0;
		foreach(Array(1,2,3,4,5,6) as $imn){
			global ${'itm'.$imn},${'itmk'.$imn},${'itme'.$imn},${'itms'.$imn},${'itmsk'.$imn};
			if(${'itm'.$imn} == '毒药' && ${'itmk'.$imn} == 'Y' && ${'itme'.$imn} > 0 ){
				$position = $imn;
				break;
			}
		}
		if($position){
			if(strpos($wepsk,'p')!==false){
				$log .= '<span class="red">武器已经带毒，不用改造！</span><br />';
				$mode = 'command';
				return;
			}elseif(strlen($wepsk)>=5){
				$log .= '<span class="red">武器属性数目达到上限，无法改造！</span><br />';
				$mode = 'command';
				return;
			}
			$wepsk .= 'p';
			$log .= "<span class=\"yellow\">用毒药为{$wep}淬毒了，{$wep}增加了带毒属性！</span><br />";
			$wep = '毒性'.$wep;
			${'itms'.$position}-=1;
			$itm = ${'itm'.$position};
			if(${'itms'.$position} == 0){
				$log .= "<span class=\"red\">$itm</span>用光了。<br />";
				${'itm'.$position} = ${'itmk'.$position} = ${'itmsk'.$position} = '';
				${'itme'.$position} =${'itms'.$position} =0;				
			}
			$mode = 'command';
			return;
		}else{
			$log .= '<span class="red">你没有毒药，无法给武器淬毒！</span><br />';
			$mode = 'command';
			return;
		}
	}else{
		$log .= '<span class="red">你不懂得如何改造武器！</span><br />';
		$mode = 'command';
		return;
	}
}

function chkpoison($itmn){
	global $log,$mode,$club;
	if($club != 8){
		$log .= '你不会查毒。';
		$mode = 'command';
		return;
	}

	if ( $itmn < 1 || $itmn > 6 ) {
		$log .= '此道具不存在，请重新选择。';
		$mode = 'command';
		return;
	}

	global ${'itm'.$itmn},${'itmk'.$itmn},${'itme'.$itmn},${'itms'.$itmn},${'itmsk'.$itmn};
	$itm = & ${'itm'.$itmn};
	$itmk = & ${'itmk'.$itmn};
	$itme = & ${'itme'.$itmn};
	$itms = & ${'itms'.$itmn};
	$itmsk = & ${'itmsk'.$itmn};

	if(!$itms) {
		$log .= '此道具不存在，请重新选择。<br>';
		$mode = 'command';
		return;
	}
	
	if(strpos($itmk,'P') === 0) {
		$log .= '<span class="red">'.$itm.'有毒！</span>';
	} else {
		$log .= '<span class="yellow">'.$itm.'是安全的。</span>';
	}
	$mode = 'command';
	return;
}

?>