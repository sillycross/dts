<?php

namespace item_slip
{
	$item_slip_npclist = array();//游戏重置时用来储存临时变量的数组
	
	$item_slip_hint = Array(
		'A' => '“除了坐镇<span class="yellow b">无月之影</span>的<span class="red b">红暮</span>和镇守<span class="yellow b">冰封墓场</span>的<span class="cyan b">蓝凝</span>之外，这个世界还有很多危险的敌人在游荡。<br>千万不要随意闯入<span class="red b">SCP研究设施</span>以及<span class="red b">英灵殿</span>。<br>除此之外，如果遇到以<span class="red b">黑幕</span>或者<span class="red b">杏仁豆腐</span>为头衔的NPC，也不要轻易挑衅！”',
		'B' => '“所有玩家都在囚徒困境中。你最大的敌人不是红暮他们，而是其他玩家。<br>“时空特使会为救出所有人而战，但对你而言，面对那些攻击同伴的家伙，<span class="yellow b">不择手段地活下去，是最重要的任务</span>。<br>“如果有余力，再想办法挑战红暮和她的爪牙们吧。”',
		'C' => '“「执行官」并非真身，而是幻影，是红暮的手下扮演的。<br>“红暮把「游戏解除钥匙」分成三份交给手下保管，自己则携带<span class="yellow b">「挑战者之证」</span>亲自压阵。<br>“使用「挑战者之证」就能召出幻影，再缴获那些幻影身上的<span class="yellow b">ID卡</span>，就能合成<span class="yellow b">「游戏解除钥匙」</span>来解放所有参战者了。”',
		'D' => '“我们在这个世界里安置了一个「病毒」。<br>“病毒的触发器以<span class="yellow b">「歌词卡片」</span>作为伪装，藏在三名<span class="yellow b">「数据碎片」</span>身上。<br>这些「数据碎片」的<span class="red b">真正战斗力</span>十分不俗，足以抵抗幻境的自我防御机制，但也让获取触发器变得十分困难。我估计，<span class="yellow b">生命超过900、防御力超过3000</span>并且拥有<span class="yellow b">属性防御</span>的装备<span class="yellow b">和</span>技能，才勉强有资格挑战最无厘头的那位吧……<br>“把三个触发器合并到一起，就能激活病毒。那时，受到病毒感染的幻境代码核心<span class="yellow b">将在这个世界现身</span>。她的战斗力很强，但是并不是无敌的。击倒她获得<span class="yellow b">「十字发卡」</span>之后……<br>“呃，地方写不下了。”',
		'E' => '“纸条「K」，加上这张纸条「E」，就能合成第三张本不存在的纸条。<br>“这就是虚拟幻境的‘合成逻辑’，素材不是靠规律，而是靠联想来转化为产物的。<br>“某种意义上，发明这套体系的人还真是天才呢。”',
		'F' => '“<span class="yellow b">「英灵殿」</span>里徘徊着这个虚拟世界曾经的搭建者们。不过，他们为什么失去了自我、变成了那种半人半代码的样子，仔细想想，恐怖极了。<br>“进入英灵殿的钥匙：<br><span class="lime b">面包 + 矿泉水 + 男生校服/女生校服</span><br>“不过，活着进去容易，要活着出来就得看实力了。”',
		'G' => '“用自己的节奏来打乱敌人，要比被动应付敌人的节奏要有利得多。<br>“如果你有办法<span class="yellow b">提高自己的先手率</span>，那么无论是攻是守，都十分有利。<br>“如果做不到，至少要想办法<span class="yellow b">驱散云雾</span>。能见度提高了，也就不那么容易被偷袭。”',
		'H' => '“我们时空特使还曾派一名爱吃秋刀鱼的特工潜入幻境，结果在她去执行其他任务后，幻境系统反倒复制了她潜入时的作战风格……（苦笑）<br>“我的意思是，<span class="yellow b">「数据碎片-守卫者 静流」</span>的攻击面甚至比红暮还要强，请务必抓住她防御面的弱点迅速击败她，否则被放倒的可能是你！”',
		//N.I.K.O.似有似无的提示
		'I' => '“如果能把遭遇过的NPC的位置记住，在你战斗力成型之后就能很方便地再次找到他，并且反杀之。<br>“俗话说好记性不如烂笔头，你看我就喜欢在纸条上记东西。<br>“你也可以利用一切方便的手段——哪怕是「游戏之外」的手段——来记录你想记录的信息。<br>“毕竟，在这个游戏里我们都是能打破第四墙的人，对吧？”',
		'J' => '“战场上，情报有时就是战斗力。<br>“要多加留意<span class="yellow b">枪声、爆炸声和灵气等频繁传来的位置</span>，那是玩家在行动的迹象。<br>“幻境系统会把死去角色的<span class="red b">遗言及位置</span>公告在<span class="yellow b">聊天讯息</span>里，<span class="yellow b">进行状况</span>也会即时更新玩家与其他角色之间战斗的情况。<br>“若能从瞬息万变的讯息里分析出对方的动向，进而加以反制，无论是求生还是求胜，都会更加轻松吧。”',
		//N.I.K.O.似有似无的提示
		'K' => '“虽然这张纸条「K」本身就是隐藏合成的素材，但幻境里有用的「隐藏合成」并不多，常用合成公式大部分都已列在「帮助页面」里了。<br>“当然，你也可以直接用「合成公式反查」功能来寻找最适合当下的合成发展路线。<br>“不过话说回来，极少数的「隐藏要素」可还是存在于此的。”',
		'L' => '“商店里有一些围绕陷阱的技能书。对，我说的就是<span class="yellow b">《太平要术》和《占星术导论》</span>。<br>“它们一个能增加你施加的陷阱伤害，一个能降低你受到的陷阱伤害，价格公道，又容易被忽略。<br>“和玩家相持不下时，可以用它们配合陷阱来赌一把。”',
		'M' => '“经验之谈：刚开局时，<span class="yellow b">「属性攻击」</span>更容易快速打出伤害，助你迅速击杀「各路党派」NPC杂兵，拿下第一滴血；而到了游戏中后期，常规属性攻击的伤害基本达到了上限，<span class="yellow b">「物理攻击」</span>以及<span class="yellow b">爆炸、灼焰、冰华、音爆等「上位属性」</span>的输出就变得至关重要。<br>“在选择武器和防具时，请务必结合游戏状况来综合考虑！”',
		//N.I.K.O.似有似无的提示
		'N' => '“「广域生命探测器」可以获知其他玩家和NPC的位置。<br>“要追踪玩家，确实要准备电池，并多次探测。但NPC是不会动的，所以每次禁区期间<span class="yellow b">只要探测1次</span>就够了。<br>“当然，不需要你去硬记每个区域的NPC数目，用截图、新开网页之类的「游戏之外」的办法，保留住探测结果就好了。<br>“当然，极少数「隐藏要素」同样需要你用类似的技巧来挖掘。”',
		//N.I.K.O.似有似无的提示
		'O' => '“幻境有一个奇妙的现象：<br>“<span class="yellow b">造成1.5倍伤害的毒物在「同调合成」时，可以当做★1的素材；造成2倍伤害的毒物可以当★2的素材</span>。不过，造成1倍伤害的毒物没有这个效果。<br>“我仿佛能听见妖精使在大喊：‘住手啊，这才不是游戏王！’……<br>“不知道这种反常现象是不是游戏代码有意为之。也许「游戏代码」里还存在别的玄机？”',
		'P' => '“<span class="red b">「红暮」</span>身手不凡，请准备充分再对付她！<br>“我估计，你至少要有<span class="yellow b">超过1000的生命、超过10000的防御力</span>并且拥有<span class="yellow b">属性抹消</span>才能与她一战。而且就算符合了要求，也尽量一击解决战斗，不要给她反击的机会。<br>“至于物理抹消属性……在红暮的枪面前跟纸没有区别。”',
		'Q' => '“时空特使的数据库里找不到<span class="cyan b">「蓝凝」</span>的情报，可能是红杀组织把他们的秘密武器保护得很好。<br>“蓝凝的实力非常惊人，就算你能轻松扛下红暮的攻击，被蓝凝击中就等于死亡。不过，和红暮不同，她用的不是枪，而是某种灵刃。<br>“也许<span class="yellow b">利用射程放风筝</span>，是对付她的好主意……”',
		'R' => '“如果你所选的卡片或者称号带有<span class="yellow b">「学习类技能」</span>，请务必注意<span class="yellow b">所学技能的解锁等级</span>，以免前期浪费技能点学了后期才生效的技能，或者后期才发现错过了能够持续生效的前期技能。”',
		'S' => '“这个世界几乎没有绝对生效的事情。<br>无论是各减半类、抹消类，还是控伤属性，<span class="yellow b">都有各自的失效概率</span>，而且紧要关头，失效就等于死亡。<br>“因此，在防御面差不多的情况下，谁能达到更高的血量、谁能时刻保持满血，谁的胜率就越高。<br>“无论是玩家间自卫反击，还是对抗这个世界的NPC黑幕，这个道理都是一样的。”',
		'T' => '“如果你想打遍「英灵殿」——虽然我觉得，虚拟幻境的受害者应该不会有这么触的想法吧——根据时空特使的观测，你需要准备<span class="yellow b">超过2000的血量</span>、<span class="yellow b">物抹+属抹</span>皆备的防具、<span class="yellow b">至少1项先手技能</span>，而且武器的<span class="yellow b">射程越远越好</span>（最好是不受反击的爆炸物），这样才能有一定的胜算而不是单纯送死。<br>“实际上如果你的抹消属性被打穿，那点血量根本扛不住，2000血量是为你发挥<span class="yellow b">「控伤」</span>装备的最大效力而准备的。”',
		'U' => '“而如果你想大闹「SCP研究设施」——喂喂，你真的是受害者吗？——根据时空特使的观测，你需要准备<span class="yellow b">物抹+属抹+控伤</span>皆备的防具，<span class="yellow b">至少1项先手技能</span>，武器的效果<span class="yellow b">至少千万数量级</span>，最好具备<span class="yellow b">物穿/属穿</span>的属性，这样才有机会在自己的防御被打穿之前，干掉1-2只那种怪物。<br>“至于生命值？拜托，如果你的双抹被打穿了，游戏中能获得的生命值简直杯水车薪啊……”',
		'V' => '',
		'W' => '',
		'X' => '',
		'Y' => '“如果你看到这段文字，说明你成功创造了本来不存在的这第三张纸条。<br>是的，合成产物是凭空创造的，所以它们的作用和特性都和素材的情况完全无关，也就是说你<span class="yellow b">可以用有毒的补给来创造无毒的产物</span>。当然，<span class="red b">也有些产物本来就是有毒的！</span>”',
		'Z' => '“（前面半张纸条被烧毁了）……以下道具：<br><span class="lime b">黑色碎片 + 十字发卡 = 黑色发卡<br>琉璃血 + 武神之魂（另有2个代用品） = 『C.H.A.O.S』</span><br>“早知道我应该带U盘来，这样就不会写不下了。<br>“如果你在商店里找不到别的纸条，那应该是被红暮的手下处理掉了。别担心，我在幻境的各个角落都放置了类似的提示，去寻找它们吧，会对你大有帮助的。”',
		'AA' => '“看来你打败了至少一只「全息幻象」了。<br>它们携带的武器装备有一定强度，能帮助你对抗甚至击杀其他玩家。<br>如果你需要进一步提升实力，需要去寻找并击败<span class="yellow b">「真职人」</span>。”',
		'AB' => '“看来你击败至少一只「真职人」了。<br>除了数值还算不错的防具，它们还能为你提供宝贵的<span class="yellow b">「控噬」</span>属性，让你能继续毫无顾忌地提升战斗力。<br>但如果你的目标是红暮，你还要去寻找并击败<span class="cyan b">「数据碎片」</span>，可不要小瞧了她们的真正实力！”',
		'AC' => '“你连「数据碎片」都击倒了吗？真不简单。<br>用她提供的装备来增强自己的防御面，你就有资格挑战<span class="red b">红暮</span>了。<br>如果你还有余力继续帮助我们，就去收集齐所有三名「数据碎片」身上的「歌词卡片」吧，它们可以合成病毒的触发器「破灭之诗」。<br>既然你都走到这一步了，战斗方面应该能照顾好自己了吧？”<br><br><br>“什么，你问我是怎么把纸条塞在这些NPC身上的？……你猜。”',
	);
	
