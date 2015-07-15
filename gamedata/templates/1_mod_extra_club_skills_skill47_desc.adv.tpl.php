<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL47__VARS__ragecost; $ragecost=&$___LOCAL_SKILL47__VARS__ragecost;   $___TEMP_SKILL_ID=47; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
花雨
</span>
<?php } else { echo '___aahQ'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<span class="yellow">战斗技</span>：本次攻击随机附加一种基本属性伤害<br>
持投系武器方可发动；发动消耗<span class="yellow"><?php } else { echo '___aahR'; } ?><?php echo $ragecost?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点怒气。<br>
</span></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aafK'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
3级时解锁
</span>
<?php } else { echo '___aaf.'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

