<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill26')); $___TEMP_SKILL_ID=26; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
聚能
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php $clv = (int)\skillbase\skill_getvalue(26,'lvl');  if($clv <= 1) { ?>
<span class="yellow">战斗技</span>：消耗<span class="yellow"><?php echo $ragecost['1']?></span>点怒气发动，本次攻击伤害全部视为<span class="red">火焰</span>伤害<br>
可花费<span class="lime"><?php echo $upgradecost?></span>点技能点升级，升级后伤害变为<span class="red">灼焰</span>伤害，消耗<span class="yellow"><?php echo $ragecost['2']?></span>点怒气
<?php } else { ?>
<span class="yellow">战斗技</span>：消耗<span class="yellow"><?php echo $ragecost['2']?></span>点怒气发动，本次攻击伤害全部视为<span class="red">灼焰</span>伤害<br>
<?php } ?>
</span></td>
<td class=b3 width=46>
<?php if($clv==1 && $skillpoint>=12) { ?>
<input type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill26_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="升级">
<?php } elseif($clv==1) { ?>
<input type="button" style="width:46px" disabled="true" value="升级">
<?php } ?>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
3级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
