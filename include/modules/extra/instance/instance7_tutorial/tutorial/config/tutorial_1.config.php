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
		'tips' => '“能听到我说话吗？<br>我是……嗯，我是武侠作家<span class="yellow b">李天明</span>。<br>我在这个虚拟世界取材已经有好几年了，而你好像是个新手。如果你不想在悲惨地死在这里，就好好记住我要说的话。”<br>',
		'object' => 'continue',
		'obj2' => Array(
			'addnpc' => 91,
			'asub' => 0,
		),
		'pulse' => '#continue',
		'next' => 20
	),
	20 => Array(
		'tips' => '“画面上方是你的<span class="yellow b">基本数据</span>；其中的<span class="yellow b">人形状态条</span>代表你的<span class="yellow b">SP和HP</span>。SP过低时你将无法进行一些行动，而HP归零即意味着你的<span class="red b">死亡</span>。”<br>',
		'object' => 'continue',
		'pulse' => '#profile',
		'next' => 30
	),
	30 => Array(
		'tips' => '“画面中下方是你目前的<span class="yellow b">装备</span>和包裹里的<span class="yellow b">道具</span>。它们对你能否在游戏中存活和获胜有重要的意义。”<br>',
		'object' => 'continue',
		'pulse' => '#packs',
		'next' => 40
	),
	40 => Array(
		'tips' => '“画面最下方是<span class="yellow b">聊天框</span>。你可以在这里看到一些游戏状况提示，NPC的遗言，以及跟其他玩家互动交流。”<br>',
		'object' => 'continue',
		'pulse' => '#chat',
		'next' => 50
	),
	50 => Array(
		'tips' => '“而这里是<span class="yellow b">操作框</span>，你会在这里发出大部分指令，并得到指令的反馈。”<br>',
		'object' => 'continue',
		'pulse' => '#cmd',
		'next' => 60
	),
