<?php

namespace kujibase
{
	function init() {}
	
	function kujidraw($kujiid, &$pa, $is_dryrun = false){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase','kujibase'));
		$cost = $kujicost[$kujiid];
		$num = $kujinum[$kujiid];
		if(!$cost) return;
		if (!$is_dryrun && $pa['gold'] < $cost) return -1;
		list($sw, $aw, $bw) = $kujiobbs[$kujiid];
		\cardbase\get_qiegao(-$cost,$pa);//只扣1次
		$rr =array();
		for($i=0;$i<$num;$i++){
			if(0 == $i && $num > 1){//非单抽第一张保底
				$r = rand(1,$bw);
			}else{
				$r = rand(1,99);
			}
			if ($r<=$sw){
				$arr=$cardindex['S'];
			}else if($r<=$aw){
				$arr=$cardindex['A'];
			}else if($r<=$bw){
				$arr=$cardindex['B'];
			}else{
				$arr=$cardindex['C'];
			}
			$c=count($arr)-1;
			$r=$arr[rand(0,$c)];
			if (!$is_dryrun)
			{
				\cardbase\get_card_process($r,$pa);
			}
			$rr[] = $r;
		}
		
		if($pa){
			$p_username = $pa['username'];
			$p_cardlist = $pa['cardlist'];
			$p_gold = $pa['gold'];
			
			$db->query("UPDATE {$gtablepre}users SET cardlist='$p_cardlist',gold='$p_gold' WHERE username='$p_username'");
		}
		
		return $rr;
	}
}

?>