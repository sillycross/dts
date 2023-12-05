<?php

namespace ex_residue
{
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^res'] = '余物';//不显示
		$itemspkinfo['^rtype'] = '如何获得/改变记录物品';
		//0:不会得到或变成新的物品；1:每次消耗耐久后得到新的物品；2:耐久消耗完后得到新的物品；3:消耗耐久后变成新的物品；4:自身不存在；5:作为武器/防具损坏后会变成新的物品
		$itemspkinfo['^reptype'] = '如何代表记录物品';
		//0:不代表新的物品；1:使用时代表新的物品；2:合成时代表新的物品；3:使用和合成时代表新的物品
	}
	
	function get_res_itm($itmsk)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = array();
		$ress = \itemmain\check_in_itmsk('^res', $itmsk);
		if (empty($ress)) return $ret;
		$ress = \attrbase\base64_decode_comp_itmsk($ress);	
		if (!empty($ress)) {
			$resarr = explode(',', $ress);
			$ret = array(
				'itm' => $resarr[0],
				'itmk' => $resarr[1],
				'itme' => $resarr[2],
				'itms' => $resarr[3],
				'itmsk' => $resarr[4]
			);
		}
		return $ret;
	}
	
	function put_res_itm($itmsk, $resarr)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$nowress = \itemmain\check_in_itmsk('^res', $itmsk);
		if(!empty($nowress)) $itmsk = \itemmain\replace_in_itmsk('^res','',$itmsk);
		if(!empty($resarr)){
			$ress = $resarr['itm'].','.$resarr['itmk'].','.$resarr['itme'].','.$resarr['itms'].','.$resarr['itmsk'];
			$itmsk .= '^res_'.\attrbase\base64_encode_comp_itmsk($ress).'1';
		}
		return $itmsk;
	}
	
	//根据rtype决定如何得到记录物品
	function itms_reduce(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain','logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		$rtype = (int)\itemmain\check_in_itmsk('^rtype', $itmsk);
		if (in_array($rtype, array(1,2,3)))
		{
			$resitem = get_res_itm($itmsk);
			if (!empty($resitem))
			{
				if ($itms != $nosta)
				{
					$itms --;
					if ($itms <= 0)
					{
						$log .= "<span class=\"red b\">$itm</span>用光了。<br>你获得了<span class=\"red b\">{$resitem['itm']}</span>。<br>";
						$itm=$resitem['itm']; $itmk=$resitem['itmk'];
						$itme=$resitem['itme']; $itms=$resitem['itms']; $itmsk=$resitem['itmsk'];
					}
					elseif ((1 == $rtype) && defined('MOD_SEARCHMEMORY') && defined('MOD_SKILL1006'))
					{
						if (\searchmemory\searchmemory_available())
						{
							eval(import_module('player'));
							$log .= "<span class=\"red b\">{$resitem['itm']}</span>掉到了你的脚边。<br>";
							$dropid = \itemmain\itemdrop_query($resitem['itm'], $resitem['itmk'], $resitem['itme'], $resitem['itms'], $resitem['itmsk'], $pls);
							$amarr = array('iid' => $dropid, 'itm' => $resitem['itm'], 'pls' => $pls, 'unseen' => 0);
							\skill1006\add_beacon($amarr, $sdata);
							\player\player_save($sdata);
						}
					}
					elseif (3 == $rtype)
					{
						$log .= "<span class=\"red b\">$itm</span>变成了<span class=\"red b\">{$resitem['itm']}</span>。<br>";
						$itm=$resitem['itm']; $itmk=$resitem['itmk'];
						$itme=$resitem['itme']; $itms=$resitem['itms']; $itmsk=$resitem['itmsk'];
					}
				}
			}
			else $chprocess($theitem);
		}
		else $chprocess($theitem);
	}
	
	//itemmix中如何得到记录物品的处理，添加了合成时代表其他物品的处理
	function itemmix_reduce($item){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		if(strpos($item,'itm') === 0) {
			$itmn = substr($item,3,1);
			$itm = & ${'itm'.$itmn};
			$itmk = & ${'itmk'.$itmn};
			$itme = & ${'itme'.$itmn};
			$itms = & ${'itms'.$itmn};
			$itmsk = & ${'itmsk'.$itmn};
		} else {
			return;
		}
		
		if(!$itms) return;
		
		$reptype = (int)\itemmain\check_in_itmsk('^reptype', $itmsk);
		$rtype = (int)\itemmain\check_in_itmsk('^rtype', $itmsk);
		if (in_array($reptype, array(2,3)))
		{
			$resitem = get_res_itm($itmsk);
			if (!empty($resitem))
			{
				if ($resitem['itms'] !== $nosta && preg_match('/^(Y|B|C|X|TN|GB|H|P|V|M)/',$resitem['itmk'])) $resitem['itms']--;
				else $resitem['itms'] = 0;
				if ($resitem['itms'] > 0)
				{
					if (3 == $rtype)
					{
						$log .= "<span class=\"red b\">$itm</span>变成了<span class=\"red b\">{$resitem['itm']}</span>。<br>";
						$itm=$resitem['itm']; $itmk=$resitem['itmk'];
						$itme=$resitem['itme']; $itms=$resitem['itms']; $itmsk=$resitem['itmsk'];
					}
					else $itmsk = put_res_itm($itmsk, $resitem);
				}
				//resitem用完时判断是否保留原物品
				else
				{
					if (4 == $rtype)
					{
						$log .= "<span class=\"red b\">$itm</span>烟消云散了。<br>";
						$itm = $itmk = $itmsk = '';
						$itme = $itms = 0;
					}
					else
					{
						$log .= "<span class=\"yellow b\">$itm</span>变得没有什么特别了。<br>";
						$itmsk = \itemmain\replace_in_itmsk('^res','',$itmsk);
						$itmsk = \itemmain\replace_in_itmsk('^rtype','',$itmsk);
						$itmsk = \itemmain\replace_in_itmsk('^reptype','',$itmsk);
					}
				}
			}
			else $chprocess($item);
		}
		elseif (in_array($rtype, array(1,2,3)))
		{
			$resitem = get_res_itm($itmsk);
			if (!empty($resitem))
			{
				if($itms !== $nosta && preg_match('/^(Y|B|C|X|TN|GB|H|P|V|M)/',$itmk)){
					$itms--;
				}
				else{
					$itms=0;
				}
				if ($itms <= 0)
				{
					$log .= "<span class=\"red b\">$itm</span>用光了。<br>你获得了<span class=\"red b\">{$resitem['itm']}</span>。<br>";
					$itm=$resitem['itm']; $itmk=$resitem['itmk'];
					$itme=$resitem['itme']; $itms=$resitem['itms']; $itmsk=$resitem['itmsk'];
				}
				elseif ((1 == $rtype) && defined('MOD_SEARCHMEMORY') && defined('MOD_SKILL1006'))
				{
					if (\searchmemory\searchmemory_available())
					{
						$log .= "<span class=\"red b\">{$resitem['itm']}</span>掉到了你的脚边。<br>";
						$dropid = \itemmain\itemdrop_query($resitem['itm'], $resitem['itmk'], $resitem['itme'], $resitem['itms'], $resitem['itmsk'], $pls);
						$amarr = array('iid' => $dropid, 'itm' => $resitem['itm'], 'pls' => $pls, 'unseen' => 0);
						\skill1006\add_beacon($amarr, $sdata);
						\player\player_save($sdata);
					}
				}
				elseif (3 == $rtype)
				{
					$log .= "<span class=\"red b\">$itm</span>变成了<span class=\"red b\">{$resitem['itm']}</span>。<br>";
					$itm=$resitem['itm']; $itmk=$resitem['itmk'];
					$itme=$resitem['itme']; $itms=$resitem['itms']; $itmsk=$resitem['itmsk'];
				}
				return;
			}
			else $chprocess($item);
		}
		else $chprocess($item);
	}
	
	//根据reptype决定是否在使用时代表记录物品
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain','logger','player'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		$reptype = (int)\itemmain\check_in_itmsk('^reptype', $itmsk);
		if (in_array($reptype, array(1,3)))
		{
			$resitem = get_res_itm($itmsk);
			$rtype = (int)\itemmain\check_in_itmsk('^rtype', $itmsk);
			if (!empty($resitem))
			{
				$log .= "你使用了<span class=\"yellow b\">$itm</span>……不对，这分明是<span class=\"yellow b\">{$resitem['itm']}</span>！<br>";
				if (in_array($resitem['itmk'][0], array('W','D')))
				{
					if (4 == $rtype)
					{
						$itm=$resitem['itm']; $itmk=$resitem['itmk'];
						$itme=$resitem['itme']; $itms=$resitem['itms']; $itmsk=$resitem['itmsk'];
						$chprocess($theitem);
					}
					else
					{
						if (defined('MOD_SEARCHMEMORY') && defined('MOD_SKILL1006') && \searchmemory\searchmemory_available())
						{
							$log .= "<span class=\"red b\">{$resitem['itm']}</span>掉到了你的脚边。<br>";
							$dropid = \itemmain\itemdrop_query($resitem['itm'], $resitem['itmk'], $resitem['itme'], $resitem['itms'], $resitem['itmsk'], $pls);
							$amarr = array('iid' => $dropid, 'itm' => $resitem['itm'], 'pls' => $pls, 'unseen' => 0);
							\skill1006\add_beacon($amarr, $sdata);
							\player\player_save($sdata);
							$resitem['itms'] = 0;
						}
					}
				}
				else $chprocess($resitem);
				if ($resitem['itms'] > 0)
				{
					if (3 == $rtype)
					{
						$log .= "<span class=\"red b\">$itm</span>变成了<span class=\"red b\">{$resitem['itm']}</span>。<br>";
						$itm=$resitem['itm']; $itmk=$resitem['itmk'];
						$itme=$resitem['itme']; $itms=$resitem['itms']; $itmsk=$resitem['itmsk'];
					}
					else $itmsk = put_res_itm($itmsk, $resitem);
				}
				//resitem用完时判断是否保留原物品
				else
				{
					if (4 == $rtype)
					{
						$log .= "<span class=\"red b\">$itm</span>烟消云散了。<br>";
						$itm = $itmk = $itmsk = '';
						$itme = $itms = 0;
					}
					else
					{
						$log .= "<span class=\"yellow b\">$itm</span>变得没有什么特别了。<br>";
						$itmsk = \itemmain\replace_in_itmsk('^res','',$itmsk);
						$itmsk = \itemmain\replace_in_itmsk('^rtype','',$itmsk);
						$itmsk = \itemmain\replace_in_itmsk('^reptype','',$itmsk);
					}
				}
			}
			else $chprocess($theitem);
		}
		else $chprocess($theitem);
	}
	
	//根据reptype决定是否在合成时代表记录物品
	function itemmix_recipe_check($mixitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		foreach($mixitem as &$mi)
		{
			$reptype = (int)\itemmain\check_in_itmsk('^reptype', $mi['itmsk']);
			if (in_array($reptype, array(2,3)))
			{
				$resitem = get_res_itm($mi['itmsk']);
				if (!empty($resitem))
				{
					$mi = array(
						'itm' => $resitem['itm'],
						'itmk' => $resitem['itmk'],
						'itme' => $resitem['itme'],
						'itms' => $resitem['itms'],
						'itmsk' => $resitem['itmsk'],
					);
				}
			}
		}
		return $chprocess($mixitem);
	}
	
	//rtype类别5
	function weapon_break(&$pa, &$pd, $active)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$rtype = (int)\itemmain\check_in_itmsk('^rtype', $pa['wepsk']);
		if ((5 == $rtype) && !in_array($pa['wep_kind'], array('G','J','B')))
		{
			$resitem = get_res_itm($pa['wepsk']);
			if (!empty($resitem))
			{
				eval(import_module('logger'));
				if ($active) $log .= "<span class=\"yellow b\">你的{$pa['wep']}在历尽沧桑之后，显现出了真实的样貌！</span><br>";
				else $log .= "<span class=\"yellow b\">{$pa['name']}的{$pa['wep']}在历尽沧桑之后，显现出了真实的样貌！</span><br>";
				$pa['wep'] = $resitem['itm'];
				$pa['wepk'] = $resitem['itmk'];
				$pa['wepe'] = $resitem['itme'];
				$pa['weps'] = $resitem['itms'];
				$pa['wepsk'] = $resitem['itmsk'];
			}
			else $chprocess($pa,$pd,$active);
		}
		else $chprocess($pa,$pd,$active);
	}
	
	function armor_break(&$pa, &$pd, $active, $whicharmor)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$rtype = (int)\itemmain\check_in_itmsk('^rtype', $pd[$whicharmor.'sk']);
		if (5 == $rtype)
		{
			$resitem = get_res_itm($pd[$whicharmor.'sk']);
			if (!empty($resitem))
			{
				eval(import_module('logger'));
				if ($active)
				{
					$log .= "<span class=\"yellow b\">{$pd['name']}的{$pd[$whicharmor]}在历尽沧桑之后，显现出了真实的样貌！！</span><br>";
					$pd['armorbreaklog'] .= "你的<span class=\"red b\">{$pd[$whicharmor]}</span>变成了<span class=\"red b\">{$resitem['itm']}</span>！<br>";
				}
				else $log .= "<span class=\"yellow b\">你的{$pd[$whicharmor]}在历尽沧桑之后，显现出了真实的样貌！</span><br>";
				$pd[$whicharmor] = $resitem['itm'];
				$pd[$whicharmor.'k'] = $resitem['itmk'];
				$pd[$whicharmor.'e'] = $resitem['itme'];
				$pd[$whicharmor.'s'] = $resitem['itms'];
				$pd[$whicharmor.'sk'] = $resitem['itmsk'];
			}
			else $chprocess($pa,$pd,$active,$whicharmor);
		}
		else $chprocess($pa,$pd,$active,$whicharmor);
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$ret = $chprocess($cinfo);
		if ($ret) {
			if (strpos($cinfo[0], '^res') === 0) return false;
			if ('^rtype' == $cinfo[0]) return false;
			if ('^reptype' == $cinfo[0]) return false;
		}
		return $ret;
	}

}

?>
