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
			'itm' => '电磁充能手套',
			'itmk' => 'DA',
			'itme' => 45,
			'itms' => 15,
			'itmsk' => 'E'
		),
		'pulse' => 'zz',
		'next' => 80
	),
	80 => Array(
		'tips' => '搜寻有一定概率发现道具。如果你不小心丢弃了，在原地搜寻有概率重新捡到。<br>',
		'object' => 'itemget',
		'obj2' => Array(
			'itm' => '电磁充能手套',
			'itmk' => 'DA',
			'itme' => 45,
			'itms' => 15,
			'itmsk' => 'E'
		),
		'pulse' => 'z',
		'next' => 90
	),
	90 => Array(
		'tips' => '“搜寻是获得有用的装备或道具的重要方式之一。现在请再次点击<span class="yellow">【搜寻】</span>按钮。”<br>',
		'object' => 'search',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 0
		),
		'pulse' => 'zz',
		'next' => 100
	),
	100 => Array(
		'tips' => 'NPC袭击',
		'object' => 'back',
		'obj2' => Array(
			'meetnpc' => 91, 'meetsub' => 0,'active' => 0
		),
		'pulse' => 'z',
		'next' => 110
	),
	110 => Array(
		'tips' => '“嘛，在战场上难免遭遇敌人并受伤，如果不及时处理很容易遇到危险。<br>HP伤害需要使用<span class="yellow">【HP回复】</span>道具。首先点击<span class="yellow">【面包】</span>以使用之。”',
		'object' => 'itm1',
		'pulse' => 'aa',
		'next' => 120
	),
	120 => Array(
		'tips' => '然后直接点击人形状态条的<span class="yellow">【受伤部位】</span>进行包扎。',
		'object' => 'inff',
		'pulse' => 'inff',
		'next' => 130
	),
);
?>