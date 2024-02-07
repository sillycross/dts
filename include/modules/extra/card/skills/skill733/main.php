<?php

namespace skill733
{
	//扭蛋机道具列表。name：扭蛋种类名；price：单抽价格；items：道具列表，数值依次为名称、类别、效果值、耐久值、属性、权重、稀有度等级（1-4，1为最稀有）
	$skill733_gachalist = array(
		1 => array(
			'name' => '大逃杀手办扭蛋',
			'price' => 1000,
			'desc' => '大逃杀游戏角色手办的扭蛋。',
			'items' => array(
				array('黄鸡玩偶','A',1,1,'z',30,3),
				array('棕色的Howling手办','A',1,1,'N',10,2),
				array('深蓝色的SAS手办','A',1,1,'n',10,2),
				array('天青色的Annabelle手办','A',1,1,'i',10,2),
				array('红色的星海手办','A',1,1,'M',10,2),
				array('蓝色的妖精手办','A',9,9,'cirno',1,1),
				array('Ruby&Snow手办组合','A',1,1,'Bb',1,1),
			),
		),
		2 => array(
			'name' => '卡片扭蛋',
			'price' => 150,
			'desc' => '各种来历不明的奇怪卡片的扭蛋。',
			'items' => array(
				array('风景明信片','WC',20,1,'',200,4),
				array('☆杀人扑克牌☆','WC',60,5,'r',100,4),
				array('★库洛牌★','WC',500,3,'',70,3),
				array('游戏王卡包','ygo',1,1,'',150,4),
				array('《现代游戏王》','ygo2',1,1,'',75,3),
				array('★铁斩波★','WP',5,1,'c',50,4),
				array('【Alicemagic】','ss',30,1,'',20,3),
				array('【Crow Song】','ss',90,1,'',20,3),
				array('【雨だれの歌】','ss',60,1,'',10,2),
				array('【驱寒颂歌】','ss',120,1,'',10,2),
				array('【爸爸野猪】','ss',88,1,'',10,2),
				array('【小苹果】','ss',10,1,'',30,3),
				array('【通顶雪道】','DFS',100,100,'Oik',30,2),
				array('★黑莲花★','ME',20,3,'x',30,2),
				array('一大捆沾满灰尘的大逃杀卡牌包','VO8',1,9,'',8,3),
				array('一叠陈旧的大逃杀卡牌包','VO9',1,3,'',4,3),
				array('陈旧的大逃杀卡牌包','VO9',1,1,'',10,3),
				array('普通的大逃杀卡牌包','VO2',1,1,'',5,2),
				array('精致的大逃杀卡牌包','VO3',1,1,'',3,2),
				array('★闪熠着光辉的大逃杀卡牌包★','VO4',1,1,'',1,1),
			),
		),
		3 => array(
			'name' => '摇号机■■',
			'price' => 300,
			'desc' => '让人很想唱歌和全速前进的扭蛋。',
			'items' => array(
				array('次世代数据网络决斗盘','DA',100,100,'MH',40,2),
				array('青眼白猫 ★1','WC01',60,200,'',233,4),
				array('「青眼白龙」-仮','X',1,1,'',150,3),
				array('「青眼白龙」 ★8','WC08',300,'∞','d',20,2),
				array('「白色灵龙」 ★8','WC08',250,'∞','M',20,3),
				array('「青眼亚白龙」 ★8','WC08',300,'∞','d',20,2),
				array('「青眼喷气龙」 ★8','WC08',300,'∞','wD',20,2),
				array('「深渊青眼龙」 ★8','WC08',250,'∞','Hc',20,2),
				array('「青眼卡通龙」 ★8','WC08',300,'∞','n',80,3),
				array('「青色眼睛的激临」','DH',300,250,'N',10,2),
				array('「青色眼睛的幻出」','DA',300,250,'Hc',10,2),
				array('破灭的爆裂疾风弹','WC',3000,1,'d',2,1),
				array('「强韧！无敌！最强！」','A',3000,1,'Bb',1,1),
				array('黄金青眼白龙 ★8','A08',3000,1,'',1,1),
				array('篝酱的奇迹☆胶带～刃','WK',42,300,'cN',233,4),
				array('篝酱的奇迹☆胶带～棍','WP',42,300,'de',233,4),
				array('篝酱的奇迹☆胶带～炎','WF',42,300,'lu',233,4),
				array('黄色雏菊','HM',120,1,'',120,3),
				array('生命之源','Z',1200,1,'z',30,3),
				array('红色的丝带','DB',1200,1,'Aa',5,2),
				array('红色的丝带','DA',1200,1,'Aa',5,2),
				array('红色的丝带','WP',1200,1,'u',5,2),
				array('红色的丝带','WK',1200,1,'N',5,2),
				array('红色的丝带','WP',800,20,'uy',3,2),
				array('黑色连衣裙','DB',800,20,'Aa',3,2),
				array('红色的丝带','DH',800,15,'B',2,2),
				array('红色的丝带','DA',800,15,'b',2,2),
				array('黑色布鞋','DF',800,21,'',10,2),
				array('红黑色的耳塞','A',1,1,'W',5,2),
				array('篝酱的奇迹☆胶带～刃','WK',4200,300,'cNj',1,1),
			),
		)
	);
	
