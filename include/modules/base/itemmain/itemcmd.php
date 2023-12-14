<?php

namespace itemmain
{	
	$itemfind_extra_log = '';
	//决定是拾取还是丢弃的画面的过程，正常只有discover_item()会跳转过来，而如果开启了允许当场使用道具，则大量功能会通过itemget()跳转到此处
	function itemfind() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		if(!$itm0||!$itmk0||!$itms0){
			$log .= '获取物品信息错误！';
			$mode = 'command';
			return;
		}
		show_itemfind();
		return;
	}
	
	function parse_interface_gameinfo() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if($itm0 && $itmk0 && $itms0) {
			eval(import_module('sys','logger','itemmain'));
			if(!empty($tpldata['itm0_words_noelli'])) $itm = $tpldata['itm0_words_noelli'];
			else $itm = $itm0;
			//$tpldata['itmk0_words']=parse_itmk_words($itmk0);
			$tpldata['itmsk0_words']=parse_itmsk_words($itmsk0);//获取的道具属性是完整显示的
			//if(!empty(trim($log))) $log .= '<br>';
			if(!empty($itemfind_extra_log)) $log .= '<:ex_log:>';//额外发现道具的提示不影响此处“发现”还是“握着”的判定
			
			if(false === strpos($log, $itm0)) $log .= "<br>发现了物品 <span class='yellow b'>{$itm}</span>，<br>";
			else $log .= "<br>你正握着物品 <span class='yellow b'>{$itm}</span>，<br>";
			$log .= "类型：{$tpldata['itmk0_words']}";
			if ($itmsk0 && !is_numeric($itmsk0) && !empty($tpldata['itmsk0_words'])) $log .= "，属性：{$tpldata['itmsk0_words']}";
			$log .= "，效：{$itme0}，耐：{$itms0}。";
			
			if(!empty($itemfind_extra_log)) $log = str_replace('<:ex_log:>', $itemfind_extra_log, $log);
		}
		$chprocess();
	}
	
	function show_itemfind(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
//		$tpldata['itmk0_words']=parse_itmk_words($itmk0);
//		$tpldata['itmsk0_words']=parse_itmsk_words($itmsk0);
		if(empty($itms0)) return;
		ob_start();
		include template(MOD_ITEMMAIN_ITEMFIND);
		$cmd = ob_get_contents();
		ob_end_clean();
	}
	
	//由于大量功能都直接跳转到itemget()，但业务上又需要有变化，估抽空原itemget()作为前置判断过程，实际流程在itemget_process()
	//中间有些缘由，现在它真的只是个壳子了
	function itemget(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','itemmain'));
//		if(get_item_allow_find_and_use()){
//			itemfind();
//		}else{
//			itemget_process();
//		}		
		itemget_process();
		return;
	}
	
	function get_item_allow_find_and_use(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain'));
		if(!empty($item_allow_find_and_use)) return true;
		else return false;
	}
	
	//实际处理拾取道具的过程，如果包括无空位则询问丢弃哪一个，否则清空0号物品并把物品放入包裹
	//如果开启了允许当场使用道具，这里也会多出一个当场使用的按钮
	function itemget_process() {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		$log .= "获得了物品<span class=\"yellow b\">$itm0</span>。<br>";
		if(1 == check_mergable($itmk0) && $itms0 !== $nosta){
			if($wep == $itm0 && $wepk == $itmk0 && $wepe == $itme0 && $wepsk == $itmsk0){
				$weps += $itms0;
				$log .= "与装备着的武器<span class=\"yellow b\">$wep</span>合并了。";
				$itm0 = $itmk0 = $itmsk0 = '';
				$itme0 = $itms0 = 0;
				$mode = 'command';
				return;
			}else{
				for($i = 1;$i <= 6;$i++){
					if((${'itms'.$i})&&($itm0 == ${'itm'.$i})&&($itmk0 == ${'itmk'.$i})&&($itme0 == ${'itme'.$i})&&($itmsk0 == ${'itmsk'.$i})){
						${'itms'.$i} += $itms0;
						$log .= "与包裹里的<span class=\"yellow b\">$itm0</span>合并了。";
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
				$log .= "是否将 <span class='yellow b'>$itm0</span>与以下物品合并？";
//				$tpldata['itme0_words'] = \itemmain\parse_itmnum_words($itme,1);
//				$tpldata['itms0_words'] = \itemmain\parse_itmnum_words($itms,1);
				include template(MOD_ITEMMAIN_ITEMMERGE0);
				$cmd = ob_get_contents();
				ob_clean();
	//			$cmd .= '<input type="hidden" name="mode" value="itemmain"><input type="hidden" name="command" value="itemmerge"><input type="hidden" name="merge1" value="0"><br>是否将 <span class="yellow b">'.$itm0.'</span> 与以下物品合并？<br><input type="radio" name="merge2" id="itmn" value="n" checked><a onclick=sl("itmn"); href="javascript:void(0);" >不合并</a><br><br>';
	//			foreach($sameitem as $n) {
	//				$cmd .= '<input type="radio" name="merge2" id="itm'.$n.'" value="'.$n.'"><a onclick=sl("itm'.$n.'"); href="javascript:void(0);">'."${'itm'.$n}/${'itme'.$n}/${'itms'.$n}".'</a><br>';
	//			}
				return;
			}
			
		}

		itemadd();
		return;
	}

	function itemdrop_valid_check($itm, $itmk, $itme, $itms, $itmsk, $itmpos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!$itms||!$itmk||$itmk=='WN'||$itmk=='DN'){
			eval(import_module('logger'));
			$log .= '该物品不存在！<br>';
			return false;
		}
		return true;
	}
	
	//丢弃道具
	//输入$item是装备道具位（wep arb itm1之类），返回值为包含丢弃后地图id（键名iid）的一个标准道具数组
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
		
		if(!itemdrop_valid_check($itm, $itmk, $itme, $itms, $itmsk, $item)){
			$mode = 'command';
			return;
		}
		
//		$db->query("INSERT INTO {$tablepre}mapitem (itm, itmk, itme, itms, itmsk ,pls) VALUES ('$itm', '$itmk', '$itme', '$itms', '$itmsk', '$pls')");
//		$dropid = $db->insert_id();
		$dropid = itemdrop_query($itm, $itmk, $itme, $itms, $itmsk, $pls);
		$ret = array('iid' => $dropid, 'itm' => $itm, 'itmk' => $itmk, 'itme' => $itme, 'itms' => $itms, 'itmsk' => $itmsk);

		$log .= "你丢弃了<span class=\"red b\">$itm</span>。<br>";
		$mode = 'command';
		
		item_destroy_core($item, $sdata);
		
		return $ret;
	}
	
	//丢弃道具核心函数，在数据库里追加对应的数据，返回iid
	function itemdrop_query($itm, $itmk, $itme, $itms, $itmsk, $pls){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$db->query("INSERT INTO {$tablepre}mapitem (itm, itmk, itme, itms, itmsk ,pls) VALUES ('$itm', '$itmk', '$itme', '$itms', '$itmsk', '$pls')");
		return $db->insert_id();
	}
	
	//摧毁道具用的内部函数，把对应位置的道具数据清空，视位置会自动替换成拳头或者内衣
	function item_destroy_core($item, &$pa) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain'));

		if($item == 'wep'){
			$itm = & $pa['wep'];
			$itmk = & $pa['wepk'];
			$itme = & $pa['wepe'];
			$itms = & $pa['weps'];
			$itmsk = & $pa['wepsk'];
		} elseif(strpos($item,'ar') === 0) {
			$itm = & $pa[$item];
			$itmk = & $pa[$item.'k'];
			$itme = & $pa[$item.'e'];
			$itms = & $pa[$item.'s'];
			$itmsk = & $pa[$item.'sk'];

		} elseif(strpos($item,'itm') === 0) {
			$itmn = substr($item,3,1);
			$itm = & $pa['itm'.$itmn];
			$itmk = & $pa['itmk'.$itmn];
			$itme = & $pa['itme'.$itmn];
			$itms = & $pa['itms'.$itmn];
			$itmsk = & $pa['itmsk'.$itmn];
		}
		
		if($item == 'wep'){
			$itm = $nowep;
			$itmk = 'WN';
			$itme = 0;
			$itms = $nosta;
			$itmsk = '';
		} else {
			$itm = $itmk = $itmsk = '';
			$itme = $itms = 0;
		}
		
		return;
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
		$log .= "你卸下了装备<span class=\"yellow b\">$itm</span>。<br>";

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
	
	function get_pack_blank()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		for($i = 1;$i <= 6;$i++){
			if(!${'itms'.$i}){
				return $i;
			}
		}
		return 0;
	}

	function itemadd($pos=0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		if(!$itms0){
			$log .= '你没有捡取物品。<br>';
			$mode = 'command';
			return;
		}
		if($pos > 0 && !${'itms'.$pos}) $i = $pos;
		else $i = get_pack_blank();
		if($i > 0) {
			$log .= "将<span class=\"yellow b\">$itm0</span>放入包裹。<br>";
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
		
		//这里在parse_interface_profile()之前，需要单独生成每个道具的itm_words
		//在网页里生成吧
		include template(MOD_ITEMMAIN_ITEMDROP0);
		$cmd = ob_get_contents();
		ob_clean();
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
			$log .= "<span class=\"yellow b\">$it1</span>与<span class=\"yellow b\">$it2</span>不是同名同效果物品，不能合并！";
			$mode = 'command';
			return false;
		}

		//之所以这样，是因为合并补给需要特判
		if( $itk1==$itk2 && $itsk1==$itsk2 && 1 == check_mergable($itk1)) {
			$its2 += $its1;
			$it1 = $itk1 = $itsk1 = '';
			$ite1 = $its1 = 0;
			$log .= "你合并了<span class=\"yellow b\">$it2</span>。";
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
			
			$log .= "你合并了 <span class=\"yellow b\">$it2</span>。";
			$mode = 'command';
			return true;
		} elseif($itk1!=$itk2||$itsk1!=$itsk2) {
			$log .= "<span class=\"yellow b\">$it1</span>与<span class=\"yellow b\">$it2</span>不是同类型同属性物品，不能合并！";
			return false;
		} else{
			$log .= "<span class=\"yellow b\">$it1</span>与<span class=\"yellow b\">$it2</span>不是可以堆叠的物品，不能合并！";
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
			$log .= "将<span class=\"yellow b\">{$f}</span>移动到了<span class=\"yellow b\">包裹{$to}</span>。<br>";
			$t = $f;
			$tk = $fk;
			$te = $fe;
			$ts = $fs;
			$tsk = $fsk;
			$f = $fk = $fsk = '';
			$fe = $fs = 0;
			
		}else {
			$log .= "将<span class=\"yellow b\">{$f}</span>与<span class=\"yellow b\">{$t}</span>互换了位置。<br>";
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
