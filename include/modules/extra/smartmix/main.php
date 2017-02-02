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
	function smartmix_find_recipe_overlay($mlist, $tp=0){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','itemmix_overlay'));
		$ovl_res = \itemmix_overlay\itemmix_overlay_check($mlist, 1);
		foreach($ovl_res as &$oval){
			$oval['type'] = 'overlay';
		}
		return $ovl_res;
	}
	
	//检查玩家包裹，返回可合成的道具列表
	function smartmix_check_available(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		//itms为零的道具不参与判断
		$packn = array();
		for($i=1;$i<=6;$i++){
			if(${'itms'.$i} > 0){
				$packn[] = $i;
				$packname[] = \itemmix\itemmix_name_proc(${'itm'.$i});
			}
		}
		//生成道具序号的全组合
		$fc = full_combination($packn, 2);
		//基于全组合生成道具名的组合
		$fcname = full_combination($packname, 2);
		//所有的组合全部判断一遍是否可以合成，最简单粗暴和兼容
		$mix_available = array();
		foreach($fcname as $fval){
			$mix_res = \itemmix\itemmix_recipe_check($fval);
			if($mix_res){
				$mix_res['type'] = 'normal';
				$mix_available[] = $mix_res;
			}
		}
		//$mix_available = smartmix_find_recipe($pack0);
		//$mix_available_overlay = smartmix_find_recipe_overlay($pack0);
		//$mix_available = array_merge($mix_available, $mix_available_overlay);
		return $mix_available;
	}
	
	function parse_smartmix_itemshow($itemindex, $dtext = ''){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
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
								$log .= parse_smartmix_itemshow($ms).' + ';
							}
							$log = substr($log,0,-3);
						}
						$mr = $mval['result'][0];
						$log .= ' → '.parse_smartmix_itemshow($mr).'<br>';
					}
				}	else{
					$log .= '所选道具不存在相关的合成公式。<br>';
				}
			}else{
				$mix_available = smartmix_check_available();
				if(empty($mix_available)){
					$log .= '可合成的道具不存在。<br>';
				}else{
					$log .= '<span class="yellow">合成提示：</span><br>';
					foreach($mix_available as $mval){
						if(!isset($mval['type']) || $mval['type'] == 'normal'){
							foreach($mval['stuff'] as $ms){
								$log .= parse_smartmix_itemshow($ms).' + ';
							}
							$log = substr($log,0,-3).'可合成'.parse_smartmix_itemshow($mval['result'][0]).'。<br>';
						}elseif($mval['type'] == 'overlay'){
							
							$ostuff = '';
							foreach($mval['list'] as $ml){
								$ostuff .= ${'itm'.$ml}.' ';
							}
							$ostuff = substr($ostuff,0,-1);
							
							$oresult = '';
							foreach($mval['choices'] as $mc){
								$oresult .= $mc[0].' ';
							}
							$oresult = substr($oresult,0,-1);
							
							$log .= '<span class="yellow">'.$ostuff.'</span>可超量合成<span class="yellow">'.$oresult.'</span>。<br>';
						}elseif($mval['type'] == 'sync'){
						}
					}
				}
			}
		}
		$chprocess();
	}
}

?>