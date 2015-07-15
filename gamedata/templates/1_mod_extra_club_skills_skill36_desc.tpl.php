<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill36')); $___TEMP_SKILL_ID=36; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
偷袭
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<span class="yellow">战斗技</span>：本次攻击必定触发技能“<span class="yellow">猛击</span>”且不会被反击。<br>
持殴系武器方可发动，发动消耗<span class="yellow"><?php echo $ragecost?></span>点怒气。<br>
</span></td>
<td class=b3 width=46>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
3级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
