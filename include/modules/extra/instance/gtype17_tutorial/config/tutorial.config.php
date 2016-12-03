<?php
if(!defined('IN_GAME')) exit('Access Denied'); 
$tutorialsetting = Array(
	10 => Array(
		'tips' => '“能听到我说话吗？我是红杀菁英——<span class="yellow">芙蓉</span>。看起来你是个新手，如果你不想在这个虚拟世界中悲惨地死去，就给我好好听着。”<br>',
		'allowed' => 'continue',
		'pulse' => 'continue',
		'next' => 20
	),
	20 => Array(
		'tips' => '“画面上方是你的基本数据；中间的人形状态条代表你的SP和HP。SP过低时你将无法进行一些行动，而HP归零即意味着你的死亡。”<br>',
		'allowed' => 'continue',
		'pulse' => 'profile',
		'next' => 30
	),
	30 => Array(
		'tips' => '“画面下方是你目前的装备和包裹里的道具。它们对你能否在游戏中存活和获胜有重要的意义。”<br>',
		'allowed' => 'continue',
		'pulse' => 'packs',
		'next' => 40
	),
	40 => Array(
		'tips' => '“画面最下方是聊天框。你可以在这里看到一些游戏状况提示，NPC的遗言，以及跟其他玩家互动交流。”<br>',
		'allowed' => 'continue',
		'pulse' => 'chat',
		'next' => 50
	),
	50 => Array(
		'tips' => '“而这里是操作框，你会在这里发出大部分指令，并得到反馈。”<br>',
		'allowed' => 'continue',
		'pulse' => 'cmd',
		'next' => 100
	),
	1000 => Array(
		'tips' => '再按则游戏结束',
		'allowed' => 'continue',
		'pulse' => 'continue',
		'next' => -1
	)
);
?>