	//纸条效果：商店卖的纸条没有特殊效果；地上刷的纸条在生成时就决定了随机的效果
	//随机效果由属性决定，规则如下：1-500备用；501-999 META_GAME；1000以上时十万为+万位+千位决定是哪个pid的NPC的位置，个、十、百位是对应所在地点（禁区之后地点全部失效）
	
	//被纸条提示的NPC类型
	$item_slip_npc = array(5, 6, 11, 14 ,45);
	
	$item_slip_metagame_list = array(
		'印着黑色三叶草的卡片' => array('印着黑色三叶草的卡片','VO',1,1,165),
	);
	
	function init() 
	{
	}
	
	function parse_itmuse_desc($n, $k, $e, $s, $sk){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($n, $k, $e, $s, $sk);
		if(('Y' == $k || 'Z' == $k) && strpos($n,'提示纸条') === 0) {
			$ret .= '这是林苍月安放的纸条。除了基础提示之外，似乎还有别的作用。';
		}
		return $ret;
	}
	
	function itemuse(&$theitem)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		
		eval(import_module('sys','player','logger','item_slip'));
		
		$itm=&$theitem['itm']; $itmk=&$theitem['itmk'];
		$itme=&$theitem['itme']; $itms=&$theitem['itms']; $itmsk=&$theitem['itmsk'];
		if (('Y' == $itmk || 'Z' == $itmk) && strpos($itm,'提示纸条') === 0) {
			//纸条本体显示
			$slip_mark = str_replace('提示纸条','',$itm);
			if(!empty($item_slip_hint[$slip_mark])) {
				$log .= '你读着纸条上的内容：<br><br>'.$item_slip_hint[$slip_mark].'<br><br>';
			}else{
				$log .= '你打开了纸条，发现是一张白纸。<br>';
			}
			
			//纸条特殊效果显示
			if(!empty($itmsk && is_numeric($itmsk))){
				if($itmsk >= 500 && $itmsk <= 1000){//提示并参与随机合成
					$log .= '除此之外，纸条上有一句意味深长的话：<br>“有的提示在字里，有的提示在行间，有的提示甚至在游戏之外。”后面是一大段空白。<br><br>';
					$log .= '<!--'.gurl().'gamedata/cache/'.$itmsk.'.htm-->';
					$log .= '<br><br>这是什么意思呢？<br><br>';
				}elseif($itmsk > 1000){//提示NPC位置
					$nid = floor($itmsk / 1000);
					$npls = $itmsk % 1000;
					$result = $db->query("SELECT * FROM {$tablepre}players WHERE pid='$nid' AND type>0");
					if($db->num_rows($result)){
						eval(import_module('npc','map'));
						$edata = $db->fetch_array($result);
						$log .= '除此之外，纸条上还草草写着……<br>“<span class="yellow b">「'.$npc_typeinfo[$edata['type']].'」'.$edata['name'].'</span>在游戏开始时位于<span class="yellow b">'.$plsinfo[$npls].'</span>。”<br><br>';
						if(\gameflow_duel\is_gamestate_duel()){
							$log .= '不过，游戏已经进入了死斗阶段。<span class="yellow b">'.$edata['name'].'肯定已经不在那里了。</span><br><br>';
						}
						elseif($edata['pls'] != $npls) {
							$log .= '不过，游戏已经过去一段时间了。<span class="yellow b">你怀疑'.$edata['name'].'现在已经不在那里了。</span><br><br>';
						}
					}else{
						$log .= '纸条上还有一些字迹被擦掉了。';
					}
				}
			}
			return;
		}
		$chprocess($theitem);
	}
	
	//纸条的属性处理
	function mapitem_single_data_process($iname, $ikind, $ieff, $ista, $iskind, $imap){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		list($iname, $ikind, $ieff, $ista, $iskind, $imap) = $chprocess($iname, $ikind, $ieff, $ista, $iskind, $imap);
		if(strpos($iname,'提示纸条')===0){
			eval(import_module('sys','item_slip'));
			$dice = rand(0,99);
			if(in_array(substr($iname, strlen($iname)-1), Array('N','I','K','O')) && $dice < 20){//20%概率给meta-game提示
				$metaid = item_slip_set_puzzle($iname);
				list($istuff,$iresult) = item_slip_get_puzzle($gamevars['metagame']);
				if(in_array($iname, $istuff)) {
					$iskind = $metaid;//保证提示只会写在有参与合成的纸条上，不然有可能要找4张纸条……
				}
			}elseif($dice < 50){//纸条50%概率有提示
				//目前只提示NPC
				$updateflag = 0;
				$islip_gameprefix = empty($groomid) ? $gamenum : 's_'.$gamenum;//每局都应该是不同的前缀，防止穿透读取大房间
				if(empty($item_slip_npclist)) {
					$item_slip_npclist = Array();
					$updateflag = 1;
				}elseif(empty($item_slip_npclist[$islip_gameprefix])){
					$updateflag = 1;
				}
				
				if($updateflag){//如果没拉取过NPC资料，则一次性拉取并储存
					
					$item_slip_npclist[$islip_gameprefix] = Array();
					$where = "'".implode("','",$item_slip_npc)."'";
					$result = $db->query("SELECT pid, type, pls FROM {$tablepre}players WHERE type IN ($where)");
					while($nd = $db->fetch_array($result)) {
						$item_slip_npclist[$islip_gameprefix][$nd['pid']] = $nd;
					}
				}
				
				$isnl = & $item_slip_npclist[$islip_gameprefix];
				if(!empty($isnl)){
					$nlist0 = array_randompick(array_keys($isnl));
					$iskind = $nlist0 * 1000 + $isnl[$nlist0]['pls'];
				}else{
					$iskind = '';
				}
			}else{
				$iskind = '';
			}
		}
		return array($iname, $ikind, $ieff, $ista, $iskind, $imap);
	}
	
	//META大法好
	function item_slip_set_puzzle($itm){
		if (eval(__MAGIC__)) return $___RET_VALUE; 
		eval(import_module('sys'));
		if(empty($gamevars['metagame'])) {
			//返回一个随机数字，并生成以这个数字命名的文本文件
			do {
				$ret = rand(501,999);
				$file = GAME_ROOT.'./gamedata/cache/'.$ret.'.htm';
			} while(file_exists($file));
			//内容是合成
			$arr1 = array('提示纸条N');
			$arr2 = array('提示纸条I', '★I-力场★');
			$arr3 = array('提示纸条K', '原型武器K');
			$arr4 = array('提示纸条O', '『Oathkeeper』', '『Oblivion』');
			$stuff = array();
			foreach(array($arr1,$arr2,$arr3,$arr4) as $v){
				if(in_array($itm, $v)) {
					$nowv = $itm;
				}else{
					$nowv = array_randompick($v);
				}
				$stuff[] = $nowv;
			}
			$cont = implode('+',$stuff).'=印着黑色三叶草的卡片';
			$cont_html = '<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>'.$cont.'</body>';
			writeover($file, $cont_html);
			chmod($file,0777);
			//记录一下生成的文件，避免重复生成，游戏结束时删除
			$gamevars['metagame'] = $ret;
			$gamevars['metagame_mixinfo'] = $cont;
			\sys\save_gameinfo();
		}else{
			$ret = $gamevars['metagame'];
		}
		return $ret;
	}
	
	//meta特殊合成
	//现在改到get_mixinfo()这里
	function get_mixinfo()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess();
		eval(import_module('sys'));
		if(!empty($gamevars['metagame'])) {//如果有metagame数据，则追加一项合成
			eval(import_module('item_slip'));
			list($stuff,$result) = item_slip_get_puzzle($gamevars['metagame']);
			if(isset($item_slip_metagame_list[$result])) {
				$ret['metagame'] = array('class' => 'hidden', 'stuff' => $stuff, 'result' => $item_slip_metagame_list[$result]);
			}
		}
		return $ret;
	}
	
