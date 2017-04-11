<?php

namespace smartmix
{
	function init() {}
	
	//以道具名反查mixinfo数据
	//tp & 1 以原料反查，tp & 2 以产物反查
	//返回mixinfo里的单个array
	function smartmix_find_recipe($itm, $tp=0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmix'));
		$mix_res = array();		
		$itm = \itemmix\itemmix_name_proc($itm);
		foreach ($mixinfo as $ma){
			$ma['type'] = 'normal';
			if(($tp & 1 && in_array($itm, $ma['stuff'])) || ($tp & 2 && $itm == $ma['result'][0])){
				$mix_res[] = $ma;
			}
		}		
		return $mix_res;
	}
	
	//以道具编号反查mixinfo_overlay数据
//	function smartmix_find_recipe_overlay($mlist, $tp=0){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','itemmix_overlay'));
//		$ovl_res = \itemmix_overlay\itemmix_overlay_check($mlist, 1);
//		foreach($ovl_res as &$oval){
//			$oval['type'] = 'overlay';
//		}
//		return $ovl_res;
//	}
	
	//检查玩家包裹，返回可合成的道具列表
	function smartmix_check_available(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		//itms为零的道具不参与判断
		$packn = array();
		for($i=1;$i<=6;$i++){
			if(!empty(${'itms'.$i})){
				$packn[] = $i;
				$packname[] = \itemmix\itemmix_name_proc(${'itm'.$i});
			}
		}
		//生成道具序号的全组合
		$fc = full_combination($packn, 2);
		//基于全组合生成道具名的组合
		$fcname = full_combination($packname, 2);
		//所有的组合全部判断一遍是否可以合成，最简单粗暴和兼容
		$mix_available = $mix_overlay_available = $mix_sync_available = array();
		foreach($fcname as $fnval){
			$mix_res = \itemmix\itemmix_recipe_check($fnval);
			if($mix_res){
				//$mix_res['type'] = 'normal';
				$mix_available[] = $mix_res;
			}
		}
		foreach($fc as $fval){
			$mix_overlay_res = \itemmix_overlay\itemmix_overlay_check($fval);
			if($mix_overlay_res){
				foreach($mix_overlay_res as $mkey => $mval){
					//$mval['type'] = 'overlay';
					if(!isset($mix_overlay_available[$mkey])){
						$mix_overlay_available[$mkey] = array($mval);
					}else{
						$mix_overlay_available[$mkey][] = $mval;
					}
				}
			}
			$mix_sync_res = \itemmix_sync\itemmix_sync_check($fval);
			if($mix_sync_res){
				foreach($mix_sync_res as $mkey => $mval){
					//$mval['type'] = 'sync';
					if(!isset($mix_sync_available[$mkey])){
						$mix_sync_available[$mkey] = array($mval);
					}else{
						$mix_sync_available[$mkey][] = $mval;
					}
				}
			}
		}
		return array($mix_available,$mix_overlay_available,$mix_sync_available);
	}
	
	function parse_smartmix_recipelink($itemindex, $dtext = ''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return "<a class=\"yellow\" onclick=\"$('mode').value='command';$('command').value='itemmain';$('subcmd').name='itemcmd';$('subcmd').value='itemmix';$('subcmd2').name='itemindex';$('subcmd2').value='$itemindex';postCmd('gamecmd','command.php');\">".($dtext ? $dtext : $itemindex).'</a>';
	}
	
	function get_itemmix_filename(){//彻底覆盖原来的
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($gametype == 17) {
			$uip['tutorial_cmd_inner_tpl'] = MOD_SMARTMIX_ITEMMIX;
			return MOD_TUTORIAL_TUTORIAL_CMD;
		}
		else{
			return MOD_SMARTMIX_ITEMMIX;
		}
	}
	
	//显示之前的处理
	function act(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger','input','itemmix'));
		if ($mode == 'command' && $command == 'itemmain' && $itemcmd=='itemmix'){
			if(isset($itemindex)){
				$mix_res = smartmix_find_recipe($itemindex, 1 + 2);				
				if($mix_res){
					$log .= '<span class="yellow">'.$itemindex.'</span>涉及的合成公式有：<br>';
					foreach($mix_res as $mval){
						if(!isset($mval['type']) || $mval['type'] == 'normal'){
							foreach($mval['stuff'] as $ms){
								$log .= parse_smartmix_recipelink($ms).' + ';
							}
							$log = substr($log,0,-3);
						}
						$mr = $mval['result'][0];
						$log .= ' → '.parse_smartmix_recipelink($mr, \itemmix\parse_itemmix_resultshow($mval['result'])).'<br>';
					}
				}	else{
					$log .= '所选道具不存在相关的合成公式。<br>';
				}
			}else{
				list($mix_available,$mix_overlay_available,$mix_sync_available) = smartmix_check_available();
				if(empty($mix_available) && empty($mix_overlay_available) && empty($mix_sync_available)){
					$log .= '可合成的道具不存在。<br>';
				}else{
					$log .= '<span class="yellow">合成提示：</span><br>';
					foreach($mix_available as $mval){
						foreach($mval['stuff'] as $ms){
							$log .= parse_smartmix_recipelink($ms).' + ';
						}
						$log = substr($log,0,-3).'可合成'.parse_smartmix_recipelink($mval['result'][0], \itemmix\parse_itemmix_resultshow($mval['result'])).'。<br>';
					}
					foreach($mix_overlay_available as $mval){
						$ostuff = $oresult = '';
						foreach($mval as $mv){
							foreach($mv['list'] as $ml){
								$ostuff .= ${'itm'.$ml}.' + ';
							}
							$ostuff = substr($ostuff,0,-3).' / ';
							if(!$oresult){
								foreach($mv['choices'] as $mc){
									$oresult .= '<li>'.\itemmix\parse_itemmix_resultshow($mc).'</li>';
								}
								//$oresult = substr($oresult,0,-3);
							}
						}
						$ostuff = substr($ostuff,0,-3);
						$log .= '<span class="yellow">'.$ostuff.'</span>可超量合成'.$oresult;
					}
					foreach($mix_sync_available as $mval){
						$sstuff = $sresult = '';
						foreach($mval as $mv){
							foreach($mv['list'] as $ml){
								$sstuff .= ${'itm'.$ml}.' + ';
							}
							$sstuff = substr($sstuff,0,-3).' / ';
							if(!$sresult){
								foreach($mv['choices'] as $mc){
									$sresult .= '<li>'.\itemmix\parse_itemmix_resultshow($mc).'</li>';
								}
								$sresult = substr($sresult,0,-3);
							}
						}
						$sstuff = substr($sstuff,0,-3);
						$log .= '<span class="yellow">'.$sstuff.'</span>可同调合成'.$sresult;
					}
					$log .= '<br><br>';
				}
			}
		}
		$chprocess();
	}
}

?>