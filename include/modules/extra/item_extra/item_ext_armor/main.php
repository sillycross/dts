<?php

namespace item_ext_armor
{
	function init() {
		eval(import_module('itemmain'));
		$iteminfo['DBS'] = '身体外甲';
		$iteminfo['DHS'] = '头部外甲';
		$iteminfo['DAS'] = '手臂外甲';
		$iteminfo['DFS'] = '腿部外甲';		
		$itemspkinfo['^su'] = '外甲';
		$itemspkdesc['^su'] = '当前装备外甲的信息为：<:skn:>';
		$itemspkinfo['^are'] = '原防具效果值';//不会显示
		$itemspkinfo['^ars'] = '原防具耐久值';//不会显示
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if (strpos(substr($k, 2), 'S') !== false)
		{
			$ret .= '这一防具可以叠加装备在相同位置的防具上';
		}
		return $ret;
	}
	
	//显示外甲信息
	function get_itmsk_desc_single_comp_process($skk, $skn, $sks) {
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$skn = $chprocess($skk, $skn, $sks);
		if(strpos($skk, '^su')===0) {
			$skarr = explode(',',\attrbase\base64_decode_comp_itmsk($sks));
			$itm = $skarr[0];
			$itmk_words = \itemmain\parse_itmk_words($skarr[1]);
			$itme = $skarr[2];
			$itms = $skarr[3];
			$itmsk_words = \itemmain\parse_itmsk_words($skarr[4]);
			$skn = $itm.'/'.$itmk_words.'/'.$itme.'/'.$itms.(!empty($itmsk_words) ? '/'.$itmsk_words : '');
		}
		return $skn;
	}
	
	//使用外甲时，如果防具已经包含外甲，则替换此外甲；如果防具不包含外甲，则使该防具装备外甲；如果未装备防具，则直接作为防具穿上
	function use_armor(&$theitem, $pos = '')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','armor','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
				
		if(!$pos) {
			if(strpos ( $itmk, 'DB' ) === 0) {
				$pos = 'arb';
				$noeqp = 'DN';
			}elseif(strpos ( $itmk, 'DH' ) === 0) {
				$pos = 'arh';
				$noeqp = '';
			}elseif(strpos ( $itmk, 'DA' ) === 0) {
				$pos = 'ara';
				$noeqp = '';
			}elseif(strpos ( $itmk, 'DF' ) === 0) {
				$pos = 'arf';
				$noeqp = '';
			}
		}		
		
