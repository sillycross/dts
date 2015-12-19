<?php

namespace kuji0
{
	function init() {}
	
	function kujidraw0(&$pa, $is_dryrun = false){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('cardbase','kuji0'));
		if ($pa['gold']<100 && !$is_dryrun) return -1;
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
		if (!$is_dryrun)
		{
			if ((\cardbase\get_card($r,$pa))==1){
				\cardbase\get_qiegao(-100,$pa);
				$pa['gold']-=100;
			}else{
				\cardbase\get_qiegao(-70,$pa);
				$pa['gold']-=70;
			}
		}
		return $r;
	}
}

?>
