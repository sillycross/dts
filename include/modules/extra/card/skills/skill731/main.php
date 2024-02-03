<?php

namespace skill731
{
	//初始值。数值分别为：名称、类别、效果值、耐久值、属性、基准价格、价格变动参数、初始库存、库存投放参数
	$skill731_itemlist = array(
		array('杀币','Y','1','1','',250,1,10000,1),
		array('大菠萝3','HB','33','1','',500,1,8000,2),
		array('红杀药酒','PH2','100','1','',1000,2,5000,0.5),
		array('文文。核桃','WC','1','1','z',1500,1,10000,1.5),
		array('OC头像','WC','20','1','',3000,2,3000,0.5),
		array('GTX690战略核显卡','WD','400','1','d',5000,1,1000,0.5),
		array('悲叹之种','Y','1','1','',8000,1,500,0.1),
		array('精致的大逃杀卡牌套','Y','1','1','',20000,0.5,100,0.5),
		array('AI智能纳米区块链量子养生壶','A','1','1','q',100000,0.5,10,0.1),
		array('黄金青眼白龙 ★8','A08','3000','1','',87000000,0.5,1,0),
	);

	$skill731_trends = Array();//涨跌趋势
	
	function init() 
	{
		define('MOD_SKILL731_INFO','card;active;');
		eval(import_module('clubbase'));
		$clubskillname[731] = '欺货';
	}
	
	function acquire731(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_setvalue(731,'updtime','0',$pa);
	}
	
	function lost731(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		\skillbase\skill_delvalue(731,'updtime',$pa);
	}
	
