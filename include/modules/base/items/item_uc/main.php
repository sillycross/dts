<?php

namespace item_uc
{
	function init()
	{
		eval(import_module('itemmain'));
		$iteminfo['C'] = '药剂';
	}
	
	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','itemmain','wound','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'C' ) === 0) 
		{
			$ck=substr($itmk,1,1);
			if($ck == 'a'){
				$flag=false;
				$log .= "服用了<span class=\"red\">$itm</span>。<br>";
				for ($i=0; $i<strlen($inf); $i++)
				{
					if(substr($inf_place,$inf[$i])!==false){//肢体受伤不会被异常药剂治愈
						$log .= "{$infname[$inf[$i]]}状态解除了。<br>";
						$inf = str_replace($inf[$i],'',$inf);
						$flag=true;
					}
				}
				//$inf = '';
				if(!$flag){
					$log .= '但是什么也没发生。<br>';
				}
			}
			else
			{
				if(strpos ( $inf, $ck ) !== false){
					$inf = str_replace ( $ck, '', $inf );
					$log .= "服用了<span class=\"red\">$itm</span>，{$infname[$ck]}状态解除了。<br>";
				}else{
					$log .= "服用了<span class=\"red\">$itm</span>，但是什么效果也没有。<br>";
				}
			}
			
			\itemmain\itms_reduce($theitem);
			return;
		}
		$chprocess($theitem);
	}
}

?>