	$skill733_rarecolor = array(1=>'gold', 2=>'cyan', 3=>'brickred', 4=>'white');
	
	function init() 
	{
		define('MOD_SKILL733_INFO','card;active;hidden;');
		eval(import_module('clubbase'));
		$clubskillname[733] = '扭蛋';
	}
	
	function acquire733(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost733(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked733(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function skill733_gacha($gachaid, $gnum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player','skill733'));
		if (!\skillbase\skill_query(733, $sdata)) 
		{
			$log .= '你没有这个技能。<br>';
			return;
		}
		elseif ($gnum <= 0 || $gnum > 5 || !isset($skill733_gachalist[$gachaid]))
		{
			$log .= '输入参数错误。<br>';
			return;
		}
		$gachainfo = $skill733_gachalist[$gachaid];
		$cost = $gnum * $gachainfo['price'];
		if ($cost > $money)
		{
			$log .= '你的钱不够。<br>';
			return;
		}
		$money -= $cost;
		
		$wsum = 0;
		foreach($gachainfo['items'] as $v)
		{
			$wsum += $v[5];
		}
		$gachainfo['wsum'] = $wsum;
		
		$log .= "花费<span class='yellow b'>$cost</span>元，抽取了<span class='yellow b'>$gnum</span>次<span class=\"yellow b\">【{$gachainfo['name']}】</span>。<br>";
		
		$gacha_result = array();
		$gacha_result_words = array();
		for ($i=0; $i<$gnum; $i++)
		{
			$gacha_item = skill733_get_gacha_result($gachainfo);
			$gacha_result_words[] = "<span class=\"{$skill733_rarecolor[$gacha_item[6]]} b\">{$gacha_item[0]}</span>";
			$gacha_result[] = array('itm'=>$gacha_item[0], 'itmk'=>$gacha_item[1], 'itme'=>$gacha_item[2], 'itms'=>$gacha_item[3], 'itmsk'=>$gacha_item[4]);
		}
		
		$grcount = count($gacha_result);
		if ($grcount > 1) $gacha_result_words = implode('、', array_slice($gacha_result_words, 0, $grcount - 1)).'和'.end($gacha_result_words);
		else $gacha_result_words = $gacha_result_words[0];
		
		\skill1006\multi_itemget($gacha_result, $sdata, 0);
		$log .= "{$gacha_result_words}从扭蛋机里掉了出来。<br>";
		
		addnews(0, 'gacha733', $name, $gnum, $skill733_gachalist[$gachaid]['name'], $gacha_result_words);
		return;
	}
	
	function skill733_get_gacha_result($gachainfo)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$dice = rand(1,$gachainfo['wsum']);
		foreach ($gachainfo['items'] as $v)
		{
			if ($dice <= $v[5]) return $v;
			else $dice -= $v[5];
		}
		return $gachainfo['items'][0];
	}
	
	function cast_skill733()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys','logger','player'));
		if (!\skillbase\skill_query(733, $sdata)) 
		{
			$log .= '你没有这个技能。';
			return;
		}
		$gachaid = (int)get_var_input('skill733_gachaid');
		if (!empty($gachaid))
		{
			$gnum = (int)get_var_input('skill733_gnum');
			skill733_gacha($gachaid, $gnum);
		}
		if(empty($itms0)) {//为了防止卡死，手里是空的才显示界面
			ob_start();
			include template(MOD_SKILL733_CASTSK733);
			$cmd=ob_get_contents();
			ob_end_clean();
		}
		return;
	}
	
	function act()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger'));
	
		if ($mode == 'special' && $command == 'skill733_special') 
		{
			cast_skill733();
			return;
		}
		
		$chprocess();
	}

	function parse_news($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr = array())
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player'));
		
		if($news == 'gacha733')
			return "<li id=\"nid$nid\">{$hour}时{$min}分{$sec}秒，<span class=\"cyan b\">{$a}抽取了{$b}次<span class=\"yellow b\">【{$c}】</span>，获得了{$d}！</span></li>";
		return $chprocess($nid, $news, $hour, $min, $sec, $a, $b, $c, $d, $e, $exarr);
	}
	
}

?>