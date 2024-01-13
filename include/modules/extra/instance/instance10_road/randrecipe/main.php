<?php

namespace randrecipe
{
	//会生成随机配方的游戏模式
	$randrecipe_allow_mode = array(20);
	
	function init()
	{
	}
	
	function create_randrecipe_config($num = 50)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$rl = array();
		for ($i=1; $i<=$num; $i++)
		{
			$rl[] = generate_randrecipe();
		}
		//保存为config文件。每个房间一个文件，这个函数应该是仅在每局开始时执行的，如果因为某些原因覆盖了那就覆盖了罢
		$file = GAME_ROOT.'./gamedata/cache/randrecipe'.$room_id.'.php';
		$contents = str_replace('?>','',$checkstr);//防窥屏字符串"<?php\r\nif(!defined('IN_GAME')) exit('Access Denied');\r\n";
		$contents .= '$randrecipe = '.var_export($rl,1).';';
		file_put_contents($file, $contents);
	}
	
	//生成一个标准格式的随机配方
	function generate_randrecipe($itmk = '')
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('randrecipe'));
		$r = array('result' => array(),'extra' => array());
		if (empty($itmk)) $itmk = array_randompick(array('WP','WK','WC','WG','WD','WF','WP','WK','WC','WG','WD','WF','DB','DH','DA','DF','A','HH','HS','HB'));
		//初始化合成结果
		$r['result'][0] = '';
		$r['result'][1] = $itmk;
		$r['result'][4] = array();
		if ($itmk[0] == 'H')
		{
			$r['result'][2] = rand(50,140);
			$r['result'][3] = rand(10,30);
		}
		elseif ($itmk[0] == 'W')
		{
			$r['result'][2] = rand(50,200);
			$r['result'][3] = rand(20,50);
			$skcount = rand(0,2);
			if ($skcount == 1) $r['result'][4] = [array_randompick($randrecipe_itmsk_list['W'])];
			elseif ($skcount > 1) $r['result'][4] = array_randompick($randrecipe_itmsk_list['W'], $skcount);
			else
			{
				$r['result'][2] += rand(80,150);
				$r['result'][3] += rand(30,60);
			}
			//爆炸物大概率有爆炸
			if (($itmk == 'WD') && (rand(0,99) > 30)) $r['result'][4][] = 'd';
		}
		elseif ($itmk[0] == 'D')
		{
			$r['result'][2] = rand(60,150);
			$r['result'][3] = rand(15,30);
			$skcount = rand(0,2);
			if ($skcount == 1) $r['result'][4] = [array_randompick($randrecipe_itmsk_list['D'])];
			elseif ($skcount > 1) $r['result'][4] = array_randompick($randrecipe_itmsk_list['D'], $skcount);
			else
			{
				$r['result'][2] += rand(40,80);
				$r['result'][3] += rand(15,30);
			}
		}
		else
		{
			$r['result'][2] = 1;
			$r['result'][3] = 1;
			$skcount = rand(1,2);
			if ($skcount == 1) $r['result'][4] = [array_randompick($randrecipe_itmsk_list['A'])];
			elseif ($skcount > 1) $r['result'][4] = array_randompick($randrecipe_itmsk_list['A'], $skcount);
		}
		$si = 1;
		//主要素材1-2个
		$c = rand(1,2);
		for ($i=0; $i<$c; $i++)
		{
			$r['stuff'.$si] = generate_randrecipe_stuff('main', $itmk, $r['result']);
			$si += 1;
		}
		if ($itmk[0] != 'A')
		{
			//副素材0-2个
			$c = rand(0,2);
			for ($i=0; $i<$c; $i++)
			{
				$r['stuff'.$si] = generate_randrecipe_stuff('sub', $itmk, $r['result']);
				$si += 1;
			}
		}
		//额外素材0-2个，饰品则为1-3个，但总素材数不超过6个
		if ($itmk[0] == 'A') $c = rand(1,3);
		else $c = rand(0,2);
		for ($i=0; $i<$c; $i++)
		{
			if ($itmk[0] == 'H') $type = array_randompick(array('itme','itms'));
			elseif ($itmk[0] == 'A') $type = array_randompick(array('itmsk'));
			else $type = array_randompick(array('itmsk','itme','itms'));
			$r['stuff'.$si] = generate_randrecipe_stuff($type, $itmk, $r['result']);
			
			$si += 1;
			if ($si > 5) break;
		}
		if (isset($randrecipe_bonus_itmsk_list[$itmk[0]]))
		{
			foreach($randrecipe_bonus_itmsk_list[$itmk[0]] as $k => $v)
			{
				if (($r['result'][2] >= $k) && (rand(0,99) > 70)) $r['result'][4][] = array_randompick($v);
			}
		}		
		if (isset($result[5]))
		{
			if ($itmk == 'WG')
			{
				$r['result'][1] == 'WJ';
				if ($r['result'][2] < 1000)
				{
					$r['result'][3] = rand(1,5);
					$r['result'][4][] = 'o';
				}
			}
			elseif ($itmk == 'WC') $r['result'][1] == 'WB';
			unset($result[5]);
		}
		if (!empty($r['result'][4])) $r['result'][4] = implode('', array_unique($r['result'][4]));
		//生成名称
		$dice = rand(0,2);
		if ($dice) $r['result'][0] .= array_randompick($randrecipe_resultname['rand_prefix']).$r['result'][0];
		if ($itmk[0] == 'H') $r['result'][0] .= array_randompick($randrecipe_resultname['H_prefix']);
		else $r['result'][0] .= array_randompick($randrecipe_resultname['E_prefix']);
		if ($dice) $r['result'][0] .= array_randompick(array('','','之'));
		else $r['result'][0] .= '的';
		$r['result'][0] .= array_randompick($randrecipe_resultname[$itmk]);
		if (($itmk[0] != 'H') && rand(0,1))
		{
			$bracket = array_randompick(array(array('☆','☆'),array('★','★'),array('〖','〗'),array('【','】'),array('『','』'),array('「','」')));
			$r['result'][0]	= $bracket[0].$r['result'][0].$bracket[1];
		}
		$r['extra']['materials'] = $si - 1;
		return $r;
	}
	
	//生成一个随机配方素材条件
	function generate_randrecipe_stuff($type, $itmk, &$result)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('randrecipe'));
		$s = array();
		if ($type == 'itmsk')
		{
			$sk = array_randompick($randrecipe_stuff_itmsk_list[$itmk[0]]);
			$k = array_rand($itmsk_stuff[$sk]);
			$result[4][] = $sk;
			$v = array_randompick($itmsk_stuff[$sk][$k]);
		}
		elseif (($type == 'itme') || ($type == 'itms'))
		{
			$r = array_merge_recursive(${$type.'_stuff'}[$itmk], ${$type.'_stuff'}['common']);
			$k = array_rand($r);
			$v = array_rand($r[$k]);
			$change = $r[$k][$v];
			if ($type == 'itme') $rk = 2;
			else $rk = 3;
			if (!empty($change))
			{
				if (($type == 'itme') && ($change == 'u')) $result[5] == 1;
				elseif (($type == 'itms') && ($change == 'i')) $result[3] == '∞';
				else
				{
					$c = (float)substr($change, 1);
					if ($change[0] == '+')
					{
						$result[$rk] = round($result[$rk] + $c);
					}
					elseif ($change[0] == '-')
					{
						$result[$rk] = min(round($result[$rk] - $c), 1);
					}
					elseif ($change[0] == '*')
					{
						$result[$rk] = round($result[$rk] * $c);
					}
				}
			}
		}
		else
		{
			$k = array_rand(${$type.'_stuff'}[$itmk]);
			$v = array_rand(${$type.'_stuff'}[$itmk][$k]);
			$change = ${$type.'_stuff'}[$itmk][$k][$v];
			if (!empty($change))
			{
				$c = (float)substr($change, 1);
				if ($change[0] == '+')
				{
					$result[2] = round($result[2] + $c);
					$result[3] = round($result[3] + $c);
				}
				elseif ($change[0] == '-')
				{
					$result[2] = min(round($result[2] - $c), 1);
					$result[3] = min(round($result[3] - $c), 1);
				}
				elseif ($change[0] == '*')
				{
					$result[2] = round($result[2] * $c);
					$result[3] = round($result[3] * $c);
				}
			}
		}
		$s[$k] = $v;
		if ($k == 'itm') $s['itm_match'] = 1;
		elseif ($k == 'itmk') $s['itmk_match'] = 1;
		elseif ($k == 'itmsk') $s['itmsk_match'] = 1;
		return $s;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if (strpos ( $itmk, 'Y' ) === 0 || strpos ( $itmk, 'Z' ) === 0)
		{	
			if ($itm == '测试配方config生成器')
			{
				$log .= "使用了<span class=\"yellow b\">$itm</span>。<br>";
				create_randrecipe_config(12);
				return;
			}
			elseif ($itm == '测试配方生成器')
			{
				$log .= "使用了<span class=\"yellow b\">$itm</span>。<br>";
				$recipe = \randrecipe\generate_randrecipe();
				$recipe_tip = '';
				$flag = 0;
				for ($i=1;$i<=5;$i++)
				{
					if (isset($recipe['stuff'.$i]))
					{
						$recipe_tip .= \item_recipe\generate_single_itemtip($recipe['stuff'.$i]).'<br>+ ';
						$flag = 1;
					}
				}
				if (1 === $flag) $recipe_tip = substr($recipe_tip,0,-2);
				if (isset($recipe['stuffa']))
				{
					if (1 === $flag) $recipe_tip .= '其余';
					$recipe_tip .= '素材为：'.\item_recipe\generate_single_itemtip($recipe['stuffa']).'<br>';
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
				$log .= "你产生的配方公式：<br><span class=\"yellow b\">$recipe_tip</span><br>";
				return;
			}
		}
		$chprocess($theitem);
	}
	
}

?>