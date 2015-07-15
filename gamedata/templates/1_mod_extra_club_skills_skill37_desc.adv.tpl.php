<?php if(!defined('IN_GAME')) exit('Access Denied'); $___TEMP_SKILL_ID=37; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
灭气
</span>
<?php } else { echo '___aag4'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
持殴系武器攻击后敌人体力减少<span class="yellow">伤害值的2/3</span>点<br>
被攻击时你额外获得<span class="yellow">1～2点</span>怒气
</span></td>
<td class=b3 width=46>

</td>
<?php } else { echo '___aag5'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
6级时解锁
</span>
<?php } else { echo '___aag6'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

