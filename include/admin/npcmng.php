<?php
if(!defined('IN_ADMIN')) {
	exit('Access Denied');
}
if(!isset($command)){$command = 'list';}
if(!isset($start)){$start = 0;}
if(!isset($checkmode)){$checkmode = '';}
if(!isset($checkinfo)){$checkinfo = '';}
if(!isset($pagemode)){$pagemode = '';}

if (defined('MOD_CLUBBASE')) eval(import_module('clubbase'));

$cmd_info = '';
$start = getstart($start,$pagemode);
$resultinfo = '';
if($command != 'submitedit'){	
	$npcdata = dbsearch($start,$checkmode,$checkinfo);
}


if($command == 'kill' || $command == 'live' || $command == 'del') {
	$operlist = $operlist2 = $dfaillist = $gfaillist = array();
	for($i=0;$i<$showlimit;$i++){
		if(isset(${'npc_'.$i})) {
			if(isset($npcdata[$i]) && $npcdata[$i]['pid'] == ${'npc_'.$i}){
				if($command == 'kill'){
					if($npcdata[$i]['hp'] > 0){
						$operlist[${'npc_'.$i}] = $npcdata[$i]['name'].'(PID:'.$npcdata[$i]['pid'].')';
						$npcdata[$i]['hp'] = 0;
						$npcdata[$i]['state'] = 15;
						$deathnum ++;
						adminlog('killnpc',$room_prefix,$gamenum,$npcdata[$i]['name']);
						addnews($now,'death15',$npcdata[$i]['name']);
					}else{
						$gfaillist[] = $npcdata[$i]['name'].'(PID:'.$npcdata[$i]['pid'].')';
					}					
				}elseif($command == 'live'){
					if($npcdata[$i]['hp'] <= 0){
						$operlist[${'npc_'.$i}] = $npcdata[$i]['name'].'(PID:'.$npcdata[$i]['pid'].')';
						$npcdata[$i]['hp'] = $npcdata[$i]['mhp'];
						$npcdata[$i]['state'] = 0;
						$deathnum --;
						adminlog('livenpc',$room_prefix,$gamenum,$npcdata[$i]['name']);
						addnews($now,'alive',$npcdata[$i]['name']);
					}else{
						$gfaillist[] = $npcdata[$i]['name'].'(PID:'.$npcdata[$i]['pid'].')';
					}					
				}elseif($command == 'del'){
					
					if($npcdata[$i]['hp'] > 0){					
						$operlist[${'npc_'.$i}] = $npcdata[$i]['name'].'(PID:'.$npcdata[$i]['pid'].')';	
						$npcdata[$i]['hp'] = 0;
						$npcdata[$i]['state'] = 16;
						$deathnum ++;
						adminlog('delnpc',$room_prefix,$gamenum,$npcdata[$i]['name']);
						addnews($now,'death16',$npcdata[$i]['name']);
					}else{
						$operlist2[${'npc_'.$i}] = $npcdata[$i]['name'].'(PID:'.$npcdata[$i]['pid'].')';
						adminlog('delncp',$room_prefix,$gamenum,$npcdata[$i]['name']);
						addnews($now,'delcp',$npcdata[$i]['name']);
					}
				}
			}else{
				$dfaillist[] = ${'npc_'.$i};
			}			
		}
	}
	if($operlist || $operlist2 || $dfaillist || $gfaillist){
		if($command == 'kill'){
			$operword = '被杀死';
			$qryword = "UPDATE {$tablepre}players SET hp='0',state='15' ";
		}elseif($command == 'live'){
			$operword = '被复活';
			$qryword = "UPDATE {$tablepre}players SET hp=mhp,state='0' ";
		}elseif($command == 'del'){
			$operword = '被清除';
			$qryword = "UPDATE {$tablepre}players SET hp='0',state='16',weps='0',arbs='0',arhs='0',aras='0',arfs='0',arts='0',itms0='0',itms1='0',itms2='0',itms3='0',itms4='0',itms5='0',itms6='0',money='0' ";
			$operword2 = '的尸体被清除';
			$qryword2 = "UPDATE {$tablepre}players SET weps='0',state='16',arbs='0',arhs='0',aras='0',arfs='0',arts='0',itms0='0',itms1='0',itms2='0',itms3='0',itms4='0',itms5='0',itms6='0',money='0' ";
		}
		if($operlist){
			$qrywhere = '('.implode(',',array_keys($operlist)).')';
			$opernames = implode(',',($operlist));
			$db->query("$qryword WHERE pid IN $qrywhere");
			//echo "$qryword WHERE pid IN $qrywhere";
			$cmd_info .= " NPC $opernames $operword 。<br>";
		}
		if($operlist2){
			$qrywhere2 = '('.implode(',',array_keys($operlist2)).')';
			$opernames = implode(',',($operlist2));
			$db->query("$qryword2 WHERE pid IN $qrywhere2");
			//echo "$qryword2 WHERE pid IN $qrywhere2";
			$cmd_info .= " NPC $opernames $operword2 。<br>";
		}
		if($gfaillist){
			$gfailnames = implode(',',($gfaillist));
			$cmd_info .= " NPC $gfailnames 已经处于该状态，无法 $operword  。<br>";
		}
		if($dfaillist){
			$dfailnames = implode(',',($dfaillist));
			$cmd_info .= " PID为 $dfailnames 的NPC不存在或位于查询范围外  。<br>";
		}
		save_gameinfo();
	}else{
		$cmd_info = "指定的帐户超出查询范围或指令错误。";
	}
	$command = 'list';
} elseif(strpos($command ,'edit')===0) {
	$pid = explode('_',$command);
	$no = (int)$pid[1];
	$pid = (int)$pid[2];
	if(!$pid){
		$cmd_info = "帐户UID错误。";
	}elseif(!isset($npcdata[$no]) || $npcdata[$no]['pid'] != $pid){
		$cmd_info = "该帐户不存在或超出查询范围。";
	}else{
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$pid' AND type>'0'");
		$npc = $db->fetch_array($result);
		if(!$npc) {
			$cmd_info = "找不到角色 ".$npcdata[$no]['name']." 。";
		}else{
			$command = 'check';
		}
		\skillbase\skillbase_load($npc);
		$npc['acquired_list'] = array_keys($npc['acquired_list']);
		$npc['skillv'] = json_encode($npc['acquired_list']);
		$npc['skillparav'] = json_encode($npc['parameter_list']);
	}
} elseif($command == 'submitedit') {
	$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$pid' AND type!='0'");
	$po = $db->fetch_array($result);
	$pfields = array('gd','icon','club','sNo','hp','mhp','sp','msp','att','def','pls','lvl','exp','money','bid','inf','rage','pose','tactic','killnum','wp','wk','wg','wc','wd','wf','teamID','teamPass','wep','wepk','wepe','weps','wepsk','arb','arbk','arbe','arbs','arbsk','arh','arhk','arhe','arhs','arhsk','ara','arak','arae','aras','arask','arf','arfk','arfe','arfs','arfsk','art','artk','arte','arts','artsk','itm0','itmk0','itme0','itms0','itmsk0','itm1','itmk1','itme1','itms1','itmsk1','itm2','itmk2','itme2','itms2','itmsk2','itm3','itmk3','itme3','itms3','itmsk3','itm4','itmk4','itme4','itms4','itmsk4','itm5','itmk5','itme5','itms5','itmsk5','itm6','itmk6','itme6','itms6','itmsk6');
	$npc = $log_old_data = $log_new_data = array();
	foreach($pfields as $pfv){
		$npc[$pfv] = $log_new_data[$pfv] = $$pfv;
		$log_old_data[$pfv] = $po[$pfv];
	}
	list($log_old_data['nskill'],$log_old_data['nskillpara']) = \skillbase\skillbase_load_process($po['nskill'], $po['nskillpara']);
	unset($po,$result);
	
	$skillv = json_decode(htmlspecialchars_decode($skillv),1);
	if(!$skillv) $skillv = array();
	$skillv2 = array();
	foreach($skillv as $skkey){
		$skillv2[$skkey] = 1;
	}
	$skillparav = json_decode(htmlspecialchars_decode($skillparav),1);
	if(!$skillparav) $skillparav = array();
	$log_new_data['nskill'] = $skillv2;
	$log_new_data['nskillpara'] = $skillparav;
	
	list($npc['nskill'], $npc['nskillpara']) = \skillbase\skillbase_save_process($skillv2, $skillparav);
	unset($npc['acquired_list'], $npc['parameter_list']);
	$db->array_update("{$tablepre}players", $npc, "pid='$pid'");
	
	//$db->query("UPDATE {$tablepre}players SET gd='$gd',icon='$icon',club='$club',sNo='$sNo',hp='$hp',mhp='$mhp',sp='$sp',msp='$msp',att='$att',def='$def',pls='$pls',lvl='$lvl',exp='$exp',money='$money',bid='$bid',inf='$inf',rage='$rage',pose='$pose',tactic='$tactic',killnum='$killnum',wp='$wp',wk='$wk',wg='$wg',wc='$wc',wd='$wd',wf='$wf',teamID='$teamID',teamPass='$teamPass',wep='$wep',wepk='$wepk',wepe='$wepe',weps='$weps',wepsk='$wepsk',arb='$arb',arbk='$arbk',arbe='$arbe',arbs='$arbs',arbsk='$arbsk',arh='$arh',arhk='$arhk',arhe='$arhe',arhs='$arhs',arhsk='$arhsk',ara='$ara',arak='$arak',arae='$arae',aras='$aras',arask='$arask',arf='$arf',arfk='$arfk',arfe='$arfe',arfs='$arfs',arfsk='$arfsk',art='$art',artk='$artk',arte='$arte',arts='$arts',artsk='$artsk',itm0='$itm0',itmk0='$itmk0',itme0='$itme0',itms0='$itms0',itmsk0='$itmsk0',itm1='$itm1',itmk1='$itmk1',itme1='$itme1',itms1='$itms1',itmsk1='$itmsk1',itm2='$itm2',itmk2='$itmk2',itme2='$itme2',itms2='$itms2',itmsk2='$itmsk2',itm3='$itm3',itmk3='$itmk3',itme3='$itme3',itms3='$itms3',itmsk3='$itmsk3',itm4='$itm4',itmk4='$itmk4',itme4='$itme4',itms4='$itms4',itmsk4='$itmsk4',itm5='$itm5',itmk5='$itmk5',itme5='$itme5',itms5='$itms5',itmsk5='$itmsk5',itm6='$itm6',itmk6='$itmk6',itme6='$itme6',itms6='$itms6',itmsk6='$itmsk6' where pid='$pid'");
	if(!$db->affected_rows()){
		$cmd_info = "无法修改角色 $name";
	} else {
				//记录操作，蛋疼
		$log_diff_data = array_diff_assoc($log_new_data, $log_old_data);
		if($log_new_data['nskill'] !== $log_old_data['nskill']) {
			$log_nskill = implode(' ',array_keys(array_diff_assoc($log_new_data['nskill'], $log_old_data['nskill'])));
			if($log_nskill) $log_diff_data['nskill'] = $log_nskill;
			$log_nskilldel = implode(' ',array_keys(array_diff_assoc($log_old_data['nskill'], $log_new_data['nskill'])));
			if($log_nskilldel) $log_diff_data['nskilldel'] = $log_nskilldel;
		}
		if($log_new_data['nskillpara'] !== $log_old_data['nskillpara']) {
			$log_nskillpara = json_encode(array_diff_assoc($log_new_data['nskillpara'], $log_old_data['nskillpara']));
			if($log_nskillpara) $log_diff_data['nskillpara'] = $log_nskillpara;
//			$log_nskillparadel = json_encode(array_diff_assoc($log_old_data['nskillpara'], $log_new_data['nskillpara']));
//			if($log_nskillparadel) $log_diff_data['nskillparadel'] = $log_nskillparadel;
		}
		adminlog('editnpc',$room_prefix.'_'.$gamenum,$name,gencode($log_diff_data));
		addnews($now,'editpc',$name);
		$cmd_info = "角色 $name 的属性被修改了";
	}
	$npcdata = dbsearch($start,$checkmode,$checkinfo);
}
include template('admin_npcmng');


