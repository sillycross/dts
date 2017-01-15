<?php

namespace itemmix
{
	function init() {}
	
	function itemmix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$log .= "<span class=\"yellow\">$itmstr</span>合成了<span class=\"yellow\">{$itm0}</span><br>";
		addnews($now,'itemmix',$name,$itm0);
	
		$wd+=1;
		if((strpos($itmk0,'H') === 0)&&($club == 16)&&($itms0 !== $nosta)){ $itms0 = ceil($itms0*2); }
		elseif(($itmk0 == 'EE' || $itmk0 == 'ER') && ($club == 7)){ $itme0 *= 5; }

		\itemmain\itemget();
	}
	
	function itemmix_place_check($mlist){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmix'));
		$mlist2 = array_unique($mlist);	
		if(count($mlist) != count($mlist2)) {
			$log .= '相同道具不能进行合成！<br>';
			$mode = 'itemmix';
			return false;
		}
		if(count($mlist) < 2){
			$log .= '至少需要2个道具才能进行合成！';
			$mode = 'itemmix';
			return false;
		}
		
		foreach($mlist as $val){
			if(!${'itm'.$val}){
				$log .= '所选择的道具不存在！';
				$mode = 'itemmix';
				return false;
			}
		}
		return true;
	}
	
	function itemmix_name_proc($n){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		foreach(Array('/锋利的/','/电气/','/毒性/','/-改$/') as $value){
			$n = preg_replace($value,'',$n);
		}
		$n = str_replace('钉棍棒','棍棒',$n);
		return $n;
	}
	
	function itemmix($mlist, $itemselect=-1) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','itemmix'));
		
		if(!itemmix_place_check($mlist)) return;
		
		$mixitem = array();
		foreach($mlist as $val){
			$mixitem[] = itemmix_name_proc(${'itm'.$val});
		}
		
		$mixflag = false;
		foreach($mixinfo as $minfo)
		{
			if (count($mixitem)==count($minfo['stuff']))
			{
				$t1=$mixitem; $t2=$minfo['stuff'];
				sort($t1); sort($t2);
				$flag=1;
				for ($i=0; $i<count($t1); $i++)
					if ($t1[$i]!=$t2[$i])
					{
						$flag=0; break;
					}
				if ($flag) { $mixflag=true; break; }			
			}
		}
		
		$itmstr = '';
		foreach($mixitem as $val){
			$itmstr .= $val.' ';
		}
		$itmstr = substr($itmstr,0,-1);
			
		if(!$mixflag) {
			$log .= "<span class=\"yellow\">$itmstr</span>不能合成！<br>";
			ob_clean();
			template(get_itemmix_filename());
			$cmd = ob_get_contents();
			ob_clean();
		} else {
			foreach($mlist as $val){
				itemreduce('itm'.$val);
			}

			$itm0 = $minfo['result'][0];
			$itmk0 = $minfo['result'][1];
			$itme0 = $minfo['result'][2];
			$itms0 = $minfo['result'][3];
			if (isset($minfo['result'][4]))
				$itmsk0 = $minfo['result'][4];
			else{
				$itmsk0 = '';
				$minfo['result'][4]='';
			}
			itemmix_success();
		}
		return;
	}
	
	function get_itemmix_filename(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		return MOD_ITEMMIX_ITEMMIX;
	}
	
	function itemreduce($item){ //只限合成使用！！
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if(strpos($item,'itm') === 0) {
			$itmn = substr($item,3,1);
			$itm = & ${'itm'.$itmn};
			$itmk = & ${'itmk'.$itmn};
			$itme = & ${'itme'.$itmn};
			$itms = & ${'itms'.$itmn};
			$itmsk = & ${'itmsk'.$itmn};
		} else {
			return;
		}

		if(!$itms) { return; }
		if(preg_match('/^(Y|B|C|X|TN|GB|H|P|V|M)/',$itmk)){$itms--;}
		else{$itms=0;}
		if($itms <= 0) {
			$itms = 0;
			$log .= "<span class=\"red\">$itm</span>用光了。<br>";
			$itm = $itmk = $itmsk = '';
			$itme = $itms = 0;
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','input'));
		if($mode == 'itemmain') {
			if($command == 'itemmix') {
				if (isset($itemselect) && $itemselect==999)
					$mode='command';
				else
				{
					$mixlist = array();
					if (!isset($mixmask))
					{
						for($i=1;$i<=6;$i++)
							if(isset(${'mitm'.$i}) && ${'mitm'.$i} == $i)
								$mixlist[] = $i;
					}
					else
					{
						for($i=1;$i<=6;$i++)
							if ($mixmask&(1<<($i-1)))
								$mixlist[] = $i;
					}
					if (isset($itemselect))
						itemmix($mixlist,$itemselect);
					else  itemmix($mixlist);
				}
			}
		}
		if ($mode == 'command' && $command == 'itemmain' && $itemcmd=='itemmix')
		{
			ob_clean();
			if ($itemcmd=='itemmix') include template(get_itemmix_filename());
			$cmd = ob_get_contents();
			ob_clean();
		}
		$chprocess();
	}
	
	function parse_news($news, $hour, $min, $sec, $a, $b, $c, $d, $e)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($news == 'itemmix') 
			return "<li>{$hour}时{$min}分{$sec}秒，<span class=\"lime\">{$a}合成了{$b}</span><br>\n";
		return $chprocess($news, $hour, $min, $sec, $a, $b, $c, $d, $e);
	}

}

?>
