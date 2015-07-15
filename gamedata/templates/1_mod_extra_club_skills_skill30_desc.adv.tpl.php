<?php if(!defined('IN_GAME')) exit('Access Denied'); global $___LOCAL_SKILL30__VARS__ragecost; $ragecost=&$___LOCAL_SKILL30__VARS__ragecost;   $___TEMP_SKILL_ID=30; include template('MOD_CLUBBASE_SKILLDESC_START'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span>
压制
</span>
<?php } else { echo '___aafP'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID1'); ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><td class=b3><span style="margin-top:8px; margin-bottom:8px; display:block;">
<span class="yellow">战斗技</span>：消耗相当于<span class="yellow">15%</span>生命上限的生命值，附加相同的伤害。<br>
发动消耗<span class="yellow"><?php } else { echo '___aafQ'; } ?><?php echo $ragecost?><?php if (!defined('GEXIT_RETURN_JSON')) { ?></span>点怒气。<br>
</span></td>
<td class=b3 width=46>
</td>
<?php } else { echo '___aafR'; } ?><?php include template('MOD_CLUBBASE_SKILLDESC_MID2'); if(!$___TEMP_THIS_SKILL_ACQUIRED) { ?>
<?php if (!defined('GEXIT_RETURN_JSON')) { ?><span class="yellow">
7级时解锁
</span>
<?php } else { echo '___aafS'; } ?><?php } include template('MOD_CLUBBASE_SKILLDESC_END'); ?>

