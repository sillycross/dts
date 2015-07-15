<?php if(!defined('IN_GAME')) exit('Access Denied'); __MODULE_NULLFUNCTION__();  $___TEMP_SKILL_ID=46; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
暴打
</span>
<?php } else { echo '___aagj'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<?php } else { echo '___aaf1'; } ?><?php $skill46_cnt=(int)\skill46\get_remaintime46(); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">战斗技</span>&nbsp;&nbsp;<span class="red">限定技&nbsp;本局还可发动<span><?php } else { echo '___aagk'; } ?><?php echo $skill46_cnt?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>/2次</span><br>
本次攻击物理伤害<span class="yellow">+65%</span>，本次战斗中敌方所有称号技能无效<br>
</span></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aagl'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
15级时解锁
</span>
<?php } else { echo '___aagm'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