function dbsearch($start,$checkmode,$checkinfo){
	global $showlimit,$db,$tablepre,$resultinfo,$cmd_info,$plsinfo;
	$limitstr = " LIMIT $start,$showlimit";
	if(($checkmode == 'name')&&($checkinfo)) {
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE name LIKE '%{$checkinfo}%' AND type>'0'".$limitstr);
	} elseif($checkmode == 'teamID') {
		if($checkinfo){
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE teamID LIKE '%".$checkinfo."%' AND type>'0' ORDER BY teamID".$limitstr);
		} else {
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE type>'0' ORDER BY teamID DESC".$limitstr);
		}
	} elseif($checkmode == 'pls') {
		if($checkinfo){
			$plslist = array();
			foreach($plsinfo as $key => $val){
				if(strpos($val,$checkinfo)!==false){
					$plslist[] = $key;
				}
			}
			if(is_numeric($checkinfo)){$plslist[] = (int)$checkinfo;}
			if(!empty($plslist)){
				$plsstr = implode(',',$plslist);
				$result = $db->query("SELECT * FROM {$tablepre}players WHERE pls IN (".$plsstr.") AND type>'0' ORDER BY pls".$limitstr);
			}else{$result = false;}
		} else {
			$result = $db->query("SELECT * FROM {$tablepre}players WHERE type>'0' ORDER BY pls".$limitstr);
		}
	} else {
		$result = $db->query("SELECT * FROM {$tablepre}players WHERE type>'0'".$limitstr);
	}
	if(!$result || !$db->num_rows($result)) {
		$cmd_info = '没有符合条件的角色。';
		$startno = $start + 1;
		$resultinfo = '位置：第'.$startno.'条记录';
		$npcdata = Array();
	} else {
		while($npc = $db->fetch_array($result)) {
			$npcdata[] = $npc;
		}
		$startno = $start + 1;
		$endno = $start + count($npcdata);
		$resultinfo = '第'.$startno.'条-第'.$endno.'条记录';
	}
	return $npcdata;
}
?>