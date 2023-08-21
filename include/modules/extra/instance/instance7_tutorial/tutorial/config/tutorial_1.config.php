<?php
if(!defined('IN_GAME')) exit('Access Denied'); 

$tutorial_tough_hp = 50;//被攻击时如果HP大于这个值，则变为这个值
$tutorial_areahour_augment = 22075200;//禁区时间追加值，单位分钟
$tutorial_disable_area_timing = true;//不显示禁区倒计时
$tutorial_disable_combo = true;//阻止连斗
$tutorial_force_teamer = true;//强制认为所有玩家都是队友
$tutorial_allowed_weather = array(0,2);//允许出现的天气

$tutorial_story[1] = Array(
	10 => Array(
		'tips' => '“能听到我说话吗？<br>我是……嗯，我是武侠作家<span class="yellow b">李天明</span>。<br>我在这个虚拟世界取材已经有好几年了，而你好像是个新手。<br>如果你不想在悲惨地死在这里，就好好记住我要说的话。”<br>',
		'object' => 'continue',
		'obj2' => Array(
			'addnpc' => 91,
			'asub' => 0,
		),
		'pulse' => '#continue',
		'next' => 200
	),
	200 => Array(
		'tips' => '“画面上方是你的<span class="yellow b">基本数据</span>；其中的<span class="yellow b">人形状态条</span>代表你的<span class="yellow b">SP和HP</span>。SP过低时你将无法进行一些行动，而HP归零即意味着你的<span class="red b">死亡</span>。”<br>',
		'object' => 'continue',
		'pulse' => '#profile',
		'next' => 300
	),
	300 => Array(
		'tips' => '“画面中下方是你目前的<span class="yellow b">装备</span>和包裹里的<span class="yellow b">道具</span>。它们对你能否在游戏中存活和获胜有重要的意义。”<br>',
		'object' => 'continue',
		'pulse' => '#packs',
		'next' => 400
	),
	400 => Array(
		'tips' => '“画面最下方是<span class="yellow b">聊天框</span>。你可以在这里看到一些游戏状况提示，NPC的遗言，以及跟其他玩家互动交流。”<br>',
		'object' => 'continue',
		'pulse' => '#chat',
		'next' => 500
	),
	500 => Array(
		'tips' => '“而这里是<span class="yellow b">操作框</span>，你会在这里发出大部分指令，并得到指令的反馈。”<br>',
		'object' => 'continue',
		'pulse' => '#cmd',
		'next' => 600
	),
	600 => Array(
		'tips' => '“熟悉了游戏界面，现在让我们迈出第一步。<br><br>点击<span class="yellow b">【移动】</span>下拉列表，然后选择别的区域。”<br>',
		'object' => 'move',
		'obj2' => Array('leave'),
		'pulse' => '#moveto',
		'next' => 700		
	),
	700 => Array(
		'tips' => '“很好。移动需要消耗体力，所以请确保你体力充足。移动之后也可能遇到<span class="yellow b">突发事件</span>甚至<span class="red b">敌人</span>，不过这次没事。<br><br>除了移动，你还可以原地探索。点击<span class="yellow b">【探索】</span>按钮。”<br>',
		'object' => 'search',
		'pulse' => '#zz',
		'next' => 800
	),
	800 => Array(
		'tips' => '“有时候会捡到不需要的道具，那种情况丢掉就好。不过你的腕部正缺防护，<span class="yellow b">拾取</span>这个手套吧。”<br>',
		'object' => 'itemget',
		'obj2' => Array(
			'itm' => '电磁充能手套',
			'itmk' => 'DA',
			'itme' => 45,
			'itms' => 15,
			'itmsk' => 'E'
		),
		'prog' => '“如果你不小心丢弃了物品，可以把<span class="yellow b">视野</span>中的道具捡回来，或者在原地<span class="yellow b">探索</span>也有概率重新捡到。”<br>',
		'pulse' => '#z',
		'next' => 900
	),
	900 => Array(
		'tips' => '“探索是获得有用的装备或道具的重要方式之一。<br>现在请再次点击<span class="yellow b">【探索】</span>按钮。”<br>',
		'object' => 'search',
		'pulse' => '#zz',
		'next' => 1000
	),
	1000 => Array(
		'tips' => '“【泡面】吗，诶……为什么这个地方会有泡面？看起来有点可疑。<span class="yellow b">拾取</span>它吧。”<br>',
		'object' => 'itemget',
		'obj2' => Array(
			'itm' => '泡面',
			'itmk' => 'PB',
			'itme' => 20,
			'itms' => 1,
			'itmsk' => '1'
		),
		'prog' => '“如果你不小心丢弃了物品，可以把<span class="yellow b">视野</span>中的道具捡回来，或者在原地<span class="yellow b">探索</span>也有概率重新捡到。”<br>',
		'pulse' => '#z',
		'next' => 1100
	),
	1100 => Array(
		'tips' => '“食物一般属于【回复道具】，比如这个泡面就是<span class="yellow b">【命体回复】</span>道具，使用后可以吃掉它来同时回复生命值和体力值。<br><br>点击道具名左边的<span class="yellow b">【使用】</span>按钮来使用它吧。”<br>',
		'object' => 'itemuse',
		'obj2' => Array(
			'itm' => '泡面'
		),
		'pulse' => ".itmsingle :contains('泡面') __BUTTON__",
		'prog' => '“这泡面啊，十分地珍贵。不来一口吗？”<br>',
		'next' => 1200
	),
	1200 => Array(
		'tips' => '“噗……对不起，我笑场了，其实是我干的。别担心，只是一点点黑暗料理。<br>探索获得的【回复道具】中，有一部分可能<span class="purple b">【有毒】</span>，胡乱吃下就会倒扣生命值并且<span class="purple b">【中毒】</span>。<br><span class="purple b">【中毒】</span>属于异常状态，需要用对应的药剂进行处理。幸好你随身携带了解毒剂。<br><br>点击<span class="yellow b">【解毒剂】</span>对应的按钮以使用之。<br>除了解毒剂以外，其他异常状态也有对应的药剂，实战时可以捡到，也可以在商店中买到。”<br>',
		'object' => 'itemuse',
		'obj2' => Array(
			'itmk' => Array('Ca','Cp'),
		),
		'pulse' => ".itmsingle :contains('解毒剂') __BUTTON__",
		'prog' => '“如果已经处理完了，请随便执行一次行动。”<br>',
		'next' => 1300
	),
	1300 => Array(
		'tips' => '“除了有毒食物，也有其他的持有负面效果的道具存在，探索时需要谨慎。<br>现在再次点击<span class="yellow b">【探索】</span>按钮。”<br>',
		'object' => 'search',
//		'obj2' => Array(
//			'meetnpc' => 91, 'meetsub' => 0,'active' => 0,'inf' => 'f','ex_inf' => 'p','always_hit' => 1
//		),
		'pulse' => '#zz',
		'next' => 1400
	),
	1400 => Array(
		'tips' => '“哎呀，看来这次运气不好，你在探索时遭遇了一次袭击。”<br>',
		'object' => 'any',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 0,'inf' => 'f','ex_inf' => 'p','always_hit' => 1
		),
		'pulse' => '#z',
		'next' => 1500
	),
	1500 => Array(
		'tips' => '“嘛，在战场上难免遭遇敌人并受伤。如果不及时回复生命，情况就会很危险。<br><br>生命伤害需要使用<span class="yellow b">【生命回复】</span>道具。首先点击<span class="yellow b">【面包】</span>以使用之。”<br>',
		'object' => 'itemuse',
		'obj2' => Array(
			'itmk' => Array('HH','HB'),
		),
		'pulse' => ".itmsingle :contains('面包') __BUTTON__",
		'next' => 1600
	),
	1600 => Array(
		'tips' => '“然后直接点击人形状态条的<span class="red b">【受伤部位】</span>进行<span class="yellow b">包扎</span>。包扎需要消耗体力，请确保你的体力充足。”<br>',
		'object' => 'inff',
		'pulse' => '#inff',
		'prog' => '“如果已经包扎完了，请随便执行一次行动。”<br>',
		'next' => 1700
	),
	1700 => Array(
		'tips' => '“很好，现在你从受伤状态完全恢复了。<br>除了使用道具之外，消耗<span class="lime b">【技能点数】</span>或者<span class="lime b">【静养】</span>一定时间也可以解除异常状态。<br><br>敌人还在当前地点，让我们去还以颜色。点击<span class="yellow b">【探索】</span>来寻找敌人。”<br>',
		'object' => 'search',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 1,'changevar' => Array('hp' => 1),'always_hit' => 1
		),
		'pulse' => '#zz',
		'next' => 1800
	),
	1800 => Array(
		'tips' => '“这次你应该能够先手了。<br>找到敌人的话，就点击具体的<span class="yellow b">【攻击方式】</span>按钮。”<br>',
		'object' => 'kill',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 1,'changevar' => Array('hp' => 1),'always_hit' => 1
		),
		'pulse' => '#z',		
		'prog' => '“错过攻击机会也没有关系，在原地<span class="yellow b">探索</span>仍有机会发现同一个敌人。”<br>',
		'next' => 1900
	),
	1900 => Array(
		'tips' => '“不错，你干掉了这个陪练对象。<br>击杀敌方后，可以<span class="yellow b">捡取</span>敌方尸体上的<span class="yellow b">装备、道具或者金钱</span>。1次只能捡取<span class="yellow b">1件</span>东西，其他东西则需要再次找到尸体才能捡。<br><br>请选择<span class="yellow b">【金钱（具体钱数）】</span>然后<span class="yellow b">【提交】</span>。”<br>',
		'object' => 'money',
		'pulse' => '#amoney',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'corpse' => 1
		),
		'prog' => '“拿了别的东西？没关系，在原地<span class="yellow b">探索</span>仍有机会发现那具尸体。<br>不过，游戏进入<span class="red b">【连斗】</span>阶段或者其他玩家将尸体销毁后，就没法再发现尸体了。”<br>',
		'next' => 2000
	),
	2000 => Array(
		'tips' => '“获得金钱后，可以去<span class="yellow b">【商店】</span>添置装备道具。<br>商店并非哪里都有，它只位于特定的两个地点。请先<span class="yellow b">【移动】</span>到<span class="lime b">【光坂高校】</span>或者<span class="lime b">【花菱商厦】</span>。”<br>',
		'object' => 'move',
		'obj2' => Array('shop'),
		'pulse' => Array('#moveto',"option:contains('光坂高校')","option:contains('花菱商厦')"),
		'next' => 2100		
	),
	2100 => Array(
		'tips' => '“然后点击<span class="yellow b">【商店】</span>按钮。”<br>',
		'object' => 'sp_shop',
		'pulse' => '#c',
		'next' => 2200		
	),
	2200 => Array(
		'tips' => '“之后点击<span class="yellow b">【锐器】</span>按钮。”<br>',
		'object' => 'shop4',
		'pulse' => Array('#bshop4'),
		'next' => 2300		
	),
	2300 => Array(
		'tips' => '“之后选择<span class="red b">【红杀铁剑】</span>并购买吧。”<br>',
		'object' => 'itembuy',
		'obj2' => Array(
			'item' => '【红杀铁剑】',
		),
		'pulse' => Array('#buy_f1b3f633'),
		'prog' => Array(
			'money<1300' => '“钱不够了？在<span class="yellow b">天使队移动格纳库</span>能找到我留下的一些盘缠。”<br>',
			'“不要乱买东西，后面还需要用到钱的。”',//注意如果要修改编号，得把事件相关给改了
		),
		'next' => 2400	
	),
	2400 => Array(
		'tips' => '“这把剑显然比你的初始武器好多了。<br><span class="lime b">商店购买</span>、<span class="lime b">地图探索</span>，或者拾取<span class="lime b">战利品</span>，都有可能给你带来更好的武器，如何尽快获得高级武器是一门学问。”<br>',
		'object' => 'continue',
		'obj2' => Array(
			'addchat' => Array(
				'type' => 'IMMEDIATLY',
				'cont' => array(
					Array(
						'type' => 5,
						'cname' => '',
						'crecv' => 'pid',
						'ccont' => '警告，以下区域即将成为禁区：pls'
					),
				)
			),
		),
		'pulse' => '#continue',
		'next' => 2500
	),
	2500 => Array(
		'tips' => '“<span class="yellow b">注意！</span><br><span class="lime b">聊天框</span>提示，你所在的位置马上就要成为禁区了。如果禁区时间到时，你没有离开禁区所在地点，那么你会被<span class="red b">【禁区杀】</span>。<br><br>马上点击<span class="yellow b">【移动】</span>并<span class="yellow b">离开这个区域</span>！”<br>',
		'object' => 'move',
		'obj2' => Array(
			'leave',
			'addchat' => Array(
				'type' => 'WHEN_DONE',
				'cont' => array(
					Array(
						'type' => 5,
						'cname' => '',
						'crecv' => 'pid',
						'ccont' => '游戏进入连斗阶段！'
					),
					Array(
						'type' => 5,
						'cname' => '',
						'crecv' => 'pid',
						'ccont' => '增加禁区：o_pls'
					)
				)
			),
		),
		'prog' => '“不要磨蹭，立刻离开这个区域！”<br>',
		'pulse' => Array('#moveto','#chat'),
		'setcountdown' => 60,
		'next' => 2600
	),	
	2600 => Array(
		'tips' => '“<:sub1:>此外，现在游戏进入了<span class="yellow b">【连斗阶段】</span>。<br><span class="yellow b">【连斗】</span>之后，还存活的玩家必须厮杀到<span class="yellow b">只剩1人幸存</span>为止，而那个幸存者将获得这场大逃杀的胜利。”<br>',
		'tips_sub' => Array(
			'<:sub1:>' => Array(
				'cmdintv>0' => '你及时躲过了禁区，干得好。<br>',
				'cmdintv<=0' => '动作太慢了。如果真正在幻境里厮杀，此刻你已经被<span class="red b">禁区杀</span>了。<br>'
			)
		),
		'object' => 'continue',
		'obj2' => Array(
			'addchat' => Array(
				'type' => 'IMMEDIATLY',
				'cont' => array(
					Array(
						'type' => 3,
						'cname' => '<:pls:> 各路党派 AC搬运职人',
						'crecv' => 'pid',
						'ccont' => '我觉得我还可以抢救一下……'
					),
					Array(
						'type' => 3,
						'cname' => '<:rpls:> 各路党派 AC字幕职人',
						'crecv' => 'pid',
						'ccont' => '我觉得我还可以抢救一下……'
					),
					Array(
						'type' => 3,
						'cname' => '<:rpls:> 各路党派 AC翻唱职人',
						'crecv' => 'pid',
						'ccont' => '我觉得我还可以抢救一下……'
					)
				)				
			),
		),
		'pulse' => Array('#continue','#gamedate'),
		'next' => 2700
	),
	2700 => Array(
		'tips' => '“现在……等等，那是什么声音？”<br>',
		'object' => 'continue',
		'obj2' => Array(
			'addnpc' => 9,
			'asub' => 0,
		),
		'pulse' => Array('#chat','#continue'),
		'next' => 2800	
	),
	2800 => Array(
		'tips' => '“<span class="yellow b">【聊天记录】</span>会显示死去玩家和NPC的<span class="red b">遗言</span>，看起来有另一个玩家开始屠杀NPC了，请务必小心行事。”<br>',
		'object' => 'search',
//		'obj2' => Array(
//			'meetnpc' => 9, 'meetsub' => 0,'active' => 0,'changehp' => 3000,'always_hit' => 1
//		),
		'pulse' => Array('#chat','#zz'),
		'next' => 2900	
	),
	2900 => Array(
		'tips' => '“她直接找到你了！战场上这很常见，高手玩家多半会使用生命探测器来判断你的位置。而且，看起来你们之间的实力差距相当之大。”<br>',
		'object' => 'any',
		'obj2' => Array(
			'meetnpc' => 9, 'meetsub' => 0,'active' => 0,'changevar' => Array('hp' => 3000),'always_hit' => 1
		),
		'pulse' => '#z',
		'next' => 3000	
	),
	3000 => Array(
		'tips' => '“你受了很大伤害，<span class="red b">原地不动容易遭到补刀！</span><br><br>点击<span class="yellow b">【移动】离开这个区域</span>，然后伺机回复HP吧。”<br>',
		'object' => 'move',
		'obj2' => Array('leave'),
		'pulse' => '#moveto',
		'prog' => '“使用回复道具之后，迅速离开这个区域。如果对方是熟练的玩家，也许下一秒你已经死了。”<br>',
		'next' => 3100	
	),
	3100 => Array(
		'tips' => '“现在已经是<span class="yellow b">【连斗阶段】</span>了，不是你击倒她获得胜利，就是你倒在她的刀下。让我想想有什么办法……”<br>',
		'object' => 'continue',
		'pulse' => '#continue',
		'next' => 3150	
	),
	3150 => Array(
		'tips' => '“对了，你还没有选择<span class="yellow b">【内定称号】</span>。<br>【内定称号】是这个幻境赋予你的一系列特殊能力的统称，你暂时理解成某种‘专长’就好。每次进入幻境，可供你选择的称号都不一样。就目前而言，选择<span class="yellow b">【见敌必斩】</span>这个强化刀剑攻击的称号最为合适，当然其他称号也各有长处。<br><br>现在点击<span class="yellow b">【内定称号】</span>下拉列表，选择1个称号吧。”<br>',
		'object' => 'clubsel',
		'pulse' => '#clubsel',
		'next' => 3200
	),
	3200 => Array(
		'tips' => '“接下来是强化武器。如果用<span class="yellow b">【电磁充能手套】</span>为这把<span class="red b">【红杀铁剑】</span>提供能量，可以使它变化为更强大的形态。你可以试着<span class="yellow b">【合成】</span>一下。<br>通过<span class="yellow b">【合成】</span>，你可以把多个较弱的道具转化为更有用的武器、防具或者别的东西。<br><br>点击<span class="yellow b">【道具合成】</span>按钮吧。”<br>',
		'object' => 'itemmain',
		'obj2' => Array('itemmix'),
		'pulse' => '#itemmix',
		'next' => 3300	
	),
	3300 => Array(
		'tips' => '“在合成页面分别选择<span class="yellow b">【红杀铁剑】</span>和<span class="yellow b">【电磁充能手套】</span>前面的复选框，然后选择<span class="yellow b">【提交】</span>。”<br>',
		'object' => 'itemmix',
		'obj2' => Array(
			'item' => '【红杀铁剑·雷击】'
		),
		'pulse' => Array('#z','#itemmix', ".slitmsingle :contains('电磁充能手套')",  ".slitmsingle :contains('【红杀铁剑】')"),
		'prog' => '“已经装备的道具要先<span class="yellow b">【卸下】</span>才能参与合成。”<br>如果你已经合成了装备，随便执行一次行动。<br>',
		'next' => 3400	
	),
	3400 => Array(
		'tips' => '“成功了！现在你在武器上已经有优势了。不过，要发挥武器的最大威力，还需要你提升对应的<span class="yellow b">【武器熟练度】</span>。<br><span class="yellow b">使用武器作战</span>是提升对应的熟练度的主要途径，也就是熟能生巧；不过，也有一些道具能快速提升熟练度。<br><br>先回到<span class="yellow b">【商店】</span>所在的地图吧。”<br>',
		'object' => 'move',
		'obj2' => Array('shop'),
		'pulse' => Array('#wk','#wkv','#moveto'),
		'prog' => '“商店位于<span class="lime b">【光坂高校】</span>和<span class="lime b">【花菱商厦】</span>。”<br>',
		'next' => 3500
	),
	3500 => Array(
		'tips' => '“可以在商店购买【技能书】来提升你的熟练度。<br>刀剑类武器对应<span class="yellow b">【斩熟】</span>，其技能书位于<span class="yellow b">【商店页面】→【书籍】→【斩系指南】</span>。”<br>',
		'object' => 'itembuy',
		'obj2' => Array(
			'item' => '《斩系指南》'
		),
		'pulse' => Array('#c','#bshop10','#buy_6c086eaf'),
		'next' => 3600	
	),
	3600 => Array(
		'tips' => '“然后直接使用<span class="yellow b">【斩系指南】</span>就可以提升斩系熟练度了。”<br>',
		'object' => 'itemuse',
		'obj2' => Array(
			'itmk' => Array('VK'),
		),
		'pulse' => ".itmsingle :contains('《斩系指南》') __BUTTON__",
		'next' => 3620
	),
	3620 => Array(
		'tips' => '“在作战之前，还需要把你的<span class="yellow b">【基础姿态】</span>和<span class="yellow b">【应战策略】</span>调整到合适的状态。<br>先前你一直使用【探物姿态】，其有利于探索道具但不利于面对敌人。现在把你的基础姿态切换到<span class="yellow b">【作战姿态】</span>。”<br>',
		'object' => 'pose1',
		'pulse' => Array('#pose',"option:contains('作战姿态')"),
		'next' => 3640
	),
	3640 => Array(
		'tips' => '“不同的姿态和策略有着不同的用途，你可以过后通过悬浮提示和帮助页面等方式再详细了解。<br><br>把你的<span class="yellow b">【应战策略】</span>切换到<span class="yellow b">【重视反击】</span>。”<br>',
		'object' => 'tactic3',
		'pulse' => Array('#tactic',"option:contains('重视反击')"),
		'next' => 3700
	),
	3700 => Array(
		'tips' => '“现在，你有资格跟那个玩家一较高下了。”<br>',
		'object' => 'search',
//		'obj2' => Array(
//			'meetnpc' => 9, 'meetsub' => 0, 'changehp' => 400,
//		),
		'pulse' => '#zz',
		'next' => 3800
	),
	3800 => Array(
		'tips' => '“以你目前的实力击倒她应该不难，之后就拥抱胜利吧。”<br>',
		'object' => 'kill',
		'obj2' => Array(
			'meetnpc' => 9, 'meetsub' => 0, 'changevar' => Array('hp' => 400),
		),
		'pulse' => Array(
			'wep!=【红杀铁剑·雷击】' => ".itmsingle :contains('【红杀铁剑·雷击】') __BUTTON__",
			'hp<mhp*0.8' => ".itmsingle :contains('面包') __BUTTON__",
			'#zz',
		),
		'prog' => Array(
			'wep!=【红杀铁剑·雷击】' => '“对了，<span class="lime b">别忘了把刚才的武器装备上</span>。”<br>',
			'hp<mhp*0.8' => '“如果受到了伤害，记得及时<span class="lime b">回复生命</span>。”<br>',
			'“用【探索】来遭遇并击倒对方吧。”<br>',
		),
		'next' => 3900
	),
	3900 => Array(
		'tips' => '“祝贺你，你已经能够最低限度地在这个『幻境』中存活下去了。”<br>',
		'object' => 'any',
		'pulse' => '#z',
		'next' => -1
	),
);

