<?php

namespace skill731
{
	$skill731_itemlist = array(
		array('杀币','Y','1','1','','250'),
		array('大菠萝3','HB','33','1','','500'),
		array('红茅药酒','PH2','100','1','','1000'),
		array('文文。核桃','WC','1','1','z','1500'),
		array('OC头像','DH','20','1','','3000'),
		array('GTX690战略核显卡','WD','400','1','d','5000'),
		array('悲叹之种','X','1','1','','8000'),
		array('精致的大逃杀卡牌套','X','1','1','','20000'),
		array('AI智能纳米区块链量子养生壶','A','1','1','q','100000'),
		array('黄金青眼白龙 ★8','WC08','3000','1','','87000000'),
	);
	
	function init() 
	{
		define('MOD_SKILL731_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[731] = '开盘';
	}
	
	function acquire731(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(731,'updtime','0',$pa);
		\skillbase\skill_setvalue(731,'prices','',$pa);
	}
	
	function lost731(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(731,'updtime',$pa);
		\skillbase\skill_delvalue(731,'prices',$pa);
	}
	
	function check_unlocked731(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill731_get_prices(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (empty($pa))
		{
			eval(import_module('player'));
			$pa = $sdata;
		}
		$prices_str = \skillbase\skill_getvalue(731,'prices',$pa);
		if (empty($prices_str)) $prices = skill731_prices_update($pa);
		else
		{
			eval(import_module('sys'));
			$updtime = (int)\skillbase\skill_getvalue(731,'updtime',$pa);
			if ($now - $updtime > rand(100,240)) $prices = skill731_prices_update($pa);
		}
		if (!isset($prices)) $prices = explode('_', $prices_str);
		return $prices;
	}
	
	function skill731_prices_update(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill731','logger'));
		
		$prices = array();
		foreach ($skill731_itemlist as $v)
		{
			$dice = rand(0,99);
			if ($dice < 4) $r = rand(131,250) / 100;
			elseif ($dice < 96) $r = rand(51,130) / 100;
			else $r = rand(20,50) / 100;
			$prices[] = round($v[5] * $r);
		}
		$prices_str = implode('_', $prices);
		\skillbase\skill_setvalue(731,'prices',$prices_str,$pa);
		\skillbase\skill_setvalue(731,'updtime',$now,$pa);
		$log .= '<span class="yellow b">市场价格更新了。</span><br><br>';
		return $prices;
	}
	
	function skill731_buy($bitmn, $bnum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','player','skill731'));
		if (!\skillbase\skill_query(731, $sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		elseif ($bnum <= 0 || $bitmn <= 0 || $bitmn > count($skill731_itemlist))
		{
			$log .= '输入参数错误。<br>';
			return;
		}
		$skill731_prices = skill731_get_prices($sdata);
		$cost = $bnum * $skill731_prices[$bitmn-1];
		if ($cost > $money)
		{
			$log .= '你的钱不够。<br>';
			return;
		}
		$money -= $cost;
		$itm0 = $skill731_itemlist[$bitmn-1][0];
		$itmk0 = $skill731_itemlist[$bitmn-1][1];
		$itme0 = $skill731_itemlist[$bitmn-1][2];
		$itms0 = $skill731_itemlist[$bitmn-1][3] * $bnum;
		$itmsk0 = $skill731_itemlist[$bitmn-1][4];
		
		$log .= "购买成功。<br>";
		\itemmain\itemget();
		return;
	}
	
	function skill731_sell($sitmn, $snum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('logger','player','skill731'));
		if (!\skillbase\skill_query(731, $sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		elseif ($snum <= 0 || $sitmn <= 0 || $sitmn > count($skill731_itemlist))
		{
			$log .= '输入参数错误。<br>';
			return;
		}
		$flag = 0;
		for ($i=1;$i<=6;$i++)
		{
			if (${'itm'.$i} == $skill731_itemlist[$sitmn-1][0])
			{
				if ((${'itms'.$i} === '∞') && ($snum == 1)) ${'itms'.$i} = 0;
				elseif (is_numeric(${'itms'.$i}) && (${'itms'.$i} >= $snum)) ${'itms'.$i} -= $snum;
				else continue;
				$flag = 1;
				if (${'itms'.$i} <= 0)
				{
					${'itm'.$i} = ${'itmk'.$i} = ${'itmsk'.$i} = '';
					${'itme'.$i} = ${'itms'.$i} = 0;
				}
				break;
			}
		}
		if (!$flag)
		{
			$log .= '道具数量不足，或需要分次卖出。<br>';
			return;
		}
		$skill731_prices = skill731_get_prices($sdata);
		$gain =  $snum * $skill731_prices[$sitmn-1];
		$cmsn = round($gain * 0.05);
		$gain -= $cmsn;
		$money += $gain;
		
		$log .= "出售成功，获得了<span class='yellow b'>$gain</span>元<span style='font-size:6px'>（已扣除手续费{$cmsn}元）</span>。<br>";
		return;
	}
	
	function cast_skill731()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(731, $sdata)) 
		{
			$log .= '你没有这个技能。';
			return;
		}
		$bitmn = get_var_input('skill731_bitmn');
		$sitmn = get_var_input('skill731_sitmn');
		if (!empty($bitmn))
		{
			$bnum = (int)get_var_input('skill731_bnum');
			skill731_buy($bitmn, $bnum);
		}
		elseif(!empty($sitmn))
		{
			$snum = (int)get_var_input('skill731_snum');
			skill731_sell($sitmn, $snum);
		}
		if(empty($itms0)) {//为了防止卡死，手里是空的才显示界面
			ob_start();
			include template(MOD_SKILL731_CASTSK731);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','input'));
	
		if ($mode == 'special' && $command == 'skill731_special') 
		{
			cast_skill731();
			return;
		}
			
		$chprocess();
	}
}

?>