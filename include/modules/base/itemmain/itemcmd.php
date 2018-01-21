<?php

namespace itemmain
{
	function itemfind() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		if(!$itm0||!$itmk0||!$itms0){
			$log .= '获取物品信息错误！';
			$mode = 'command';
			return;
		}
		$tpldata['itmk0_words']=parse_itmk_words($itmk0);
		$tpldata['itmsk0_words']=parse_itmsk_words($itmsk0);
		show_itemfind();
	}
	
	function show_itemfind(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		ob_start();
		include template(MOD_ITEMMAIN_ITEMFIND);
		$cmd = ob_get_contents();
		ob_end_clean();
	}

	function itemget() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		$log .= "获得了物品<span class=\"yellow\">$itm0</span>。<br>";
		if(1 == check_mergable($itmk0) && $itms0 !== $nosta){
			if($wep == $itm0 && $wepk == $itmk0 && $wepe == $itme0 && $wepsk == $itmsk0){
				$weps += $itms0;
				$log .= "与装备着的武器<span class=\"yellow\">$wep</span>合并了。";
				$itm0 = $itmk0 = $itmsk0 = '';
				$itme0 = $itms0 = 0;
				$mode = 'command';
				return;
			}else{
				for($i = 1;$i <= 6;$i++){
					if((${'itms'.$i})&&($itm0 == ${'itm'.$i})&&($itmk0 == ${'itmk'.$i})&&($itme0 == ${'itme'.$i})&&($itmsk0 == ${'itmsk'.$i})){
						${'itms'.$i} += $itms0;
						$log .= "与包裹里的<span class=\"yellow\">$itm0</span>合并了。";
						$itm0 = $itmk0 = $itmsk0 = '';
						$itme0 = $itms0 = 0;
						$mode = 'command';
						return;
					}
				}
			}
		} elseif(2 == check_mergable($itmk0) && $itms0 !== $nosta){
			$sameitem = array();
			for($i = 1;$i <= 6;$i++){
				if(${'itms'.$i} && $itm0 == ${'itm'.$i} && $itme0 == ${'itme'.$i} && substr($itmk0,1,1) == substr(${'itmk'.$i}, 1, 1) && 2 == check_mergable(${'itmk'.$i})){
					$sameitem[] = $i;
				}
			}
			if(isset($sameitem[0])){
				include template(MOD_ITEMMAIN_ITEMMERGE0);
				$cmd = ob_get_contents();
				ob_clean();
	//			$cmd .= '<input type="hidden" name="mode" value="itemmain"><input type="hidden" name="command" value="itemmerge"><input type="hidden" name="merge1" value="0"><br>是否将 <span class="yellow">'.$itm0.'</span> 与以下物品合并？<br><input type="radio" name="merge2" id="itmn" value="n" checked><a onclick=sl("itmn"); href="javascript:void(0);" >不合并</a><br><br>';
	//			foreach($sameitem as $n) {
	//				$cmd .= '<input type="radio" name="merge2" id="itm'.$n.'" value="'.$n.'"><a onclick=sl("itm'.$n.'"); href="javascript:void(0);">'."${'itm'.$n}/${'itme'.$n}/${'itms'.$n}".'</a><br>';
	//			}
				return;
			}
			
		}

		itemadd();
		return;
	}

	function itemdrop_valid_check($itm, $itmk, $itme, $itms, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$itms||!$itmk||$itmk=='WN'||$itmk=='DN'){
			eval(import_module('logger'));
			$log .= '该物品不存在！<br>';
			return false;
		}
		return true;
	}

	function itemdrop($item) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));

		if($item == 'wep'){
			$itm = & $wep;
			$itmk = & $wepk;
			$itme = & $wepe;
			$itms = & $weps;
			$itmsk = & $wepsk;
		} elseif(strpos($item,'ar') === 0) {
			$itmn = substr($item,2,1);
			$itm = & ${'ar'.$itmn};
			$itmk = & ${'ar'.$itmn.'k'};
			$itme = & ${'ar'.$itmn.'e'};
			$itms = & ${'ar'.$itmn.'s'};
			$itmsk = & ${'ar'.$itmn.'sk'};

		} elseif(strpos($item,'itm') === 0) {
			$itmn = substr($item,3,1);
			$itm = & ${'itm'.$itmn};
			$itmk = & ${'itmk'.$itmn};
			$itme = & ${'itme'.$itmn};
			$itms = & ${'itms'.$itmn};
			$itmsk = & ${'itmsk'.$itmn};
		}
		
		if(!itemdrop_valid_check($itm, $itmk, $itme, $itms, $itmsk)){
			$mode = 'command';
			return;
		}

	//	$mapfile = GAME_ROOT."./gamedata/mapitem/{$pls}mapitem.php";
	//	$itemdata = "$itm,$itmk,$itme,$itms,$itmsk,\n";
	//	writeover($mapfile,$itemdata,'ab');
		$db->query("INSERT INTO {$tablepre}mapitem (itm, itmk, itme, itms, itmsk ,pls) VALUES ('$itm', '$itmk', '$itme', '$itms', '$itmsk', '$pls')");
		$dropid = $db->insert_id();
		$dropname = $itm;
		$log .= "你丢弃了<span class=\"red\">$dropname</span>。<br>";
		$mode = 'command';
		if($item == 'wep'){
		$itm = '拳头';
		$itmsk = '';
		$itmk = 'WN';
		$itme = 0;
		$itms = $nosta;
		} else {
		$itm = $itmk = $itmsk = '';
		$itme = $itms = 0;
		}
		return array($dropid,$dropname);
	}
	
	function itemoff_valid_check($itm, $itmk, $itme, $itms, $itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$itms||!$itmk||$itmk=='WN'||$itmk=='DN'){
			eval(import_module('logger'));
			$log .= '该物品不存在！<br>';
			return false;
		}
		return true;
	}

	function itemoff($item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));

		if($item == 'wep'){
			$itm = & $wep;
			$itmk = & $wepk;
			$itme = & $wepe;
			$itms = & $weps;
			$itmsk = & $wepsk;
		} elseif(strpos($item,'ar') === 0) {
			$itmn = substr($item,2,1);
			$itm = & ${'ar'.$itmn};
			$itmk = & ${'ar'.$itmn.'k'};
			$itme = & ${'ar'.$itmn.'e'};
			$itms = & ${'ar'.$itmn.'s'};
			$itmsk = & ${'ar'.$itmn.'sk'};
		}
		if(!itemoff_valid_check($itm, $itmk, $itme, $itms, $itmsk)){
			$mode = 'command';
			return;
		}
		$log .= "你卸下了装备<span class=\"yellow\">$itm</span>。<br>";

		$itm0 = $itm;
		$itmk0 = $itmk;
		$itme0 = $itme;
		$itms0 = $itms;
		$itmsk0 = $itmsk;
		
		if($item == 'wep'){
		$itm = '拳头';
		$itmsk = '';
		$itmk = 'WN';
		$itme = 0;
		$itms = $nosta;
		} else {
		$itm = $itmk = $itmsk = '';
		$itme = $itms = 0;
		}
		itemget();
		return;
	}

	function itemadd(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		if(!$itms0){
			$log .= '你没有捡取物品。<br>';
			$mode = 'command';
			return;
		}
		for($i = 1;$i <= 6;$i++){
			if(!${'itms'.$i}){
				$log .= "将<span class=\"yellow\">$itm0</span>放入包裹。<br>";
				${'itm'.$i} = $itm0;
				${'itmk'.$i} = $itmk0;
				${'itme'.$i} = $itme0;
				${'itms'.$i} = $itms0;
				${'itmsk'.$i} = $itmsk0;
				$itm0 = $itmk0 = $itmsk0 = '';
				$itme0 = $itms0 = 0;
				$mode = 'command';
				return;
			}
		}
		//$log .= '你的包裹已经满了。想要丢掉哪个物品？<br>';
		include template(MOD_ITEMMAIN_ITEMDROP0);
		$cmd = ob_get_contents();
		ob_clean();
	//	$cmd .= '<input type="hidden" name="mode" value="itemmain"><br><input type="radio" name="command" id="dropitm0" value="dropitm0" checked><a onclick=sl("dropitm0"); href="javascript:void(0);" >'."$itm0/$itme0/$itms0".'</a><br><br>';
	//
	//	for($i = 1;$i <= 6;$i++){
	//		$cmd .= '<input type="radio" name="command" id="swapitm'.$i.'" value="swapitm'.$i.'"><a onclick=sl("swapitm'.$i.'"); href="javascript:void(0);" >'."${'itm'.$i}/${'itme'.$i}/${'itms'.$i}".'</a><br>';
	//	}
		return;
	}

	function itemmerge($itn1,$itn2){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		if($itn1 == $itn2) {
			$log .= '需要选择两个物品才能进行合并！';
			$mode = 'itemmerge';
			return false;
		}
		
		$it1 = & ${'itm'.$itn1};
		$itk1 = & ${'itmk'.$itn1};
		$ite1 = & ${'itme'.$itn1};
		$its1 = & ${'itms'.$itn1};
		$itsk1 = & ${'itmsk'.$itn1};
		$it2 = & ${'itm'.$itn2};
		$itk2 = & ${'itmk'.$itn2};
		$ite2 = & ${'itme'.$itn2};
		$its2 = & ${'itms'.$itn2};
		$itsk2 = & ${'itmsk'.$itn2};
		
		if(!$its1 || !$its2) {
			$log .= '请选择正确的物品进行合并！';
			$mode = 'command';
			return false;
		}
		
		if($its1==$nosta || $its2==$nosta) {
			$log .= '耐久是无限的物品不能合并！';
			$mode = 'command';
			return false;
		}
		
		if($it1 != $it2 || $ite1 != $ite2){
			$log .= "<span class=\"yellow\">$it1</span>与<span class=\"yellow\">$it2</span>不是同名同效果物品，不能合并！";
			$mode = 'command';
			return false;
		}

		//之所以这样，是因为合并补给需要特判
		if( $itk1==$itk2 && $itsk1==$itsk2 && 1 == check_mergable($itk1)) {
			$its2 += $its1;
			$it1 = $itk1 = $itsk1 = '';
			$ite1 = $its1 = 0;
			$log .= "你合并了<span class=\"yellow\">$it2</span>。";
			$mode = 'command';
			return true;
		} elseif(2 == check_mergable($itk1) && 2 == check_mergable($itk2) && substr($itk1,1,1) == substr($itk2, 1,1) ) {
			if((strpos($itk1,'P') === 0)||(strpos($itk2,'P') === 0)){
				//毒性判定，取大的
				$p1 = (int)substr($itk1,2);
				$p2 = (int)substr($itk2,2);
				$k = substr($itk1,1,1);
				if($p2 < $p1) $p2 = $p1;
				$itk2 = "P$k$p2";
				//属性判定（下毒玩家）
				if(!empty($itsk1)){
					$itsk2=$itsk1;
				}
			}
			$its2 += $its1;
			$it1 = $itk1 = $itsk1 = '';
			$ite1 = $its1 = 0;
			
			$log .= "你合并了 <span class=\"yellow\">$it2</span>。";
			$mode = 'command';
			return true;
		} elseif($itk1!=$itk2||$itsk1!=$itsk2) {
			$log .= "<span class=\"yellow\">$it1</span>与<span class=\"yellow\">$it2</span>不是同类型同属性物品，不能合并！";
			return false;
		} else{
			$log .= "<span class=\"yellow\">$it1</span>与<span class=\"yellow\">$it2</span>不是可以堆叠的物品，不能合并！";
			return false;
		}

//		if(!$itn1 || !$itn2) {//这句是不是永远运行不到？
//			itemadd();
//		}

		//$mode = 'command';
		return false;
	}
		
	function itemmove($from,$to){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		if(!$from || !is_numeric($from) || !$to || !is_numeric($to) || $from < 1 || $to < 1 || $from > 6 || $to > 6){
			$log .= '错误的包裹位置参数。<br>';
			return false;
		}	elseif($from == $to){
			$log .= '同一物品无法互换。<br>';
			return false;
		}
		$f = & ${'itm'.$from};
		$fk = & ${'itmk'.$from};
		$fe = & ${'itme'.$from};
		$fs = & ${'itms'.$from};
		$fsk = & ${'itmsk'.$from};
		$t = & ${'itm'.$to};
		$tk = & ${'itmk'.$to};
		$te = & ${'itme'.$to};
		$ts = & ${'itms'.$to};
		$tsk = & ${'itmsk'.$to};
		if(!$fs){
			$log .= '错误的道具参数。<br>';
			return false;
		}
		if(!$ts){
			$log .= "将<span class=\"yellow\">{$f}</span>移动到了<span class=\"yellow\">包裹{$to}</span>。<br>";
			$t = $f;
			$tk = $fk;
			$te = $fe;
			$ts = $fs;
			$tsk = $fsk;
			$f = $fk = $fsk = '';
			$fe = $fs = 0;
			
		}else {
			$log .= "将<span class=\"yellow\">{$f}</span>与<span class=\"yellow\">{$t}</span>互换了位置。<br>";
			$temp = $t;
			$tempk = $tk;
			$tempe = $te;
			$temps = $ts;
			$tempsk = $tsk;
			$t = $f;
			$tk = $fk;
			$te = $fe;
			$ts = $fs;
			$tsk = $fsk;
			$f = $temp;
			$fk = $tempk;
			$fe = $tempe;
			$fs = $temps;
			$fsk = $tempsk;
			
		}
		return true;
	}
}

?>
