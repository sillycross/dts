<?php

namespace kujibase
{
	function init() {}
	
	//抽卡主函数。
	//返回0表示指令错误，返回-1表示抽卡失败，返回以卡片编号为元素的数组表示抽卡成功
	function kujidraw($kujiid, &$pa, $is_dryrun = false){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase','kujibase'));
		$cost = $kujicost[$kujiid];
		$num = $kujinum[$kujiid];
		$packchoice = \input\get_var('packchoice');
		if(empty($cost)) return;
		if((empty($packchoice) || !in_array($packchoice, \cardbase\pack_filter($packlist))) && $kujinum_in_pack[$kujiid] > 0) return;
		
		if (!$is_dryrun && $pa['gold'] < $cost) return -1;
		list($sw, $aw, $bw) = $kujiobbs[$kujiid];
		\cardbase\get_qiegao(-$cost,$pa);//只扣1次
		$rr =array();
		for($i=0;$i<$num;$i++){
			if(0 == $i && $num > 1){//非单抽第一张保底B卡
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
			do{
				$r=$arr[rand(0,$c)];
			}while(!empty($kujinum_in_pack[$kujiid]) && $i < $kujinum_in_pack[$kujiid] && $cards[$r]['pack'] != $packchoice);
			
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
			
			update_udata_by_username(array('cardlist' => $p_cardlist, 'gold' => $p_gold), $p_username);
		}
		
		return $rr;
	}
}

?>