//	function itemmix_recipe_check($mixitem){
//		if (eval(__MAGIC__)) return $___RET_VALUE;
//		eval(import_module('sys','itemmix','item_slip'));
//		if(count($mixitem) >= 2){	
//			if(!empty($gamevars['metagame'])) {//如果有metagame数据，则追加一项合成
//				list($stuff,$result) = item_slip_get_puzzle($gamevars['metagame']);
//				if(isset($item_slip_metagame_list[$result]) && empty($mixinfo['metagame'])) {
//					$mixinfo['metagame'] = array('class' => 'hidden', 'stuff' => $stuff, 'result' => $item_slip_metagame_list[$result]);
//				}
//			}
//		}
//		return $chprocess($mixitem);
//	}
	
	function item_slip_get_puzzle($puzzleid){
		if (eval(__MAGIC__)) return $___RET_VALUE;
		eval(import_module('sys'));
		$ret = array();
		if(!empty($gamevars['metagame']) && $puzzleid == $gamevars['metagame']) {
			$cont = $gamevars['metagame_mixinfo'];
			//$file = GAME_ROOT.'./gamedata/cache/'.$gamevars['metagame'].'.txt';
			//$cont = file_get_contents($file);
			list($stuff0, $result) = explode('=',$cont);
			$stuff = explode('+',$stuff0);
			$ret[0] = $stuff;
			$ret[1] = $result;
		}
		return $ret;
	}
	
	//游戏结束时删除生成的metagame提示
	function post_gameover_events()
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$chprocess();
		eval(import_module('sys'));
		if(!empty($gamevars['metagame'])) {
			$file = GAME_ROOT.'./gamedata/cache/'.$gamevars['metagame'].'.htm';
			if(file_exists($file)) unlink($file);
		}
//		logmicrotime('清除纸条和猫的提示');
	}
	
	//某些特定模式给NPC进化功能加上纸条
	function evonpc_npcdata_process($enpc, $xtype, $xname)
	{
		if (eval(__MAGIC__)) return $___RET_VALUE;
		$ret = $chprocess($enpc, $xtype, $xname);
		eval(import_module('sys'));
		if(in_array($gametype, Array(0, 4, 6, 15, 16, 18))){//目前实装纸条的模式：标准、随机、卡片、PVE、伐木、荣耀
			if('战斗模式 梦美' == $ret['name'] || '本气（？） 叶留佳' == $ret['name']) {
				$ret['itm6'] = '提示纸条AC';
				$ret['itmk6'] = 'Y';
				$ret['itme6'] = 1;
				$ret['itms6'] = 1;
				$ret['itmsk6'] = '';
			}elseif('守卫者 静流' == $ret['name']) {
				$ret['itmsk0'] .= '^res_<:comp_itmsk:>{提示纸条AC_Y_1_1__}1^rtype1';
			}
		}
		return $ret;
	}
}
?>