<?php

namespace kujibase
{
	function init() {}
	
	//抽卡主函数。
	//返回0表示指令错误，返回-1表示抽卡失败，返回以卡片编号或者“卡片编号_镜碎等级”为元素的数组表示抽卡成功
	function kujidraw($kujiid, &$pa, $is_dryrun = false){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','cardbase','kujibase'));
		$cost = $kujicost[$kujiid];
		$num = $kujinum[$kujiid];
		$packchoice = get_var_in_module('packchoice', 'input');
		if(empty($cost)) return;
		if((empty($packchoice) || !in_array($packchoice, \cardbase\pack_filter($packlist))) && $kujinum_in_pack[$kujiid] > 0) return;
		
		if (!$is_dryrun && $pa['gold'] < $cost) return -1;
		list($sw, $aw, $bw) = $kujiobbs[$kujiid];
		if(!empty($pa)) \cardbase\get_qiegao(-$cost,$pa);//只扣1次
		$rr = array();
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
			
			$blink = 0;
			if (!$is_dryrun)
			{
				$blink = \cardbase\get_card_calc_blink($r, $pa);
				\cardbase\get_card_alternative($r, $pa, 0, $blink);
				if(!empty($blink)) $r.='_'.$blink;//如果镜碎等级不为零则返回“编号_镜碎等级”这样的字符串，接收时注意处理
				//\cardbase\get_card_process($r,$pa);
			}
			$rr[] = $r;
		}
		
		if($pa){
			$un = $pa['username'];
			//3202.10.15 这里保存一次$card_data
			//游戏内获得卡片的时候$pa是即时读取的，站内信和抽卡则会有一段距离，这中间对$cardlist的修改会丢失。不过$cardlist本来也不是引用，应该不算问题
			
			$upd = Array(
				'gold' => $pa['gold'],
				'card_data' => $pa['card_data'],
			);
			update_udata_by_username($upd, $un);
		}
		
		return $rr;
	}
}

?>