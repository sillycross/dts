<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<table border="1" cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td>
<table border="1" cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td>
<IMG width=140px src="img/n_<?php echo $nownpc['icon']?>.gif" border="0" valign="middle"/>
</td>
<td>
<table border="1" height=100% width=100% cellspacing="0" cellpadding="0">
<tr>
<td width=100px align="center" class="b1">
NPC类别
</td>
<td width=100px align="center" class="b3">
<?php echo $typeinfo[$npckind]?>
</td>
<td width=100px align="center" class="b1">
数目
</td>
<td width=100px align="center" class="b3">
<?php echo $nownpc['___count']?>
</td>
<td width=100px align="center" class="b1">
所处地点
</td>
<td width=100px align="center" class="b3">
<?php if($nownpc['pls']==99) { ?>
随机
<?php } else { ?>
<span class="yellow">
<?php echo $plsinfo[$nownpc['pls']]?>
</span>
<?php } ?>
</td>
</tr>
<tr>
<td width=100px align="center" class="b1">
NPC等级
</td>
<td width=100px align="center" class="b3">
<span>
Lv. <?php echo $nownpc['lvl']?>
</span>
</td>
<td width=100px align="center" class="b1">
NPC名称
</td>	
<td width=100px align="center" class="b3">
<span class="lime">
<?php echo $nownpc['name']?>
</span>
</td>
<td width=100px align="center" class="b1">
性别
</td>
<td width=100px align="center" class="b3">
<?php if($nownpc['gd']=='m') { ?>
男
<?php } else { if($nownpc['gd']=='f') { ?>
女
<?php } else { ?>
随机
<?php } } ?>
</td>
</tr>					
<tr>
<td width=100px align="center" class="b1">
内定称号
</td>					
<td width=100px align="center" class="b3">
<?php if(isset($nownpc['club'])) { ?>
<?php echo $clubinfo[$nownpc['club']]?>
<?php } else { ?>
无
<?php } ?>
</td>
<td width=100px align="center" class="b1">
基础姿态
</td>
<td width=100px align="center" class="b3">
<?php echo $poseinfo[$nownpc['pose']]?>
</td>
<td width=100px align="center" class="b1">
应战策略
</td>
<td width=100px align="center" class="b3">
<?php echo $tacinfo[$nownpc['tactic']]?>
</td>
</tr>
<tr>
<td width=100px align="center" class="b1">
生命上限
</td>
<td width=100px align="center" class="b3">
<?php echo $nownpc['mhp']?>
</td>
<td width=100px align="center" class="b1">
熟练度
</td>
<td width=100px align="center" class="b3">
<?php echo $nownpc['skill']?>
</td>
<td width=100px align="center" class="b1">
怒气值
</td>
<td width=100px align="center" class="b3">
<?php echo $nownpc['rage']?>
</td>
</tr>
<tr>
<td width=100px align="center" class="b1">
基础攻击
</td>
<td width=100px align="center" class="b3">
<?php echo $nownpc['att']?>
</td>
<td width=100px align="center" class="b1">
基础防御
</td>
<td width=100px align="center" class="b3">
<?php echo $nownpc['def']?>
</td>
<td width=100px align="center" class="b1">
掉落金钱
</td>
<td width=100px align="center" class="b3">
<?php echo $nownpc['money']?>
</td>
</tr>
</table>
</td>
</tr>
</table>
</td>
</tr>
<tr>
<td>
<table border="1" cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td width=140px>
<?php echo $nownpc['description']?>
</td>
<td>
<table border="1" cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td width=100px align="center" class="b1">
武器名称
</td>
<td width=505px align="center" class="b3">
<?php \npcinfo\npcinfo_gen_item_description($nownpc['wep'],$nownpc['wepk'],$nownpc['wepe'],$nownpc['weps'],$nownpc['wepsk']); ?>
</td>
</tr>
<tr>
<td width=100px align="center" class="b1">
身体装备
</td>
<td width=505px align="center" class="b3">
<?php \npcinfo\npcinfo_gen_item_description($nownpc['arb'],'',$nownpc['arbe'],$nownpc['arbs'],$nownpc['arbsk']); ?>
</td>
</tr>
<tr>
<td width=100px align="center" class="b1">
头部装备
</td>
<td width=505px align="center" class="b3">
<?php \npcinfo\npcinfo_gen_item_description($nownpc['arh'],'',$nownpc['arhe'],$nownpc['arhs'],$nownpc['arhsk']); ?>
</td>
</tr>
<tr>
<td width=100px align="center" class="b1">
手臂装备
</td>
<td width=505px align="center" class="b3">
<?php \npcinfo\npcinfo_gen_item_description($nownpc['ara'],'',$nownpc['arae'],$nownpc['aras'],$nownpc['arask']); ?>
</td>
</tr>
<tr>
<td width=100px align="center" class="b1">
腿部装备
</td>
<td width=505px align="center" class="b3">
<?php \npcinfo\npcinfo_gen_item_description($nownpc['arf'],'',$nownpc['arfe'],$nownpc['arfs'],$nownpc['arfsk']); ?>
</td>
</tr>
<tr>
<td width=100px align="center" class="b1">
饰品 
</td>
<td width=505px align="center" class="b3">
<?php \npcinfo\npcinfo_gen_item_description($nownpc['art'],$nownpc['artk'],'','',$nownpc['artsk']); ?>
</td>
</tr>
<?php if(is_array(Array(1,2,3,4,5,6))) { foreach(Array(1,2,3,4,5,6) as $id) { if($nownpc['itm'.$id]!='') { ?>
<tr>
<td width=100px align="center" class="b1">
掉落物品
</td>
<td width=505px align="center" class="b3">
<?php \npcinfo\npcinfo_gen_item_description($nownpc['itm'.$id],$nownpc['itmk'.$id],$nownpc['itme'.$id],$nownpc['itms'.$id],$nownpc['itmsk'.$id]); ?>
</td>
</tr>
<?php } } } ?>
</table>
</td>
</tr>
</table>
</td>
</tr>
</table> 

