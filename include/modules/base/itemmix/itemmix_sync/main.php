<?php

namespace itemmix_sync
{
	function init() 
	{
		eval(import_module('itemmain','itemmix'));
		$itemspkinfo['s'] = '调整';
		$itemspkdesc['s']='参与同调合成的必要属性';
		$itemspkinfo['^001'] = '同调';
		$itemspkdesc['^001']='这一道具是同调合成出来的';
		$itemspkinfo['^002'] = '变星';
		$itemspkdesc['^002']='这一道具参与同调合成时，其他素材的星数也当做1星';
		$itemspkremark['s']=$itemspkremark['^001']='……';
		$itemspkremark['^002']='原本的星数/1星两种情况能够达成的同调结果都有效，非常灵活';
		$mix_type['sync'] = '同调';
	}
	
	function get_itemmix_sync_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		return __DIR__.'/config/sync.config.php';
	}
	
	function itemmix_prepare_sync(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		$file = get_itemmix_sync_filename();
		$slist = openfile($file);
		$n = count($slist);
		$prp_res = array();
		for ($i=0;$i<$n;$i++){
			$prp_res[] = explode(',',$slist[$i]);
		}
		return $prp_res;
	}
	
	function itemmix_star_culc_sync($itmn){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		$star=0;
		$tunner=false;
		if(${'itms'.$itmn}){
			$star = itemmix_get_star(${'itmk'.$itmn});
			if(\itemmain\check_in_itmsk('s', ${'itmsk'.$itmn})) $tunner = true;
		}
		return array($star, $tunner);
	}
	
	function itemmix_get_star($z){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$star = 0;
		for ($i=0; $i<strlen($z); $i++)
			if ('0'<=$z[$i] && $z[$i]<='9')
				$star=$star*10+(int)$z[$i];
		return $star;
	}
	
	function itemmix_sync_check($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		//判断是否存在调整，并计算总星数 
		$star = $star2 = 0;
		$tunner = $stuff = array();
		foreach($mlist as $mval){
			$stuff[] = ${'itm'.$mval};
			list($mstar, $mtunner) = itemmix_star_culc_sync($mval);
			if($mstar == 0){
				$star = 0;
				break;
			}else{
				$star += $mstar;
			}
			if($mtunner){
				$tunner[] = $mval;
			}
			if(\itemmain\check_in_itmsk('^002', ${'itmsk'.$mval}))//生命肌瘤龙变星效果 
			{
				$streamstar = $mstar;
			}
		}
		
		$chc_res = array();
		if($star && count($tunner) == 1){
			if(!empty($streamstar)){//生命肌瘤龙的变星实际上提供两个星数分支 
				$star2 = $streamstar + count($mlist) - 1;
			}
			//然后判断是否存在对应的同调产物 
			$prp_res = itemmix_prepare_sync();
			foreach($prp_res as $pra){
				$pstar = $pra[5];
				$preq = $pra[6];
				$preqflag = true;
				if($preq){//检查是不是有特殊需求 
					$req=explode('+',$preq);
					$mname = array();
					foreach($mlist as $mi){
						$mname[] = \itemmix\itemmix_name_proc(${'itm'.$mi});
					}
					//如果素材没有满足则认为无法合成 
					foreach($req as $rv){
						if('st'==$rv){//调整要求是同调属性的 
							$tunnersk = ${'itmsk'.$tunner[0]};
							if(!\itemmain\check_in_itmsk('^001', $tunnersk)) $preqflag = false;
						}elseif(strpos($rv,'sm')===0){//调整以外要求是同调属性的 
							$smnum = (int)substr($rv,2);
							foreach($mlist as $mi){
								if(!in_array($mi, $tunner)){
									$misk = ${'itmsk'.$mi};
									if(!\itemmain\check_in_itmsk('^001', $misk)) {
										$preqflag = false;
										break;
									}
								}
							}
							if(count($mlist) <= $smnum) $preqflag = false;//素材数目不足
						}else{//其他，认为是名字要求
							if(!in_array($rv, $mname)) $preqflag = false;
						}
					}
				}
				if(($pstar == $star || $pstar == $star2) && $preqflag){
					if($pstar == $star2) list($star, $star2) = array($star2, $star);
					if(empty($chc_res[$star])) $chc_res[$star] = array();
					//用键名记录星数和素材数方便提示 
					$chc_res[$star][] = array('stuff' => $stuff, 'list' => $mlist, 'result' => $pra, 'type' => 'sync');
				}
			}
		}
		return $chc_res;
	}
	
	function itemmix_get_result($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($mlist);
		$chc_res = itemmix_sync_check($mlist);
		if($chc_res){
			foreach($chc_res as $cv) {
				foreach($cv as $v){
					$ret[] = $v;
				}
			}
		}
		return $ret;
	}
	
//	function itemmix($mlist, $itemselect=-1) 
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','player','logger','itemmix'));
//		$chc_res = itemmix_sync_check($mlist);
//
//		//无满足条件的同调结果，失败 
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
//			$cmd.= "请选择同调结果<br><br>";
//			$sync = count($chc['result']);
//			for($i=0;$i<$sync;$i++){
//				$tn=$chc['result'][$i][0];
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
//			addnews($now,'syncmix',$name,$itm0);
//			\itemmain\itemget();
//			$mode = 'command';
//			return;
//		}
//		$chprocess($mlist, $itemselect);
//	}
//	
//	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
//	{
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys'));
//		if($news == 'syncmix') 
//			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}同调合成了{$b}</span></li>";
//		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
//	}
}

?>