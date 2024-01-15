<?php

namespace skill721
{
	$skill721_cooltime = 4000;
	
	function init() 
	{
		define('MOD_SKILL721_INFO','card;');
		eval(import_module('clubbase'));
		$clubskillname[721] = '电动';
	}
	
	function acquire721(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function lost721(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
	}
	
	function check_unlocked721(&$pa)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		return 1;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;	
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		
		if ($itm == '笔记本电脑' && \skillbase\skill_query(721))
		{
			eval(import_module('logger'));
			if ($itme <= 0)
			{
				if ($itms > 1)
				{
					$log .= "你换了一台新的电脑。<br>";
					$itme = 1;
					$itms -= 1;
				}
				else
				{
					$log .= "<span class=\"yellow b\">$itm</span>没电了，需要换电池了。<br>";
					return;
				}
			}
			else $itme -= 1;
			skill721_simulation();
			return;
		}
		$chprocess($theitem);
	}

	function skill721_simulation()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger','cooldown','skill721'));
		$con = rand(20,60);
		$pow = rand(20,60);
		$dex = rand(20,60);
		$wis = rand(20,60);
		$swep = '拳头';
		$flag = 0;//用于判断模拟游戏的结局事件
		$expsum = 0;
		$i = 0;
		$log .= "你开始用<span class=\"yellow b\">笔记本电脑</span>打电动……<br><br>";
		$limit = rand(30,50);
		while ($i <= $limit)
		{			
			$i += 1;
			$log .= '<span id="autopower'.$i.'" style="display:none">';
			if ($i == 1) $log .= "<span class=\"yellow b\">ACFUN大逃杀，启动！</span><br>";
			$result = simulation_event($con, $pow, $dex, $wis, $swep, $flag, $expsum);
			if (0 === $result)
			{
				$log .= "<br><span class=\"yellow b\">哎呀，寄了！你意犹未尽地放下了手中的电脑。</span><br>";
				break;
			}
			elseif (2 === $result)
			{
				$log .= "<br><span class=\"yellow b\">什么，原来这游戏是能通关的吗？你意犹未尽地放下了手中的电脑。</span><br>";
				break;
			}
			elseif ($i >= $limit)
			{
				$rageup = \rage\get_rage(30);
				$log .= "<br>笔记本电脑的屏幕一黑——怎么会这样？";
				if ($rageup) $log .= "你的怒气上升了<span class=\"yellow b\">{$rageup}</span>点。";
				$log .= "<br>";
				break;
			}
			else $log .= '</span>';
		}
		$log .= '<span id="autopower_curnum" style="display:none">1</span>';
		$log .= '<span id="autopower_totnum" style="display:none">'.$i.'</span>';
		$log .= '<span id="autopower_cd" style="display:none">'.$skill721_cooltime.'</span>';
		if ($expsum)
		{
			$log .= "大逃杀真是太好玩啦！你感觉颇有收获。<br>";
			\lvlctl\getexp($expsum,$sdata);
		}
		\cooldown\set_coldtime($skill721_cooltime * $i, true);
	}

	function simulation_event(&$con, &$pow, &$dex, &$wis, &$swep, &$flag, &$expsum)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('player','logger'));
		$result = 1;
		$dice = rand(1,75);
		//道具或战斗
		if ($dice == 1)
		{
			$log .= "你在一所高校找到了一些投掷物的材料。<br>";
			if ($wis > 30)
			{
				$swep = array_randompick(array('装弹枪管柠檬龙 ★8','暗黑俯冲轰炸鸡 ★7','No1919.绝望皇 雷普 ☆4','「开放世界的〇神」LINK-5'));
				$pow += 15;
				$log .= "你将它们组装成了<span class=\"yellow b\">{$swep}</span>。<br>";
			}
			else
			{
				$log .= "但是你根本不知道这些材料该怎么用，只能随便捡了一个能用的武器离开了。<br>";			
				$swep = array_randompick(array('狮子男巫 ★5','幻兽机 同温层仓鼠 ★3','小白兔 ★1','增殖的G ★2'));
				$pow += 3;
				$log .= "你获得了<span class=\"yellow b\">{$swep}</span>。<br>";
			}
		}
		elseif ($dice == 2)
		{
			$log .= "你在一片废墟中找到了一些能用的武器。<br>";
			$swep = array_randompick(array('★铁索连环★','大钉棍棒','不定时炸弹'));
			$pow += 8;
			$log .= "你获得了<span class=\"yellow b\">{$swep}</span>！<br>";
		}
		elseif ($dice == 3)
		{
			$log .= "你在一处小屋中找到了一位魔法师的笔记和他的武器。<br>";
			$swep = array_randompick(array('☼罕见的王国制狙击步枪☼','燃烧着的回响型乌鸡肉','☼火热的根须掌心雷☼','梦见门之钥的杂糅弹药包'));		
			$pow += 11;
			$log .= "你获得了<span class=\"yellow b\">{$swep}</span>！<br>";
			if (rand(0,1))
			{
				$log .= "阅读完笔记后，你感觉自己变得更睿智了。<br>";
				$wis += 5;
			}
		}
		elseif ($dice == 4)
		{
			$log .= "你来到了一片被冰封的战场！地面上的武器看起来还能使用。<br>";
			$swep = array_randompick(array('『游戏解除钥匙』','『火之高兴』','『黄鸡刀』','『午夜赶稿』'));
			$pow += 9;
			$log .= "你获得了<span class=\"yellow b\">{$swep}</span>！<br>";
		}
		elseif ($dice == 5)
		{
			$log .= "你找到了一台自动售货机。<br>";
			if (($expsum > 20) && ($swep != '拳头'))
			{
				$log .= "你购买了改造道具强化了你的武器。";
				if (strpos($swep, '-改') === false) $swep = $swep.'-改';
				$log .= "你的武器升级成了<span class=\"yellow b\">{$swep}</span>！<br>";
				$pow = (int)$pow*1.2;
			}
			else
			{
				$log .= "好像也没啥能买的。走了走了！<br>";
			}
		}
		elseif ($dice == 6)
		{
			$log .= "你通过一个传送门，来到了一处停机坪。地上好像有一些还能用的枪械……<br>";
			$swep = array_randompick(array('『圆形激光』','『方形激光』','『三角形激光』','『菱形激光』','『六边形激光』','『星形激光』'));
			$pow += 8;
			$log .= "你获得了<span class=\"yellow b\">{$swep}</span>！<br>";
			if ($dex < 40)
			{
				$log .= '离开的时候，你被空中的实弹演习的流弹击中了！还好伤的不重，你一瘸一拐地撤出了这里。<br>';
				$con -= 20;
			}
		}
		elseif ($dice == 7)
		{
			$log .= "你找到了一台自动售货机。<br>";
			if (($expsum > 40) && ($swep != '拳头'))
			{
				$log .= "你购买了一堆宝石用来强化你的武器。";
				if (rand(0,3))
				{
					$swep = $swep.'[+7]';
					$log .= "你的武器升级成了<span class=\"yellow b\">{$swep}</span>！<br>";
					$pow = (int)$pow*1.8;
				}
				else
				{
					$swep = '一块废铁';
					$log .= "但是强化失败了！你的武器变成了<span class=\"yellow b\">{$swep}</span>！<br>";
					$pow = 20;
				}
			}
			else
			{
				$log .= "好像也没啥能买的。走了走了！<br>";
			}
		}
		elseif ($dice == 8)
		{
			$log .= "你在一个大水池里收集了一些饮用水。<br>";
			$con += 8;
		}
		elseif ($dice == 9)
		{
			$log .= "你找到了一些他人遗留的书籍。<br>";
			$wis += 10;
		}
		elseif ($dice == 10)
		{
			$log .= "你找到了一些写着奇怪文字的纸条。<br>";
			if ($wis > 40)
			{
				$log .= "你发现上面写着一些关键敌人的位置和信息。<br>";
				$wis += 15;
			}
			else $log .= "但是你完全看不懂上面写了啥。<br>";
		}
		elseif ($dice == 11)
		{
			$log .= "你找到了一些还能穿的衣物。<br>";
			$con += 15;
		}
		elseif ($dice == 12)
		{
			$log .= "你找到了一个耐久为<span class=\"yellow b\">4</span>的月饼。<br>";
			if ($wis > 40)
			{
				$log .= "你发现它已经被咬了一口，还是不吃为妙。<br>";
				$wis += 5;
			}
			else
			{
				$log .= "你没有多想便啃了一口，然后中毒了！虽然最后还是找到了解毒药，但你感觉已经丢了半条小命。<br>";
				$con -= 10;
			}
		}
		elseif ($dice == 13)
		{
			$log .= "你找到了一根绿色的丝带。<br>";
			$con += 5;
		}
		elseif ($dice == 14)
		{
			$log .= "你找到了一个椅子。在再三确认它没法坐之后，你扫兴地离开了。<br>";
		}
		elseif ($dice == 15)
		{
			$log .= "你找到了一个柜子，但是并不能打开。像是有什么东西把门粘上了。<br>";
		}
		elseif ($dice == 16)
		{
			if ($swep != '拳头')
			{
				$v = array_randompick(array('鞭炮','拖把','折凳','电锯','黄鸡炸弹'));
				$log .= "你找到了一个{$v}，并将它和你的武器绑在了一起。<br>";
				$swep = '捆着'.$v.'的'.$swep;
				$log .= "你的武器变成了<span class=\"yellow b\">{$swep}</span>！<br>";
			}	
			else
			{
				$log .= "你在捡垃圾的过程中锻炼了你的身体。<br>";
				$con += 10;
				$dex += 10;
			}
		}
		elseif ($dice == 17)
		{
			$log .= "你尝试用打火机制造一些爆炸物。<br>";
			if ($wis > 40)
			{
				$swep = '黄鸡炸弹';
				$log .= "你造出了<span class=\"yellow b\">{$swep}</span>！<br>";
				$pow += 10;
			}				
			else
			{
				$log .= "但是制造失败了！你还不小心点着了自己的手。<br>";
				$con -= 10;
			}
		}
		elseif ($dice == 18)
		{
			$log .= "你找到了一根梆硬的面包。<br>";
			if ($pow < 60)
			{
				$swep = '长棍面包';
				$log .= "你拿起它挥动了几下。感觉不错！不如就用这个当武器吧！<br>";
				$pow += 60;
			}
			else
			{
				$log .= "这个应该可以当备用粮。<br>";
				$con += 10;
			}
		}
		elseif ($dice == 19)
		{
			$log .= "你找到了一把看起来很厉害的大剑。<br>";
			if ($pow > 80)
			{
				$swep = '黄鸡大剑';
				$log .= "还好你平时没有疏于锻炼，一下子就把它提了起来。你获得了<span class=\"yellow b\">{$swep}</span>！";
				$pow += 70;
			}
			else
			{
				$log .= "但是这玩意太重了，你拿不起来。<br>";
			}
		}
		elseif ($dice == 20)
		{
			$swep = '我剥光了小马才做出来的【彩虹光束炮】';
			$log .= "你找到了一把看起来很厉害的枪械。是谁把它丢在这里的？<br>你获得了<span class=\"yellow b\">{$swep}</span>！<br>";
			$pow += 50;
		}
		elseif ($dice == 21)
		{
			$log .= "你找到了一堆明显有人翻过的技能书和一些奇怪的液体。<br>这……可太厉害了！<br>你感觉大有收获。<br>";
			$wis += 50;
			$con -= 20;
		}
		elseif ($dice == 22)
		{
			$log .= "你找到了一个很厉害的陷阱，坏消息是你是用脚找到的。<br>";
			$con -= 30;
			if ($con < 10) $result = 0;
		}
		elseif ($dice == 23)
		{
			$log .= "你在森林里找到了一双耐用的鞋子。为什么衣服会出现在森林里呢？<br>";
			$con += 10;
			$dex += 15;
		}
		elseif ($dice == 24)
		{
			$log .= "你在森林里采摘了一些水果。<br>";
			$con += 5;
		}
		elseif ($dice == 25)
		{
			$log .= "你在自动售货机里买了一些应该还算新鲜的面包。<br>";
			$con += 10;
		}
		elseif ($dice == 26)
		{
			if ($swep == '拳头')
			{
				$swep = '☆地碎☆';
				$log .= "你找到了一双看起来很厉害的拳套。你获得了<span class=\"yellow b\">{$swep}</span>！<br>";
				$pow += 20;
			}
			else
			{
				$log .= "你抽空锻炼了躲避追杀的技巧。<br>";
				$dex += 10;
			}
		}
		elseif ($dice == 27)
		{
			$swep = '黑铁球';
			$log .= "你找到了一本写着什么收服特训的笔记。总之就是需要用什么球去砸吧？你找来了一个<span class=\"yellow b\">{$swep}</span>。<br>";
			$pow += 50;
			$dex -= 30;
		}
		elseif ($dice == 28)
		{
			$log .= "毒也要吃，营养也要吃，这样才能称得上健全！你猛吃毒酱包，感觉身体变得更结实了（确信）。<br>";
			$con += 50;
			$pow += 5;
		}
		elseif ($dice == 29)
		{
			$log .= "你找到了一堆被冻住的青蛙。为什么要这么做？你想了半天也没能明白。<br>";
			$wis = 9;
		}
		elseif ($dice == 30)
		{
			$log .= "你找到了一个录音机，然后电脑的音量突然被调到最大并开始自己放起了音乐！<br>你感觉突然很想跟着唱歌。<br>";
			$mss += 20;
			$ss += 20;
		}
		elseif ($dice == 31)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 32)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 33)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 34)
		{
			$log .= '你轻车熟路地收拾了一些杂兵。<br>';
			$expsum += 10;
		}
		elseif ($dice == 35)
		{
			$log .= '你轻车熟路地收拾了一些杂兵。<br>';
			$expsum += 10;
		}
		elseif ($dice == 36)
		{
			$log .= '你轻车熟路地收拾了一些杂兵。<br>';
			$expsum += 10;
		}
		elseif ($dice == 37)
		{
			$log .= "你看到了一个穿着很二次元的NPC！<br>";
			if ($pow > 40)
			{
				$log .= "对方不是你的对手，你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 10;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 38)
		{
			$log .= "你看到了一个穿着很二次元的NPC！<br>";
			if ($pow > 40)
			{
				$log .= "对方不是你的对手，你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 10;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 39)
		{
			$log .= "你看到了一个穿着很二次元的NPC！<br>";
			if ($pow > 40)
			{
				$log .= "对方不是你的对手，你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 10;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 40)
		{
			if ($pow > 40)
			{
				$log .= '你轻车熟路地收拾了一些打得过的NPC。<br>';
				$expsum += 10;
			}
			else
			{
				$log .= '你被不断涌来的NPC打倒了……<br>';
				$result = 0;
			}
		}
		elseif ($dice == 41)
		{
			$log .= '你看到一个黑白画风的怪人正在闲逛！<br>';
			if ($dex > 80) $log .= '你避开了对方的视线，小心地离开了。<br>';
			elseif ($pow > 80)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>把他打跑了！对方逃得匆忙，掉下来一些钱和合成材料。<br>";
				$expsum += 30;
				$pow += 30;
			}
			else
			{
				$log .= '对方提前发现了你，并向你抛来了一堆炸弹！你感觉自己不是对手，赶紧逃走了。<br>';
				$con -= 30;
			}
		}
		elseif ($dice == 42)
		{
			$log .= '你看到一个拿着二胡的怪人正在闲逛！<br>';
			if ($dex > 80) $log .= '你避开了对方的视线，小心地离开了。<br>';
			elseif ($con > 80)
			{
				$log .= '你在逃离的过程中踩到了奇怪的陷阱！还好你身体结实，但还是受了重伤。<br>';
				$con -= 30;
			}
			else
			{
				$log .= '你在逃离的过程中踩到了奇怪的陷阱！画面逐渐变成一片漆黑……<br>';
				$result = 0;
			}
		}
		elseif ($dice == 43)
		{
			$log .= '一个戴着兜帽拿着枪械的家伙突然朝你袭来，并且向你射击！<br>';
			if ($con > 80)
			{
				$con -= 30;
				if ($pow > 100)
				{
					$log .= '你撑过了攻击，然后迎上前去用<span class=\"yellow b\">{$swep}</span>猛击他！<br>几回合后，对方倒在了你的身前。<br>你从对方身上获得了大量的资源。<br>';
					$expsum += 70;
					$pow += 30;
					$con += 30;
				}
				else
				{
					$log .= '你撑过了攻击，然后迎上前去用<span class=\"yellow b\">{$swep}</span>猛击他！<br>然而几回合后，你后继乏力，被对方打出的无数弹幕淹没了。<br>';
				}
			}
			else
			{
				$log .= '你被对方发射出的无数弹幕淹没了……<br>';
				$result = 0;
			}
		}
		elseif ($dice == 44)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 45)
		{
			$log .= "一个奇装异服的玩家突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 7;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 46)
		{
			$log .= "一个奇装异服的玩家突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>地战胜了他。<br>然后你在对方的身上找到了一些钱和补给。<br>";
				$expsum += 9;
				$con += 9;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 47)
		{
			$rageup = \rage\get_rage(30);
			$log .= "你发现了一个看起来很扛揍的家伙！<br>在数十次攻击之后，你发现根本没法破防，然后忿忿地离开了。<br>";
			if ($rageup) $log .= "你的怒气上升了<span class=\"yellow b\">{$rageup}</span>点。<br>";
		}
		elseif ($dice == 48)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 49)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 50)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 51)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 52)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 53)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 54)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 55)
		{
			if (($swep != '拳头') && ($wis > 30))
			{
				$log .= "你找来了一些肥料，并用它加工了你的武器。<br>";
				if (strpos($swep, '淬毒的') === false) $swep = '淬毒的'.$swep;
				$log .= "你的武器变成了<span class=\"yellow b\">{$swep}</span>！<br>";
				$pow += 12;
			}	
			else
			{
				$log .= "你在捡垃圾的过程中锻炼了你的身体。<br>";
				$con += 10;
				$dex += 10;
			}
		}
		elseif ($dice == 56)
		{
			if (($swep != '拳头') && ($wis > 50))
			{
				$log .= "你找来了几节电池，并用它魔改了你的武器。<br>";
				if (strpos($swep, '带电的') === false) $swep = '带电的'.$swep;
				$log .= "你的武器变成了<span class=\"yellow b\">{$swep}</span>！<br>";
				$pow += 12;
			}	
			else
			{
				$log .= "你在捡垃圾的过程中锻炼了你的身体。<br>";
				$con += 10;
				$dex += 10;
			}
		}
		elseif ($dice == 57)
		{
			if ($swep != '拳头')
			{
				$log .= "你找来了一个打火机，并用它点着了你的武器。<br>";
				if (strpos($swep, '燃烧着的') === false) $swep = '燃烧着的'.$swep;
				$log .= "你的武器变成了<span class=\"yellow b\">{$swep}</span>！<br>";
				$pow += 15;
			}	
			else
			{
				$log .= "你在捡垃圾的过程中锻炼了你的身体。<br>";
				$con += 10;
				$dex += 10;
			}
		}
		elseif ($dice == 58)
		{
			if ($swep != '拳头')
			{
				$log .= "你找来了一些方块，并用它们强化了你的武器。<br>";
				if (strpos($swep, '五颜六色的') === false) $swep = '五颜六色的'.$swep;
				$log .= "你的武器变成了<span class=\"yellow b\">{$swep}</span>！<br>";
				$pow += 7;
			}	
			else
			{
				$log .= "你在捡垃圾的过程中锻炼了你的身体。<br>";
				$con += 10;
				$dex += 10;
			}
		}
		elseif ($dice == 59)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 60)
		{
			$log .= "一个杂兵突然向你袭来！<br>";
			if ($pow > 25)
			{
				$log .= "你用<span class=\"yellow b\">{$swep}</span>轻松地战胜了他。<br>";
				$expsum += 5;
			}
			else
			{
				$log .= '他三下五除二把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 61)
		{
			$log .= "一个兄贵突然向你袭来！<br>";
			if ($pow > 50)
			{
				$log .= "你经历了一番苦战，最终用<span class=\"yellow b\">{$swep}</span>战胜了他。<br>";
				$expsum += 10;
			}
			else
			{
				$log .= '他用你不认识的武器把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 62)
		{
			$log .= "一个兄贵突然向你袭来！<br>";
			if ($pow > 50)
			{
				$log .= "你经历了一番苦战，最终用<span class=\"yellow b\">{$swep}</span>战胜了他。<br>";
				$expsum += 10;
			}
			else
			{
				$log .= '他用你不认识的武器把你解决了！<br>';
				$result = 0;
			}
		}
		elseif ($dice == 63)
		{
			$log .= "你遇到了一位看起来人畜无害的少女。你思索了一番，决定不去招惹对方。<br>";
		}
		elseif ($dice == 64)
		{
			$log .= "你发现了另一位熟练的玩家。<br>";
			if ($wis > 80)
			{
				$log .= "你设下了陷阱，并用<span class=\"yellow b\">{$swep}</span>在不远处制造出动静吸引了对方的注意。<br>几分钟后，你听到了那里传来了巨大的爆炸声。<br>";
				$expsum += 20;
			}
			else
			{
				$log .= '你小心地避开了战斗。<br>';
			}
		}
		elseif ($dice == 65)
		{
			$log .= "在不断的战斗中，你的武器似乎有了灵性。<br>";
			if (strpos($swep, '有灵性的') === false) $swep = '有灵性的'.$swep;
			$log .= "你的武器变成了<span class=\"yellow b\">{$swep}</span>！<br>";
			$pow += 10;
		}
		elseif ($dice == 66)
		{
			$log .= "在不断的战斗中，你的武器磨损了。<br>";
			$pow -= 10;
		}
		elseif ($dice == 67)
		{
			$log .= "在不断的战斗中，你的武器磨损了。<br>";
			$pow -= 10;
		}
		elseif ($dice == 68)
		{
			$log .= "在不断的战斗中，你感觉你变得更扛揍了。<br>";
			$con += 20;
		}
		elseif ($dice == 69)
		{
			$log .= "在不断的战斗中，你感觉你的战斗技巧更熟练了。<br>";
			$pow += 20;
		}
		elseif ($dice == 70)
		{
			$log .= "在不断与敌人周旋的过程中，你感觉你的逃跑技巧更熟练了。<br>";
			$dex += 20;
		}		
		//结局相关事件
		elseif ($dice == 71)
		{
			$log .= "你收集了一些电子垃圾，";
			if (($wis > 60) && !$flag)
			{
				$log .= "然后用它们组装成了一台电脑！真是不可思议！<br>";
				if ($wis < 80) $log .= "你开始用它玩ACFUN大逃杀……<br>";
				else
				{
					$log .= "你熟练地黑进了后台，然后解开了那个入口处的禁区。要找的家伙应该就在那里吧？<br>";
					$flag = 1;
				}
			}
			else $log .= "但是好像并没有什么卯月。<br>";
		}
		elseif ($dice == 72)
		{
			if ($flag)
			{
				$log .= "你回到了这片战场的入口处，在这里迎接你的是一个穿着红色盔甲手持大枪的少女。<br>这可能就是最后一战了？<br>";
				if (($pow > 240) && ($con > 240) && ($wis + $dex > 200))
				{
					$log .= "你小心应对着对方的猛烈火力，并巧妙地展开还击。<br>战斗逐渐升温，你和对方的护甲和身躯上都留下了无数的创痕——而最终分出胜负的是你用{$swep}挥出的致命一击。<br>……就这么结束了？敌人的尸体和整个战场一同崩散，化为代码的碎片……<br>";
					$result = 2;
				}
				else
				{
					$log .= "你在对方的手下甚至没有撑过三回合。<br>";
					$result = 0;
				}
			}
			else
			{
				$log .= "你找来了一些罐装食品，然后买来毒药对它们进行了再加工。<br>";
				$wis += 3;
			}
		}
		//其他事件
		elseif ($dice == 73)
		{
			$log .= "在与其他玩家对喷的过程中，你感觉自己的语速和肺活量增加了。<br>你的体力上限增加了<span class=\"yellow b\">20</span>点。<br>";
			$msp += 30;
			$sp += 30;
		}
		elseif ($dice == 74)
		{
			$log .= '你正忙着打游戏，并没有注意到一个穿着熊布偶装的家伙来到你身后！<br>';
			if ($con+$pow+$dex+$wis<300)
			{
				$log .= '他看了一会儿你的捉急操作，顿时怒从心头起！<br>他一掌把你的头摁在了键盘上，然后自顾自地离开了。<br>';
				$result = 0;
			}
			else $log .= '他看了一会儿，然后又自顾自地离开了。<br>';
		}
		else
		{
			$log .= '你看到了一个一身黑衣的少女影子。不知为何，你感觉突然很有开腔的欲望。<br>';
			$mss += 20;
			$ss += 20;
		}
		if ($result > 0) $expsum += 1;
		return $result;
	}

}

?>