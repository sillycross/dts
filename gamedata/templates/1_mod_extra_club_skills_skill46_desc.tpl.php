<?php if(!defined('IN_GAME')) exit('Access Denied'); eval(import_module('skill46')); $___TEMP_SKILL_ID=46; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<span>
暴打
</span>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php $skill46_cnt=(int)\skill46\get_remaintime46(); ?>
<span class="yellow">战斗技</span>&nbsp;&nbsp;<span class="red">限定技&nbsp;本局还可发动<span><?php echo $skill46_cnt?></span>/2次</span><br>
本次攻击物理伤害<span class="yellow">+65%</span>，本次战斗中敌方所有称号技能无效<br>
</span></td>
<td class=b3 width=46>
</td>
<?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<span class="yellow">
15级时解锁
</span>
<?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>
