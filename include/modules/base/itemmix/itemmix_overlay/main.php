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
			$prp_res[] = explode(',',$olist[$i]);
		}
		return $prp_res;
	}
	
	function itemmix_star_culc_overlay($itmn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		$star=0;
		if(${'itms'.$itmn} && strpos(${'itmsk'.$itmn},'J')!==false){
			$z=${'itmk'.$itmn};
			for ($i=0; $i<strlen($z); $i++) if ('0'<=$z[$i] && $z[$i]<='9') $star=$star*10+(int)$z[$i];
		}
		return $star;
	}
	
//	function itemmix_star_culc_overlay($mlist){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','itemmix'));
//		$star_res = array();
//		foreach($mlist as $val)
//		{
//			if(${'itms'.$val} && strpos(${'itmsk'.$val},'J')!==false){
//				$z=${'itmk'.$val};
//				$star=0;
//				for ($i=0; $i<strlen($z); $i++) if ('0'<=$z[$i] && $z[$i]<='9') $star=$star*10+(int)$z[$i];
//				if(isset($star_res[$star])){
//					$star_res[$star]['num']++;
//					$star_res[$star]['list'][] = $val;
//				}else{
//					$star_res[$star] = array('num' => 1, 'list' => array($val));
//				}
//			}
//		}
//		return $star_res;
//	}
	
	function itemmix_overlay_check($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		//先判断是否是同星素材2张以上
		$star = 0;
		$num = 0;
		foreach($mlist as $mval){
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
					if(!isset($chc_res[$star.'-'.$num])){//用键名记录星数和素材数方便提示
						$chc_res[$star.'-'.$num] = array('list' => $mlist, 'choices' => array($pra));
					}else{
						$chc_res[$star.'-'.$num]['choices'][] = $pra;
					}
				}
			}
		}
		return $chc_res;
	}
	
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
			addnews($now,'overmix',$name,$itm0);
			\itemmain\itemget();
			$mode = 'command';
			return;
		}
		$chprocess($mlist, $itemselect);
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($news == 'overmix') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}超量合成了{$b}</span><br>\n";
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}
}

?>