$tutorial_npc[1] = array(
		9 => array
		(
			'mode' => 2,
			'num' => 1,
			'pass' => 'bra',
			'club' => 0,
			'bid' => 0,
			'inf' => '',
			'rage' => 0,
			'pose'=> 0,
			'tactic' => 0,
			'killnum' => 0,
			'teamID' => '',
			'teamPass' => '',
			'gd' => 'f',
			'pls' => 99,
			'mhp' => 400,
			'msp' => 100,
			'att' => 200,
			'def' => 200,
			'lvl' => 15,
			'skill' => 99,
			'money' => 3000,
			'arb' => '红杀战甲',
			'arbk' => 'DB',
			'arbe' => 80,
			'arbs' => 60,
			'arbsk' => '',
			'arh' => '红杀战盔',
			'arhk' => 'DH',
			'arhe' => 60,
			'arhs' => 30,			
			'arf' => '红杀战靴',
			'arfk' => 'DF',
			'arfe' => 75,
			'arfs' => 30,
			'arfsk' => '',
			'ara' => '红杀辅助瞄准系统S.A.T.S.',
			'arak' => 'DA',
			'arae' => 120,
			'aras' => 30,
			'arask' => 'c',
			
			'sub' => array
			(
			0 => array
				(
				'name' => '"FLEUR"',
				'icon' => 211,
				'wep' => '【红杀军剑】',
				'wepk' => 'WK',
				'wepe' => 150,
				'weps' => 100,
				'arask' => 'd',
				),
			),
		),
		91 => array
		(
			'mode' => 1,
			'num' => 1,
			'pass' => 'bra',
			'club' => 9,
			'bid' => 0,
			'inf' => '',
			'state' => 1,
			'rage' => 15,
			'pose'=> 0,
			'tactic' => 0,
			'killnum' => 0,
			'teamID' => '',
			'teampsss' => '',
			'gd' => 'r',
			'pls' => 99,
			'mhp' => 100,
			'msp' => 100,
			'att' => 120,
			'def' => 80,
			'lvl' => 1,
			'skill' => 50,
			'money' => 2000,
			'arb' => '便服',
			'arbk' => 'DB',
			'arbe' => 10,
			'arbs' => 20,
			'arbsk' => '',
			'arh' => '鸭舌帽',
			'arhk' => 'DH',
			'arhe' => 10,
			'arhs' => 15,
			'arf' => '布鞋',
			'arfk' => 'DF',
			'arfe' => 10,
			'arfs' => 10,
			'ara' => '塑料盾',
			'arak' => 'DA',
			'arae' => 10,
			'aras' => 15,
			'arask' => '',
			'sub' => array
			(
				0 => array
				(
				'name' => '人偶',
				'icon' => 27,
				'wep' => '铁棒',
				'wepk' => 'WP',
				'wepe' => 76,
				'weps' => 20,
				'wepsk' => '',
				),
			),
		)
);
?>