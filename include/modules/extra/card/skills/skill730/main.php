<?php

namespace skill730
{
	$skill730_itemlist = array(
		0 => array(
			array('小白兔 ★1','WC01','15','205',''),
			array('童话动物·小兔子 ★2','WC02','0','210',''),
			array('童话动物·小马 ★2','WC02','40','∞',''),
			array('童话动物·小狗 ★2','WC02','30','10',''),
			array('童话动物·小猫 ★2','WC02','20','20',''),
			array('童话动物·小阔耳狐 ★2','WC02','10','30',''),
			array('童话动物·小小袋鼠 ★2','WC02','0','40',''),
			array('童话动物·小海豹 ★2','WC02','30','10','s'),//差点忘了这玩意有属性
			array('森之圣兽 月兔耳兽 ★2','WC02','20','140','d'),
			array('安康鱼','HH','200','1',''),
			array('河豚鱼','PH2','700','1',''),
			array('野生的雪貂','PH','400','1',''),
			array('走失的猫咪','PH','400','1',''),
			array('南京挂花鸭','PB2','70','5',''),
		),
		1 => array(
			array('凸眼鱼','Y','1','1',''),
			array('招潮蟹','TN','800','1',''),
			array('黄鸡方块','X','1','1',''),
			array('「娱乐伙伴 合唱鹦鹉」★3','DA03','50','50','^sa3'),
			array('兽王 阿尔法 ★8','WC08','300','60','wN')
		),
		2 => array(
			array('油炖萌物「金鲤」','HS','450','∞',''),
			array('油炖萌物「石斑」','HH','450','∞',''),
			array('「天霆号 阿宙斯」-仮','R','1','1','23'),
			array('「天霆号 阿宙斯」-仮','R','1','1','23')
		)
	);
	
	function init()
	{
		define('MOD_SKILL730_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[730] = '魔术';
	}
	
	function acquire730(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost730(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked730(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function storage_fetchout_core($bagn, $pos, &$pa=NULL)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if(empty($pa)) {
			eval(import_module('player'));
			$pa = $sdata;
		}
		$ret = $chprocess($bagn, $pos, $pa);
		if (\skillbase\skill_query(730,$pa) && check_unlocked730($pa))
		{
			if(!empty($ret))
			{
				eval(import_module('skill730','logger'));
				$itm_temp = $ret['itm'];
				$dice = rand(0,99);
				if ($dice < 1) $ritm = array_randompick($skill730_itemlist[2]);
				elseif ($dice < 6) $ritm = array_randompick($skill730_itemlist[1]);
				else $ritm = array_randompick($skill730_itemlist[0]);
				$ret['itm'] = $ritm[0];
				$ret['itmk'] = $ritm[1];
				$ret['itme'] = $ritm[2];
				$ret['itms'] = $ritm[3];
				$ret['itmsk'] = $ritm[4];
				if ($ret['itm'] != $itm_temp) $log = "<span class=\"cyan\">{$itm_temp}变成了{$ret['itm']}！</span><br>";
				else $log = "<span class=\"cyan\">{$itm_temp}变成了{$ret['itm']}……好像有哪里不对？</span><br>";
			}
		}
		return $ret;
	}
	
}

?>