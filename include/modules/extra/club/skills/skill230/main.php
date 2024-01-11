<?php

namespace skill230
{
	function init() 
	{
		define('MOD_SKILL230_INFO','club;active;feature;');
		eval(import_module('clubbase'));
		$clubskillname[230] = '感电';
		$clubdesc_h[7] = $clubdesc_a[7] = '能够用电池、探测器电池为武器增加电击属性';
	}
	
	function acquire230(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost230(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked230(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function wele(){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','itemmain','logger'));
		if($wepk == 'WN' || !$wepe || !$weps){
			$log .= '<span class="red b">你没有装备武器，无法改造！</span><br />';
			$mode = 'command';
			return;
		}
		$wepsk_arr = \itemmain\get_itmsk_array($wepsk);
		if (\itemmain\check_in_itmsk('j', $wepsk_arr)){
			$log.='多重武器不能改造。<br>';
			$mode='command';
			return;
		}
		if(\skillbase\skill_query(230)){
			$position = 0;
			foreach(Array(1,2,3,4,5,6) as $imn){
				global ${'itm'.$imn},${'itmk'.$imn},${'itme'.$imn},${'itms'.$imn},${'itmsk'.$imn};
				if(strpos(${'itmk'.$imn},'B')===0 && ${'itme'.$imn} > 0 ){
					$position = $imn;
					break;
				}
			}
			if($position){
				if(\itemmain\check_in_itmsk('e', $wepsk_arr)){
					$log .= '<span class="red b">武器已经带电，不用改造！</span><br />';
					$mode = 'command';
					return;
				}elseif(count($wepsk_arr) >= 12){
					$log .= '<span class="red b">武器属性数目达到上限，无法改造！</span><br />';
					$mode = 'command';
					return;
				}
				${'itms'.$position}-=1;
				$itm = ${'itm'.$position};
				$log .= "<span class=\"yellow b\">用{$itm}改造了{$wep}，{$wep}增加了电击属性！</span><br />";
				if(strpos($wep,'电气')===false)
					$wep = '电气'.$wep;
				$wepsk .= 'e';
				if(${'itms'.$position} == 0){
					$log .= "<span class=\"red b\">$itm</span>用光了。<br />";
					${'itm'.$position} = ${'itmk'.$position} = ${'itmsk'.$position} = '';
					${'itme'.$position} =${'itms'.$position} =0;				
				}
				$mode = 'command';
				return;
			}else{
				$log .= '<span class="red b">你没有电池，无法改造武器！</span><br />';
				$mode = 'command';
				return;
			}
		}else{
			$log .= '<span class="red b">你没有这个技能！</span><br />';
			$mode = 'command';
			return;
		}
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
	

		if ($mode == 'special' && $command == 'skill230_special' && get_var_input('subcmd')=='wele') 
		{
			wele();
			return;
		}
			
		$chprocess();
	}
	
}

?>