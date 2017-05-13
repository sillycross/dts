<?php

namespace itemmix_sync
{
	function init() 
	{
		eval(import_module('itemmain'));
		$itemspkinfo['s'] = '调整';
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
			$z=${'itmk'.$itmn};
			for ($i=0; $i<strlen($z); $i++) if ('0'<=$z[$i] && $z[$i]<='9') $star=$star*10+(int)$z[$i];
			if((strpos(${'itmsk'.$itmn},'s')!==false)) $tunner = true;
		}
		return array($star, $tunner);
	}
	
	function itemmix_sync_check($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		//判断是否存在调整，并计算总星数
		$star = 0;
		$tunner = array();
		foreach($mlist as $mval){
			list($mstar, $mtunner) = itemmix_star_culc_sync($mval);
			if($mstar == 0){
				$star = 0;break;
			}else{
				$star += $mstar;
			}
			if($mtunner){
				$tunner[] = $mval;
			}
		}
		$chc_res = array();
		if($star && count($tunner) == 1){
			//然后判断是否存在对应的同调成果
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
					sort($mname);sort($req);
					if($mname != $req) $preqflag = false;
				}
				if($pstar == $star && $preqflag){
					if(!isset($chc_res[$star])){//用键名记录星数和素材数方便提示
						$chc_res[$star] = array('list' => $mlist, 'choices' => array($pra));
					}else{
						$chc_res[$star]['choices'][] = $pra;
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
		$chc_res = itemmix_sync_check($mlist);

		//无满足条件的同调结果，失败
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
			$cmd.= "请选择同调结果<br><br>";
			$sync = count($chc['choices']);
			for($i=0;$i<$sync;$i++){
				$tn=$chc['choices'][$i][0];
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
			addnews($now,'syncmix',$name,$itm0);
			\itemmain\itemget();
			$mode = 'command';
			return;
		}
		$chprocess($mlist, $itemselect);
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($news == 'syncmix') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}同调合成了{$b}</span></li>\n";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>