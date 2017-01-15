<?php

namespace smartmix
{
	function init() {}
	
	//以道具名称反查mixinfo数据
	//$elem为字符串时，type&1=以原料反查，type&2=以产物反查
	//$elem为数组时无视$type，当$elem包含一条mixinfo的原料时才认为查到
	//返回mixinfo里的单个array
	function smartmix_find_mixinfo($elem, int $type=0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','itemmix','itemmix_overlay'));
		if(is_array($elem)){
			foreach($elem as &$val){
				$val = \itemmix\itemmix_name_proc($val);
			}
		}else{
			$elem = \itemmix\itemmix_name_proc($elem);
		}
		$mixres = array();
		foreach ($mixinfo as $ma){
			$ma['type'] = 'normal';
			if(is_array($elem)){
				$elem0 = $elem; $aflag = true;
				foreach($ma['stuff'] as $ms){
					if(!in_array($ms,$elem0)){
						$aflag = false;
						break;
					}else{
						array_splice($elem0, array_search($ms, $elem0),1);
					}					
				}
				if($aflag){
					$mixres[] = $ma;
				}
//				if(empty(array_diff($ma['stuff'], $elem))){这样做遇到重复素材会悲剧
//					$mixres[] = $ma;
//				}
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
				$log .= '<span class="yellow">合成提示：</span><br>';
				foreach($mix_available as $mval){
					if($mval['type'] == 'normal'){
						$log .= '<span class="yellow">'.implode('+',$mval['stuff']).'</span>可合成<span class="yellow">'.$mval['result'][0].'</span>。<br>';
					}elseif($mval['type'] == 'overlay'){
					}elseif($mval['type'] == 'sync'){
					}
				}
			}
		}
		$chprocess();
	}
}

?>