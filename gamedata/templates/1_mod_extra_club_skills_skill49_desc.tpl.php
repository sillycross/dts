<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill49')); $___TEMP_SKILL_ID=49; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
潜能
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<span class="yellow">战斗技</span>：本次攻击必中且物理伤害<span class="yellow">+20%</span><br>
持投系武器方可发动，消耗<span class="yellow"><?php echo $ragecost?></span>点怒气
</span></td>
<td class=b3 width=46>
<!--这个只是一个假的按钮，实际点击的是下面那个-->
<?php if(!$___TEMP_THIS_SKILL_ACQUIRED) { if($skillpoint>=$unlockcost) { ?>
<input id="skill49_unlock_button" type="button" style="width:46px" onclick="$('mode').value='special';$('command').value='skill49_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');this.disabled=true;" value="解锁">
<?php } else { ?>
<input type="button" style="width:46px" disabled="true" value="解锁">
<?php } } ?>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
<span style="float:left;width:46px;min-width:46px;max-width:46px;">&nbsp;</span>
消耗<span><?php echo $unlockcost?></span>点技能点解锁
<span style="float:right;margin-right:1px;">
<?php if($skillpoint>=$unlockcost) { ?>
<input type="button" style="width:47px" onclick="$('mode').value='special';$('command').value='skill49_special';$('subcmd').value='upgrade';postCmd('gamecmd','command.php');$('skill49_unlock_button').disabled=true;this.disabled=true;" value="解锁">
<?php } else { ?>
<input type="button" style="width:47px" disabled="true" value="解锁">
<?php } ?>
</span>
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
