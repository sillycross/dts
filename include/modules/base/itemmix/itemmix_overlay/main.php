<?php

namespace itemmix_overlay
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['J'] = '超量素材';
	}
	
//	function itemmix_star_culc(array $mlist){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','logger','itemmix'));
//		$last_star=-1; $num = 0;
//		foreach($mlist as $val)
//		{
//			$z=${'itmk'.$val};
//			$star=0;
//			for ($i=0; $i<strlen($z); $i++) if ('0'<=$z[$i] && $z[$i]<='9') $star=$star*10+(int)$z[$i];
//			if($star==0 || ($star!=$last_star && $last_star != -1) || strpos(${'itmsk'.$val},'J')===false){
//				break;
//			}else{
//				$last_star=$star;
//				$num ++;
//			}
//		}
//		return array($last_star, $num);
//	}
	
	
	function get_itemmix_overlay_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		return __DIR__.'/config/overlay.config.php';
	}
	
	function itemmix_prepare_overlay(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		$file = get_itemmix_overlay_filename();
		$olist = openfile($file);
		$n = count($olist);
		$prp_res = array();
		for ($i=0;$i<$n;$i++){
			$t = explode(',',$olist[$i]);
			if(isset($prp_res[$t[5]])){
				$prp_res[$t[5]][] = $t;
			}else{
				$prp_res[$t[5]] = array($t);
			}
		}
		return $prp_res;
	}
	
	function itemmix_star_culc_overlay($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		$star_res = array();
		foreach($mlist as $val)
		{
			if(${'itms'.$val} && strpos(${'itmsk'.$val},'J')!==false){
				$z=${'itmk'.$val};
				$star=0;
				for ($i=0; $i<strlen($z); $i++) if ('0'<=$z[$i] && $z[$i]<='9') $star=$star*10+(int)$z[$i];
				if(isset($star_res[$star])){
					$star_res[$star]['num']++;
					$star_res[$star]['list'][] = $val;
				}else{
					$star_res[$star] = array('num' => 1, 'list' => array($val));
				}
			}
		}
		return $star_res;
	}
	
	function itemmix_overlay_check($mlist, $tp=0){//$tp=0严格模式，$tp=1遍历模式（提示用）
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		$star_res = itemmix_star_culc_overlay($mlist);
		$prp_res = itemmix_prepare_overlay();
		$chc_res = array();
		foreach($star_res as $star => $sval){
			if($star && $sval['num'] > 1){
				
				$snum = $sval['num'];
				$slist = $sval['list'];
				if($snum > 1 && isset($prp_res[$star])){
					if((!$tp && count($star_res) == 1 && !isset($star_res[0])) || $tp){//严格模式，必须星数和数目都相同而且不能有0星；遍历模式无妨
						foreach($prp_res[$star] as $pra){
							$pnum = $pra[6];
							if((!$tp && $snum == $pra[6]) || ($tp && $snum >= $pra[6])){
								if(!isset($chc_res[$star.'-'.$pnum])){
									$chc_res[$star.'-'.$pnum] = array('list' => $slist, 'choices' => array($pra));
								}else{
									$chc_res[$star.'-'.$pnum]['choices'][] = $pra;
								}
							}
						}
					}
				}				
			}
		}
		return $chc_res;
	}
	
//	function itemmix_overlay_check(int $star, int $num, $tp=0){//$tp=0严格模式，$tp=1遍历模式（提示及反查用）
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','logger','itemmix'));
//		$file = get_itemmix_overlay_filename();
//		$olist = openfile($file); $n = count($olist);
//		//$sync=-1; 
//		//$syncn=$synck=$synce=$syncs=$syncsk=Array();
//		$ovl_res = array();
//		if($tp == 1){//
//		}elseif(!$tp){//严格模式，必须星数和数目都相同
//			for ($i=0;$i<$n;$i++){
//				$t = explode(',',$olist[$i]);
//				if ($t[5]!=$star || $t[6]!=$num) continue;
//				//$sync++;
//				$ovl_res[] = array(
//					'syncn' => $t[0],
//					'synck' => $t[1],
//					'synce' => $t[2],
//					'syncs' => $t[3],
//					'syncsk' => $t[4]
//				);
//				//$syncn[$sync]=$t[0]; $synck[$sync]=$t[1]; $synce[$sync]=$t[2]; $syncs[$sync]=$t[3]; $syncsk[$sync]=$t[4];
//			}
//		}		
//		//return array($sync, $syncn, $synck, $synce, $syncs, $syncsk);
//		return $ovl_res;
//	}
	
	function itemmix($mlist, $itemselect=-1) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmix'));
		
//		list($last_star, $num) = itemmix_star_culc($mlist);
//		
//		if ($last_star==-1) return $chprocess($mlist, $itemselect);
		
		//list($sync, $syncn, $synck, $synce, $syncs, $syncsk) = itemmix_overlay_check($last_star, $num);
		$chc_res = itemmix_overlay_check($mlist);

		//无满足条件的超量结果，失败
		//if ($sync==-1) return $chprocess($mlist, $itemselect);	
		if (!$chc_res) return $chprocess($mlist, $itemselect);	
		$chc = array_pop($chc_res);
		if ($itemselect==-1)
		{
			$mask=0;
			foreach($chc['list'] as $k)
				if (1<=$k && $k<=6)
					$mask|=(1<<((int)$k-1));
						
			$cmd.='<input type="hidden" id="mode" name="mode" value="itemmain">';
			$cmd.='<input type="hidden" id="command" name="command" value="itemmix">';
			$cmd.='<input type="hidden" id="mixmask" name="mixmask" value="'.$mask.'">';
			$cmd.='<input type="hidden" id="itemselect" name="itemselect" value="999">';
			$cmd.= "请选择超量结果<br><br>";
			$sync = count($chc['choices']);
			for($i=0;$i<$sync;$i++){
				$tn = $chc['choices'][$i][0];
				$cmd.="<input type=\"button\" class=\"cmdbutton\"  style=\"width:200\" value=\"".$tn."\" onclick=\"$('itemselect').value='".$i."';postCmd('gamecmd','command.php');this.disabled=true;\">";
			}
			$cmd.="<input type=\"button\" class=\"cmdbutton\"  style=\"width:200\" value=\"返回\" onclick=\"postCmd('gamecmd','command.php');this.disabled=true;\">";
			return;
		}
		else
		{
			$i=(int)$itemselect;
			if ($i<0 || $i > count($chc['choices']) - 1)
			{
				$mode='command'; return; 
			}
			foreach($chc['list'] as $val)
			{
				\itemmix\itemreduce('itm'.$val);
			}
			list($itm0,$itmk0,$itme0,$itms0,$itmsk0) = $chc['choices'][$i];
//			$itm0=$chc['choices'][$i]['syncn'];
//			$itmk0=$chc['choices'][$i]['synck'];
//			$itme0=$chc['choices'][$i]['synce'];
//			$itms0=$chc['choices'][$i]['syncs'];
//			$itmsk0=$chc['choices'][$i]['syncsk'];
			addnews($now,'overmix',$name,$itm0);
			\itemmain\itemget();
			$mode = 'command';
			return;
		}
		$chprocess($mlist, $itemselect);
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($news == 'overmix') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}超量合成了{$b}</span><br>\n";
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
