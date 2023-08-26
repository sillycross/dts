<?php

namespace itemmix_overlay
{
	function init() 
	{
		eval(import_module('itemmain','itemmix'));
		$itemspkinfo['J'] = '超量素材';
		$itemspkdesc['J']='可以参与超量合成';
		$itemspkremark['J']='……';
		$mix_type['overlay'] = '超量';
	}
	
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
			$prp_res[] = explode(',',$olist[$i]);
		}
		return $prp_res;
	}
	
	function itemmix_star_culc_overlay($itmn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		$star=0;
		if(${'itms'.$itmn}){
			$star = \itemmix_sync\itemmix_get_star(${'itmk'.$itmn});
			if(!check_valid_overlay_material(${'itm'.$itmn}, ${'itmsk'.$itmn}, $star)){
				$star = 0;
			}
		}
		return $star;
	}
	
	//有效的超量素材：带有“超量素材”属性，或者是真卡（名称里有★数字，数字与星数一致，并且没有“-仮”字样）
	function check_valid_overlay_material($itm, $itmsk, $star)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\itemmain\check_in_itmsk('J',$itmsk)) return true;
		preg_match('/★(\d+)/s', $itm, $matches);
		//gwrite_var('a.txt',$matches);
		if(!empty($matches) && $star == $matches[1] && strpos($itm,'-仮')===false) return true;
		return false;
	}
	
	function itemmix_overlay_check($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		//先判断是否是同星素材2张以上
		$star = $num = 0;
		$stuff = array();
		foreach($mlist as $mval){
			$stuff[] = ${'itm'.$mval};
			$mstar = itemmix_star_culc_overlay($mval);
			if(($star && $mstar != $star) || $mstar == 0){
				$star = 0;break;
			}else{
				$star = $mstar;
				$num ++;
			}
		}
		$chc_res = array();
		if($star && $num > 1){
			//然后判断是否存在对应的超量成果
			$prp_res = itemmix_prepare_overlay();
			foreach($prp_res as $pra){
				$pstar = $pra[5];
				$pnum = $pra[6];
				if($star == $pstar && $num == $pnum){
					if(empty($chc_res[$star.'-'.$num])) $chc_res[$star.'-'.$num] = array();
					//用键名记录星数和素材数方便提示
					$chc_res[$star.'-'.$num][] = array('stuff' => $stuff, 'list' => $mlist, 'result' => $pra, 'type' => 'overlay');
				}
			}
		}
		return $chc_res;
	}
	
	function itemmix_get_result($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($mlist);
		$chc_res = itemmix_overlay_check($mlist);
		if($chc_res){
			foreach($chc_res as $cv) {
				foreach($cv as $v){
					$ret[] = $v;
				}
			}
		}
		return $ret;
	}
	
	//已废弃，现在全部做到itemmix_get_result()里
//	function itemmix_old($mlist, $itemselect=-1) 
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','logger','itemmix'));
//		$chprocess($mlist, $itemselect);	//可以兼容其他合成
//		$chc_res = itemmix_overlay_check($mlist);
//		if ($chc_res){
//			
//		}
//
//		
//		//if ($sync==-1) return $chprocess($mlist, $itemselect);	
//		if (!$chc_res) return $chprocess($mlist, $itemselect);	
//		$chc = array_pop($chc_res);
//		if ($itemselect==-1)
//		{
//			$mask=0;
//			foreach($chc['list'] as $k)
//				if (1<=$k && $k<=6)
//					$mask|=(1<<((int)$k-1));
//						
//			$cmd.='<input type="hidden" id="mode" name="mode" value="itemmain">';
//			$cmd.='<input type="hidden" id="command" name="command" value="itemmix">';
//			$cmd.='<input type="hidden" id="mixmask" name="mixmask" value="'.$mask.'">';
//			$cmd.='<input type="hidden" id="itemselect" name="itemselect" value="999">';
//			$cmd.= "请选择超量结果<br><br>";
//			$sync = count($chc['result']);
//			for($i=0;$i<$sync;$i++){
//				$tn = $chc['result'][$i][0];
//				$cmd.="<input type=\"button\" class=\"cmdbutton\"  style=\"width:200\" value=\"".$tn."\" onclick=\"$('itemselect').value='".$i."';postCmd('gamecmd','command.php');this.disabled=true;\">";
//			}
//			$cmd.="<input type=\"button\" class=\"cmdbutton\"  style=\"width:200\" value=\"返回\" onclick=\"postCmd('gamecmd','command.php');this.disabled=true;\">";
//			return;
//		}
//		else
//		{
//			$i=(int)$itemselect;
//			if ($i<0 || $i > count($chc['result']) - 1)
//			{
//				$mode='command'; return; 
//			}
//			foreach($chc['list'] as $val)
//			{
//				\itemmix\itemreduce('itm'.$val);
//			}
//			list($itm0,$itmk0,$itme0,$itms0,$itmsk0) = $chc['result'][$i];
//			addnews($now,'overmix',$name,$itm0);
//			\itemmain\itemget();
//			$mode = 'command';
//			return;
//		}
//		$chprocess($mlist, $itemselect);
//	}
	
//	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys'));
//		if($news == 'overmix') 
//			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}超量合成了{$b}</span></li>";
//		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
//	}
}

?>