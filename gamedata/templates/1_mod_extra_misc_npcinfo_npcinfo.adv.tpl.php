<?php if(!defined('IN_GAME')) exit('Access Denied'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><table border="1" cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td>
<table border="1" cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td>
<IMG width=140px src="img/n_<?php } else { echo '___aaip'; } ?><?php echo $nownpc['icon']?><?php if (!defined('GEXIT_RETURN_JSON')) { ?>.gif" border="0" valign="middle"/>
</td>
<td>
<table border="1" height=100% width=100% cellspacing="0" cellpadding="0">
<tr>
<td width=100px align="center" class="b1">
NPC类别
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiq'; } ?><?php echo $typeinfo[$npckind]?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td width=100px align="center" class="b1">
数目
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aair'; } ?><?php echo $nownpc['___count']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td width=100px align="center" class="b1">
所处地点
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aais'; } ?><?php if($nownpc['pls']==99) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>随机
<?php } else { echo '___aait'; } ?><?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
<?php } else { echo '___aaf6'; } ?><?php echo $plsinfo[$nownpc['pls']]?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
<?php } else { echo '___aacN'; } ?><?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td width=100px align="center" class="b1">
NPC等级
</td>
<td width=100px align="center" class="b3">
<span>
Lv. <?php } else { echo '___aaiu'; } ?><?php echo $nownpc['lvl']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</td>
<td width=100px align="center" class="b1">
NPC名称
</td>    
<td width=100px align="center" class="b3">
<span class="lime">
<?php } else { echo '___aaiv'; } ?><?php echo $nownpc['name']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>
</td>
<td width=100px align="center" class="b1">
性别
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiw'; } ?><?php if($nownpc['gd']=='m') { ?>
男
<?php } else { if($nownpc['gd']=='f') { ?>
女
<?php } else { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?>随机
<?php } else { echo '___aait'; } ?><?php } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>                    
<tr>
<td width=100px align="center" class="b1">
内定称号
</td>                    
<td width=100px align="center" class="b3">
<?php } else { echo '___aaix'; } ?><?php if(isset($nownpc['club'])) { ?>
<?php echo $clubinfo[$nownpc['club']]?>
<?php } else { ?>
无
<?php } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td width=100px align="center" class="b1">
基础姿态
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiy'; } ?><?php echo $poseinfo[$nownpc['pose']]?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td width=100px align="center" class="b1">
应战策略
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiz'; } ?><?php echo $tacinfo[$nownpc['tactic']]?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td width=100px align="center" class="b1">
生命上限
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiA'; } ?><?php echo $nownpc['mhp']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td width=100px align="center" class="b1">
熟练度
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiB'; } ?><?php echo $nownpc['skill']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td width=100px align="center" class="b1">
怒气值
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiC'; } ?><?php echo $nownpc['rage']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td width=100px align="center" class="b1">
基础攻击
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiD'; } ?><?php echo $nownpc['att']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td width=100px align="center" class="b1">
基础防御
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiE'; } ?><?php echo $nownpc['def']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td width=100px align="center" class="b1">
掉落金钱
</td>
<td width=100px align="center" class="b3">
<?php } else { echo '___aaiF'; } ?><?php echo $nownpc['money']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
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
<?php } else { echo '___aaiG'; } ?><?php echo $nownpc['description']?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
<td>
<table border="1" cellspacing="0" cellpadding="0" valign="middle">
<tr>
<td width=100px align="center" class="b1">
武器名称
</td>
<td width=505px align="center" class="b3">
<?php } else { echo '___aaiH'; } ?><?php \npcinfo\npcinfo_gen_item_description($nownpc['wep'],$nownpc['wepk'],$nownpc['wepe'],$nownpc['weps'],$nownpc['wepsk']); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td width=100px align="center" class="b1">
身体装备
</td>
<td width=505px align="center" class="b3">
<?php } else { echo '___aaiI'; } ?><?php \npcinfo\npcinfo_gen_item_description($nownpc['arb'],'',$nownpc['arbe'],$nownpc['arbs'],$nownpc['arbsk']); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td width=100px align="center" class="b1">
头部装备
</td>
<td width=505px align="center" class="b3">
<?php } else { echo '___aaiJ'; } ?><?php \npcinfo\npcinfo_gen_item_description($nownpc['arh'],'',$nownpc['arhe'],$nownpc['arhs'],$nownpc['arhsk']); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td width=100px align="center" class="b1">
手臂装备
</td>
<td width=505px align="center" class="b3">
<?php } else { echo '___aaiK'; } ?><?php \npcinfo\npcinfo_gen_item_description($nownpc['ara'],'',$nownpc['arae'],$nownpc['aras'],$nownpc['arask']); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td width=100px align="center" class="b1">
腿部装备
</td>
<td width=505px align="center" class="b3">
<?php } else { echo '___aaiL'; } ?><?php \npcinfo\npcinfo_gen_item_description($nownpc['arf'],'',$nownpc['arfe'],$nownpc['arfs'],$nownpc['arfsk']); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<tr>
<td width=100px align="center" class="b1">
饰品 
</td>
<td width=505px align="center" class="b3">
<?php } else { echo '___aaiM'; } ?><?php \npcinfo\npcinfo_gen_item_description($nownpc['art'],$nownpc['artk'],'','',$nownpc['artsk']); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<?php } else { echo '___aaiN'; } ?><?php if(is_array(Array(1,2,3,4,5,6))) { foreach(Array(1,2,3,4,5,6) as $id) { if($nownpc['itm'.$id]!='') { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><tr>
<td width=100px align="center" class="b1">
掉落物品
</td>
<td width=505px align="center" class="b3">
<?php } else { echo '___aaiO'; } ?><?php \npcinfo\npcinfo_gen_item_description($nownpc['itm'.$id],$nownpc['itmk'.$id],$nownpc['itme'.$id],$nownpc['itms'.$id],$nownpc['itmsk'.$id]); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></td>
</tr>
<?php } else { echo '___aaiN'; } ?><?php } } } ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?></table>
</td>
</tr>
</table>
</td>
</tr>
</table> 

<?php } else { echo '___aaiP'; } ?>