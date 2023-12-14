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
		$itemspkinfo['^arn'] = '原防具名';//不会显示
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
		
		//叠甲外甲的判定
		//仅在对应部位有装备且要换上的装备是空外甲才会走这段判定，如果要换上的是内外双甲或者身上没穿则直接走$chprocess()的替换防具流程
		if (false !== strpos(substr($itmk,2),'S') && !\itemmain\check_in_itmsk('^su', $itmsk))
		{
			if ((!empty($noeqp) && strpos(${$pos.'k'}, $noeqp) !== 0) || ${$pos.'s'})
			{
				$positem = array('itm' => &${$pos}, 'itmk' => &${$pos.'k'}, 'itme' => &${$pos.'e'},'itms' => &${$pos.'s'},'itmsk' => &${$pos.'sk'});
				//$getitem = array('itm' => &$itm0, 'itmk' => &$itmk0, 'itme' => &$itme0,'itms' => &$itms0,'itmsk' => &$itmsk0);
				
				$tmpitem = Array();
				//清除原位置的外甲数据，并保存。由于可能从itm0装备道具，此时放到一个暂存的变量里
				$result = armor_remove_su($positem, $tmpitem);
				
				$o_posname = ${$pos};
				
				//由于记录外甲属性、添加临时属性和替换效果值一定是绑定的，提取出来做一个核心函数
				use_armor_ext_armor_process($theitem, $positem);
				
				//清除来源数据
				$o_itm = $itm;
				$itm = $itmk = $itmsk = '';
				$itme = $itms = 0;
							
				if (1 === $result)
				{	
					//把暂存的变量放入itm0
					$itm0 = $tmpitem['itm'];
					$itmk0 = $tmpitem['itmk'];
					$itme0 = $tmpitem['itme'];
					$itms0 = $tmpitem['itms'];
					$itmsk0 = $tmpitem['itmsk'];
					
					$log .= "你脱下了<span class=\"yellow b\">$itm0</span>，然后在<span class=\"yellow b\">$o_posname</span>外面套上了<span class=\"yellow b\">$o_itm</span>。<br>";
					\itemmain\itemget();
				}
				else $log .= "你在<span class=\"yellow b\">$o_posname</span>外面套上了<span class=\"yellow b\">$o_itm</span>。<br>";
				
				return;
			}
		}	
		$chprocess($theitem, $pos);
	}
	
	//使用外甲数据来修改防具部位数据的函数，包含修改道具名、记录外甲数据、临时属性和效果耐久值四个功能
	//传入&$theitem, &$positem两个道具数组，数组元素都应该是引用对应的数值，类似itemmain模块的$theitem参数的结构
	function use_armor_ext_armor_process(&$theitem, &$positem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','armor'));
		
		if(empty($theitem['itms']) || empty($positem['itms'])) return; //防呆措施
		
		//把外甲数据记录到装备属性
		$positem['itmsk'] = armor_put_su($positem['itmsk'], $theitem);
		
		//如果外甲有属性，把属性临时写到装备属性位
		$itmsk_arr = \itemmain\get_itmsk_array($theitem['itmsk']);
		$itmsk_arr = array_diff($itmsk_arr, Array('Z'));//不会把外甲的菁英属性写到装备属性位
		if (!empty($itmsk_arr)){
			$positem['itmsk'] .= '|'.implode('', $itmsk_arr).'|';
		}
		
		//根据装备外甲后，损耗的是效果还是耐久，记录原防具效果值或耐久值
		if ($theitem['itms'] === $nosta)
		{
			//外甲无限耐，则记录原防具效果值
			$positem['itmsk'] .= '^are'.$positem['itme'];
		}
		else
		{
			//外甲耐久有限
			//如果原防具是无限耐，记录原防具耐久为0，并把原防具耐久值改成外甲耐久值
			if ($positem['itms'] === $nosta)
			{
				$positem['itmsk'] .= '^ars0';
				$positem['itms'] = $theitem['itms'];
			}				
			else//原防具耐久也有限，则记录原防具耐久，并把外甲耐久加到原防具耐久值上
			{
				$positem['itmsk'] .= '^ars'.$positem['itms'];
				$positem['itms'] += $theitem['itms'];
			}
		}			
		//之后，把外甲效果值加到原防具效果值上，这个顺序能保证数据不出错
		$positem['itme'] += $theitem['itme'];
		//修改道具名成为外甲（内部衣服）的形式，并保存原有的道具名
		armor_changename_to_ext($theitem, $positem);
	}
	
	//修改道具名成为外甲（内部衣服）的形式，并保存原有的道具名
	function armor_changename_to_ext(&$theitem, &$positem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(!empty(\itemmain\check_in_itmsk('^arn', $positem['itmsk']))) {
			$positem['itmsk'] = \itemmain\replace_in_itmsk('^arn','',$positem['itmsk']);
		}
		$positem['itmsk'] .= '^arn_'.\attrbase\base64_encode_comp_itmsk($positem['itm']).'1';
		$positem['itm'] = $theitem['itm'].'('.$positem['itm'].')';
		return;
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
					else $x = $hurtvalue;
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
						else $x = $hurtvalue;
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
		return $chprocess($pa, $pd, $active, $which, $hurtvalue);
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
		
		//恢复原来的护甲名
		$null = NULL;
		$positem = Array('itm' => &$pd[$whicharmor], 'itmsk' => &$pd[$whicharmor.'sk']);
		armor_changename_from_ext($positem, $null);
		
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
			
			$positem = array('itm' => &${'ar'.$itmn}, 'itmk' => &${'ar'.$itmn.'k'}, 'itme' => &${'ar'.$itmn.'e'},'itms' => &${'ar'.$itmn.'s'},'itmsk' => &${'ar'.$itmn.'sk'});
			$getitem = array('itm' => &$itm0, 'itmk' => &$itmk0, 'itme' => &$itme0,'itms' => &$itms0,'itmsk' => &$itmsk0);
			
			$result = armor_remove_su($positem, $getitem);
			if (1 === $result)
			{
				$log .= "你卸下了外甲<span class=\"yellow b\">$itm0</span>。<br>";
				\itemmain\itemget();
				return;
			}
		}
		$chprocess($item);
	}
		
	//在尸体上拾取防具时，如果包含外甲，则仅拾取外甲
	function getcorpse_action(&$edata, $item)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		if(strpos($item,'ar') === 0)
		{
			$positem = array('itm' => &$edata[$item], 'itmk' => &$edata[$item.'k'], 'itme' => &$edata[$item.'e'],'itms' => &$edata[$item.'s'],'itmsk' => &$edata[$item.'sk']);
			$getitem = array('itm' => &$itm0, 'itmk' => &$itmk0, 'itme' => &$itme0,'itms' => &$itms0,'itmsk' => &$itmsk0);
			
			$result = armor_remove_su($positem, $getitem);
			if (1 === $result)
			{
				\itemmain\itemget();
				$mode = 'command';
				return;
			}
		}
		$chprocess($edata, $item);
	}
	
	//从外甲属性字符串返回外甲道具数组
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
	
	//把外甲数据覆盖到$itmsk字符串并返回，并没有改变原值
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
	
	//将一件防具上的外甲移动到另一个道具位，通常用于卸下外甲到itm0
	function armor_remove_su(&$positem, &$getitem){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('armor'));
		$suitem = armor_get_su($positem['itmsk']);			
		if ($suitem)
		{
			armor_clean_suit_sk($positem['itmsk']);
			$positem['itmsk'] = \itemmain\replace_in_itmsk('^su','',$positem['itmsk']);	
			
			$getitem['itm'] = $suitem['itm'];
			$getitem['itmk'] = $suitem['itmk'];
			$getitem['itme'] = $suitem['itme'];
			$getitem['itms'] = $suitem['itms'];
			$getitem['itmsk']= $suitem['itmsk'];
			$are = \itemmain\check_in_itmsk('^are', $positem['itmsk']);
			//如果记录了原防具的效果值，则先恢复防具效果到记录值
			if (false !== $are)
			{
				$getitem['itme'] = max($positem['itme'] - $are, 0);
				$positem['itme'] = $are;
				$positem['itmsk'] = \itemmain\replace_in_itmsk('^are','',$positem['itmsk']);
			}
			else
			{
				$positem['itme'] -= $getitem['itme'];
				if ($positem['itme'] < 0) $positem['itme'] = 0;
				//如果记录了原防具的耐久值，则先恢复防具耐久到记录值
				$ars = \itemmain\check_in_itmsk('^ars', $positem['itmsk']);
				if (false === $ars) $ars = 1;
				if (0 === (int)$ars)
				{
					$getitem['itms'] = $positem['itms'];
					$positem['itms'] = $nosta;
				}
				else
				{
					$getitem['itms'] = max($positem['itms'] - $ars, 1);
					$positem['itms'] = $ars;
				}
				$positem['itmsk'] = \itemmain\replace_in_itmsk('^ars','',$positem['itmsk']);
			}
			//恢复原防具的名字
			armor_changename_from_ext($positem, $getitem);
			return 1;
		}
		return 0;
	}
	
	//把道具名恢复成内部衣服
	function armor_changename_from_ext(&$positem, &$getitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$arn = \itemmain\check_in_itmsk('^arn', $positem['itmsk']);
		if(!empty($arn))
			$positem['itm'] = \attrbase\base64_decode_comp_itmsk($arn);
		$positem['itmsk'] = \itemmain\replace_in_itmsk('^arn','',$positem['itmsk']);
	}
	
	//清除目标属性字符串的临时属性
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
			if(substr($cinfo[0],0,4) ==='^arn') return false;
			if('^are' == $cinfo[0]) return false;
			if('^ars' == $cinfo[0]) return false;
		}
		return $ret;
	}
	
	//必须先把外甲拆开才能丢弃
	function itemdrop_valid_check($itm, $itmk, $itme, $itms, $itmsk, $itmpos)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(\itemmain\check_in_itmsk('^su', $itmsk)){
			eval(import_module('logger'));
			$log .= '<span class="yellow b">必须先把外甲卸下来才能丢弃！</span><br>';
			return false;
		}
		return $chprocess($itm, $itmk, $itme, $itms, $itmsk, $itmpos);
	}
	
	//NPC载入时，如果存在外甲数据，自动装上外甲
	function init_npcdata($npc, $plslist=array()){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		$npc = $chprocess($npc, $plslist);
		if(!empty($npc['ext_armor'])) {
			//为了性能，把栏位写死。另外武器和饰物一定不可能是外甲，跳过
			$equip_list = Array('arb', 'arh', 'ara', 'arf', 'itm0', 'itm1', 'itm2', 'itm3', 'itm4', 'itm5', 'itm6');
			foreach($equip_list as $pos) {
				if(!empty($npc['ext_armor'][$pos])) {
					if(strpos($pos, 'itm')===0) {
						$n = substr($pos, 3);
						$ik = 'itmk'.$n; $ie = 'itme'.$n; $is = 'itms'.$n; $isk = 'itmsk'.$n;
					}else{
						$ik = $pos.'k'; $ie = $pos.'e'; $is = $pos.'s'; $isk = $pos.'sk';
					}
					if(substr($npc[$ik],0,1) != 'D' || substr($npc['ext_armor'][$ik],0,1) != 'D' || substr($npc['ext_armor'][$ik],2,1) != 'S') continue;//不是防具，跳过
					
					//数据初始化防呆措施，防止没有写属性值的数据出错。
					if(empty($npc[$pos])) $npc[$pos] = '';
					if(empty($npc[$ik])) $npc[$ik] = '';
					if(empty($npc[$ie])) $npc[$ie] = 0;
					if(empty($npc[$is])) $npc[$is] = 0;
					if(empty($npc[$isk])) $npc[$isk] = '';
					
					//外甲的名字类别没写就根本不会到这里。耐久没写就跳过
					if(empty($npc['ext_armor'][$ie])) $npc['ext_armor'][$ie] = 0;
					if(empty($npc['ext_armor'][$is])) continue;//如果你中了这招说明你是天然呆
					if(empty($npc['ext_armor'][$isk])) $npc['ext_armor'][$isk] = '';
					
					$theitem = Array(
						'itm' => &$npc['ext_armor'][$pos],
						'itmk' => &$npc['ext_armor'][$ik],
						'itme' => &$npc['ext_armor'][$ie],
						'itms' => &$npc['ext_armor'][$is],
						'itmsk' => &$npc['ext_armor'][$isk]
					);
					$positem = Array(
						'itm' => &$npc[$pos],
						'itmk' => &$npc[$ik],
						'itme' => &$npc[$ie],
						'itms' => &$npc[$is],
						'itmsk' => &$npc[$isk]
					);
					use_armor_ext_armor_process($theitem, $positem);
				}
			}
		}
		return $npc;
	}
	
	//加入视野时处理外甲显示
//	function add_memory_itm_process($marr, &$pa=NULL){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		$ret = $chprocess($marr, $pa);
//		if(!empty($ret['itmsk'])) {
//			$ret['itm'] = parse_ext_armor_words($ret['itm'], '', $ret['itmsk'])[0];
//			if(strpos($ret['itm'], '<!--CRLF-->')!==false) $ret['itm'] = str_replace('<!--CRLF-->', '', $ret['itm']);
//		}
//		return $ret;
//	}
}

?>