		//如果要穿的外甲也包含外甲，则会替换当前防具
		if (false !== strpos(substr($itmk,2),'S') && !\itemmain\check_in_itmsk('^su', $itmsk))
		{
			if ((!empty($noeqp) && strpos(${$pos.'k'}, $noeqp) !== 0) || ${$pos.'s'})
			{
				$suitem = armor_get_su(${$pos.'sk'});
				armor_clean_suit_sk(${$pos.'sk'});
				${$pos.'sk'} = armor_put_su(${$pos.'sk'}, $theitem);
				$itmsk_arr = \itemmain\get_itmsk_array($itmsk);
				if (!empty($itmsk_arr)){
					${$pos.'sk'} .= '|'.implode('', $itmsk_arr).'|';
				}
				
				if ($suitem)
				{
					$itm0 = $suitem['itm'];
					$itmk0 = $suitem['itmk'];
					$itme0 = $suitem['itme'];
					$itms0 = $suitem['itms'];
					$itmsk0 = $suitem['itmsk'];
					$are = \itemmain\check_in_itmsk('^are', ${$pos.'sk'});
					//如果记录了原防具的效果值，则先恢复防具效果到记录值
					if (false !== $are)
					{
						$itme0 = max(${$pos.'e'} - $are, 0);
						${$pos.'e'} = $are;
						${$pos.'sk'} = \itemmain\replace_in_itmsk('^are','',${$pos.'sk'});
					}
					else
					{
						${$pos.'e'} -= $itme0;
						if (${$pos.'e'} < 0) ${$pos.'e'} = 0;
						//如果记录了原防具的耐久值，则先恢复防具耐久到记录值
						$ars = \itemmain\check_in_itmsk('^ars', ${$pos.'sk'});
						if (false === $ars) $ars = 1;
						if (0 === (int)$ars)
						{
							$itms0 = ${$pos.'s'};
							${$pos.'s'} = $nosta;
						}
						else
						{
							$itms0 = max(${$pos.'s'} - $ars, 1);
							${$pos.'s'} = $ars;
						}
						${$pos.'sk'} = \itemmain\replace_in_itmsk('^ars','',${$pos.'sk'});
					}
				}
				
				//根据装备外甲后，损耗的是效果还是耐久，记录效果值或耐久值
				if ($itms === $nosta)
				{
					${$pos.'sk'} .= '^are'.${$pos.'e'};
				}
				else
				{
					if (${$pos.'s'} === $nosta)
					{
						${$pos.'sk'} .= '^ars'.'0';
						${$pos.'s'} = $itms;
					}				
					else
					{
						${$pos.'sk'} .= '^ars'.${$pos.'s'};
						${$pos.'s'} += $itms;
					}
				}			
				${$pos.'e'} += $itme;
							
				if ($suitem)
				{	
					$log .= "你脱下了<span class=\"yellow b\">$itm0</span>，然后在<span class=\"yellow b\">${$pos}</span>外面套上了<span class=\"yellow b\">$itm</span>。<br>";
					\itemmain\itemget();
				}
				else $log .= "你在<span class=\"yellow b\">${$pos}</span>外面套上了<span class=\"yellow b\">$itm</span>。<br>";
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
				return;
			}
		}	
		$chprocess($theitem, $pos);
	}
	
	//防具受损时，检测外甲是否完全损坏
	function armor_hurt(&$pa, &$pd, $active, $which, $hurtvalue)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('armor','wound','logger'));
		if (in_array($which,$armor_equip_list) && isset($pd[$which.'e']) && $pd[$which.'e']>0)	//有防具
		{
			$suitem = armor_get_su($pd[$which.'sk']);
			if ($suitem)
			{
				$su = $suitem['itm'];
				$suk = $suitem['itmk'];
				$sue = $suitem['itme'];
				$sus = $suitem['itms'];
				$susk = $suitem['itmsk'];
				$su_break_flag = 0;
				$are = \itemmain\check_in_itmsk('^are', $pd[$which.'sk']);
				if (false !== $are)
				{
					if ($hurtvalue >= $pd[$which.'e'] - $are)
					{
						$x = max($pd[$which.'e'] - $are, 1);
						$su_break_flag = 1;
					}
					$pd[$which.'e'] -= $x;
					if ($active)
					{
						$log .= "{$pd['name']}的外甲".$su."的效果值下降了{$x}！<br>";
					}
					else
					{
						$log .= "你的外甲".$su."的效果值下降了{$x}！<br>";
					}					
					if (1 === $su_break_flag)
					{
						suit_break($pa, $pd, $active, $which);
					}
				}
				else
				{
					$ars = \itemmain\check_in_itmsk('^ars', $pd[$which.'sk']);
					if (false === $ars) $ars = 1;
					if (0 === (int)$ars)
					{
						$x = min($pd[$which.'s'], $hurtvalue);
						$pd[$which.'s'] -= $x;
						if ($pd[$which.'s']<=0)
						{
							$pd[$which.'s'] = $nosta;
							$pd[$which.'e'] -= $sue;
							if ($pd[$which.'e'] < 0) $pd[$which.'e'] = 0;
							if ($active)
							{
								$log .= "{$pd['name']}的外甲".$su."的耐久度下降了{$x}！<br>";
							}
							else
							{
								$log .= "你的外甲".$su."的耐久度下降了{$x}！<br>";
							}
							suit_break($pa, $pd, $active, $which);
						}
					}
					else
					{		
						if ($hurtvalue >= $pd[$which.'s'] - $ars)
						{
							$x = max($pd[$which.'s'] - $ars, 1);
							$su_break_flag = 1;
						}
						if ($active)
						{
							$log .= "{$pd['name']}的外甲".$su."的耐久度下降了{$x}！<br>";
						}
						else
						{
							$log .= "你的外甲".$su."的耐久度下降了{$x}！<br>";
						}
						$pd[$which.'s'] -= $x;					
						if (1 === $su_break_flag)
						{
							$pd[$which.'e'] -= $sue;
							if ($pd[$which.'e'] < 0) $pd[$which.'e'] = 0;
							suit_break($pa, $pd, $active, $which);
						}
					}
				}
				return $x;
			}
		}
		$chprocess($pa, $pd, $active, $which, $hurtvalue);
	}
	
	//外甲损坏的效果和耐久值处理在armor_hurt中完成
	function suit_break(&$pa, &$pd, $active, $whicharmor)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		
		eval(import_module('logger'));
		$suitem = armor_get_su($pd[$whicharmor.'sk']);
		if ($active)
		{
			$log .= "{$pd['name']}的外甲<span class=\"red b\">".$suitem['itm']."</span>受损过重，无法再装备了！<br>";
			$pd['armorbreaklog'] .= "你的外甲<span class=\"red b\">".$suitem['itm']."</span>受损过重，无法再装备了！<br>";
		}
		else  $log .= "你的外甲<span class=\"red b\">".$suitem['itm']."</span>受损过重，无法再装备了！<br>";
		
		$pd[$whicharmor.'sk'] = \itemmain\replace_in_itmsk('^su', '', $pd[$whicharmor.'sk']);
		$pd[$whicharmor.'sk'] = \itemmain\replace_in_itmsk('^are', '', $pd[$whicharmor.'sk']);
		$pd[$whicharmor.'sk'] = \itemmain\replace_in_itmsk('^ars', '', $pd[$whicharmor.'sk']);
		armor_clean_suit_sk($pd[$whicharmor.'sk']);		
	}
	
	//卸下防具时，如果防具包含外甲，则优先卸下外甲
	function itemoff($item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if (strpos($item, 'ar') === 0)
		{
			eval(import_module('player','logger','armor'));
			$itmn = substr($item,2,1);
			$itm = & ${'ar'.$itmn};
			$itmk = & ${'ar'.$itmn.'k'};
			$itme = & ${'ar'.$itmn.'e'};
			$itms = & ${'ar'.$itmn.'s'};
			$itmsk = & ${'ar'.$itmn.'sk'};
			
			if(!\itemmain\itemoff_valid_check($itm, $itmk, $itme, $itms, $itmsk))
			{
				$mode = 'command';
				return;
			}
			
			$suitem = armor_get_su($itmsk);
			if ($suitem)
			{
				armor_clean_suit_sk($itmsk);
				$itmsk = \itemmain\replace_in_itmsk('^su','',$itmsk);
				
				$itm0 = $suitem['itm'];
				$itmk0 = $suitem['itmk'];
				$itme0 = $suitem['itme'];
				$itms0 = $suitem['itms'];
				$itmsk0 = $suitem['itmsk'];
				$log .= "你卸下了外甲<span class=\"yellow b\">$itm0</span>。<br>";
				
				$are = \itemmain\check_in_itmsk('^are', $itmsk);
				if (false !== $are)
				{			
					$itme0 = max($itme - $are,0);
					$itme = $are;
					$itmsk = \itemmain\replace_in_itmsk('^are','',$itmsk);
				}
				else
				{
					$itme -= $itme0;
					if ($itme < 0) $itme = 0;
					$ars = \itemmain\check_in_itmsk('^ars', $itmsk);
					if (false === $ars) $ars = 1;
					if (0 === (int)$ars)
					{
						$itms0 = $itms;
						$itms = $nosta;
					}
					else
					{
						$itms0 = max($itms - $ars,1);
						$itms = $ars;
					}
					$itmsk = \itemmain\replace_in_itmsk('^ars','',$itmsk);
				}
				\itemmain\itemget();
				return;
			}
		}
		$chprocess($item);
	}
	
	function armor_get_su($itmsk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = Array();
		$sus = \itemmain\check_in_itmsk('^su', $itmsk);
		if(empty($sus)) return $ret;
		$sus = \attrbase\base64_decode_comp_itmsk($sus);
		
		if(!empty($sus)) {
			$suarr = explode(',', $sus);
			$ret = Array(
				'itm' => $suarr[0],
				'itmk' => $suarr[1],
				'itme' => $suarr[2],
				'itms' => $suarr[3],
				'itmsk' => $suarr[4]
			);
		}
		return $ret;
	}
	
	function armor_put_su($itmsk, $suarr){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		$nowsus = \itemmain\check_in_itmsk('^su', $itmsk);
		if(!empty($nowsus)) {
			$itmsk = \itemmain\replace_in_itmsk('^su','',$itmsk);
		}
		if(!empty($suarr)){
			$sus = $suarr['itm'].','.$suarr['itmk'].','.$suarr['itme'].','.$suarr['itms'].','.$suarr['itmsk'];
			$itmsk .= '^su_'.\attrbase\base64_encode_comp_itmsk($sus).'1';
		}
		return $itmsk;
	}
	
	function armor_clean_suit_sk(&$itmsk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(strpos($itmsk,'|')===false) return '';
		if(substr_count($itmsk, '|') % 2) $itmsk .= '|';
		preg_match('/\|.*\|/s',$itmsk,$matches);
		$ret = '';
		if(!empty($matches)) {
			$itmsk = preg_replace('/\|.*?\|/s','',$itmsk);
			$ret = substr($matches[0], 1, -1);
		}
		return $ret;
	}
	
	//判定复合属性是否显示
	function check_comp_itmsk_visible($cinfo){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($cinfo);
		if($ret) {
			if('^are' == $cinfo[0]) return false;
			if('^ars' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
	//外甲道具名的显示
	function parse_item_words($edata, $simple = 0, $elli = 0)	
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($edata, $simple, $elli);
		eval(import_module('armor','player'));
		if($edata['pid'] == $pid) $selflag = 1;
		foreach($armor_equip_list as $pos) {
			$sus = \itemmain\check_in_itmsk('^su', $edata[$pos.'sk']);
			if(!empty($sus)) {
				$sus = \attrbase\base64_decode_comp_itmsk($sus);
				if(!empty($sus)) {
					$susitm = explode(',', $sus)[0];
					$itm = \itemmain\parse_itmname_words($susitm, $elli);
					$itm_short = \itemmain\parse_itmname_words($susitm, 1, 15);
					$ret[$pos.'_words'] = $itm . (!empty($selflag) ? '<br>' : '') . '(' . $ret[$pos.'_words'] . ')'; //如果是玩家界面的调用，换个行
					$ret[$pos.'_words_short'] = $itm_short . (!empty($selflag) ? '<br>' : '') . '(' . $ret[$pos.'_words_short'] . ')';
				}
			}
		}
		return $ret;
	}
}

?>
