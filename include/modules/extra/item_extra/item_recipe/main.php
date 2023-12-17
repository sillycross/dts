<?php

namespace item_recipe
{
	$mix_type = array('link' => '连接');
	
	function init() 
	{
		eval(import_module('itemmain'));
		$iteminfo['R'] = '配方';
		$itemspkinfo['^l'] = '连接';
		$itemspkdesc['^l'] = '这一道具是连接合成的产物，并可作为连接合成的素材';
		$itemspkinfo['^l1'] = '连接1';
		$itemspkinfo['^l2'] = '连接2';
		$itemspkinfo['^l3'] = '连接3';
		$itemspkinfo['^l4'] = '连接4';
		$itemspkinfo['^l5'] = '连接5';
		$itemspkinfo['^l6'] = '连接6';
	}

	function itemuse(&$theitem) 
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		$itm = &$theitem['itm'];
		$itmk = &$theitem['itmk'];
		
		if (strpos($itmk, 'R') === 0)
		{
			eval(import_module('logger'));
			$log .= "你打开了配方<span class=\"yellow b\">$itm</span>。<br>";
			ob_start();
			include template(MOD_ITEM_RECIPE_USE_RECIPE);
			$cmd = ob_get_contents();
			ob_end_clean();
			return;
		}
		$chprocess($theitem);
	}

	function generate_single_itemtip($stuff)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('itemmain'));
		if (isset($stuff['tips'])) return $stuff['tips'];
		$s = '';
		if (isset($stuff['itm_match']))
		{
			if (0 === $stuff['itm_match']) $s .= '名称与“'.$stuff['itm'].'”完全一致的';
			else if (1 === $stuff['itm_match']) $s .= '名称包含“'.$stuff['itm'].'”的';
			else if (2 === $stuff['itm_match']) $s .= '名称为“'.$stuff['itm'].'”的';
		}
		if (isset($stuff['itmsk_match']))
		{
			if (0 === $stuff['itmsk_match']) $s .= '属性仅有“'.$itemspkinfo[$stuff['itmsk']].'”的';
			else if (1 === $stuff['itmsk_match']) $s .= '属性包含“'.$itemspkinfo[$stuff['itmsk']].'”的';
		}
		$flag = 0;
		if (isset($stuff['itmk_match']))
		{
			if (0 === $stuff['itmk_match'])
			{
				$s .= $iteminfo[$stuff['itmk']];
				$flag = 1;
			}
			else if (1 === $stuff['itmk_match'])
			{
				//我觉得没法写出区别
				$s .= $iteminfo[$stuff['itmk']];
				$flag = 1;
			}
			//理论上匹配方式2只用来判断游戏王星级
			else if (2 === $stuff['itmk_match']) $s .= '星级为'.(int)$stuff['itmk'].'的';
		}
		if (isset($stuff['extra']))
		{
			$flag = 1;
			if ('ygo' === $stuff['extra']) $s .= '游戏王道具';
			else if ('edible' === $stuff['extra']) $s .= '回复道具';
			else if ('weapon' === $stuff['extra']) $s .= '武器';
			else if ('armor' === $stuff['extra']) $s .= '防具';	
		}
		if (0 === $flag) $s .= '道具';
		if (isset($stuff['if_consume']) && (false === $stuff['if_consume'])) $s .= '（不消耗）';
		return $s;
	}

	function show_recipe($recipekey)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$recipe_tip = '';
		eval(import_module('item_recipe'));
		if (isset($recipe_mixinfo[$recipekey])) $recipe = $recipe_mixinfo[$recipekey];
		else return $recipe_tip;
		$flag = 0;
		for ($i=1;$i<=5;$i++)
		{
			if (isset($recipe['stuff'.$i]))
			{
				$recipe_tip .= generate_single_itemtip($recipe['stuff'.$i]).'<br>+ ';
				$flag = 1;
			}
		}
		if (1 === $flag) $recipe_tip = substr($recipe_tip,0,-2);
		if (isset($recipe['stuffa']))
		{
			if (1 === $flag) $recipe_tip .= '其余';
			$recipe_tip .= '素材为：'.generate_single_itemtip($recipe['stuffa']).'<br>';
		}
		if (isset($recipe['extra']))
		{
			if (isset($recipe['extra']['link'])) $recipe_tip .= '连接'.$recipe['extra']['link'].'，';
			if (isset($recipe['extra']['materials']))
			{
				$recipe_tip .= '素材数';
				if (0 === strpos($recipe['extra']['materials'],'>')) $recipe_tip .= '不少于'.((int)substr($recipe['extra']['materials'],1)+1).'，';
				else $recipe_tip .= '为'.$recipe['extra']['materials'].'，';
			}
			if (isset($recipe['extra']['allow_repeat']) && (false === $recipe['extra']['allow_repeat'])) $recipe_tip .= '素材不允许重复，';
			if (isset($recipe['extra']['consume_recipe']) && (true === $recipe['extra']['consume_recipe'])) $recipe_tip .= '消耗配方，';			
		}
		
		$recipe_tip .= '<br>合成结果：<br>'.\itemmix\parse_itemmix_resultshow($recipe['result']);
		
		if(sizeof(debug_backtrace()) > 573) return '<span class="red b">你写出死循环了，笨蛋！如果你不是这个笨蛋，请通知那个笨蛋程序员。</span>';
		if (('R' === $recipe['result'][1]) && isset($recipe['result'][4])) {
			$recipe_tip .= '<br><br>下一级配方公式为：<br>'.show_recipe($recipe['result'][4]);
		}
		
		return $recipe_tip;
	}

	function check_single_item($itm, $itmk, $itmsk, $stuff)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		//名称判定
		if (isset($stuff['itm_match']))
		{
			if (0 === $stuff['itm_match'])
			{
				if ($itm !== $stuff['itm']) return false;
			}
			else if (1 === $stuff['itm_match'])
			{
				if (false === strpos($itm, $stuff['itm'])) return false;
			}
			else if (2 === $stuff['itm_match'])
			{
				$itm = \itemmix\itemmix_name_proc($itm);
				if ($itm !== $stuff['itm']) return false;
			}
		}

		//类别判定
		if (isset($stuff['itmk_match']))
		{
			if (0 === $stuff['itmk_match'])
			{
				if ($itmk !== $stuff['itmk']) return false;
			}
			else if (1 === $stuff['itmk_match'])
			{
				if (0 !== strpos($itmk, $stuff['itmk'])) return false;
			}
			else if (2 === $stuff['itmk_match'])
			{
				if (substr($itmk, -strlen($stuff['itmk'])) !== $stuff['itmk']) return false;
			}
		}
		
		//属性判定
		if (isset($stuff['itmsk_match']))
		{
			if (0 === $stuff['itmsk_match'])
			{
				if ($itmsk !== $stuff['itmsk']) return false;
			}
			else if (1 === $stuff['itmsk_match'])
			{
				if (false === \itemmain\check_in_itmsk($stuff['itmsk'], $itmsk)) return false;
			}
		}
		
		//额外判定
		if (isset($stuff['extra']))
		{
			if (false === check_item_extra($itm, $itmk, $itmsk, $stuff['extra'])) return false;
		}
		return true;
	}
	
	function check_item_extra($itm, $itmk, $itmsk, $extra)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		if ('ygo' === $extra)
		{
			//有星级视作游戏王卡，肥料当然是游戏王卡
			if (\itemmix_sync\itemmix_get_star($itmk) > 0) return true;
			//连接卡视作游戏王卡
			if (false !== \itemmain\check_in_itmsk('^l', $itmsk)) return true;
			//超量怪视作游戏王卡
			if (false !== \itemmain\check_in_itmsk('^xyz', $itmsk)) return true;
			// $itm = \itemmix\itemmix_name_proc($itm);
			// $prp_res = \itemmix_overlay\itemmix_prepare_overlay();
			// foreach($prp_res as $pra){
				// if (0 === strpos($pra[0], $itm)) return true;
			// }
		}
		else if ('edible' === $extra)
		{
			if (in_array($itmk[0], array('H','P'))) return true;
		}
		else if ('weapon' === $extra)
		{
			if (0 === strpos($itmk, 'W')) return true;
		}
		else if ('armor' === $extra)
		{
			if (0 === strpos($itmk, 'D')) return true;
		}
		return false;
	}

	function check_recipe_extra($mlist, $recipe_extra)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (isset($recipe_extra['link']))
		{			
			eval(import_module('player'));
			$link_list = array();
			foreach($mlist as $mval)
			{
				$link = \itemmain\check_in_itmsk('^l',${'itmsk'.$mval});
				if (false === $link) $link = 1;
				$link_list[] = $link;
			}
			if (empty($link_list)) return false;
			else
			{
				//每个link值高于1的部分
				$link_count_e = array();
				foreach($link_list as $l)
				{
					if ($l > 1) $link_count_e[] = $l - 1;
				}
				//link数去掉素材数后，是否能被$link_count_e中若干个元素的和表示
				if (!check_sum_possible($recipe_extra['link'] - count($mlist), $link_count_e)) return false;
			}
		}
		if (isset($recipe_extra['materials']))
		{
			if (0 === strpos($recipe_extra['materials'],'>'))
			{
				if (count($mlist) <= (int)substr($recipe_extra['materials'],1)) return false;
			}
			else if(count($mlist) !== (int)$recipe_extra['materials']) return false;
		}
		if (isset($recipe_extra['allow_repeat']) && (false === $recipe_extra['allow_repeat']))
		{
			eval(import_module('player'));
			$namelist = array();
			foreach ($mlist as $val) $namelist[] = ${'itm'.$val};
			$namelist2 = array_unique($namelist);
			if (count($namelist) != count($namelist2)) return false;
		}
		return true;
	}

	function check_sum_possible($sum, $numbers, &$m = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (isset($m[$sum])) return $m[$sum];
		if ($sum === 0) return true;
		if ($sum < 0) return false;
		foreach ($numbers as $number)
		{
			$remainder = $sum - $number;
			if (check_sum_possible($remainder, $numbers, $m))
			{
				$m[$sum] = true;
				return true;
			}
		}
		$m[$sum] = false;
		return false;
	}
	
	function recipe_mix_place_check($mlist, $itmp)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$mlist2 = array_unique($mlist);
		if (empty($mlist) || (count($mlist) > 5))
		{
			$log .= '选择道具数量不正确！';
			return false;
		}
		if (in_array($itmp, $mlist))
		{
			$log .= '配方自身不能作为配方合成的素材！<br>';
			return false;
		}
		if (count($mlist) != count($mlist2))
		{
			$log .= '相同道具不能进行合成！<br>';
			return false;
		}
		foreach($mlist as $val)
		{
			if(!${'itm'.$val})
			{
				$log .= '所选择的道具不存在！';
				return false;
			}
		}
		return true;
	}
		
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','input'));
		if ($mode == 'item' && $usemode == 'recipe') 
		{
			if ($command == 'menu')
			{
				$mode = 'command';
				return;
			} 
			$mixlist = array();
			for($i=1; $i<=6; $i++)
			{
				if(!empty(${'mitm'.$i}))
					$mixlist[] = $i;
			}
			eval(import_module('item_recipe'));
			$minfo = $recipe_mixinfo[${'itmsk'.(int)$itmp}];
			//这里把配方id塞进$minfo，作为使用配方道具合成的标记
			$minfo['key'] = ${'itmsk'.(int)$itmp};
			recipe_mix($mixlist, $itmp, $minfo);
			$command = 'menu';
		}
		if ($mode == 'command' && $command == 'recipe')
		{
			eval(import_module('sys','player'));
			$recipe_choice = get_var_in_module('recipe_choice', 'input');
			if (!empty($recipe_choice))
			{
				$rkey = (int)$recipe_choice;
				ob_start();
				include template(MOD_ITEM_RECIPE_USE_LEARNED_RECIPE);
				$cmd = ob_get_contents();
				ob_end_clean();
				return;
			}
			include template(MOD_ITEM_RECIPE_CHOOSE_RECIPE);
			$cmd = ob_get_contents();
			ob_clean();
			return;
		}
		if ($mode == 'recipe' && $command == 'recipe')
		{
			if ($command == 'menu')
			{
				$mode = 'command';
				return;
			} 
			$mixlist = array();
			for($i=1; $i<=6; $i++)
			{
				if(!empty(${'mitm'.$i}))
					$mixlist[] = $i;
			}
			eval(import_module('item_recipe'));
			$minfo = $recipe_mixinfo[$rkey];
			$ls = get_learned_recipes();
			if (empty($minfo) || !in_array($rkey, $ls))
			{
				eval(import_module('logger'));
				$log .= '输入参数不正确！';
				$mode = 'command';
				return;
			}
			recipe_mix($mixlist, 0, $minfo);
		}
		$chprocess();
	}
	
	function recipe_mix($mlist, $itmp, $minfo)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		
		if (!recipe_mix_place_check($mlist, $itmp)) return;
		
		$seq = get_recipe_mix_sequence($mlist, $minfo);
		if (false === $seq)
		{
			$log .= '选择的素材不满足条件！';
			$mode = 'command';
			return;
		}
		else
		{
			$mixitemname = array();
			foreach($mlist as $val) $mixitemname[] = ${'itm'.$val};
			$uip['itmstr'] = implode(' ', $mixitemname);
			recipe_mix_proc($seq, $itmp, $minfo);
		}
		return;
	}
	
	function recipe_mix_proc($seq, $itmp, $minfo)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','item_recipe'));
		$i = 0;
		foreach($seq as $val)
		{
			$i += 1;
			if (isset($minfo['stuff'.$i]) && (isset($minfo['stuff'.$i]['if_consume'])) && (false === $minfo['stuff'.$i]['if_consume'])) continue;
			if (!isset($minfo['stuff'.$i]) && (isset($minfo['stuffa']['if_consume'])) && (false === $minfo['stuffa']['if_consume'])) continue;
			\itemmix\itemmix_reduce('itm'.$val);
		}
		//配方一次用一张
		if ($itmp && isset($minfo['extra']['consume_recipe']) && (true === $minfo['extra']['consume_recipe'])) \itemmix\itemmix_reduce('itm'.$itmp);
		$itm0 = $minfo['result'][0];
		$itmk0 = $minfo['result'][1];
		$itme0 = $minfo['result'][2];
		$itms0 = $minfo['result'][3];
		if (isset($minfo['result'][4]))
			$itmsk0 = $minfo['result'][4];
		else{
			$itmsk0 = '';
		}
		if (isset($minfo['extra']['link'])) $uip['mixtp'] = $mix_type['link'];
		else $uip['mixtp'] = '使用配方';
		if (isset($minfo['key']))
		{
			//不消耗配方并且未设置不能学习；或者设置了可以学习
			if (((!isset($minfo['extra']['consume_recipe']) || (false === $minfo['extra']['consume_recipe'])) && !isset($minfo['extra']['if_learnable'])) || (isset($minfo['extra']['if_learnable']) && $minfo['extra']['if_learnable']))
			{
				learn_recipe_process($minfo);
			}
			//学习额外配方
			if (isset($minfo['extra']['ex_learn']))
			{
				$ex_learn = $minfo['extra']['ex_learn'];
				if (is_array($ex_learn))
				{
					foreach ($ex_learn as $exrkey)
					{
						if (isset($recipe_mixinfo[$exrkey]))
						{
							$exminfo = $recipe_mixinfo[$exrkey];
							$exminfo['key'] = $exrkey;
							learn_recipe_process($exminfo);
						}
					}
				}
				elseif (isset($recipe_mixinfo[$ex_learn]))
				{
					$exminfo = $recipe_mixinfo[$ex_learn];
					$exminfo['key'] = $ex_learn;
					learn_recipe_process($exminfo);
				}
			}
		}
		recipe_mix_success();
	}
	
	//如果无法合成，返回false；否则按照满足条件的素材顺序排序
	function get_recipe_mix_sequence($mlist, $minfo)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player'));
		$seq = array();
		if (isset($minfo['extra']) && !check_recipe_extra($mlist, $minfo['extra'])) return false;
		for ($i=1; $i<=5; $i++)
		{
			if (isset($minfo['stuff'.$i]))
			{
				$stuff = $minfo['stuff'.$i];
				$flag = 0;
				foreach ($mlist as $val)
				{
					if (check_single_item(${'itm'.$val}, ${'itmk'.$val}, ${'itmsk'.$val}, $stuff))
					{
						$seq[] = $val;
						unset($mlist[array_search($val, $mlist)]);
						$flag = 1;
						break;
					}
				}
				if (0 === $flag) return false;
			}
			else break;
		}
		if (isset($minfo['stuffa']))
		{
			$stuff = $minfo['stuffa'];
			foreach ($mlist as $val)
			{
				if (!check_single_item(${'itm'.$val}, ${'itmk'.$val}, ${'itmsk'.$val}, $stuff)) return false;
				$seq[] = $val;
			}
		}
		return $seq;
	}

	function recipe_mix_success()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','player','logger'));
		$itmstr = $uip['itmstr'];
		$tpstr = $uip['mixtp'];
		
		$log .= "<span class=\"yellow b\">$itmstr</span>{$tpstr}合成了<span class=\"yellow b\">{$itm0}</span><br>";
		addnews($now,'recipe_mix',$name,$itm0,$tpstr);

		\itemmain\itemget();
	}
	
	function get_learned_recipes(&$pdata = NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!$pdata)
		{
			eval(import_module('player'));
			$pdata = $sdata;
		}
		$learnedrecipes = \skillbase\skill_getvalue(1003,'learnedrecipes',$pdata);
		if (empty($learnedrecipes)) $ls = array();
		else $ls = explode('_',$learnedrecipes);
		return $ls;
	}
	
	function learn_recipe_process($minfo)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ls = get_learned_recipes();
		if (isset($minfo['key']) && !in_array($minfo['key'], $ls)) 
		{
			$ls[] = $minfo['key'];
			$learnedrecipes = implode('_',$ls);
			\skillbase\skill_setvalue(1003,'learnedrecipes',$learnedrecipes);
			eval(import_module('logger'));
			$log .= "你学会了配方<span class=\"yellow b\">{$minfo['result'][0]}</span>！<br>";
		}
	}
	
	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		if($news == 'recipe_mix') 
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"lime b\">{$a}{$c}合成了{$b}</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>