	function check_unlocked731(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	//刷新并获取市场价格
	//改成用$gamevars储存，本函数仅用来判定是否需要刷新
	function skill731_refresh_prices(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$updtime = !empty($gamevars['skill731_updtime']) ? $gamevars['skill731_updtime'] : 0;
		if(empty($updtime)) {//如果从未更新过，在第一次执行时更新
			skill731_prices_update();
		}
		else//否则每100至240秒更新。这里实际刷新越多越频繁
		{
			if ($now - $updtime > rand(100,240))
				skill731_prices_update();
		}
		return;
	}

	//更新市场价格和库存
	//改成用$gamevars储存。输入$pa参数但不涉及$pa的任何操作
	//每次以上一次的价格为基准值
	function skill731_prices_update(&$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','skill731','logger'));
		//更新价格
		$prices_o = !empty($gamevars['skill731_prices']) ? $gamevars['skill731_prices'] : Array();
		//如果数组元素数目对不上，用基准值初始化$prices_o
		if(sizeof($prices_o) != sizeof($skill731_itemlist)){
			$prices_o = Array();
			foreach ($skill731_itemlist as $v)
				$prices_o[] = $v[5];
		}
		$prices = array();
		$imax = sizeof($prices_o);
		for($i=0;$i<$imax;$i++) {
			$dice = rand(0,99);
			//如果当前价格低于基准值则有更高的概率上涨，反制有概率下跌，但影响幅度限于正负10%之内
			$dice_delta = round(10 * ($prices_o[$i] - $skill731_itemlist[$i][5])/$skill731_itemlist[$i][5]);
			$dice_delta = min(10,max(-10,$dice_delta));
			$dice += $dice_delta;
			if($dice < 1) $r = rand(181,250) / 100;//1%概率变为1.81-2.5倍
			if ($dice < 5) $r = rand(133,180) / 100;//4%概率变为1.33-1.8倍
			elseif ($dice < 95) $r = rand(67,132) / 100;//90%概率在0.67-1.32之间浮动
			elseif($dice < 99) $r = rand(25,66) / 100;//4%概率变为0.25-0.66倍
			else $r = rand(1,24) / 100;//1%概率变为0.01-0.24倍，注意这里有暴跌
			//这样总期望是0.9991，长赌必输
			$delta = $skill731_itemlist[$i][6] * ($prices_o[$i] * $r - $prices_o[$i]);//变化量乘以商品的价格变动系数
			$prices[$i] = round($prices_o[$i] + $delta);//更新价格
			if($prices[$i] <= 0) $prices[$i] = 1;//保底
			//记录涨跌趋势
			if($delta > 0) $skill731_trends[$i] = 1;
			elseif($delta < 0) $skill731_trends[$i] = -1;
		}
		//储存价格
		$gamevars['skill731_prices'] = $prices;

		//更新库存
		$stocks_o = !empty($gamevars['skill731_stocks']) ? $gamevars['skill731_stocks'] : Array();
		//如果数组元素数目对不上，用基准值初始化$stocks_o
		if(sizeof($stocks_o) != sizeof($skill731_itemlist)){
			$stocks_o = Array();
			foreach ($skill731_itemlist as $v)
				$stocks_o[] = $v[7];
		}
		$stocks = array();
		$imax = sizeof($stocks_o);
		for($i=0;$i<$imax;$i++) {
			//库存每次按基准值的10%为变化量上限
			$d = (rand(0,200) - 100) / 100 * $skill731_itemlist[$i][7] * 0.1 * $skill731_itemlist[$i][8];
			$stocks[$i] = $stocks_o[$i] + round($d);//更新库存
			if($stocks[$i] <= 0) $stocks[$i] = 0;//保底
		}
		//储存库存
		$gamevars['skill731_stocks'] = $stocks;
		//记录更新时间
		$gamevars['skill731_updtime'] = $now;
		save_gameinfo();
		$log .= '<span class="yellow b">市场价格更新了。</span><br><br>';
		return;
	}
	
	function skill731_buy($bitmn, $bnum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','skill731'));
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
		skill731_refresh_prices($sdata);
		$skill731_prices = $gamevars['skill731_prices'];
		if (\skillbase\skill_getvalue(731,'updtime',$sdata) < $gamevars['skill731_updtime'])
		{
			$log .= '市场价格刚刚更新了，系统自动取消了你的买单。<br>';
			return;
		}
		$bi = $bitmn-1;
		$skill731_stocks = $gamevars['skill731_stocks'];
		$cost = $bnum * $skill731_prices[$bi];
		$flag = 1;
		if($bnum > $skill731_stocks[$bi] || (!\itemmain\check_mergable($skill731_itemlist[$bi][1]) && $bnum > 1)) {
			$flag = 0; 
		}
		if ($cost > $money)
		{
			$log .= '你的钱不够。<br>';
			return;
		}
		elseif (!$flag)
		{
			$log .= '库存数量不足，或需要分次买入。<br>';
			return;
		}
		$money -= $cost;
		$itm0 = $skill731_itemlist[$bi][0];
		$itmk0 = $skill731_itemlist[$bi][1];
		$itme0 = $skill731_itemlist[$bi][2];
		$itms0 = $skill731_itemlist[$bi][3] * $bnum;
		$itmsk0 = \attrbase\config_process_encode_comp_itmsk($skill731_itemlist[$bi][4]);
		//更新库存		
		$skill731_stocks[$bi] -= $bnum;
		$gamevars['skill731_stocks'] = $skill731_stocks;
		save_gameinfo();
		$log .= "购买成功，总计花费<span class='yellow b'>$cost</span>元。<br>";
		addnews(0, 'buy731', $name, $bnum, $skill731_itemlist[$bi][0]);
		\itemmain\itemget();
		return;
	}
	
	function skill731_sell($sitmn, $snum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','skill731'));
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
		skill731_refresh_prices($sdata);
		$skill731_prices = $gamevars['skill731_prices'];
		if (\skillbase\skill_getvalue(731,'updtime',$sdata) < $gamevars['skill731_updtime'])
		{
			$log .= '市场价格刚刚更新了，系统自动取消了你的卖单。<br>';
			return;
		}
		$si = $sitmn-1;
		$flag = 0;
		for ($i=1;$i<=6;$i++)
		{
			if (${'itm'.$i} == $skill731_itemlist[$si][0])
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
		$gain =  $snum * $skill731_prices[$si];
		$cmsn = round($gain * 0.1);
		$gain -= $cmsn;
		$money += $gain;
		//更新库存
		$skill731_stocks = $gamevars['skill731_stocks'];
		$skill731_stocks[$si] += $snum;
		$gamevars['skill731_stocks'] = $skill731_stocks;
		save_gameinfo();
		$log .= "出售成功，获得了<span class='yellow b'>$gain</span>元<span style='font-size:6px'>（已扣除手续费{$cmsn}元）</span>。<br>";
		addnews(0, 'sell731', $name, $snum, $skill731_itemlist[$si][0]);
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
		\skillbase\skill_setvalue(731, 'updtime', $now, $sdata);
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

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'buy731')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}在欺货市场买入了{$b}个{$c}。</span></li>";
		elseif($news == 'sell731')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"yellow b\">{$a}在欺货市场卖出了{$b}个{$c}。</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
}

?>