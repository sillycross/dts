<?php

namespace skill183
{
	//睡眠时，每隔多少秒获得一条提示
	$rest_get_tip_rate = 35;
	//治疗或静养时，每隔多少秒新增一个物品/角色到临时视野
	$rest_get_beacon_rate = 15;
	//睡眠时，一次最多获得多少条提示
	$rest_get_tip_max = 15;
	//治疗或静养时，一次最多新增多少个临时视野
	$rest_get_beacon_max = 20;
	//睡眠时会提示的NPC类别，依次是全息，黑幕，真职人，女主，武神，幽灵
	$tip_npctype = array(2,6,11,14,21,45);
	//睡眠时会提示的道具名列表
	$tip_itemnamelist = array('肥料', '广域生命探测器', '★阔剑地雷★', '毒物说明书', '《チト的日记》', '☆碧藍怒火☆', '☆白楼剑☆', '☆楼观剑☆', '钻石', '宅男装', '萝莉装', '女仆装', '伪娘装', '小五拖鞋', '电子马克笔', '一个能打的都没有', '德国BOY的键盘', '葱娘の葱', '容嬷嬷的针', '新八的眼镜', '新华里的领带', '新华里的西服', '新华里的手表', '新华里的皮鞋', '新华里的投入', '新华里的震撼', '新华里的乱舞', '新华里的手势', '新华里的呐喊', '新华里的眼神', '新华里的增员', '红色方块', '绿色方块', '蓝色方块', '黄色方块', '金色方块', '银色方块', '水晶方块', '黑色方块', '白色方块', 'X方块', 'Y方块', '妹汁', '《BR大逃杀》', '《防身术图解》', '《剑道社教材》', '《枪械杂志》', '《飞镖投掷法》', '《化学课本》', '《太极拳指南》', '【腕力强化剂】', '【皮肤强化剂】', '【神经强化剂】', '【超级战士药剂】', '【肉体强化剂】', '【线粒体强化剂】', '提示纸条E', '提示纸条I', '提示纸条K', '提示纸条N', '弱点探测器', '【北斗百裂拳】', '【狂暴凶刃】', '【盖特机炮】', '幻符【杀人玩偶】', '【泰迪熊炸弹】', '【西方秋霜玉】', '预言挂坠', '【紫棠花色波纹疾走】', '弱爆了！', '《哲♂学》', '★全图唯一的野生巨大香蕉★', '残存的礼品盒', '残存的结婚喜糖-红', '残存的结婚喜糖-橙', '残存的结婚喜糖-黄', '残存的结婚喜糖-绿', '残存的结婚喜糖-青', '残存的结婚喜糖-蓝', '残存的结婚喜糖-紫', '糖衣炮弹-红', '糖衣炮弹-橙', '糖衣炮弹-黄', '糖衣炮弹-绿', '糖衣炮弹-青', '糖衣炮弹-蓝', '糖衣炮弹-紫', '密封的酒瓶', '音乐录像', '五线乐谱', '葱娘肉包', 'V家蔬菜汁', '破旧录音机', '神奇的八音盒', '魂之结晶', '歌手之魂', '【Alicemagic】', '【Crow Song】', '【雨だれの歌】', '【驱寒颂歌】', '【爸爸野猪】', '《基本法》', '《皇帝的新装》', '《太平要术》', '《占星术导论》', '《气功入门》', '《三国杀军争扩展包》', '火焰纹章', '冰霜纹章', '剧毒纹章', '力量纹章', '贯穿纹章', '陈旧的大逃杀卡牌包', '普通的大逃杀卡牌包', '精致的大逃杀卡牌包', '「奥西里斯之天空龙」-仮', '「欧贝利斯克之巨神兵」-仮', '「太阳神之翼神龙」-仮', '【流星一条】', '杨叔的眼镜', '蓝蓝路的大鞋', '动感超人手表', 'MIKU的内裤', '■DeathNote■', '■魔剑－雷瓦丁■', '★Unlimited Blade Works★', '★Unlimited Code Works★', '《ACFUN大逃杀攻略》', '《北斗神拳》', '《寒蝉鸣泣之时》', '《魔法少女奈叶》', '《网球王子》', '《新机动战记高达W》', '《东方永夜抄》', '【触手的萃取液】', '【圣防护罩-反射之力】', '【金蚕王】', '【我已经天下无敌了！】', '【残机碎片】', '【S2机关】', '【宇航服】', '【楼主头】', '【哥哥鞋】', '受王拳', '★闪光迎击神话★', '鲜红的生血', '《东方幻想乡》', '「妖怪测谎机」', '正体不明的UFO', '★I-力场★', '奇怪的按钮', '【主角光环】', '【测试用具】', '驱云弹', '★奇怪的盒子★', '隐身药水', '琪露牛奶', '【Brain Power】', '油豆腐');
	
