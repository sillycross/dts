<?php
if(!defined('IN_GAME')) exit('Access Denied'); 
$tutorial = Array(
	10 => Array(
		'tips' => '能听到我说话吗？我是时空特使<span class="evergreen">林苍月</span>。看起来你是个新手，如果你不想在这个虚拟世界中悲惨地死去，就给我好好听着。<br>',
		'allowed' => 'continue',
		'next' => 1000
	),
	1000 => Array(
		'tips' => '再按则游戏结束',
		'allowed' => 'continue',
		'next' => -1
	)
);
?>