<?php

namespace smartmix
{
	function init() {}
	
	//以道具名称反查mixinfo数据
	//$elem为字符串时，type&1=以原料反查，type&2=以产物反查
	//$elem为数组时无视$type，当$elem包含一条mixinfo的原料时才认为查到
	//返回mixinfo里的单个array
	function smartmix_find_mixinfo($elem, $type=0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','itemmix'));
		$mixres = array();
		foreach ($mixinfo as $ma){
			if(is_array($elem)){
				if(empty(array_diff($ma['stuff'], $elem))){
					$mixres[] = $ma;
				}
			}else{
				if(($type & 1 && in_array($elem, $ma['stuff'])) || ($type & 2 && $elem == $ma['result'][0])){
					$mixres[] = $ma;
				}
			}
		}
		return $mixres;
	}
	
	//检查玩家包裹，返回可合成的道具列表
	function smartmix_check_available(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$pack0 = array();
		for($i=1;$i<=6;$i++){
			if(${'itms'.$i} > 0) $pack0[] = ${'itm'.$i};
		}
		$mix_available = smartmix_find_mixinfo($pack0);
		return $mix_available;
	}
	
	//显示之前的处理
	function act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','input','itemmix'));
		if ($mode == 'command' && $command == 'itemmain' && $itemcmd=='itemmix'){
			$mix_available = smartmix_check_available();
			if(empty($mix_available)){
				$log .= '可合成的道具不存在。<br>';
			}else{
				foreach($mix_available as $mval){
					$log .= '<span class="yellow">'.implode('+',$mval['stuff']).'</span>可合成<span class="yellow">'.$mval['result'][0].'</span>。<br>';
				}
			}
		}
		$chprocess();
	}
}

?>