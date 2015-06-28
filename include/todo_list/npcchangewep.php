<?php


function npc_changewep($active = 0){
	global $now,$log,$w_name,$w_type,$w_club,$w_wep, $w_wepk, $w_wepe, $w_weps, $w_itm0, $w_itmk0, $w_itme0, $w_itms0, $w_itm1, $w_itmk1, $w_itme1, $w_itms1, $w_itm2, $w_itmk2, $w_itme2, $w_itms2, $w_itm3, $w_itmk3, $w_itme3, $w_itms3, $w_itm4, $w_itmk4, $w_itme4, $w_itms4, $w_itm5, $w_itmk5, $w_itme5, $w_itms5,$w_itm6, $w_itmk6, $w_itme6, $w_itms6, $w_wepsk, $w_arbsk, $w_arhsk, $w_arask, $w_arfsk, $w_artsk, $w_itmsk0, $w_itmsk1, $w_itmsk2, $w_itmsk3, $w_itmsk4, $w_itmsk5, $w_itmsk6;
	global $w_arb, $w_arbk, $w_arbe, $w_arbs, $w_arh, $w_arhk, $w_arhe, $w_arhs, $w_ara, $w_arak, $w_arae, $w_aras, $w_arf, $w_arfk, $w_arfe, $w_arfs, $w_art, $w_artk, $w_arte, $w_arts;
	global $wepk,$wepsk,$arbsk,$arask,$arhsk,$arfsk,$artsk,$artk,$rangeinfo,$ex_dmg_def;
	if(!$w_name || !$w_type || $w_club != 98){return;}
	
	$dice = rand(0,99);
	if($dice > 50){
		$weplist = array();
		$wepklist = Array($w_wepk);$weplist2 = array();
		for($i=0;$i<=6;$i++){
			if(${'w_itms'.$i} && ${'w_itme'.$i} && strpos(${'w_itmk'.$i},'W')===0){
				$weplist[] = Array($i,${'w_itm'.$i},${'w_itmk'.$i},${'w_itme'.$i},${'w_itms'.$i},${'w_itmsk'.$i});
				$wepklist[] = ${'w_itmk'.$i};
			}
		}
		if(!empty($weplist)){
			$wepklist = array_unique($wepklist);
			$temp_def_key = getdefkey($wepsk,$arhsk,$arbsk,$arask,$arfsk,$artsk,$artk);
			$wepkAI = $wepskAI = true;
			if(strpos($temp_def_key,'_P_K_G_C_D_F')!==false || strpos($temp_def_key,'B')!==false){$wepkAI = false;}
			if(count($wepklist)<=1){$wepkAI = false;}
			if(strpos($temp_def_key,'_q_U_I_D_E')!==false || strpos($temp_def_key,'b')!==false){$wepskAI = false;}
			
			if($wepkAI){
				if(!$wepk){$wepk_temp = 'WN';}else{$wepk_temp = $wepk;}
				foreach($weplist as $val){
					if($rangeinfo[substr($val[2],1,1)] >= $rangeinfo[substr($wepk_temp,1,1)] && strpos($temp_def_key,substr($val[2],1,1))===false){
						$weplist2[] = $val;
					}
				}
				if($weplist2){
					$weplist = $weplist2;
				}				
			}
			if($wepskAI && $weplist){
				$minus = array();
				foreach($weplist as $val){
					foreach($ex_dmg_def as $key => $val2){
						if(strpos($val[5],$key)!==false && strpos($temp_def_key,$val2)!==false){
							$minus[] = $val;
						}
					}
				}
				//var_dump($minus);
				if(count($minus) < count($weplist)){
					$weplist = array_diff($weplist,$minus);
				}				
			}
		}
//		var_dump($wepkAI);echo '<br>';var_dump($wepskAI);echo '<br>';
//		var_dump($weplist);
//		if(!empty($weplist2)){
//			$weplist = $weplist2;
//		}
		
		if(!empty($weplist)){
			$oldwep = $w_wep;
			shuffle($weplist);
			$chosen = $weplist[0];$c = $chosen[0];
			//var_dump($chosen);
			${'w_itm'.$c} = $w_wep;${'w_itmk'.$c} = $w_wepk;${'w_itme'.$c} = $w_wepe;${'w_itms'.$c} = $w_weps;${'w_itmsk'.$c} = $w_wepsk;
			$w_wep = $chosen[1]; $w_wepk = $chosen[2]; $w_wepe = $chosen[3];$w_weps = $chosen[4];$w_wepsk = $chosen[5];
			//list($c,$w_wep,$w_wepk,$w_wepe,$w_weps,$w_wepsk) = $chosen;
			$log .= "<span class=\"yellow\">{$w_name}</span>将手中的<span class=\"yellow\">{$oldwep}</span>卸下，装备了<span class=\"yellow\">{$w_wep}</span>！<br>";
		}
	}
	return;
}

?>