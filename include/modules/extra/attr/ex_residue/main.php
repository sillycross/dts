<?php

namespace ex_residue
{
	function init()
	{
		eval(import_module('itemmain'));
		$itemspkinfo['^res'] = '余物';//不显示
		$itemspkinfo['^rtype'] = '余物属性类型';//1:消耗完后得到新的物品；2:使用时视为使用记录的物品，用完后变为原物品；3:使用时视为使用记录的物品，原物品并不存在
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
	
	//类型1
	function itms_reduce(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain','logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (1 == (int)\itemmain\check_in_itmsk('^rtype', $itmsk))
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
					elseif (defined('MOD_SEARCHMEMORY') && defined('MOD_SKILL1006'))
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
				}
			}
			else $chprocess($theitem);
		}
		else $chprocess($theitem);
	}
	
	//这个在itemmix
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
		
		if (1 == (int)\itemmain\check_in_itmsk('^rtype', $itmsk))
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
				elseif (defined('MOD_SEARCHMEMORY') && defined('MOD_SKILL1006'))
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
				return;
			}
			else $chprocess($item);
		}
		else $chprocess($item);
	}
	
	//类型2和3
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('itemmain','logger'));
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		$rtype = (int)\itemmain\check_in_itmsk('^rtype', $itmsk);
		if (in_array($rtype, array(2,3)))
		{
			$resitem = get_res_itm($itmsk);
			if (!empty($resitem))
			{
				$log .= "你使用了<span class=\"yellow b\">$itm</span>，但却意外地使用了道具中内藏的物品！<br>";
				$chprocess($resitem);
				if ($resitem['itms'] > 0) $itmsk = put_res_itm($itmsk, $resitem);
				//resitem用完时判断是否保留原物品
				elseif (2 == $rtype)
				{
					$log .= "<span class=\"yellow b\">$itm</span>变得没有什么特别了。<br>";
					$itmsk = \itemmain\replace_in_itmsk('^res','',$itmsk);
					$itmsk = \itemmain\replace_in_itmsk('^rtype','',$itmsk);
				}
				else
				{
					$log .= "<span class=\"red b\">$itm</span>也随之烟消云散了。<br>";
					$itm = $itmk = $itmsk = '';
					$itme = $itms = 0;
				}
			}
			else $chprocess($theitem);
		}
		else $chprocess($theitem);
	}

	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$ret = $chprocess($cinfo);
		if ($ret) {
			if (strpos($cinfo[0], '^res') === 0) return false;
			if ('^rtype' == $cinfo[0]) return false;
		}
		return $ret;
	}

}

?>