	function init()
	{
		define('MOD_SKILL181_INFO','club;hidden;');
		eval(import_module('clubbase','skillbase'));
		$clubskillname[183] = '伺机';
		
		foreach(Array(18) as $i) {//暂时仅在荣耀模式开局追加伺机技能
			if(!isset($valid_skills[$i])) {
				$valid_skills[$i] = array();
			}
			$valid_skills[$i][] = 183;
		}
	}
	
	function acquire183(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost183(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked183(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function rest($restcommand)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player'));
		if (\skillbase\skill_query(183,$sdata) && check_unlocked183($sdata))
		{
			eval(import_module('sys','rest'));
			$resttime = $now - $endtime;
			if ($state == 1)
			{
				rest_get_tips($resttime);
			}
			elseif ($state == 2 || $state == 3)
			{
				rest_get_beacon($resttime);
			}
		}
		$chprocess($restcommand);
	}
	
	function rest_get_tips($resttime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('skill183'));
		$get_tips_count = floor($resttime / $rest_get_tip_rate);
		if (rand(0,99) < $resttime) $get_tips_count += 1;//奖励骰
		$get_tips_count = min($get_tips_count, $rest_get_tip_max);
		if ($get_tips_count > 0)
		{
			eval(import_module('sys','player','logger','map'));
			$tips = array();
			//高伤雷提示
			if (rand(0,3) == 0)
			{
				$result = $db->query("SELECT * FROM {$tablepre}maptrap WHERE itme>=600");
				while($traparr = $db->fetch_array($result)){
					$traps[] = $traparr;
				}
				shuffle($traps);
				$tips[] = "你梦见自己在<span class=\"yellow b\">{$plsinfo[$traps[0]['pls']]}</span>被<span class=\"yellow b\">{$traps[0]['itm']}</span>炸上了天。<br>……<br>";
				$get_tips_count -= 1;
			}
			if ($get_tips_count > 0)
			{
				eval(import_module('skill183'));
				//重要NPC提示
				$enemytip_count = rand(0, ceil($get_tips_count/2));
				if ($enemytip_count > 0)
				{
					$tip_edata_arr = array();
					$result = $db->query("SELECT name,type,pls FROM {$tablepre}players WHERE hp>0 AND type>0");
					while($r = $db->fetch_array($result)){
						if (in_array((int)$r['type'], $tip_npctype)) $tip_edata_arr[] = $r;
					}
					if (count($tip_edata_arr) == 0) $enemytip_count = 0;
					else
					{
						$tip_edata = array_randompick($tip_edata_arr, $enemytip_count);
						if (isset($tip_edata['name']))
						{
							$tips[] = "你梦见<span class=\"red b\">{$tip_edata['name']}</span>在<span class=\"yellow b\">{$plsinfo[$tip_edata['pls']]}</span>游荡。<br>……<br>";
							$enemytip_count = 1;
						}
						else
						{
							foreach($tip_edata as $ed) {
								$tips[] = "你梦见<span class=\"red b\">{$ed['name']}</span>在<span class=\"yellow b\">{$plsinfo[$ed['pls']]}</span>游荡。<br>……<br>";
							}
							$enemytip_count = count($tip_edata);
						}
					}
				}
				//特定道具提示
				$itemtip_count = $get_tips_count - $enemytip_count;
				if ($itemtip_count > 0)
				{
					$result = $db->query("SELECT itm,pls FROM {$tablepre}mapitem");
					while($r = $db->fetch_array($result)){
						if (in_array($r['itm'], $tip_itemnamelist)) $tip_mipool[] = $r;
					}
					if (count($tip_mipool) > 0)
					{
						$tip_mi = array_randompick($tip_mipool, $itemtip_count);
						if (isset($tip_mi['itm']))
						{
							$tips[] = "你梦见自己在<span class=\"yellow b\">{$plsinfo[$tip_mi['pls']]}</span>捡到了<span class=\"yellow b\">{$tip_mi['itm']}</span>。<br>……<br>";
						}
						else
						{
							foreach($tip_mi as $mi) {
								$tips[] = "你梦见自己在<span class=\"yellow b\">{$plsinfo[$mi['pls']]}</span>捡到了<span class=\"yellow b\">{$mi['itm']}</span>。<br>……<br>";
							}
						}
					}
				}
			}
			shuffle($tips);
			$log .= implode('',$tips);
		}
	}
	
	function rest_get_beacon($resttime)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		if (!\searchmemory\searchmemory_available()) return;
		
		eval(import_module('skill183'));
		$get_beacon_count = floor($resttime / $rest_get_beacon_rate);
		if (rand(0,99) < $resttime) $get_beacon_count += 1;//奖励骰
		$get_beacon_count = min($get_beacon_count, $rest_get_beacon_max);
		if ($get_beacon_count > 0)
		{
			$flag = 0;
			eval(import_module('sys','player','logger'));
			$log .= "在休息时，你注意观察着四周……<br>";
			//发现敌人并加入临时视野
			if ($pls == 34) $findenemy_count = 0;//英灵殿特判，无法侦查到敌人
			else $findenemy_count = rand(0, $get_beacon_count);
			if ($findenemy_count > 0)
			{
				$edata_arr = \metman\discover_player_get_epids($pls, $pid);
				if (!sizeof($edata_arr)) $findenemy_count = 0;
				else
				{
					shuffle($edata_arr);
					$enamelist = \skill1006\add_beacon_from_edata_arr($edata_arr, $findenemy_count);
					$findenemy_count = count($enamelist);
					if ($findenemy_count > 0)
					{
						if ($findenemy_count > 1) $fe_words = implode('、', array_slice($enamelist, 0, $findenemy_count - 1)).'和'.end($enamelist);
						else $fe_words = $enamelist[0];
						$log .= "你发现了<span class=\"red b\">{$fe_words}</span>的位置。<br>……<br>";
						$flag = 1;
					}
				}
			}
			//发现道具并加入临时视野
			$finditem_count = $get_beacon_count - $findenemy_count;
			if ($finditem_count > 0)
			{
				$mipool = array();
				$result = $db->query("SELECT * FROM {$tablepre}mapitem WHERE pls = '$pls'");
				while($r = $db->fetch_array($result)){
					if(\itemmain\discover_item_filter($r))
						$mipool[] = $r;
				}
				if (sizeof($mipool))
				{
					shuffle($mipool);
					$itmlist = \skill1006\add_beacon_from_itempool($mipool, $finditem_count);
					$finditem_count = count($itmlist);
					if ($finditem_count > 0)
					{
						if ($finditem_count > 1) $fi_words = implode('、', array_slice($itmlist, 0, $finditem_count - 1)).'和'.end($itmlist);
						else $fi_words = $itmlist[0];
						$log .= "你发现了<span class=\"yellow b\">{$fi_words}</span>就在不远处的地方。<br>……<br>";
						$flag = 1;
					}
				}
			}
			if (!$flag) $log .= "但是你什么也没有发现。<br><br>";
		}
	}
	
}

?>