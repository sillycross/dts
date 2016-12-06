<?php
if(!defined('IN_GAME')) exit('Access Denied'); 
$tutorialsetting = Array(
	10 => Array(
		'tips' => '“能听到我说话吗？我是红杀菁英——<span class="yellow">芙蓉</span>。看起来你是个新手，如果你不想在这个虚拟世界中悲惨地死去，就给我好好听着。”<br>',
		'object' => 'continue',
		'obj2' => Array(
			'addnpc' => 91,
			'asub' => 0,
		),
		'pulse' => 'continue',
		'next' => 20
	),
	20 => Array(
		'tips' => '“画面上方是你的基本数据；中间的人形状态条代表你的SP和HP。SP过低时你将无法进行一些行动，而HP归零即意味着你的死亡。”<br>',
		'object' => 'continue',
		'pulse' => 'profile',
		'next' => 30
	),
	30 => Array(
		'tips' => '“画面下方是你目前的装备和包裹里的道具。它们对你能否在游戏中存活和获胜有重要的意义。”<br>',
		'object' => 'continue',
		'pulse' => 'packs',
		'next' => 40
	),
	40 => Array(
		'tips' => '“画面最下方是聊天框。你可以在这里看到一些游戏状况提示，NPC的遗言，以及跟其他玩家互动交流。”<br>',
		'object' => 'continue',
		'pulse' => 'chat',
		'next' => 50
	),
	50 => Array(
		'tips' => '“而这里是操作框，你会在这里发出大部分指令，并得到反馈。”<br>',
		'object' => 'continue',
		'pulse' => 'cmd',
		'next' => 60
	),
	60 => Array(
		'tips' => '“熟悉了游戏界面，现在让我们迈出第一步。请点击<span class="yellow">【移动】</span>下拉列表，然后选择别的区域。”<br>',
		'object' => 'move',
		'obj2' => 'leave',
		'pulse' => 'moveto',
		'next' => 70		
	),
	70 => Array(
		'tips' => '“很好。移动需要消耗体力，所以请确保你体力充足。移动之后也可能遇到突发事件甚至敌人，不过这次没事。<br>除了移动之外，你还可以原地搜寻。请点击<span class="yellow">【搜寻】</span>按钮。”<br>',
		'object' => 'search',
		'obj2' => Array(
			'itm' => '充能手套',
			'itmk' => 'DA',
			'itme' => 45,
			'itms' => 15,
			'itmsk' => 'e'
		),
		'pulse' => 'zz',
		'next' => 80
	),
	80 => Array(
		'tips' => '“有时候会捡到不需要的道具，那种情况丢掉就好。不过你的腕部正缺防护，把这个手套捡起来吧。”<br>',
		'object' => 'itemget',
		'obj2' => Array(
			'itm' => '电磁充能手套',
			'itmk' => 'DA',
			'itme' => 45,
			'itms' => 15,
			'itmsk' => 'E'
		),
		'prog' => '“如果你不小心丢弃了，在原地搜寻有概率重新捡到。”<br>',
		'pulse' => 'z',
		'next' => 90
	),
	90 => Array(
		'tips' => '“搜寻是获得有用的装备或道具的重要方式之一。现在请再次点击<span class="yellow">【搜寻】</span>按钮。”<br>',
		'object' => 'search',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 0,'inf' => 'f','ex_inf' => 'p','always_hit' => 1
		),
		'pulse' => 'zz',
		'next' => 100
	),
	100 => Array(
		'tips' => '“哎呀，看来这次运气不好，你在探索时遭遇了一次袭击。”<br>',
		'object' => 'any',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 0,'inf' => 'f','ex_inf' => 'p'
		),
		'pulse' => 'z',
		'next' => 110
	),
	110 => Array(
		'tips' => '“嘛，在战场上难免遭遇敌人并受伤。如果不及时回复HP，情况就会很危险。<br>HP伤害需要使用<span class="yellow">【HP回复】</span>道具。首先点击<span class="yellow">【面包】</span>以使用之。”<br>',
		'object' => 'itm1',
		'obj2' => Array(
			'itmk' => Array('HH','HB'),
		),
		'pulse' => 'aa',
		'next' => 120
	),
	120 => Array(
		'tips' => '“然后直接点击人形状态条的<span class="red">【受伤部位】</span>进行包扎。”<br>',
		'object' => 'inff',
		'pulse' => 'inff',
		'prog' => '“如果已经包扎完了，请点击<span class="red">【强行继续】</span>”<br>',
		'next' => 130
	),
	130 => Array(
		'tips' => '“<span class="purple">【中毒】</span>属于异常状态，需要用对应的药剂进行处理，幸好你随身携带了万用的药剂。点击<span class="yellow">【紧急药剂】</span>以使用之。”<br>',
		'object' => 'itm3',
		'obj2' => Array(
			'itmk' => Array('Ca','Cp'),
		),
		'pulse' => 'dd',
		'prog' => '“如果已经处理完了，请点击<span class="red">【强行继续】</span>”<br>',
		'next' => 140
	),
	140 => Array(
		'tips' => '“很好，现在你从受伤状态完全恢复了。<br>除了使用道具之外，消耗<span class="lime">【技能点数】</span>或者<span class="lime">【静养】</span>一定时间也可以解除异常状态。<br>敌人还在当前地点，让我们去还击吧。点击<span class="yellow">【探索】</span>”<br>',
		'object' => 'search',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 1,'changehp' => 1,'always_hit' => 1
		),
		'pulse' => 'zz',
		'next' => 150
	),
	150 => Array(
		'tips' => '“这次你应该能够先手了。找到敌人的话，就点击具体的<span class="yellow">【攻击方式】</span>按钮。”<br>',
		'object' => 'kill',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 1,'changehp' => 1,'always_hit' => 1
		),
		'pulse' => 'z',		
		'prog' => '“错过攻击机会也没有关系，在原地<span class="yellow">搜寻</span>仍有机会发现同一个敌人。”<br>',
		'next' => 160
	),
	160 => Array(
		'tips' => '“不错，你干掉了这个陪练对象。击杀敌方后，可以捡取敌方尸体上的装备道具或者金钱。请选择<span class="yellow">【金钱（具体钱数）】</span>然后<span class="yellow">【提交】</span>。”<br>',
		'object' => 'money',
		'pulse' => 'amoney',
		'prog' => '“拿了别的东西？没关系，在原地<span class="yellow">搜寻</span>仍有机会发现那具尸体。<br>不过，游戏进入<span class="red">【连斗】</span>阶段或者其他玩家将尸体销毁后，就没法再发现尸体了。<br>',
		'next' => 170
	),
	170 => Array(
		'tips' => '“获得金钱后，可以去<span class="yellow">【商店】</span>添置装备道具。请先<span class="yellow">【移动】</span>到<span class="lime">【光阪高校】</span>或者<span class="lime">【花菱商厦】</span>。”<br>',
		'object' => 'move',
		'obj2' => 'shop',
		'pulse' => 'moveto',
		'next' => 180		
	),
	180 => Array(
		'tips' => '“然后点击<span class="yellow">【商店】</span>按钮。”<br>',
		'object' => 'sp_shop',
		'pulse' => 'c',
		'next' => 190		
	),
	190 => Array(
		'tips' => '“之后选择<span class="yellow">【锐器】</span>类别并提交。商店购买方式稍微麻烦些，这是为了防止误操作。”<br>',
		'object' => 'shop4',
		'pulse' => 'ashop4',
		'next' => 200		
	),
	200 => Array(
		'tips' => '“之后选择<span class="red">【红杀铁剑】</span>类别并购买吧。”<br>',
		'object' => 'itembuy',
		'obj2' => '【红杀铁剑】',
		'pulse' => 'a31',
		'next' => 210		
	),
	210 => Array(
		'tips' => '“告一段落，点此结束”<br>',
		'object' => 'continue',
		'pulse' => 'continue',
		'next' => -1	
	),
);
?>