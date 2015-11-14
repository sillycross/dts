<?php

namespace kuji1
{
	function init() {}
	
	function draw1(&$pa){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase','kuji1'));
		if ($pa['gold']<1000) return -1;
		$rr=array();
		$r=rand(1,$cw);
		if ($r<=$sw){
			$arr=$cardindex['S'];
		}else if($r<=$aw){
			$arr=$cardindex['A'];
		}else{
			$arr=$cardindex['B'];
		}
		$c=count($arr)-1;
		$r=$arr[rand(0,$c)];
		if ((\cardbase\get_card($r,$pa))!=1){
			\cardbase\get_qiegao(30,$pa);
			$pa['gold']+=30;
		}
		$rr[0]=$r;
		for ($i=1;$i<=10;$i++){
			$r=rand(1,$cw);
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
			if ((\cardbase\get_card($r,$pa))==1){
				\cardbase\get_qiegao(-100,$pa);
				$pa['gold']-=100;
			}else{
				\cardbase\get_qiegao(-70,$pa);
				$pa['gold']-=70;
			}
			$rr[$i]=$r;
		}		
		return $rr;
	}
}

?>