//	55 => Array(
//		'tips' => '“进入游戏要做的第一件事，就是选择一个<span class="yellow b">【内定称号】</span>。<br>大逃杀有24个内定称号，其中有……呃拿错台词了。<br>不同的<span class="yellow b">【内定称号】</span>对应着不同的技能、特色和发展方向，作为新手，你暂时理解成某种<span class="yellow b">‘专长’</span>就好。<br>现在点击<span class="yellow b">【内定称号】</span>下拉列表，任选1个称号吧。”<br>',
//		'object' => 'clubsel',
//		'pulse' => '#clubsel',
//		'next' => 60
//	),
	60 => Array(
		'tips' => '“熟悉了游戏界面，现在让我们迈出第一步。请点击<span class="yellow b">【移动】</span>下拉列表，然后选择别的区域。”<br>',
		'object' => 'move',
		'obj2' => Array('leave'),
		'pulse' => '#moveto',
		'next' => 70		
	),
	70 => Array(
		'tips' => '“很好。移动需要消耗体力，所以请确保你体力充足。移动之后也可能遇到<span class="yellow b">突发事件</span>甚至<span class="red b">敌人</span>，不过这次没事。<br>除了移动之外，你还可以原地探索。请点击<span class="yellow b">【探索】</span>按钮。”<br>',
		'object' => 'search',
		'obj2' => Array(
			'itm' => '充能手套',
			'itmk' => 'DA',
			'itme' => 45,
			'itms' => 15,
			'itmsk' => 'e'
		),
		'pulse' => '#zz',
		'next' => 80
	),
	80 => Array(
		'tips' => '“有时候会捡到不需要的道具，那种情况丢掉就好。不过你的腕部正缺防护，把这个手套<span class="yellow b">捡起来</span>吧。”<br>',
		'object' => 'itemget',
		'obj2' => Array(
			'itm' => '电磁充能手套',
			'itmk' => 'DA',
			'itme' => 45,
			'itms' => 15,
			'itmsk' => 'E'
		),
		'prog' => '“如果你不小心丢弃了物品，在原地<span class="yellow b">探索</span>有概率重新捡到。”<br>',
		'pulse' => '#z',
		'next' => 90
	),
	90 => Array(
		'tips' => '“探索是获得有用的装备或道具的重要方式之一。现在请再次点击<span class="yellow b">【探索】</span>按钮。”<br>',
		'object' => 'search',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 0,'inf' => 'f','ex_inf' => 'p','always_hit' => 1
		),
		'pulse' => '#zz',
		'next' => 100
	),
	100 => Array(
		'tips' => '“哎呀，看来这次运气不好，你在探索时遭遇了一次袭击。”<br>',
		'object' => 'any',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 0,'inf' => 'f','ex_inf' => 'p','always_hit' => 1
		),
		'pulse' => '#z',
		'next' => 110
	),
	110 => Array(
		'tips' => '“嘛，在战场上难免遭遇敌人并受伤。如果不及时回复生命，情况就会很危险。<br>生命伤害需要使用<span class="yellow b">【生命回复】</span>道具。首先点击<span class="yellow b">【面包】</span>以使用之。”<br>',
		'object' => 'itemuse',
		'obj2' => Array(
			'itmk' => Array('HH','HB'),
		),
		'pulse' => ".itmsingle :contains('面包') __BUTTON__",
		'next' => 120
	),
	120 => Array(
		'tips' => '“然后直接点击人形状态条的<span class="red b">【受伤部位】</span>进行<span class="yellow b">包扎</span>。包扎需要消耗体力，请确保你的体力充足。”<br>',
		'object' => 'inff',
		'pulse' => '#inff',
		'prog' => '“如果已经包扎完了，请随便执行一次行动。”<br>',
		'next' => 130
	),
	130 => Array(
		'tips' => '“<span class="purple b">【中毒】</span>属于异常状态，需要用对应的药剂进行处理。幸好你随身携带了万用的药剂。点击<span class="yellow b">【紧急药剂】</span>以使用之。<br>除了紧急药剂以外，还有对应单个异常状态生效的药剂，实战时注意鉴别和收集，当然也可以在商店中买到。”<br>',
		'object' => 'itemuse',
		'obj2' => Array(
			'itmk' => Array('Ca','Cp'),
		),
		'pulse' => ".itmsingle :contains('紧急药剂') __BUTTON__",
		'prog' => '“如果已经处理完了，请随便执行一次行动。”<br>',
		'next' => 140
	),
	140 => Array(
		'tips' => '“很好，现在你从受伤状态完全恢复了。<br>除了使用道具之外，消耗<span class="lime b">【技能点数】</span>或者<span class="lime b">【静养】</span>一定时间也可以解除异常状态。<br>敌人还在当前地点，让我们去还击吧。点击<span class="yellow b">【探索】</span>”<br>',
		'object' => 'search',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 1,'changehp' => 1,'always_hit' => 1
		),
		'pulse' => '#zz',
		'next' => 150
	),
	150 => Array(
		'tips' => '“这次你应该能够先手了。找到敌人的话，就点击具体的<span class="yellow b">【攻击方式】</span>按钮。”<br>',
		'object' => 'kill',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 1,'changehp' => 1,'always_hit' => 1
		),
		'pulse' => '#z',		
		'prog' => '“错过攻击机会也没有关系，在原地<span class="yellow b">探索</span>仍有机会发现同一个敌人。”<br>',
		'next' => 160
	),
	160 => Array(
		'tips' => '“不错，你干掉了这个陪练对象。<br>击杀敌方后，可以<span class="yellow b">捡取</span>敌方尸体上的<span class="yellow b">装备、道具或者金钱</span>。1次只能捡取<span class="yellow b">1件</span>东西，其他东西则需要再次找到尸体才能捡。<br>请选择<span class="yellow b">【金钱（具体钱数）】</span>然后<span class="yellow b">【提交】</span>。”<br>',
		'object' => 'money',
		'pulse' => '#amoney',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'corpse' => 1
		),
		'prog' => '“拿了别的东西？没关系，在原地<span class="yellow b">探索</span>仍有机会发现那具尸体。<br>不过，游戏进入<span class="red b">【连斗】</span>阶段或者其他玩家将尸体销毁后，就没法再发现尸体了。<br>',
		'next' => 170
	),
	170 => Array(
		'tips' => '“获得金钱后，可以去<span class="yellow b">【商店】</span>添置装备道具。<br>商店并非哪里都有，它只位于特定的两个地点。请先<span class="yellow b">【移动】</span>到<span class="lime b">【光坂高校】</span>或者<span class="lime b">【花菱商厦】</span>。”<br>',
		'object' => 'move',
		'obj2' => Array('shop'),
		'pulse' => Array('#moveto',"option:contains('光坂高校')","option:contains('花菱商厦')"),
		'next' => 180		
	),
	180 => Array(
		'tips' => '“然后点击<span class="yellow b">【商店】</span>按钮。”<br>',
		'object' => 'sp_shop',
		'pulse' => '#c',
		'next' => 190		
	),
	190 => Array(
		'tips' => '“之后点击<span class="yellow b">【锐器】</span>按钮。”<br>',
		'object' => 'shop4',
		'pulse' => Array('#bshop4'),
		'next' => 200		
	),
	200 => Array(
		'tips' => '“之后选择<span class="red b">【红杀铁剑】</span>并购买吧。”<br>',
		'object' => 'itembuy',
		'obj2' => Array(
			'item' => '【红杀铁剑】'
		),
		'pulse' => Array('#buy_f1b3f633'),
		'next' => 202		
	),
	202 => Array(
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
		'next' => 204
	),
	204 => Array(
		'tips' => '“<span class="yellow b">注意！</span><br><span class="lime b">聊天框</span>提示，你所在的位置马上就要成为禁区了。如果禁区时间到时，你没有离开禁区所在地点，那么你会被<span class="red b">【禁区杀】</span>。<br>马上点击<span class="yellow b">【移动】</span>并<span class="yellow b">离开这个区域</span>！”<br>',
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
		'next' => 206
	),	
	206 => Array(
		'tips' => '“你及时躲过了禁区，干得好。此外，根据聊天框的讯息，游戏进入了<span class="yellow b">【连斗阶段】</span>。<br><span class="yellow b">【连斗】</span>之后，还存活的玩家必须厮杀到<span class="yellow b">只剩1人幸存</span>为止，而那个幸存者将获得这场大逃杀的胜利。”<br>',
		'object' => 'continue',
		'obj2' => Array(
			'addchat' => Array(
				'type' => 'IMMEDIATLY',
				'cont' => array(
					Array(
						'type' => 3,
						'cname' => 'pls 各路党派 AC搬运职人',
						'crecv' => 'pid',
						'ccont' => '我觉得我还可以抢救一下……'
					),
					Array(
						'type' => 3,
						'cname' => 'rpls 各路党派 AC字幕职人',
						'crecv' => 'pid',
						'ccont' => '我觉得我还可以抢救一下……'
					),
					Array(
						'type' => 3,
						'cname' => 'rpls 各路党派 AC翻唱职人',
						'crecv' => 'pid',
						'ccont' => '我觉得我还可以抢救一下……'
					)
				)				
			),
		),
		'pulse' => Array('#continue','#gamedate'),
		'next' => 210
	),
	210 => Array(
		'tips' => '“现在……等等，那是什么声音？”<br>',
		'object' => 'continue',
		'obj2' => Array(
			'addnpc' => 9,
			'asub' => 0,
		),
		'pulse' => Array('#chat','#continue'),
		'next' => 220	
	),
	220 => Array(
		'tips' => '“<span class="yellow b">【聊天记录】</span>会显示死去玩家和NPC的<span class="red b">遗言</span>，看起来有另一个玩家开始屠杀NPC了，请务必小心行事。”<br>',
		'object' => 'search',
		'obj2' => Array(
			'meetnpc' => 9, 'meetsub' => 0,'active' => 0,'always_hit' => 1
		),
		'pulse' => Array('#chat','#zz'),
		'next' => 230	
	),
	230 => Array(
		'tips' => '“他直接找到你了！战场上这很常见，高手玩家多半会使用生命探测器来判断你的位置。而且，看起来你们之间的实力差距相当之大。”<br>',
		'object' => 'any',
		'obj2' => Array(
			'meetnpc' => 9, 'meetsub' => 0,'active' => 0,'always_hit' => 1
		),
		'pulse' => '#z',
		'next' => 240	
	),
	240 => Array(
		'tips' => '“你受了很大伤害，<span class="red b">原地不动容易遭到补刀！</span><br>点击<span class="yellow b">【移动】离开这个区域</span>，然后伺机回复HP吧。”<br>',
		'object' => 'move',
		'obj2' => Array('leave'),
		'pulse' => '#moveto',
		'prog' => '“使用回复道具之后，迅速离开这个区域。如果对方是熟练的玩家，也许下一秒你已经死了。”<br>',
		'next' => 250	
	),
	250 => Array(
		'tips' => '“现在已经是<span class="yellow b">【连斗阶段】</span>了，不是你击倒他获得胜利，就是你倒在他的刀下。让我想想有什么办法……”<br>',
		'object' => 'continue',
		'pulse' => '#continue',
		'next' => 260	
	),
	260 => Array(
		'tips' => '“我记得，如果给这把<span class="red b">【红杀铁剑】</span>提供能量，可以使它变化为更强大的形态。你可以试着<span class="yellow b">【合成】</span>一下。<br>通过<span class="yellow b">【合成】</span>，你可以把多个较弱的道具转化为更有用的武器、防具或者别的东西。点击<span class="yellow b">【道具合成】</span>按钮吧。”<br>',
		'object' => 'itemmain',
		'obj2' => Array('itemmix'),
		'pulse' => '#itemmix',
		'next' => 270	
	),
	270 => Array(
		'tips' => '“在合成页面分别选择<span class="yellow b">【红杀铁剑】</span>和<span class="yellow b">【电磁充能手套】</span>前面的复选框，然后选择<span class="yellow b">【提交】</span>。”<br>',
		'object' => 'itemmix',
		'obj2' => Array(
			'item' => '【红杀铁剑·雷击】'
		),
		'pulse' => Array('#z','#itemmix', ".slitmsingle :contains('电磁充能手套')",  ".slitmsingle :contains('【红杀铁剑】')"),
		'prog' => '“已经装备的道具要先<span class="yellow b">【卸下】</span>才能参与合成。”<br>',
		'next' => 280	
	),
	280 => Array(
		'tips' => '“成功了！现在你在武器上已经有优势了。<br>不过，要发挥武器的最大威力，还需要你提升对应的<span class="yellow b">【武器熟练度】</span>。<br><span class="yellow b">使用武器作战</span>是提升对应的熟练度的主要途径，也就是熟能生巧；不过，也有一些道具能快速提升熟练度。<br>先回到<span class="yellow b">【商店】</span>所在的地图吧。”<br>',
		'object' => 'move',
		'obj2' => Array('shop'),
		'pulse' => Array('#wk','#wkv','#moveto'),
		'prog' => '“商店位于<span class="lime b">【光坂高校】</span>和<span class="lime b">【花菱商厦】</span>。”<br>',
		'next' => 290
	),
	290 => Array(
		'tips' => '“刀剑类武器对应<span class="yellow b">【斩熟】</span>，其技能书位于<span class="yellow b">【商店页面】→【书籍】→【斩系指南】</span>。”<br>',
		'object' => 'itembuy',
		'obj2' => Array(
			'item' => '《斩系指南》'
		),
		'pulse' => Array('#bshop10','#buy_6c086eaf'),
		'next' => 300	
	),
	300 => Array(
		'tips' => '“然后直接使用<span class="yellow b">【斩系指南】</span>就可以提升斩系熟练度了。”<br>',
		'object' => 'itemuse',
		'obj2' => Array(
			'itmk' => Array('VK'),
		),
		'pulse' => ".itmsingle :contains('《斩系指南》') __BUTTON__",
		'next' => 310
	),
	310 => Array(
		'tips' => '“现在，你有资格跟那个玩家一较高下了。”<br>',
		'object' => 'search',
		'obj2' => Array(
			'meetnpc' => 9, 'meetsub' => 0,
		),
		'pulse' => '#zz',
		'next' => 320
	),
	320 => Array(
		'tips' => '“以你目前的实力击倒他应该不难，之后就拥抱胜利吧。”<br>',
		'object' => 'kill',
		'obj2' => Array(
			'meetnpc' => 9, 'meetsub' => 0,
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
		'next' => 330
	),
	330 => Array(
		'tips' => '“祝贺你，你已经懂得如何在战场上生存了。”<br>',
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
			'gd' => 'm',
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
				'name' => '弗勒尔',
				'icon' => 0,
				'wep' => '【红杀之牙】',
				'wepk' => 'WK',
				'wepe' => 120,
				'weps' => 100,
				'arask' => 'nu',
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
				'name' => '自律人偶',
				'icon' => 9,
				'wep' => '镇暴失能针',
				'wepk' => 'WK',
				'wepe' => 60,
				'weps' => 20,
				'wepsk' => 'p',
				),
			),
		)
);
?>